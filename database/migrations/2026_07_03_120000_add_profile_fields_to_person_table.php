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
        Schema::table('person', function (Blueprint $table) {
            $table->text('about_me')->nullable();
            $table->text('skills')->nullable();
            $table->text('hobbies')->nullable();
            $table->text('education')->nullable();
            $table->text('experience')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('person', function (Blueprint $table) {
            $table->dropColumn(['about_me', 'skills', 'hobbies', 'education', 'experience']);
        });
    }
};
