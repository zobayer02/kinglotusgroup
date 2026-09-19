<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use RuntimeException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        if (App::isProduction()) {
            throw new RuntimeException('AdminSeeder is disabled in production. Use "php artisan admin:create" instead.');
        }

        // If an admin already exists in local/dev environment, do not re-seed.
        if (Admin::query()->exists()) {
            return;
        }

        $email = env('INITIAL_ADMIN_EMAIL');
        $password = env('INITIAL_ADMIN_PASSWORD');

        if (! filled($email) || ! filled($password)) {
            // Safe fallback only when explicitly configured
            return;
        }

        Admin::query()->firstOrCreate(
            ['email' => strtolower(trim((string) $email))],
            [
                'name' => 'Super Admin',
                'full_name' => 'Super Administrator',
                'password' => $password,
                'role' => 'super_admin',
                'session_version' => 1,
            ],
        );
    }
}
