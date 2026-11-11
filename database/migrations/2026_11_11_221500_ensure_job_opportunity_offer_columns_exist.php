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
        if (Schema::hasTable('job_opportunity_offer')) {
            Schema::table('job_opportunity_offer', function (Blueprint $table) {
                if (!Schema::hasColumn('job_opportunity_offer', 'banner_image')) {
                    $table->string('banner_image', 500)->nullable();
                }
                if (!Schema::hasColumn('job_opportunity_offer', 'external_url')) {
                    $table->string('external_url', 500)->nullable();
                }
                if (!Schema::hasColumn('job_opportunity_offer', 'vacancies')) {
                    $table->unsignedInteger('vacancies')->default(1);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Keep down empty to prevent accidental data deletion in production
    }
};
