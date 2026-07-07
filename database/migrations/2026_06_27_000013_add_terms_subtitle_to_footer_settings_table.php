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
            $table->string('terms_subtitle', 255)->nullable()->after('terms_title');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('footer_settings', function (Blueprint $table): void {
            $table->dropColumn('terms_subtitle');
        });
    }
};
