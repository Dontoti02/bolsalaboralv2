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
            if (!Schema::hasColumn('job_opportunity_offer', 'vacancies')) {
                $table->unsignedInteger('vacancies')->default(1)->after('salary_currency');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('job_opportunity_offer', function (Blueprint $table) {
            if (Schema::hasColumn('job_opportunity_offer', 'vacancies')) {
                $table->dropColumn('vacancies');
            }
        });
    }
};

