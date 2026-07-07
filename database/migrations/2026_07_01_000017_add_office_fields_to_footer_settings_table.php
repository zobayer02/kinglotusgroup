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
            $table->string('office_section_title', 180)->nullable()->after('location_map_url');
            $table->string('office_section_subtitle', 255)->nullable()->after('office_section_title');
            $table->string('office_name', 180)->nullable()->after('office_section_subtitle');
            $table->text('office_address')->nullable()->after('office_name');
            $table->string('office_map_url', 2048)->nullable()->after('office_address');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('footer_settings', function (Blueprint $table): void {
            $table->dropColumn([
                'office_section_title',
                'office_section_subtitle',
                'office_name',
                'office_address',
                'office_map_url',
            ]);
        });
    }
};
