<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\FooterSetting;
use App\Models\GallerySection;
use App\Models\LeadershipSection;
use App\Models\ProjectSection;
use App\Models\ProspectusSection;
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
        $data = Cache::remember(SiteCache::HOME_DATA_KEY, now()->addMinutes(SiteCache::ttl()), function (): array {
            $safeQuery = fn (string $modelClass) => rescue(fn () => $modelClass::query()->first(), null, false);

            return [
                'notice' => rescue(fn () => SiteNotice::query()->active()->latest('updated_at')->first(), null, false),
                'aboutSection' => $safeQuery(AboutSection::class),
                'whySection' => $safeQuery(WhySection::class),
                'projectSection' => $safeQuery(ProjectSection::class),
                'prospectusSection' => $safeQuery(ProspectusSection::class),
                'gallerySection' => $safeQuery(GallerySection::class),
                'shareholderReviewSection' => $safeQuery(ShareholderReviewSection::class),
                'leadershipSection' => $safeQuery(LeadershipSection::class),
                'valuedShareholderSection' => $safeQuery(ValuedShareholderSection::class),
                'footerSetting' => $safeQuery(FooterSetting::class),
            ];
        });

        return view('home.index', $data);
    }
}
