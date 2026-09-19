<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ValuedShareholder extends Model
{
    protected $connection = 'content';

    protected $table = 'valued_shareholders';

    protected $fillable = [
        'name',
        'position',
        'image_path',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function imageUrl(): ?string
    {
        if (! filled($this->image_path)) {
            return null;
        }

        $clean = ltrim((string) $this->image_path, '/');

        if (preg_match('~^https?://~i', $clean)) {
            return $clean;
        }

        if (! file_exists(public_path($clean))) {
            return null;
        }

        return asset($clean);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $clean = mb_substr(trim((string) $term), 0, 100);

        if (! filled($clean)) {
            return $query;
        }

        // Escape SQL LIKE wildcards (\ % _) to prevent wildcard DoS or unintended matching
        $escaped = addcslashes($clean, '\\%_');

        return $query->where(function (Builder $sub) use ($escaped): void {
            $sub->where('name', 'like', "%{$escaped}%")
                ->orWhere('position', 'like', "%{$escaped}%");
        });
    }

    public function toEditorArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name ?? '',
            'position' => $this->position ?? '',
            'image_path' => $this->image_path ?? '',
            'image_url' => $this->imageUrl(),
            'sort_order' => $this->sort_order ?? 0,
        ];
    }
}

