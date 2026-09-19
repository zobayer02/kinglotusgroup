<?php

namespace App\Http\Controllers;

use App\Models\FooterSetting;
use App\Models\ValuedShareholder;
use App\Models\ValuedShareholderSection;
use App\Support\SiteCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class ValuedShareholderPageController extends Controller
{
    public const PER_PAGE = 24;

    public function __invoke(Request $request): View
    {
        $search = mb_substr(trim((string) $request->input('search', '')), 0, 100);

        $commonData = Cache::remember(SiteCache::SHAREHOLDERS_PAGE_DATA_KEY, now()->addMinutes(SiteCache::ttl()), fn (): array => [
            'valuedShareholderSection' => ValuedShareholderSection::query()->first(),
            'footerSetting' => FooterSetting::query()->first(),
        ]);

        $query = ValuedShareholder::query()
            ->search($search)
            ->orderBy('sort_order')
            ->orderBy('id', 'desc');

        $totalCount = $query->count();
        $shareholders = $query->take(self::PER_PAGE)->get();

        return view('shareholders.index', array_merge($commonData, [
            'initialShareholders' => $shareholders,
            'totalShareholders' => $totalCount,
            'initialSearch' => $search,
            'hasMore' => $totalCount > self::PER_PAGE,
        ]));
    }

    public function items(Request $request): JsonResponse
    {
        $search = mb_substr(trim((string) $request->input('search', '')), 0, 100);
        $page = min(1000, max(1, (int) $request->input('page', 1)));
        $perPage = self::PER_PAGE;

        $query = ValuedShareholder::query()
            ->search($search)
            ->orderBy('sort_order')
            ->orderBy('id', 'desc');

        $total = $query->count();
        $items = $query->forPage($page, $perPage)->get();

        return response()->json([
            'data' => $items->map(fn (ValuedShareholder $s): array => [
                'id' => $s->id,
                'name' => $s->name ?? '',
                'position' => $s->position ?? '',
                'image_url' => $s->imageUrl(),
            ]),
            'current_page' => $page,
            'has_more' => ($page * $perPage) < $total,
            'total' => $total,
            'per_page' => $perPage,
        ]);
    }
}
