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
        Schema::create('rule_settings', function (Blueprint $table) {
            $table->id();
            $table->string('rule_code')->unique();
            $table->string('category');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('max_points')->default(10);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rule_settings');
    }
};
