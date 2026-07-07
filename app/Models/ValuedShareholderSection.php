<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValuedShareholderSection extends Model
{
    public const DEFAULT_SECTION_TITLE = 'Our Valued Shareholders';

    protected $connection = 'content';

    protected $fillable = [
        'section_title',
        'shareholders',
        'is_visible',
    ];

    protected $casts = [
        'shareholders' => 'array',
        'is_visible' => 'boolean',
    ];

    public function hasRenderableContent(): bool
    {
        return filled($this->section_title) || ! empty($this->shareholders());
    }

    public function shouldDisplayOnWebsite(): bool
    {
        return $this->is_visible && $this->hasRenderableContent();
    }

    public function shareholders(): array
    {
        return collect($this->shareholders ?? [])
            ->map(function ($shareholder): ?array {
                $data = is_array($shareholder) ? $shareholder : [];
                $imagePath = trim((string) ($data['image_path'] ?? ''));
                $name = trim((string) ($data['name'] ?? ''));
                $position = trim((string) ($data['position'] ?? ''));

                if (! filled($name) && ! filled($position) && ! filled($imagePath)) {
                    return null;
                }

                return [
                    'name' => $name,
                    'position' => $position,
                    'image_path' => $imagePath,
                    'image_url' => filled($imagePath) ? asset(ltrim($imagePath, '/')) : null,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    public function shareholdersForEditor(): array
    {
        return collect($this->shareholders())
            ->map(fn (array $shareholder): array => [
                'name' => $shareholder['name'] ?? '',
                'position' => $shareholder['position'] ?? '',
                'image_path' => $shareholder['image_path'] ?? '',
                'image_url' => $shareholder['image_url'] ?? null,
            ])
            ->values()
            ->all();
    }
}
