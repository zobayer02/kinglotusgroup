<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FooterSetting;
use App\Models\SiteNotice;
use App\Support\SiteCache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class FaqPageController extends Controller
{
    public const PER_PAGE = 6;

    public function __invoke(Request $request): View
    {
        $commonData = Cache::remember(SiteCache::FAQ_PAGE_DATA_KEY, now()->addMinutes(SiteCache::ttl()), fn (): array => [
            'footerSetting' => rescue(fn () => FooterSetting::query()->first(), null, false),
            'notice' => rescue(fn () => SiteNotice::query()->active()->latest('updated_at')->first(), null, false),
        ]);

        $faqs = collect();
        $totalCount = 0;
        try {
            $query = Faq::query()->active()->ordered();
            $totalCount = $query->count();
            $faqs = $query->take(self::PER_PAGE)->get();
        } catch (\Throwable) {
            // Table pending migration
        }

        return view('faq.index', array_merge($commonData, [
            'faqs' => $faqs,
            'totalFaqs' => $totalCount,
            'hasMore' => $totalCount > self::PER_PAGE,
        ]));
    }

    public function items(Request $request): JsonResponse
    {
        $search = mb_substr(trim((string) $request->input('search', '')), 0, 100);
        $page = min(1000, max(1, (int) $request->input('page', 1)));
        $perPage = self::PER_PAGE;

        $query = Faq::query()->active()->ordered();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('question', 'like', "%{$search}%")
                  ->orWhere('answer', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $items = $query->forPage($page, $perPage)->get();

        return response()->json([
            'data' => $items->map(fn (Faq $f): array => [
                'id' => $f->id,
                'question' => $f->question,
                'answer' => $f->answer,
            ]),
            'current_page' => $page,
            'has_more' => ($page * $perPage) < $total,
            'total' => $total,
            'per_page' => $perPage,
        ]);
    }
}
