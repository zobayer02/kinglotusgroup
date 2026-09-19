<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use RuntimeException;

class PublicWebpUploader
{
    private const MAX_WIDTH = 5000;
    private const MAX_HEIGHT = 5000;
    private const MAX_PIXELS = 16000000;
    private const MAX_DECODE_BYTES = 67108864;

    public function store(UploadedFile $file, string $directory, ?string $oldPath = null): string
    {
        $directory = trim($directory, '/');
        $targetDirectory = public_path($directory);

        File::ensureDirectoryExists($targetDirectory);
        $this->ensureUploadProtection();

        $dimensions = @getimagesize($file->getRealPath());

        if (! is_array($dimensions)) {
            throw new RuntimeException('Uploaded image could not be processed.');
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

        if (! imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }

        imagealphablending($image, true);
        imagesavealpha($image, true);

        $fileName = Str::uuid()->toString().'.webp';
        $relativePath = $directory.'/'.$fileName;
        $absolutePath = public_path($relativePath);

        if (! imagewebp($image, $absolutePath, 85)) {
            imagedestroy($image);

            throw new RuntimeException('WebP conversion failed.');
        }

        imagedestroy($image);

        // Delete old file only after the new file has been safely written
        $oldPath = $this->normalizeManagedPath($oldPath, $directory);

        if ($oldPath && File::exists(public_path($oldPath)) && public_path($oldPath) !== $absolutePath) {
            File::delete(public_path($oldPath));
        }

        return str_replace('\\', '/', $relativePath);
    }

    public function ensureUploadProtection(): void
    {
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

    public function delete(?string $path, ?string $directory = null): bool
    {
        if (! filled($path)) {
            return false;
        }

        $normalized = $directory !== null
            ? $this->normalizeManagedPath($path, $directory)
            : ltrim(str_replace('\\', '/', trim((string) $path)), '/');

        if (! filled($normalized) || str_contains($normalized, '..') || preg_match('/^[A-Za-z]:/', $normalized) === 1) {
            return false;
        }

        $fullPath = public_path($normalized);

        if (File::exists($fullPath)) {
            return File::delete($fullPath);
        }

        return false;
    }

    protected function normalizeManagedPath(?string $path, string $directory): ?string
    {
        if (! filled($path)) {
            return null;
        }

        $normalized = ltrim(str_replace('\\', '/', trim((string) $path)), '/');
        $directory = trim(str_replace('\\', '/', $directory), '/');

        if (
            $normalized === ''
            || str_contains($normalized, '..')
            || preg_match('/^[A-Za-z]:/', $normalized) === 1
            || ! str_starts_with($normalized, $directory.'/')
        ) {
            return null;
        }

        return $normalized;
    }
}
