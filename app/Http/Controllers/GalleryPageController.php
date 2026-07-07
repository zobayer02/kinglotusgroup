<?php

namespace App\Http\Controllers;

use App\Models\FooterSetting;
use App\Models\GallerySection;
use App\Support\SiteCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class GalleryPageController extends Controller
{
    public function __invoke(): View
    {
        $data = Cache::remember(SiteCache::GALLERY_PAGE_DATA_KEY, now()->addMinutes(SiteCache::ttl()), fn (): array => [
            'gallerySection' => GallerySection::query()->first(),
            'footerSetting' => FooterSetting::query()->first(),
        ]);

        return view('gallery.index', $data);
    }
}
