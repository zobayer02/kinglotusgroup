<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ContentSyncSeeder extends Seeder
{
    protected string $connection = 'content';

    public function run(): void
    {
        $filePath = database_path('data/content_data.json');

        if (! file_exists($filePath)) {
            return;
        }

        $content = file_get_contents($filePath);
        $data = json_decode($content, true);

        if (! is_array($data)) {
            return;
        }

        foreach ($data as $table => $rows) {
            if (! Schema::connection($this->connection)->hasTable($table) || ! is_array($rows) || empty($rows)) {
                continue;
            }

            // Truncate or delete existing rows in table
            DB::connection($this->connection)->table($table)->delete();

            // Insert exact content rows
            foreach ($rows as $row) {
                if (is_array($row)) {
                    DB::connection($this->connection)->table($table)->insert($row);
                }
            }
        }
    }
}
