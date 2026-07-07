<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    protected $connection = 'content';

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'left_video_url',
        'right_video_url',
        'left_thumbnail_path',
        'right_thumbnail_path',
    ];

    public function hasRenderableContent(): bool
    {
        return filled($this->title)
            || filled($this->subtitle)
            || filled($this->description)
            || $this->leftVideoEmbedUrl()
            || $this->rightVideoEmbedUrl();
    }

    public function leftThumbnailUrl(): ?string
    {
        return $this->thumbnailUrl($this->left_thumbnail_path);
    }

    public function rightThumbnailUrl(): ?string
    {
        return $this->thumbnailUrl($this->right_thumbnail_path);
    }

    public function leftVideoEmbedUrl(): ?string
    {
        return $this->buildEmbedUrl($this->left_video_url);
    }

    public function rightVideoEmbedUrl(): ?string
    {
        return $this->buildEmbedUrl($this->right_video_url);
    }

    protected function thumbnailUrl(?string $path): ?string
    {
        return filled($path) ? asset(ltrim($path, '/')) : null;
    }

    protected function buildEmbedUrl(?string $url): ?string
    {
        $videoId = $this->extractYoutubeId($url);

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
