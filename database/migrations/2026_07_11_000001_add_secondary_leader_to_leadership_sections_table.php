<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'content';

    public function up(): void
    {
        Schema::connection($this->connection)->table('leadership_sections', function (Blueprint $table): void {
            if (! Schema::connection($this->connection)->hasColumn('leadership_sections', 'secondary_leader_name')) {
                $table->string('secondary_leader_name', 180)->nullable()->after('founder_image_path');
            }
            if (! Schema::connection($this->connection)->hasColumn('leadership_sections', 'secondary_leader_position')) {
                $table->string('secondary_leader_position', 180)->nullable()->after('secondary_leader_name');
            }
            if (! Schema::connection($this->connection)->hasColumn('leadership_sections', 'secondary_leader_description')) {
                $table->string('secondary_leader_description', 200)->nullable()->after('secondary_leader_position');
            }
            if (! Schema::connection($this->connection)->hasColumn('leadership_sections', 'secondary_leader_image_path')) {
                $table->string('secondary_leader_image_path', 2048)->nullable()->after('secondary_leader_description');
            }
        });
    }

    public function down(): void
    {
        Schema::connection($this->connection)->table('leadership_sections', function (Blueprint $table): void {
            $columnsToDrop = array_filter([
                'secondary_leader_name',
                'secondary_leader_position',
                'secondary_leader_description',
                'secondary_leader_image_path',
            ], fn (string $col): bool => Schema::connection($this->connection)->hasColumn('leadership_sections', $col));

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
