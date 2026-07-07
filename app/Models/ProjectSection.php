<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectSection extends Model
{
    protected $connection = 'content';

    protected $fillable = [
        'top_title',
        'top_button_label',
        'top_button_url',
        'bottom_title',
        'top_cards',
        'bottom_cards',
        'cards',
    ];

    protected $casts = [
        'top_cards' => 'array',
        'bottom_cards' => 'array',
        'cards' => 'array',
    ];

    public function hasRenderableContent(): bool
    {
        return filled($this->top_title)
            || filled($this->bottom_title)
            || collect($this->topCards())->isNotEmpty()
            || collect($this->bottomCards())->isNotEmpty();
    }

    public function topCards(): array
    {
        if (filled($this->top_cards)) {
            return $this->normalizeCards($this->top_cards);
        }

        return array_slice($this->legacyCards(), 0, 4);
    }

    public function bottomCards(): array
    {
        if (filled($this->bottom_cards)) {
            return $this->normalizeCards($this->bottom_cards);
        }

        return array_slice($this->legacyCards(), 4);
    }

    public function topCardsForEditor(): array
    {
        return $this->cardsForEditor($this->topCards());
    }

    public function bottomCardsForEditor(): array
    {
        return $this->cardsForEditor($this->bottomCards());
    }

    protected function cardsForEditor(array $cards): array
    {
        return collect($cards)
            ->map(fn (array $card): array => [
                'order' => $card['order'] ?? null,
                'title' => $card['title'] ?? '',
                'location' => $card['location'] ?? '',
                'image_path' => $card['image_path'] ?? '',
                'image_url' => $card['image_url'] ?? null,
            ])
            ->values()
            ->all();
    }

    protected function legacyCards(): array
    {
        return $this->normalizeCards($this->cards ?? []);
    }

    protected function normalizeCards(?array $cards): array
    {
        return collect($cards ?? [])
            ->map(function ($card, $index): array {
                $data = is_array($card) ? $card : [];

                return [
                    'order' => max(1, (int) ($data['order'] ?? ($index + 1))),
                    'title' => trim((string) ($data['title'] ?? '')),
                    'location' => trim((string) ($data['location'] ?? '')),
                    'rating' => trim((string) ($data['rating'] ?? '4.7/5')),
                    'link_url' => trim((string) ($data['link_url'] ?? '')),
                    'image_path' => trim((string) ($data['image_path'] ?? '')),
                    'image_url' => filled($data['image_path'] ?? null) ? asset(ltrim((string) $data['image_path'], '/')) : null,
                    '_position' => $index,
                ];
            })
            ->filter(fn (array $card) => filled($card['image_path']) || filled($card['title']) || filled($card['location']))
            ->sortBy(fn (array $card): string => sprintf('%09d-%09d', $card['order'], $card['_position']))
            ->map(fn (array $card): array => [
                'order' => $card['order'],
                'title' => $card['title'],
                'location' => $card['location'],
                'rating' => $card['rating'],
                'link_url' => $card['link_url'],
                'image_path' => $card['image_path'],
                'image_url' => $card['image_url'],
            ])
            ->values()
            ->all();
    }
}
