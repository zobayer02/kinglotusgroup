<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadershipSection extends Model
{
    public const DEFAULT_SECTION_TITLE = 'Board Members';
    public const DEFAULT_FOUNDER_LABEL = 'Founder & CEO';

    protected $connection = 'content';

    protected $fillable = [
        'section_title',
        'founder_name',
        'founder_position',
        'founder_description',
        'founder_image_path',
        'board_members',
        'is_visible',
    ];

    protected $casts = [
        'board_members' => 'array',
        'is_visible' => 'boolean',
    ];

    public function hasRenderableContent(): bool
    {
        return filled($this->section_title)
            || filled($this->founder_name)
            || filled($this->founder_position)
            || filled($this->founder_description)
            || filled($this->founder_image_path)
            || ! empty($this->boardMembers());
    }

    public function shouldDisplayOnWebsite(): bool
    {
        return $this->is_visible && $this->hasRenderableContent();
    }

    public function founderImageUrl(): ?string
    {
        return filled($this->founder_image_path)
            ? asset(ltrim((string) $this->founder_image_path, '/'))
            : null;
    }

    public function boardMembers(): array
    {
        return collect($this->board_members ?? [])
            ->map(function ($member, $index): ?array {
                $data = is_array($member) ? $member : [];
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

    public function boardMembersForEditor(): array
    {
        return collect($this->boardMembers())
            ->map(fn (array $member): array => [
                'name' => $member['name'] ?? '',
                'position' => $member['position'] ?? '',
                'image_path' => $member['image_path'] ?? '',
                'image_url' => $member['image_url'] ?? null,
            ])
            ->values()
            ->all();
    }
}
