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
        Schema::table('job_opportunity_company', function (Blueprint $table) {
            if (!Schema::hasColumn('job_opportunity_company', 'cover_image')) {
                $table->string('cover_image', 500)->nullable()->after('logo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_opportunity_company', function (Blueprint $table) {
            if (Schema::hasColumn('job_opportunity_company', 'cover_image')) {
                $table->dropColumn('cover_image');
            }
        });
    }
};
