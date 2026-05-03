<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bill;

class BillSeeder extends Seeder
{
    public function run(): void
    {
        Bill::create([
            'user_id' => 1,
            'bill_name' => 'Wifi',
            'amount' => 150000,
            'due_day' => 10,
            'last_paid_year' => 2026,
            'last_paid_month' => 5,
        ]);

        Bill::create([
            'user_id' => 2,
            'bill_name' => 'Air',
            'amount' => 100000,
            'due_day' => 15,
        ]);

        Bill::create([
            'user_id' => 3,
            'bill_name' => 'Bayar Kos',
            'amount' => 300000,
            'due_day' => 20,
        ]);
    }
}