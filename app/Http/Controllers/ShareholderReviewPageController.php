<?php

namespace App\Http\Controllers;

use App\Models\FooterSetting;
use App\Models\ShareholderReviewSection;
use App\Support\SiteCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ShareholderReviewPageController extends Controller
{
    public function __invoke(): View
    {
        $data = Cache::remember(SiteCache::REVIEWS_PAGE_DATA_KEY, now()->addMinutes(SiteCache::ttl()), fn (): array => [
            'shareholderReviewSection' => ShareholderReviewSection::query()->first(),
            'footerSetting' => FooterSetting::query()->first(),
        ]);

        return view('reviews.index', $data);
    }
}
