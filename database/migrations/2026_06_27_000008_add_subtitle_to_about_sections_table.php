<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'content';

    public function up(): void
    {
        Schema::connection($this->connection)->table('about_sections', function (Blueprint $table): void {
            $table->string('subtitle')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('about_sections', function (Blueprint $table): void {
            $table->dropColumn('subtitle');
        });
    }
};
