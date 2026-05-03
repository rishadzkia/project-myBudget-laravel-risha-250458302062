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
        Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->foreignId('account_id')->constrained('accounts')->cascadeOnDelete();
        $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
        $table->foreignId('bill_id')->nullable()->constrained('bills')->nullOnDelete();
        $table->enum('type', ['pemasukan', 'pengeluaran']);
        $table->decimal('amount', 15, 2);
        $table->timestamp('transaction_time')->nullable();
        $table->timestamps();

    

    

    

    
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
