<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class SiteCache
{
    public const HOME_DATA_KEY = 'site.home.data';
    public const GALLERY_PAGE_DATA_KEY = 'site.gallery-page.data';
    public const REVIEWS_PAGE_DATA_KEY = 'site.reviews-page.data';
    public const TERMS_PAGE_DATA_KEY = 'site.terms-page.data';

    public static function ttl(): int
    {
        return max(1, (int) config('admin.public_cache_ttl_minutes', 30));
    }

    public static function forgetPublicPages(): void
    {
        Cache::forget(self::HOME_DATA_KEY);
        Cache::forget(self::GALLERY_PAGE_DATA_KEY);
        Cache::forget(self::REVIEWS_PAGE_DATA_KEY);
        Cache::forget(self::TERMS_PAGE_DATA_KEY);
    }
}
