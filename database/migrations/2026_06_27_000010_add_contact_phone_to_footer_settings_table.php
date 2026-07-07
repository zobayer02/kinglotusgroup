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
            $table->string('contact_phone')->nullable()->after('contact_email');
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('footer_settings', function (Blueprint $table): void {
            $table->dropColumn('contact_phone');
        });
    }
};
