<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    // 🔍 GET semua bills milik user login
    public function index(Request $request)
    {
        $bills = Bill::where('user_id', $request->user()->id)->get();

        return response([
            'data' => $bills
        ], 200);
    }

    // ➕ POST tambah bill
    public function store(Request $request)
    {
        $request->validate([
            'bill_name' => 'required|string|max:100',
            'amount' => 'required|numeric',
            'due_day' => 'required|integer|min:1|max:31',
            'last_paid_year' => 'nullable|integer',
            'last_paid_month' => 'nullable|integer',
        ]);

        $bill = Bill::create([
            'user_id' => $request->user()->id,
            'bill_name' => $request->bill_name,
            'amount' => $request->amount,
            'due_day' => $request->due_day,
            'last_paid_year' => $request->last_paid_year,
            'last_paid_month' => $request->last_paid_month,
        ]);

        return response([
            'message' => 'Bill berhasil dibuat',
            'data' => $bill
        ], 201);
    }

    // 🔍 GET detail bill
    public function show(Request $request, int $id)
    {
        $bill = Bill::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $bill) {
            return response([
                'message' => 'Bill tidak ditemukan'
            ], 404);
        }

        return response([
            'data' => $bill
        ], 200);
    }

    // ✏️ PUT update bill
    public function update(Request $request, int $id)
    {
        $bill = Bill::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $bill) {
            return response([
                'message' => 'Bill tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'bill_name' => 'sometimes|string|max:100',
            'amount' => 'sometimes|numeric',
            'due_day' => 'sometimes|integer|min:1|max:31',
            'last_paid_year' => 'nullable|integer',
            'last_paid_month' => 'nullable|integer',
        ]);

        $bill->update([
            'bill_name' => $request->bill_name ?? $bill->bill_name,
            'amount' => $request->amount ?? $bill->amount,
            'due_day' => $request->due_day ?? $bill->due_day,
            'last_paid_year' => $request->last_paid_year ?? $bill->last_paid_year,
            'last_paid_month' => $request->last_paid_month ?? $bill->last_paid_month,
        ]);

        return response([
            'message' => 'Bill berhasil diupdate',
            'data' => $bill
        ], 200);
    }

    // ❌ DELETE bill
    public function destroy(Request $request, int $id)
    {
        $bill = Bill::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $bill) {
            return response([
                'message' => 'Bill tidak ditemukan'
            ], 404);
        }

        $bill->delete();

        return response([
            'message' => 'Bill berhasil dihapus'
        ], 200);
    }
}