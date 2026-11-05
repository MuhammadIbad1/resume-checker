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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->decimal('price_monthly', 8, 2)->default(0.00);
            $table->decimal('price_yearly', 8, 2)->default(0.00);
            $table->decimal('price_pkr_monthly', 10, 2)->nullable();
            $table->decimal('price_pkr_yearly', 10, 2)->nullable();
            $table->integer('scan_limit')->default(3)->comment('-1 for unlimited');
            $table->integer('jd_limit')->default(1)->comment('-1 for unlimited');
            $table->json('features')->nullable();
            $table->string('stripe_price_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
