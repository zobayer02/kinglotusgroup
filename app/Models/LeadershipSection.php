<?php

namespace App\Models;

use App\Support\ManagedUpload;
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
        'secondary_leader_name',
        'secondary_leader_position',
        'secondary_leader_description',
        'secondary_leader_image_path',
        'board_members',
        'is_visible',
        'board_members_visible',
    ];

    protected $casts = [
        'board_members' => 'array',
        'is_visible' => 'boolean',
        'board_members_visible' => 'boolean',
    ];

    public function hasRenderableContent(): bool
    {
        return filled($this->section_title)
            || filled($this->founder_name)
            || filled($this->founder_position)
            || filled($this->founder_description)
            || filled($this->founder_image_path)
            || $this->hasSecondaryLeader()
            || ! empty($this->boardMembers());
    }

    public function shouldDisplayOnWebsite(): bool
    {
        return $this->is_visible && $this->hasRenderableContent();
    }

    public function shouldDisplayBoardMembers(): bool
    {
        return (bool) ($this->board_members_visible ?? true) && ! empty($this->boardMembers());
    }

    public function founderImageUrl(): ?string
    {
        return filled($this->founder_image_path)
            ? ManagedUpload::url($this->founder_image_path)
            : null;
    }

    public function hasSecondaryLeader(): bool
    {
        return filled($this->secondary_leader_name)
            || filled($this->secondary_leader_position)
            || filled($this->secondary_leader_description)
            || filled($this->secondary_leader_image_path);
    }

    public function secondaryLeaderImageUrl(): ?string
    {
        return filled($this->secondary_leader_image_path)
            ? ManagedUpload::url($this->secondary_leader_image_path)
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
                    'image_url' => ManagedUpload::url($imagePath),
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
