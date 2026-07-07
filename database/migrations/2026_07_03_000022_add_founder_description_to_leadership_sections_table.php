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
            $table->string('founder_description', 120)->nullable()->after('founder_position');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('leadership_sections', function (Blueprint $table): void {
            $table->dropColumn('founder_description');
        });
    }
};
