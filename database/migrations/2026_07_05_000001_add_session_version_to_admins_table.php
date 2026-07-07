<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('auth')->table('admins', function (Blueprint $table): void {
            $table->unsignedInteger('session_version')->default(1)->after('remember_token');
        });
    }

    public function down(): void
    {
        Schema::connection('auth')->table('admins', function (Blueprint $table): void {
            $table->dropColumn('session_version');
        });
    }
};
