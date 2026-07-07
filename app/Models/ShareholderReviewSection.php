<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShareholderReviewSection extends Model
{
    public const DEFAULT_TITLE = 'Shareholder Reviews';
    public const DEFAULT_SUBTITLE = 'Real stories from King Lotus Group shareholders.';
    public const DEFAULT_VIEW_ALL_LABEL = 'View All';
    public const DEFAULT_PAGE_TITLE = 'Shareholder Reviews';
    public const DEFAULT_PAGE_SUBTITLE = 'Explore video stories and first-hand experiences shared by King Lotus Group shareholders.';

    protected $connection = 'content';

    protected $fillable = [
        'section_title',
        'section_subtitle',
        'reviews',
    ];

    protected $casts = [
        'reviews' => 'array',
    ];

    public function hasRenderableContent(): bool
    {
        return filled($this->section_title)
            || filled($this->section_subtitle)
            || ! empty($this->reviews());
    }

    public function reviews(): array
    {
        return $this->normalizeReviews($this->reviews);
    }

    public function reviewsForEditor(): array
    {
        return collect($this->reviews ?? [])
            ->map(fn ($review): array => $this->normalizeReviewForEditor(is_array($review) ? $review : []))
            ->values()
            ->all();
    }

    public static function makeEmbedUrl(?string $url, bool $autoplay = true): ?string
    {
        if (! filled($url)) {
            return null;
        }

        $youtubeId = static::extractYoutubeId($url);

        if ($youtubeId) {
            $query = http_build_query([
                'autoplay' => $autoplay ? 1 : 0,
                'rel' => 0,
                'modestbranding' => 1,
            ]);

            return 'https://www.youtube-nocookie.com/embed/'.$youtubeId.'?'.$query;
        }

        return null;
    }

    protected function normalizeReviews(?array $reviews): array
    {
        return collect($reviews ?? [])
            ->map(function ($review, $index): ?array {
                $data = is_array($review) ? $review : [];
                $videoUrl = trim((string) ($data['video_url'] ?? ''));
                $embedUrl = static::makeEmbedUrl($videoUrl);

                if (! $embedUrl) {
                    return null;
                }

                $thumbnailPath = trim((string) ($data['thumbnail_path'] ?? ''));
                $thumbnailUrl = filled($thumbnailPath)
                    ? asset(ltrim($thumbnailPath, '/'))
                    : null;

                return [
                    'name' => trim((string) ($data['name'] ?? '')),
                    'video_url' => $videoUrl,
                    'thumbnail_path' => $thumbnailPath,
                    'thumbnail_url' => $thumbnailUrl,
                    'embed_url' => $embedUrl,
                    'preview_embed_url' => static::makeEmbedUrl($videoUrl, false),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    protected function normalizeReviewForEditor(array $review): array
    {
        $thumbnailPath = trim((string) ($review['thumbnail_path'] ?? ''));

        return [
            'name' => trim((string) ($review['name'] ?? '')),
            'video_url' => trim((string) ($review['video_url'] ?? '')),
            'thumbnail_path' => $thumbnailPath,
            'thumbnail_url' => filled($thumbnailPath) ? asset(ltrim($thumbnailPath, '/')) : null,
        ];
    }

    protected static function extractYoutubeId(?string $url): ?string
    {
        if (! filled($url)) {
            return null;
        }

        $parts = parse_url($url);

        if (! is_array($parts)) {
            return null;
        }

        $host = strtolower($parts['host'] ?? '');
        $path = trim($parts['path'] ?? '', '/');

        if ($host === 'youtu.be') {
            return static::sanitizeVideoId($path);
        }

        if (str_contains($host, 'youtube.com') || str_contains($host, 'youtube-nocookie.com')) {
            if (isset($parts['query'])) {
                parse_str($parts['query'], $query);

                if (! empty($query['v'])) {
                    return static::sanitizeVideoId((string) $query['v']);
                }
            }

            if (str_starts_with($path, 'embed/')) {
                return static::sanitizeVideoId(substr($path, 6));
            }

            if (str_starts_with($path, 'shorts/')) {
                return static::sanitizeVideoId(substr($path, 7));
            }
        }

        return null;
    }

    protected static function sanitizeVideoId(string $value): ?string
    {
        $videoId = preg_replace('/[^A-Za-z0-9_-]/', '', $value);

        return filled($videoId) ? $videoId : null;
    }
}
