<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // 🔍 GET semua transaksi
    public function index()
    {
        $transactions = Transaction::with(['account', 'category'])->get();

        return response([
            'data' => $transactions
        ], 200);
    }

    // ➕ CREATE transaksi
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'type' => 'required|in:pemasukan,pengeluaran',
            'amount' => 'required|numeric|min:0'
        ]);

        $transaction = Transaction::create([
            'user_id' => $request->user_id,
            'account_id' => $request->account_id,
            'category_id' => $request->category_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'transaction_time' => now()
        ]);

        // 🔥 update saldo
        $account = Account::findOrFail($request->account_id);

        if ($request->type === 'pemasukan') {
            $account->saldo += $request->amount;
        } else {
            $account->saldo -= $request->amount;
        }

        $account->save();

        return response([
            'message' => 'Transaksi berhasil dibuat',
            'data' => $transaction
        ], 201);
    }

    // 🔍 DETAIL
    public function show($id)
    {
        $transaction = Transaction::with(['account', 'category'])->find($id);

        if (! $transaction) {
            return response([
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        return response([
            'data' => $transaction
        ], 200);
    }

    // ✏️ UPDATE (FIX SALDO 🔥)
    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if (! $transaction) {
            return response([
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $account = Account::findOrFail($transaction->account_id);

        // 🔥 BALIKKAN saldo lama dulu
        if ($transaction->type === 'pemasukan') {
            $account->saldo -= $transaction->amount;
        } else {
            $account->saldo += $transaction->amount;
        }

        // ambil data baru
        $newType = $request->type ?? $transaction->type;
        $newAmount = $request->amount ?? $transaction->amount;

        // 🔥 TERAPKAN saldo baru
        if ($newType === 'pemasukan') {
            $account->saldo += $newAmount;
        } else {
            $account->saldo -= $newAmount;
        }

        $account->save();

        // update transaksi
        $transaction->update([
            'category_id' => $request->category_id ?? $transaction->category_id,
            'amount' => $newAmount,
            'type' => $newType
        ]);

        return response([
            'message' => 'Transaksi berhasil diupdate',
            'data' => $transaction
        ], 200);
    }

    // ❌ DELETE (FIX SALDO 🔥)
    public function destroy($id)
    {
        $transaction = Transaction::find($id);

        if (! $transaction) {
            return response([
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $account = Account::findOrFail($transaction->account_id);

        // 🔥 BALIKKAN saldo
        if ($transaction->type === 'pemasukan') {
            $account->saldo -= $transaction->amount;
        } else {
            $account->saldo += $transaction->amount;
        }

        $account->save();

        $transaction->delete();

        return response([
            'message' => 'Transaksi berhasil dihapus'
        ], 200);
    }
}