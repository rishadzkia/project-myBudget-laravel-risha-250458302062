<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\MonthlySetting;
use App\Models\Transaction;
use Illuminate\Http\Request;

class MonthlySettingController extends Controller
{
    // 🔍 GET semua (punya user login)
    public function index(Request $request)
    {
        $data = MonthlySetting::where('user_id', $request->user()->id)->get();

        return response([
            'data' => $data
        ], 200);
    }

    // ➕ CREATE
    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'total_income' => 'nullable|numeric|min:0',
            'total_saving' => 'nullable|numeric|min:0',
            'daily_budget_limit' => 'nullable|numeric|min:0',
        ]);

        $userId = $request->user()->id;

        // 🔥 Cek duplikat
        $exists = MonthlySetting::where('user_id', $userId)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();

        if ($exists) {
            return response([
                'message' => 'Setting bulan ini sudah ada'
            ], 400);
        }

        $data = MonthlySetting::create([
            'user_id' => $userId,
            'month' => $request->month,
            'year' => $request->year,
            'total_income' => $request->total_income ?? 0,
            'total_saving' => $request->total_saving ?? 0,
            'daily_budget_limit' => $request->daily_budget_limit ?? 0,
        ]);

        return response([
            'message' => 'Monthly setting berhasil dibuat',
            'data' => $data
        ], 201);
    }

    // 🔍 DETAIL
    public function show(Request $request, $id)
    {
        $data = MonthlySetting::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (! $data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response([
            'data' => $data
        ], 200);
    }

    // ✏️ UPDATE
    public function update(Request $request, $id)
    {
        $data = MonthlySetting::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (! $data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'total_income' => 'nullable|numeric|min:0',
            'total_saving' => 'nullable|numeric|min:0',
            'daily_budget_limit' => 'nullable|numeric|min:0',
        ]);

        $data->update([
            'total_income' => $request->total_income ?? $data->total_income,
            'total_saving' => $request->total_saving ?? $data->total_saving,
            'daily_budget_limit' => $request->daily_budget_limit ?? $data->daily_budget_limit,
        ]);

        return response([
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ], 200);
    }

    public function usage(Request $request, $id)
    {
        $monthlySetting = MonthlySetting::where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (! $monthlySetting) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $spentToday = Transaction::where('user_id', $request->user()->id)
            ->where('type', 'pengeluaran')
            ->whereDate('transaction_time', now()->toDateString())
            ->sum('amount');

        $totalBudget = Budget::where('monthly_setting_id', $monthlySetting->id)
            ->sum('budget_amount');

        return response([
            'data' => [
                'monthly_setting' => $monthlySetting,
                'spent_today' => $spentToday,
                'daily_budget_limit' => $monthlySetting->daily_budget_limit,
                'over_daily_limit' => $monthlySetting->daily_budget_limit > 0 && $spentToday > $monthlySetting->daily_budget_limit,
                'total_monthly_budget' => $totalBudget,
            ]
        ], 200);
    }
}