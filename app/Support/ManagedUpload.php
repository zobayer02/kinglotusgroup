<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

final class ManagedUpload
{
    public static function diskName(): string
    {
        return (string) config('filesystems.uploads_disk', 'uploads');
    }

    public static function normalize(?string $path): ?string
    {
        if (! filled($path)) {
            return null;
        }

        $normalized = ltrim(str_replace('\\', '/', trim((string) $path)), '/');

        if (
            $normalized === ''
            || str_contains($normalized, '..')
            || preg_match('/^[A-Za-z]:/', $normalized) === 1
            || ! str_starts_with($normalized, 'uploads/')
        ) {
            return null;
        }

        return $normalized;
    }

    public static function url(?string $path): ?string
    {
        $normalized = self::normalize($path);

        return $normalized === null
            ? null
            : Storage::disk(self::diskName())->url($normalized);
    }
}
