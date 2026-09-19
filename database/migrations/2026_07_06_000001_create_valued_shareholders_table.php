<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'content';

    public function up(): void
    {
        Schema::connection($this->connection)->create('valued_shareholders', function (Blueprint $table): void {
            $table->id();
            $table->string('name', 180)->index();
            $table->string('position', 180)->nullable();
            $table->string('image_path', 2048)->nullable();
            $table->unsignedInteger('sort_order')->default(0)->index();
            $table->timestamps();
        });

        // Migrate any existing shareholders from valued_shareholder_sections
        try {
            if (Schema::connection($this->connection)->hasTable('valued_shareholder_sections')) {
                $rawSection = DB::connection($this->connection)
                    ->table('valued_shareholder_sections')
                    ->first();

                if ($rawSection && ! empty($rawSection->shareholders)) {
                    $shareholders = is_string($rawSection->shareholders)
                        ? json_decode($rawSection->shareholders, true)
                        : (array) $rawSection->shareholders;

                    if (is_array($shareholders)) {
                        $now = now();
                        $insertData = [];

                        foreach ($shareholders as $index => $item) {
                            $name = trim((string) ($item['name'] ?? ''));
                            $position = trim((string) ($item['position'] ?? ''));
                            $imagePath = trim((string) ($item['image_path'] ?? ''));

                            if (filled($name) || filled($position) || filled($imagePath)) {
                                $insertData[] = [
                                    'name' => $name,
                                    'position' => $position,
                                    'image_path' => $imagePath,
                                    'sort_order' => $index,
                                    'created_at' => $now,
                                    'updated_at' => $now,
                                ];
                            }
                        }

                        if (! empty($insertData)) {
                            DB::connection($this->connection)
                                ->table('valued_shareholders')
                                ->insert($insertData);
                        }
                    }
                }
            }
        } catch (\Throwable) {
            // Non-blocking fallback
        }
    }

    public function down(): void
    {
        Schema::connection($this->connection)->dropIfExists('valued_shareholders');
    }
};

