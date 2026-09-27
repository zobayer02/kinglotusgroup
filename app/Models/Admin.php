<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $connection = 'auth';

    protected $fillable = [
        'name',
        'full_name',
        'email',
        'mobile',
        'password',
        'role',
        'last_login_at',
        'last_login_ip',
        'session_version',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'two_factor_secret' => 'encrypted',
            'two_factor_recovery_codes' => 'encrypted:array',
            'two_factor_confirmed_at' => 'datetime',
        ];
    }

    public function hasTwoFactorEnabled(): bool
    {
        return ! empty($this->two_factor_secret) && $this->two_factor_confirmed_at !== null;
    }

    public function displayName(): string
    {
        return $this->full_name ?: 'Name not set';
    }

    public function displayInitials(): string
    {
        $source = $this->full_name ?: $this->name;

        return (string) str($source)
            ->explode(' ')
            ->filter()
            ->map(fn (string $part) => str($part)->substr(0, 1))
            ->take(2)
            ->join('');
    }
}
