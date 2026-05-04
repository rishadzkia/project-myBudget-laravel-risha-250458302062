<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // 🔍 GET semua category
    public function index()
    {
        $categories = Category::all();

        return response([
            'data' => $categories
        ], 200);
    }

    // ➕ POST tambah category
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'category' => 'required|string|max:100',
            'icon' => 'nullable|string|max:50'
        ]);

        $category = Category::create([
            'user_id' => $request->user_id,
            'category' => $request->category,
            'icon' => $request->icon
        ]);

        return response([
            'message' => 'Category berhasil dibuat',
            'data' => $category
        ], 201);
    }

    // 🔍 GET detail category
    public function show($id)
    {
        $category = Category::find($id);

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
        $category = Category::find($id);

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
    public function destroy($id)
    {
        $category = Category::find($id);

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