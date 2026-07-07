<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\FooterSetting;
use App\Models\GallerySection;
use App\Models\LeadershipSection;
use App\Models\ProjectSection;
use App\Models\ShareholderReviewSection;
use App\Models\SiteNotice;
use App\Models\ValuedShareholderSection;
use App\Models\WhySection;
use App\Support\SiteCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $data = Cache::remember(SiteCache::HOME_DATA_KEY, now()->addMinutes(SiteCache::ttl()), fn (): array => [
            'notice' => SiteNotice::query()->active()->latest('updated_at')->first(),
            'aboutSection' => AboutSection::query()->first(),
            'whySection' => WhySection::query()->first(),
            'projectSection' => ProjectSection::query()->first(),
            'gallerySection' => GallerySection::query()->first(),
            'shareholderReviewSection' => ShareholderReviewSection::query()->first(),
            'leadershipSection' => LeadershipSection::query()->first(),
            'valuedShareholderSection' => ValuedShareholderSection::query()->first(),
            'footerSetting' => FooterSetting::query()->first(),
        ]);

        return view('home.index', $data);
    }
}
