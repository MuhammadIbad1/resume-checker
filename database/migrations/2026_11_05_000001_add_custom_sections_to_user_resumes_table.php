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
        Schema::table('user_resumes', function (Blueprint $table) {
            if (!Schema::hasColumn('user_resumes', 'custom_sections')) {
                $table->json('custom_sections')->nullable()->after('certifications');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_resumes', function (Blueprint $table) {
            if (Schema::hasColumn('user_resumes', 'custom_sections')) {
                $table->dropColumn('custom_sections');
            }
        });
    }
};

