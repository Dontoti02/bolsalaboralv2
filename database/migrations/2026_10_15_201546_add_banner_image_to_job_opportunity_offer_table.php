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
        Schema::table('job_opportunity_offer', function (Blueprint $table) {
            if (!Schema::hasColumn('job_opportunity_offer', 'banner_image')) {
                $table->string('banner_image', 500)->nullable()->after('attachments');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_opportunity_offer', function (Blueprint $table) {
            if (Schema::hasColumn('job_opportunity_offer', 'banner_image')) {
                $table->dropColumn('banner_image');
            }
        });
    }
};
