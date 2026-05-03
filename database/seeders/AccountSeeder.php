<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        Account::create([
            'user_id' => 1,
            'account_name' => 'BRI',
            'saldo' => 100000
        ]);

        Account::create([
            'user_id' => 2,
            'account_name' => 'Dana',
            'saldo' => 50000
        ]);
        Account::create([
            'user_id' => 3,
            'account_name' => 'OVO',
            'saldo' => 300000
        ]);
    }
}