<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Category;
use App\Models\MonthlySetting;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $data = Budget::with(['category', 'monthlySetting'])
            ->whereHas('monthlySetting', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->whereHas('category', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->get();

        return response([
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'monthly_setting_id' => 'required|exists:monthly_settings,id',
            'category_id' => 'required|exists:categories,id',
            'budget_amount' => 'required|numeric|min:0'
        ]);

        $userId = $request->user()->id;

        $monthlySetting = MonthlySetting::where('id', $request->monthly_setting_id)
            ->where('user_id', $userId)
            ->first();

        if (! $monthlySetting) {
            return response([
                'message' => 'Akses ditolak'
            ], 403);
        }

        $category = Category::where('id', $request->category_id)
            ->where('user_id', $userId)
            ->first();

        if (! $category) {
            return response([
                'message' => 'Category tidak ditemukan atau bukan milik Anda'
            ], 403);
        }

        $exists = Budget::where('monthly_setting_id', $request->monthly_setting_id)
            ->where('category_id', $request->category_id)
            ->first();

        if ($exists) {
            return response([
                'message' => 'Budget kategori ini sudah ada'
            ], 400);
        }

        $data = Budget::create([
            'monthly_setting_id' => $request->monthly_setting_id,
            'category_id' => $request->category_id,
            'budget_amount' => $request->budget_amount
        ]);

        return response([
            'message' => 'Budget berhasil dibuat',
            'data' => $data
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $data = Budget::with(['category', 'monthlySetting'])
            ->whereHas('monthlySetting', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->whereHas('category', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
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

    public function update(Request $request, $id)
    {
        $data = Budget::whereHas('monthlySetting', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->whereHas('category', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->where('id', $id)
            ->first();

        if (! $data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'budget_amount' => 'required|numeric|min:0'
        ]);

        $data->update([
            'budget_amount' => $request->budget_amount
        ]);

        return response([
            'message' => 'Budget berhasil diupdate',
            'data' => $data
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $data = Budget::whereHas('monthlySetting', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->whereHas('category', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->where('id', $id)
            ->first();

        if (! $data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response([
            'message' => 'Budget berhasil dihapus'
        ], 200);
    }
}