<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::query()->updateOrCreate(
            ['email' => 'superadmin@kinglotusgroup.com'],
            [
                'name' => 'Super Admin',
                'password' => 'KingLotus@123',
                'role' => 'super_admin',
            ],
        );
    }
}
