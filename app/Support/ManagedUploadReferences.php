<?php

namespace App\Support;

use App\Models\AboutSection;
use App\Models\GallerySection;
use App\Models\LeadershipSection;
use App\Models\ProjectSection;
use App\Models\ProspectusSection;
use App\Models\ShareholderReviewSection;
use App\Models\SiteNotice;
use App\Models\ValuedShareholder;
use App\Models\ValuedShareholderSection;
use App\Models\WhySection;

final class ManagedUploadReferences
{
    /** @return list<string> */
    public function all(): array
    {
        $paths = [];

        $this->collect(SiteNotice::query()->pluck('hero_background_path')->all(), $paths);
        $this->collect(AboutSection::query()->get(['left_thumbnail_path', 'right_thumbnail_path'])->toArray(), $paths);
        $this->collect(WhySection::query()->pluck('thumbnail_path')->all(), $paths);
        $this->collect(ProjectSection::query()->get(['top_cards', 'bottom_cards', 'cards'])->toArray(), $paths);
        $this->collect(ProspectusSection::query()->pluck('brochures')->all(), $paths);
        $this->collect(GallerySection::query()->get(['featured_images', 'albums'])->toArray(), $paths);
        $this->collect(ShareholderReviewSection::query()->pluck('reviews')->all(), $paths);
        $this->collect(LeadershipSection::query()->get(['founder_image_path', 'secondary_leader_image_path', 'board_members'])->toArray(), $paths);
        $this->collect(ValuedShareholder::query()->pluck('image_path')->all(), $paths);
        $this->collect(ValuedShareholderSection::query()->pluck('shareholders')->all(), $paths);

        ksort($paths);

        return array_values($paths);
    }

    /** @param array<string, string> $paths */
    private function collect(mixed $value, array &$paths): void
    {
        if (is_array($value)) {
            foreach ($value as $nested) {
                $this->collect($nested, $paths);
            }

            return;
        }

        if (! is_string($value)) {
            return;
        }

        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            $this->collect($decoded, $paths);

            return;
        }

        $normalized = ManagedUpload::normalize($value);
        if ($normalized !== null) {
            $paths[$normalized] = $normalized;
        }
    }
}
