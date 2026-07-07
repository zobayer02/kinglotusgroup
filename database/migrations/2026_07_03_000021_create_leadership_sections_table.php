<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'content';

    public function up(): void
    {
        Schema::connection($this->connection)->create('leadership_sections', function (Blueprint $table): void {
            $table->id();
            $table->string('section_title', 180)->nullable();
            $table->string('founder_name', 180)->nullable();
            $table->string('founder_position', 180)->nullable();
            $table->string('founder_image_path', 2048)->nullable();
            $table->json('board_members')->nullable();
            $table->boolean('is_visible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('leadership_sections');
    }
};
