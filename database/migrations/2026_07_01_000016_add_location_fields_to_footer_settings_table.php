<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'content';

    public function up(): void
    {
        Schema::connection($this->connection)->table('footer_settings', function (Blueprint $table): void {
            $table->string('location_title', 180)->nullable()->after('contact_phone');
            $table->string('location_subtitle', 255)->nullable()->after('location_title');
            $table->string('location_map_url', 2048)->nullable()->after('location_subtitle');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('footer_settings', function (Blueprint $table): void {
            $table->dropColumn(['location_title', 'location_subtitle', 'location_map_url']);
        });
    }
};
