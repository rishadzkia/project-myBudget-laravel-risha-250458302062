<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MonthlySetting;

class MonthlySettingSeeder extends Seeder
{
    public function run(): void
    {
        
        MonthlySetting::updateOrCreate(
            [
                'user_id' => 1,
                'month' => 5,
                'year' => 2026
            ],
            [
                'total_income' => 1000000,
                'total_saving' => 200000
            ]
        );

       
        MonthlySetting::updateOrCreate(
            [
                'user_id' => 2,
                'month' => 6,
                'year' => 2026
            ],
            [
                'total_income' => 1500000,
                'total_saving' => 300000
            ]
        );

        
        MonthlySetting::updateOrCreate(
            [
                'user_id' => 2,
                'month' => 5,
                'year' => 2026
            ],
            [
                'total_income' => 800000,
                'total_saving' => 100000
            ]
        );
    }
}