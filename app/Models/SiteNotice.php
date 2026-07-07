<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SiteNotice extends Model
{
    protected $connection = 'content';

    protected $fillable = [
        'title',
        'message',
        'is_active',
        'hero_background_path',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function heroBackgroundUrl(): ?string
    {
        return filled($this->hero_background_path)
            ? asset(ltrim((string) $this->hero_background_path, '/'))
            : null;
    }
}
