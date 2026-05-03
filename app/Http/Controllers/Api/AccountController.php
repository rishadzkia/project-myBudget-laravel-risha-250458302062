<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    // 🔍 GET semua accounts
    public function index()
    {
        $accounts = Account::all();

        return response([
            'data' => $accounts 
        ], 200);
    }

    // ➕ POST tambah account
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'account_name' => 'required|string|max:100',
            'saldo' => 'nullable|numeric'
        ]);

        $account = Account::create([
            'user_id' => $request->user_id,
            'account_name' => $request->account_name,
            'saldo' => $request->saldo ?? 0
        ]);

        return response([
            'message' => 'Account berhasil dibuat',
            'data' => $account
        ], 201);
    }

    // 🔍 GET detail account
    public function show($id)
    {
        $account = Account::find($id);

        if (! $account) {
            return response([
                'message' => 'Account tidak ditemukan'
            ], 404);
        }

        return response([
            'data' => $account
        ], 200);
    }

    // ✏️ PUT update account
    public function update(Request $request, $id)
    {
        $account = Account::find($id);

        if (! $account) {
            return response([
                'message' => 'Account tidak ditemukan'
            ], 404);
        }

        $account->update([
            'account_name' => $request->account_name ?? $account->account_name,
            'saldo' => $request->saldo ?? $account->saldo
        ]);

        return response([
            'message' => 'Account berhasil diupdate',
            'data' => $account
        ], 200);
    }

    // ❌ DELETE account
    public function destroy($id)
    {
        $account = Account::find($id);

        if (! $account) {
            return response([
                'message' => 'Account tidak ditemukan'
            ], 404);
        }

        $account->delete();

        return response([
            'message' => 'Account berhasil dihapus'
        ], 200);
    }
}