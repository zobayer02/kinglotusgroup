<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'content';

    public function up(): void
    {
        Schema::connection($this->connection)->create('gallery_sections', function (Blueprint $table): void {
            $table->id();
            $table->string('section_title')->nullable();
            $table->string('section_subtitle', 180)->nullable();
            $table->string('view_all_label', 120)->nullable();
            $table->string('page_title')->nullable();
            $table->string('page_subtitle', 255)->nullable();
            $table->json('featured_images')->nullable();
            $table->json('albums')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('gallery_sections');
    }
};
