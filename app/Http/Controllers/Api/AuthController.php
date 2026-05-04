<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mascot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 🔐 LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'Email atau password salah'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response([
            'message' => 'Login berhasil',
            'user'  => $user,
            'token' => $token,
        ], 200);
    }

    // 🚪 LOGOUT
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Logout berhasil',
        ], 200);
    }

    // 📝 REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 🎮 AUTO BUAT MASCOT
        Mascot::create([
            'user_id' => $user->id,
            'level' => 1,
            'xp' => 0
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response([
            'message' => 'Register berhasil',
            'user'  => $user,
            'token' => $token,
        ], 201);
    }

    // 👤 GET PROFILE
    public function profile(Request $request)
    {
        return response([
            'user' => $request->user()
        ], 200);
    }

    // ✏️ UPDATE PROFILE
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'nullable|string|max:100',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8'
        ]);

        $user->update([
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
            'password' => $request->password 
                ? Hash::make($request->password) 
                : $user->password
        ]);

        return response([
            'message' => 'Profile berhasil diupdate',
            'user' => $user
        ], 200);
    }

    // ❌ DELETE ACCOUNT
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        // hapus semua token dulu
        $user->tokens()->delete();

        $user->delete();

        return response([
            'message' => 'Akun berhasil dihapus'
        ], 200);
    }
}