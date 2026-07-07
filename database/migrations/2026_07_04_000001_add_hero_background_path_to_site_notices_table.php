<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('content')->table('site_notices', function (Blueprint $table): void {
            $table->string('hero_background_path')->nullable()->after('is_active');
        });
    }

    public function down(): void
    {
        Schema::connection('content')->table('site_notices', function (Blueprint $table): void {
            $table->dropColumn('hero_background_path');
        });
    }
};
