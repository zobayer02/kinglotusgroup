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
        Schema::connection($this->connection)->table('project_sections', function (Blueprint $table): void {
            $table->json('top_cards')->nullable()->after('top_button_url');
            $table->json('bottom_cards')->nullable()->after('bottom_title');
        });

        DB::connection($this->connection)
            ->table('project_sections')
            ->orderBy('id')
            ->get()
            ->each(function (object $row): void {
                $cards = json_decode($row->cards ?? '[]', true);

                if (! is_array($cards) || (! empty($row->top_cards) || ! empty($row->bottom_cards))) {
                    return;
                }

                DB::connection($this->connection)
                    ->table('project_sections')
                    ->where('id', $row->id)
                    ->update([
                        'top_cards' => json_encode(array_slice($cards, 0, 4)),
                        'bottom_cards' => json_encode(array_slice($cards, 4)),
                    ]);
            });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('project_sections', function (Blueprint $table): void {
            $table->dropColumn(['top_cards', 'bottom_cards']);
        });
    }
};
