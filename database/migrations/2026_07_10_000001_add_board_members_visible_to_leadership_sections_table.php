<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'content';

    public function up(): void
    {
        Schema::connection($this->connection)->table('leadership_sections', function (Blueprint $table): void {
            if (! Schema::connection($this->connection)->hasColumn('leadership_sections', 'board_members_visible')) {
                $table->boolean('board_members_visible')->default(true)->after('is_visible');
            }
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('leadership_sections', function (Blueprint $table): void {
            if (Schema::connection($this->connection)->hasColumn('leadership_sections', 'board_members_visible')) {
                $table->dropColumn('board_members_visible');
            }
        });
    }
};
