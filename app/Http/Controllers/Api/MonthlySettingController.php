<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MonthlySetting;
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
            'total_saving' => $request->total_saving ?? 0
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

        $data->update([
            'total_income' => $request->total_income ?? $data->total_income,
            'total_saving' => $request->total_saving ?? $data->total_saving
        ]);

        return response([
            'message' => 'Data berhasil diupdate',
            'data' => $data
        ], 200);
    }

    // ❌ DELETE
    // public function destroy(Request $request, $id)
    // {
    //     $data = MonthlySetting::where('user_id', $request->user()->id)
    //         ->where('id', $id)
    //         ->first();

    //     if (! $data) {
    //         return response([
    //             'message' => 'Data tidak ditemukan'
    //         ], 404);
    //     }

    //     $data->delete();

    //     return response([
    //         'message' => 'Data berhasil dihapus'
    //     ], 200);
    // }
}