<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class PublicWebpUploader
{
    private const MAX_UPLOAD_BYTES = 8388608;

    private const MAX_WIDTH = 6000;

    private const MAX_HEIGHT = 6000;

    private const MAX_PIXELS = 25000000;

    private const MAX_DECODE_BYTES = 104857600;

    /** @var list<string> */
    private array $newUploads = [];

    /** @var list<string> */
    private array $pendingDeletions = [];

    public function store(UploadedFile $file, string $directory, ?string $oldPath = null): string
    {
        $directory = $this->normalizeDirectory($directory);
        $this->ensureUploadProtection();

        if (($file->getSize() ?: 0) > self::MAX_UPLOAD_BYTES) {
            throw new RuntimeException('Uploaded image exceeds the allowed file size.');
        }

        $dimensions = @getimagesize($file->getRealPath());

        if (! is_array($dimensions)) {
            throw new RuntimeException('Uploaded image could not be processed.');
        }

        $imageType = (int) ($dimensions[2] ?? 0);
        if (! in_array($imageType, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)) {
            throw new RuntimeException('Uploaded image type is not supported.');
        }

        $width = (int) ($dimensions[0] ?? 0);
        $height = (int) ($dimensions[1] ?? 0);

        if ($width < 1 || $height < 1) {
            throw new RuntimeException('Uploaded image dimensions are invalid.');
        }

        if ($width > self::MAX_WIDTH || $height > self::MAX_HEIGHT) {
            throw new RuntimeException('Uploaded image dimensions exceed the allowed limit.');
        }

        if (($width * $height) > self::MAX_PIXELS) {
            throw new RuntimeException('Uploaded image resolution exceeds the allowed limit.');
        }

        if (($width * $height * 4) > self::MAX_DECODE_BYTES) {
            throw new RuntimeException('Uploaded image is too large to process safely.');
        }

        $imageData = file_get_contents($file->getRealPath());
        $image = $imageData ? @imagecreatefromstring($imageData) : false;

        if (! $image) {
            throw new RuntimeException('Uploaded image could not be processed.');
        }

        $temporaryPath = tempnam(sys_get_temp_dir(), 'king-lotus-upload-');

        if ($temporaryPath === false) {
            imagedestroy($image);

            throw new RuntimeException('A temporary upload file could not be created.');
        }

        try {
            if (! imageistruecolor($image)) {
                imagepalettetotruecolor($image);
            }

            imagealphablending($image, true);
            imagesavealpha($image, true);

            if (! imagewebp($image, $temporaryPath, 85)) {
                throw new RuntimeException('WebP conversion failed.');
            }

            $this->ensureUploadQuota(File::size($temporaryPath), $oldPath, $directory);

            $fileName = Str::uuid()->toString().'.webp';
            $relativePath = $directory.'/'.$fileName;
            $stream = fopen($temporaryPath, 'rb');

            if ($stream === false) {
                throw new RuntimeException('Converted image could not be opened.');
            }

            try {
                $stored = Storage::disk(ManagedUpload::diskName())->put(
                    $relativePath,
                    $stream
                );
            } finally {
                fclose($stream);
            }

            if (! $stored || ! Storage::disk(ManagedUpload::diskName())->exists($relativePath)) {
                throw new RuntimeException('Converted image could not be stored.');
            }

            $this->newUploads[] = $relativePath;
            $this->queueDelete($oldPath, $directory);

            return $relativePath;
        } finally {
            imagedestroy($image);
            File::delete($temporaryPath);
        }
    }

    public function queueDelete(?string $path, ?string $directory = null): void
    {
        $normalized = $directory === null
            ? ManagedUpload::normalize($path)
            : $this->normalizeManagedPath($path, $directory);

        if ($normalized !== null && ! in_array($normalized, $this->pendingDeletions, true)) {
            $this->pendingDeletions[] = $normalized;
        }
    }

    public function queueRemovedPaths(mixed $previous, mixed $current): void
    {
        $previousPaths = $this->collectManagedPaths($previous);
        $currentPaths = $this->collectManagedPaths($current);

        foreach (array_diff($previousPaths, $currentPaths) as $path) {
            $this->queueDelete($path);
        }
    }

    public function commit(): void
    {
        $protected = array_fill_keys($this->newUploads, true);

        foreach (array_unique($this->pendingDeletions) as $path) {
            if (isset($protected[$path])) {
                continue;
            }

            try {
                $this->delete($path);
            } catch (Throwable $exception) {
                Log::warning('Managed upload cleanup failed after database commit.', [
                    'path' => $path,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        $this->newUploads = [];
        $this->pendingDeletions = [];
    }

    public function rollback(): void
    {
        foreach (array_unique($this->newUploads) as $path) {
            try {
                $this->delete($path);
            } catch (Throwable $exception) {
                Log::warning('Managed upload rollback cleanup failed.', [
                    'path' => $path,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        $this->newUploads = [];
        $this->pendingDeletions = [];
    }

    public function delete(?string $path, ?string $directory = null): bool
    {
        $normalized = $directory === null
            ? ManagedUpload::normalize($path)
            : $this->normalizeManagedPath($path, $directory);

        if ($normalized === null) {
            return false;
        }

        $disk = Storage::disk(ManagedUpload::diskName());

        return $disk->exists($normalized) && $disk->delete($normalized);
    }

    public function ensureUploadProtection(): void
    {
        if (config('filesystems.disks.'.ManagedUpload::diskName().'.driver') !== 'local') {
            return;
        }

        $htaccessPath = public_path('uploads/.htaccess');
        if (! File::exists($htaccessPath)) {
            $content = <<<'HTACCESS'
# Disable script execution and directory browsing in uploads directory
Options -ExecCGI -Indexes

# Block execution of any PHP, Perl, Python, Shell, or executable files
<FilesMatch "(?i)\.(php|php[0-9]|phtml|pht|phps|phar|sh|pl|cgi|py|asp|aspx|exe|bin|bat|cmd|dll|jsp)$">
    <IfModule mod_authz_core.c>
        Require all denied
    </IfModule>
    <IfModule !mod_authz_core.c>
        Order Allow,Deny
        Deny from all
    </IfModule>
</FilesMatch>

# Disable PHP engine if mod_php is loaded
<IfModule mod_php.c>
    php_flag engine off
</IfModule>
<IfModule mod_php7.c>
    php_flag engine off
</IfModule>
<IfModule mod_php8.c>
    php_flag engine off
</IfModule>

# Remove script handlers
<IfModule mod_mime.c>
    RemoveHandler .php .phtml .php3 .php4 .php5 .php7 .php8 .phar .cgi .pl .py .asp .aspx
    RemoveType .php .phtml .php3 .php4 .php5 .php7 .php8 .phar .cgi .pl .py .asp .aspx
</IfModule>
HTACCESS;
            File::ensureDirectoryExists(dirname($htaccessPath));
            File::put($htaccessPath, $content);
        }
    }

    public function __destruct()
    {
        if ($this->newUploads !== []) {
            $this->rollback();
        }
    }

    protected function normalizeManagedPath(?string $path, string $directory): ?string
    {
        $normalized = ManagedUpload::normalize($path);
        $directory = $this->normalizeDirectory($directory);

        return $normalized !== null && str_starts_with($normalized, $directory.'/')
            ? $normalized
            : null;
    }

    private function normalizeDirectory(string $directory): string
    {
        $normalized = trim(str_replace('\\', '/', $directory), '/');

        if (
            $normalized === 'uploads'
            || str_contains($normalized, '..')
            || ! str_starts_with($normalized, 'uploads/')
        ) {
            throw new RuntimeException('Upload directory is not allowed.');
        }

        return $normalized;
    }

    private function ensureUploadQuota(int $newFileBytes, ?string $oldPath, string $directory): void
    {
        $maximumBytes = (int) config('filesystems.uploads_max_total_bytes', 0);

        if ($maximumBytes <= 0) {
            return;
        }

        $disk = Storage::disk(ManagedUpload::diskName());
        $usedBytes = 0;

        foreach ($disk->allFiles('uploads') as $path) {
            $usedBytes += $disk->size($path);
        }

        $normalizedOldPath = $this->normalizeManagedPath($oldPath, $directory);
        if ($normalizedOldPath !== null && $disk->exists($normalizedOldPath)) {
            $usedBytes -= $disk->size($normalizedOldPath);
        }

        if ((max(0, $usedBytes) + $newFileBytes) > $maximumBytes) {
            throw new RuntimeException('Upload storage quota would be exceeded.');
        }
    }

    /** @return list<string> */
    private function collectManagedPaths(mixed $value): array
    {
        $paths = [];
        $collect = function (mixed $item) use (&$collect, &$paths): void {
            if (is_array($item)) {
                foreach ($item as $nested) {
                    $collect($nested);
                }

                return;
            }

            if (is_string($item)) {
                $normalized = ManagedUpload::normalize($item);
                if ($normalized !== null) {
                    $paths[$normalized] = $normalized;
                }
            }
        };

        $collect($value);

        return array_values($paths);
    }
}
