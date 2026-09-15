<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('rol')) {
            DB::table('rol')->updateOrInsert(
                ['id' => 5],
                [
                    'name' => 'EGRESADO',
                    'key' => 'rol_graduate',
                    'level' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('rol')) {
            DB::table('rol')->where('id', 5)->delete();
        }
    }
};
