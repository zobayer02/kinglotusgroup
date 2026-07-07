<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GallerySection extends Model
{
    public const DEFAULT_SECTION_TITLE = 'Recent Gallery';
    public const DEFAULT_SECTION_SUBTITLE = 'Featured Moments';
    public const DEFAULT_VIEW_ALL_LABEL = 'View All';
    public const DEFAULT_PAGE_TITLE = 'Gallery Albums';
    public const DEFAULT_PAGE_SUBTITLE = 'Explore curated albums and featured moments from King Lotus Group.';
    public const FEATURED_IMAGE_SLOTS = 7;

    protected $connection = 'content';

    protected $fillable = [
        'section_title',
        'section_subtitle',
        'view_all_label',
        'page_title',
        'page_subtitle',
        'featured_images',
        'albums',
    ];

    protected $casts = [
        'featured_images' => 'array',
        'albums' => 'array',
    ];

    public function hasRenderableContent(): bool
    {
        return filled($this->section_title)
            || filled($this->section_subtitle)
            || filled($this->view_all_label)
            || filled($this->page_title)
            || filled($this->page_subtitle)
            || collect($this->featuredImages())->isNotEmpty()
            || collect($this->albums())->isNotEmpty();
    }

    public function featuredImages(): array
    {
        return $this->normalizeFeaturedImages($this->featured_images);
    }

    public function featuredImagesForEditor(): array
    {
        $imagesByOrder = collect($this->featuredImages())->keyBy('order');

        return collect(range(1, self::FEATURED_IMAGE_SLOTS))
            ->map(fn (int $order): array => [
                'order' => $order,
                'image_path' => $imagesByOrder->get($order)['image_path'] ?? '',
                'image_url' => $imagesByOrder->get($order)['image_url'] ?? null,
            ])
            ->values()
            ->all();
    }

    public static function emptyFeaturedImagesForEditor(): array
    {
        return collect(range(1, self::FEATURED_IMAGE_SLOTS))
            ->map(fn (int $order): array => [
                'order' => $order,
                'image_path' => '',
                'image_url' => null,
            ])
            ->values()
            ->all();
    }

    public function albums(): array
    {
        return $this->normalizeAlbums($this->albums);
    }

    public function albumsForEditor(): array
    {
        return collect($this->albums())
            ->map(fn (array $album): array => [
                'title' => $album['title'] ?? '',
                'subtitle' => $album['subtitle'] ?? '',
                'images' => collect($album['images'] ?? [])
                    ->map(fn (array $image): array => [
                        'image_path' => $image['image_path'] ?? '',
                        'image_url' => $image['image_url'] ?? null,
                    ])
                    ->values()
                    ->all(),
            ])
            ->values()
            ->all();
    }

    public function pageAlbums(): array
    {
        return $this->albums();
    }

    protected function normalizeFeaturedImages(?array $images): array
    {
        return collect($images ?? [])
            ->map(function ($image, $index): array {
                $data = is_array($image) ? $image : [];
                $order = max(1, min(self::FEATURED_IMAGE_SLOTS, (int) ($data['order'] ?? ($index + 1))));
                $imagePath = trim((string) ($data['image_path'] ?? ''));

                return [
                    'order' => $order,
                    'image_path' => $imagePath,
                    'image_url' => filled($imagePath) ? asset(ltrim($imagePath, '/')) : null,
                    '_position' => $index,
                ];
            })
            ->filter(fn (array $image) => filled($image['image_path']))
            ->sortBy(fn (array $image): string => sprintf('%09d-%09d', $image['order'], $image['_position']))
            ->map(fn (array $image): array => [
                'order' => $image['order'],
                'image_path' => $image['image_path'],
                'image_url' => $image['image_url'],
            ])
            ->values()
            ->all();
    }

    protected function normalizeAlbums(?array $albums): array
    {
        return collect($albums ?? [])
            ->map(function ($album): ?array {
                $data = is_array($album) ? $album : [];

                $images = collect($data['images'] ?? [])
                    ->map(function ($image): ?array {
                        $imageData = is_array($image) ? $image : ['image_path' => $image];
                        $imagePath = trim((string) ($imageData['image_path'] ?? ''));

                        if (! filled($imagePath)) {
                            return null;
                        }

                        return [
                            'image_path' => $imagePath,
                            'image_url' => asset(ltrim($imagePath, '/')),
                        ];
                    })
                    ->filter()
                    ->values()
                    ->all();

                $albumData = [
                    'title' => trim((string) ($data['title'] ?? '')),
                    'subtitle' => trim((string) ($data['subtitle'] ?? '')),
                    'images' => $images,
                ];

                if (! filled($albumData['title']) && ! filled($albumData['subtitle']) && empty($albumData['images'])) {
                    return null;
                }

                return $albumData;
            })
            ->filter()
            ->values()
            ->all();
    }
}
