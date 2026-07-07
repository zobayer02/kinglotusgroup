<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'content';

    public function up(): void
    {
        Schema::connection($this->connection)->table('footer_settings', function (Blueprint $table): void {
            $table->json('office_cards')->nullable()->after('office_map_url');
        });

        DB::connection($this->connection)
            ->table('footer_settings')
            ->orderBy('id')
            ->get()
            ->each(function (object $row): void {
                if (! empty($row->office_cards)) {
                    return;
                }

                $name = trim((string) ($row->office_name ?? ''));
                $address = trim((string) ($row->office_address ?? ''));
                $mapUrl = trim((string) ($row->office_map_url ?? ''));

                if ($name === '' && $address === '' && $mapUrl === '') {
                    return;
                }

                DB::connection($this->connection)
                    ->table('footer_settings')
                    ->where('id', $row->id)
                    ->update([
                        'office_cards' => json_encode([[
                            'name' => $name,
                            'address' => $address,
                            'map_url' => $mapUrl,
                        ]]),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('footer_settings', function (Blueprint $table): void {
            $table->dropColumn('office_cards');
        });
    }
};
