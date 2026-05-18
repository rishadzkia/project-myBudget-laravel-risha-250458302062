<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Bill;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // 🔍 GET semua transaksi milik user login
    public function index(Request $request)
    {
        $transactions = Transaction::with(['account', 'category', 'bill'])
            ->where('user_id', $request->user()->id)
            ->get();

        return response([
            'data' => $transactions
        ], 200);
    }

    // ➕ CREATE transaksi
    public function store(Request $request)
    {
        $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'bill_id' => 'nullable|exists:bills,id',
            'type' => 'required|in:pemasukan,pengeluaran',
            'amount' => 'required|numeric|min:0'
        ]);

        $userId = $request->user()->id;

        $account = Account::where('id', $request->account_id)
            ->where('user_id', $userId)
            ->first();

        if (! $account) {
            return response([
                'message' => 'Account tidak ditemukan atau bukan milik Anda'
            ], 403);
        }

        if ($request->category_id && ! Category::where('id', $request->category_id)
            ->where('user_id', $userId)
            ->exists()) {
            return response([
                'message' => 'Category tidak ditemukan atau bukan milik Anda'
            ], 403);
        }

        if ($request->bill_id && ! Bill::where('id', $request->bill_id)
            ->where('user_id', $userId)
            ->exists()) {
            return response([
                'message' => 'Bill tidak ditemukan atau bukan milik Anda'
            ], 403);
        }

        $transaction = Transaction::create([
            'user_id' => $userId,
            'account_id' => $request->account_id,
            'category_id' => $request->category_id,
            'bill_id' => $request->bill_id,
            'type' => $request->type,
            'amount' => $request->amount,
            'transaction_time' => now()
        ]);

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
    public function show(Request $request, $id)
    {
        $transaction = Transaction::with(['account', 'category', 'bill'])
            ->where('user_id', $request->user()->id)
            ->find($id);

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
        $request->validate([
            'account_id' => 'sometimes|exists:accounts,id',
            'category_id' => 'nullable|exists:categories,id',
            'bill_id' => 'nullable|exists:bills,id',
            'type' => 'sometimes|in:pemasukan,pengeluaran',
            'amount' => 'sometimes|numeric|min:0'
        ]);

        $userId = $request->user()->id;

        $transaction = Transaction::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (! $transaction) {
            return response([
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $oldAccount = Account::findOrFail($transaction->account_id);

        if ($transaction->type === 'pemasukan') {
            $oldAccount->saldo -= $transaction->amount;
        } else {
            $oldAccount->saldo += $transaction->amount;
        }

        $oldAccount->save();

        $newAccountId = $request->account_id ?? $transaction->account_id;
        $newAccount = Account::where('id', $newAccountId)
            ->where('user_id', $userId)
            ->first();

        if (! $newAccount) {
            return response([
                'message' => 'Account baru tidak ditemukan atau bukan milik Anda'
            ], 403);
        }

        if ($request->category_id && ! Category::where('id', $request->category_id)
            ->where('user_id', $userId)
            ->exists()) {
            return response([
                'message' => 'Category tidak ditemukan atau bukan milik Anda'
            ], 403);
        }

        if ($request->bill_id && ! Bill::where('id', $request->bill_id)
            ->where('user_id', $userId)
            ->exists()) {
            return response([
                'message' => 'Bill tidak ditemukan atau bukan milik Anda'
            ], 403);
        }

        $newType = $request->type ?? $transaction->type;
        $newAmount = $request->amount ?? $transaction->amount;

        if ($newType === 'pemasukan') {
            $newAccount->saldo += $newAmount;
        } else {
            $newAccount->saldo -= $newAmount;
        }

        $newAccount->save();

        $transaction->update([
            'account_id' => $newAccountId,
            'category_id' => $request->category_id ?? $transaction->category_id,
            'bill_id' => $request->bill_id ?? $transaction->bill_id,
            'type' => $newType,
            'amount' => $newAmount
        ]);

        return response([
            'message' => 'Transaksi berhasil diupdate',
            'data' => $transaction
        ], 200);
    }

    // ❌ DELETE (FIX SALDO 🔥)
    public function destroy(Request $request, $id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $transaction) {
            return response([
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        $account = Account::findOrFail($transaction->account_id);

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