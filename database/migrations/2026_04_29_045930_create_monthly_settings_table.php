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
        Schema::create('monthly_settings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->integer('month');
        $table->integer('year');
        $table->decimal('daily_budget', 15, 2);
        $table->decimal('total_income', 15, 2);
        $table->decimal('total_saving', 15, 2);
        $table->unique(['user_id', 'month', 'year']);
        $table->timestamps();




});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_settings');
    }
};
