<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'content';

    public function up(): void
    {
        Schema::connection($this->connection)->create('shareholder_review_sections', function (Blueprint $table): void {
            $table->id();
            $table->string('section_title')->nullable();
            $table->string('section_subtitle', 255)->nullable();
            $table->json('reviews')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('shareholder_review_sections');
    }
};
