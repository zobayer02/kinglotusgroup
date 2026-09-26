<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProspectusSection extends Model
{
    public const DEFAULT_SECTION_TITLE = 'Project Prospectus & Brochure';
    public const DEFAULT_SECTION_SUBTITLE = 'কিং লোটাস ইন্টারন্যাশনালের পূর্ণাঙ্গ প্রকল্প রূপরেখা';

    protected $connection = 'content';

    protected $fillable = [
        'section_title',
        'section_subtitle',
        'is_visible',
        'brochures',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'brochures' => 'array',
    ];

    public function brochures(): array
    {
        return collect($this->brochures ?? [])
            ->filter(fn ($item) => is_array($item) && filled($item['image_path'] ?? null))
            ->values()
            ->map(function ($item, $index) {
                $imagePath = ltrim((string) ($item['image_path'] ?? ''), '/');

                return [
                    'title' => trim((string) ($item['title'] ?? ('Page ' . ($index + 1)))),
                    'subtitle' => trim((string) ($item['subtitle'] ?? '')),
                    'image_path' => $imagePath,
                    'image_url' => filled($imagePath) ? asset($imagePath) : null,
                ];
            })
            ->all();
    }

    public function brochuresForEditor(): array
    {
        return collect($this->brochures ?? [])
            ->values()
            ->map(function ($item, $index) {
                return [
                    'title' => (string) ($item['title'] ?? ''),
                    'subtitle' => (string) ($item['subtitle'] ?? ''),
                    'image_path' => (string) ($item['image_path'] ?? ''),
                ];
            })
            ->all();
    }

    public function shouldDisplayOnWebsite(): bool
    {
        return (bool) $this->is_visible && count($this->brochures()) > 0;
    }

    public function hasRenderableContent(): bool
    {
        return count($this->brochures()) > 0;
    }
}
