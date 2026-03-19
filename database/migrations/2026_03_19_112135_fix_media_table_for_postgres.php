<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Handle mediable_id type change for Postgres
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE media ALTER COLUMN mediable_id TYPE VARCHAR USING mediable_id::varchar');
        } else {
            Schema::table('media', function (Blueprint $table) {
                $table->string('mediable_id')->change();
            });
        }

        // 2. Ensure all other columns exist
        Schema::table('media', function (Blueprint $table) {
            if (!Schema::hasColumn('media', 'mime_type')) {
                $table->string('mime_type')->nullable()->after('path');
            }
            if (!Schema::hasColumn('media', 'size')) {
                $table->unsignedBigInteger('size')->nullable()->after('mime_type');
            }
            if (!Schema::hasColumn('media', 'original_name')) {
                $table->string('original_name')->nullable()->after('path');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed for a fix migration
    }
};
