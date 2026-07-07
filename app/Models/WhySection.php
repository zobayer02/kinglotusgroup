<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhySection extends Model
{
    protected $connection = 'content';

    protected $fillable = [
        'title',
        'description',
        'feature_points',
        'cta_label',
        'cta_url',
        'video_url',
        'thumbnail_path',
    ];

    public function hasRenderableContent(): bool
    {
        return filled($this->title)
            || filled($this->description)
            || filled($this->featurePoints())
            || filled($this->cta_label)
            || $this->videoEmbedUrl();
    }

    public function featurePoints(): array
    {
        return collect(preg_split('/\r\n|\r|\n/', (string) $this->feature_points))
            ->map(fn (?string $item) => trim((string) $item))
            ->filter()
            ->values()
            ->all();
    }

    public function thumbnailUrl(): ?string
    {
        return filled($this->thumbnail_path) ? asset(ltrim($this->thumbnail_path, '/')) : null;
    }

    public function videoEmbedUrl(): ?string
    {
        $videoId = $this->extractYoutubeId($this->video_url);

        if (! $videoId) {
            return null;
        }

        return 'https://www.youtube-nocookie.com/embed/'.$videoId.'?autoplay=1&rel=0';
    }

    protected function extractYoutubeId(?string $url): ?string
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
            return $this->sanitizeVideoId($path);
        }

        if (str_contains($host, 'youtube.com') || str_contains($host, 'youtube-nocookie.com')) {
            if (isset($parts['query'])) {
                parse_str($parts['query'], $query);

                if (! empty($query['v'])) {
                    return $this->sanitizeVideoId($query['v']);
                }
            }

            if (str_starts_with($path, 'embed/')) {
                return $this->sanitizeVideoId(substr($path, 6));
            }

            if (str_starts_with($path, 'shorts/')) {
                return $this->sanitizeVideoId(substr($path, 7));
            }
        }

        return null;
    }

    protected function sanitizeVideoId(string $value): ?string
    {
        $videoId = preg_replace('/[^A-Za-z0-9_-]/', '', $value);

        return filled($videoId) ? $videoId : null;
    }
}
