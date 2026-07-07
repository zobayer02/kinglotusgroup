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
            $table->string('terms_title', 180)->nullable()->after('contact_phone');
            $table->text('terms_intro')->nullable()->after('terms_title');
            $table->longText('terms_content')->nullable()->after('terms_intro');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('footer_settings', function (Blueprint $table): void {
            $table->dropColumn(['terms_title', 'terms_intro', 'terms_content']);
        });
    }
};
