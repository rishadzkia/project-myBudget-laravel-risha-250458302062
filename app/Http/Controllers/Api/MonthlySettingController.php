<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MonthlySetting;
use Illuminate\Http\Request;

class MonthlySettingController extends Controller
{
    // 🔍 GET semua
    public function index()
    {
        $data = MonthlySetting::all();

        return response([
            'data' => $data
        ], 200);
    }

    // ➕ CREATE
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer',
            'daily_budget' => 'required|numeric|min:0'
        ]);

        // 🔥 CEK DUPLIKAT (karena unique constraint)
        $exists = MonthlySetting::where('user_id', $request->user_id)
            ->where('month', $request->month)
            ->where('year', $request->year)
            ->first();

        if ($exists) {
            return response([
                'message' => 'Setting bulan ini sudah ada'
            ], 400);
        }

        $data = MonthlySetting::create([
            'user_id' => $request->user_id,
            'month' => $request->month,
            'year' => $request->year,
            'daily_budget' => $request->daily_budget,
            'total_income' => 0,
            'total_saving' => 0
        ]);

        return response([
            'message' => 'Monthly setting berhasil dibuat',
            'data' => $data
        ], 201);
    }

    // 🔍 DETAIL
    public function show($id)
    {
        $data = MonthlySetting::find($id);

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
        $data = MonthlySetting::find($id);

        if (! $data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->update([
            'daily_budget' => $request->daily_budget ?? $data->daily_budget,
            'total_income' => $request->total_income ?? $data->total_income,
            'total_saving' => $request->total_saving ?? $data->total_saving
        ]);

        return response([
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ], 200);
    }

    // ❌ DELETE
    public function destroy($id)
    {
        $data = MonthlySetting::find($id);

        if (! $data) {
            return response([
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $data->delete();

        return response([
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}