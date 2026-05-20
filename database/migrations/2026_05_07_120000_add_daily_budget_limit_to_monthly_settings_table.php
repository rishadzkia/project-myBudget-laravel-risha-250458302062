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
        Schema::table('monthly_settings', function (Blueprint $table) {
            $table->decimal('daily_budget_limit', 15, 2)->default(0)->after('total_saving');
        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('monthly_settings', function (Blueprint $table) {
            $table->dropColumn('daily_budget_limit');
        });
    }
};
