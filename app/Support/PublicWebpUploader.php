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

        $oldPath = $this->normalizeManagedPath($oldPath, $directory);

        if ($oldPath) {
            File::delete(public_path($oldPath));
        }

        return str_replace('\\', '/', $relativePath);
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
