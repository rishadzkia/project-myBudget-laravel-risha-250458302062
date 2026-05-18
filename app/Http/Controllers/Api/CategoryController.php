<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 🔍 GET semua category milik user login
    public function index(Request $request)
    {
        $categories = Category::where('user_id', $request->user()->id)->get();

        return response([
            'data' => $categories
        ], 200);
    }

    // ➕ POST tambah category
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50'
        ]);

        $category = Category::create([
            'user_id' => $request->user()->id,
            'category' => $request->category,
            'icon' => $request->icon
        ]);

        return response([
            'message' => 'Category berhasil dibuat',
            'data' => $category
        ], 201);
    }

    // 🔍 GET detail category
    public function show(Request $request, $id)
    {
        $category = Category::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $category) {
            return response([
                'message' => 'Category tidak ditemukan'
            ], 404);
        }

        return response([
            'data' => $category
        ], 200);
    }

    // ✏️ UPDATE category
    public function update(Request $request, $id)
    {
        $category = Category::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $category) {
            return response([
                'message' => 'Category tidak ditemukan'
            ], 404);
        }

        $category->update([
            'category' => $request->category ?? $category->category,
            'icon' => $request->icon ?? $category->icon
        ]);

        return response([
            'message' => 'Category berhasil diupdate',
            'data' => $category
        ], 200);
    }

    // ❌ DELETE category
    public function destroy(Request $request, $id)
    {
        $category = Category::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $category) {
            return response([
                'message' => 'Category tidak ditemukan'
            ], 404);
        }

        $category->delete();

        return response([
            'message' => 'Category berhasil dihapus'
        ], 200);
    }
}