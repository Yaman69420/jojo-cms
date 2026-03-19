<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix Postgres strict type comparison for morph relationships.
     * 
     * mediable_id is VARCHAR (to support UUIDs from Messages),
     * but Episode/Part/User have integer IDs. Postgres refuses
     * to compare VARCHAR = INTEGER without an explicit cast.
     * 
     * This creates implicit casts so Postgres automatically
     * converts integers to varchar when comparing.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Create implicit casts: integer/bigint → varchar
            // These are safe (lossless conversion) and fix ALL morph queries
            try {
                DB::unprepared('CREATE CAST (integer AS character varying) WITH INOUT AS IMPLICIT;');
            } catch (\Exception $e) {
                // Cast may already exist
            }
            try {
                DB::unprepared('CREATE CAST (bigint AS character varying) WITH INOUT AS IMPLICIT;');
            } catch (\Exception $e) {
                // Cast may already exist
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            try {
                DB::unprepared('DROP CAST IF EXISTS (integer AS character varying);');
            } catch (\Exception $e) {
                // Ignore
            }
            try {
                DB::unprepared('DROP CAST IF EXISTS (bigint AS character varying);');
            } catch (\Exception $e) {
                // Ignore
            }
        }
    }
};
