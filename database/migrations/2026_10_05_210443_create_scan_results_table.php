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
        Schema::create('scan_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scan_id')->constrained()->cascadeOnDelete();
            $table->string('category')->comment('structure, contact, experience, metrics, keywords');
            $table->string('rule_code');
            $table->string('rule_name');
            $table->boolean('passed')->default(false);
            $table->integer('score_awarded')->default(0);
            $table->integer('max_score')->default(10);
            $table->text('message');
            $table->text('recommendation')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scan_results');
    }
};
