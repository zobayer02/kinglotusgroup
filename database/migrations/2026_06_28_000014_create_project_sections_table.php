<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'content';

    public function up(): void
    {
        Schema::connection($this->connection)->create('project_sections', function (Blueprint $table): void {
            $table->id();
            $table->string('top_title')->nullable();
            $table->string('top_button_label', 120)->nullable();
            $table->string('top_button_url', 2048)->nullable();
            $table->string('bottom_title')->nullable();
            $table->json('cards')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('project_sections');
    }
};
