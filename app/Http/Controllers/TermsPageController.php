<?php

namespace App\Http\Controllers;

use App\Models\FooterSetting;
use App\Support\SiteCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class TermsPageController extends Controller
{
    public function __invoke(): View
    {
        $data = Cache::remember(SiteCache::TERMS_PAGE_DATA_KEY, now()->addMinutes(SiteCache::ttl()), fn (): array => [
            'footerSetting' => FooterSetting::query()->first(),
        ]);

        return view('legal.terms', $data);
    }
}
