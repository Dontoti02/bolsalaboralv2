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
        // 1. Quitar llave foránea anterior en job_opportunity_offer
        Schema::table('job_opportunity_offer', function (Blueprint $table) {
            $table->dropForeign('job_opportunity_offer_location_id_foreign');
        });

        // 2. Renombrar la tabla job_opportunity_location
        Schema::rename('job_opportunity_location', 'job_opportunity_modalities');

        // 3. Renombrar la columna location_id a modality_id
        Schema::table('job_opportunity_offer', function (Blueprint $table) {
            $table->renameColumn('location_id', 'modality_id');
        });

        // 4. Recrear la llave foránea sobre modality_id
        Schema::table('job_opportunity_offer', function (Blueprint $table) {
            $table->foreign('modality_id', 'job_opportunity_offer_modality_id_foreign')
                  ->references('id')
                  ->on('job_opportunity_modalities')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Quitar llave foránea nueva
        Schema::table('job_opportunity_offer', function (Blueprint $table) {
            $table->dropForeign('job_opportunity_offer_modality_id_foreign');
        });

        // 2. Renombrar columna modality_id de vuelta a location_id
        Schema::table('job_opportunity_offer', function (Blueprint $table) {
            $table->renameColumn('modality_id', 'location_id');
        });

        // 3. Renombrar la tabla de vuelta a job_opportunity_location
        Schema::rename('job_opportunity_modalities', 'job_opportunity_location');

        // 4. Recrear la llave foránea original
        Schema::table('job_opportunity_offer', function (Blueprint $table) {
            $table->foreign('location_id', 'job_opportunity_offer_location_id_foreign')
                  ->references('id')
                  ->on('job_opportunity_location')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }
};
