<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->string('poster_path')->nullable()->after('summary');
        });

        Schema::table('episodes', function (Blueprint $table) {
            $table->string('thumbnail_url')->nullable()->after('summary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seasons', function (Blueprint $table) {
            $table->dropColumn('poster_path');
        });

        Schema::table('episodes', function (Blueprint $table) {
            $table->dropColumn('thumbnail_url');
        });
    }
};
