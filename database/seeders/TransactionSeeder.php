<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Account;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        // ambil account
        $account = Account::find(1);

        // 🔥 TRANSAKSI 1 (PENGELUARAN)
        Transaction::create([
            'user_id' => 1,
            'account_id' => 1,
            'category_id' => 3,
            'type' => 'pengeluaran',
            'amount' => 20000,
            'transaction_time' => now()
        ]);

        $account->saldo -= 20000;

        // 🔥 TRANSAKSI 2 (PEMASUKAN)
        Transaction::create([
            'user_id' => 1,
            'account_id' => 1,
            'category_id' => 4,
            'type' => 'pemasukan',
            'amount' => 100000,
            'transaction_time' => now()
        ]);

        $account->saldo += 100000;

        // 🔥 TRANSAKSI 3
        Transaction::create([
            'user_id' => 1,
            'account_id' => 1,
            'category_id' => 2,
            'type' => 'pengeluaran',
            'amount' => 50000,
            'transaction_time' => now()
        ]);

        $account->saldo -= 50000;

        // simpan saldo akhir
        $account->save();
    }
}