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
        $clean = trim((string) $term);

        if (! filled($clean)) {
            return $query;
        }

        return $query->where(function (Builder $sub) use ($clean): void {
            $sub->where('name', 'like', "%{$clean}%")
                ->orWhere('position', 'like', "%{$clean}%");
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

