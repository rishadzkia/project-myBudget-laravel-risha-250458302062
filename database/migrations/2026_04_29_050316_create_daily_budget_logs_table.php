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
         Schema::create('daily_budget_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('monthly_setting_id')->constrained('monthly_settings')->cascadeOnDelete();
            $table->date('date');
            $table->decimal('total_pengeluaran', 15, 2);
            $table->decimal('daily_budget', 15, 2);
            $table->boolean('is_success')->default(false);
            $table->unique(['monthly_setting_id', 'date']);
            $table->timestamps();



        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_budget_logs');
    }
};
