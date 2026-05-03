<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Mascot;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return response([
                'message' => ['User not found'],
            ], 404);
        }

        if (! Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['Incorrect password'],
            ], 404);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response([
            'user'  => $user,
            'token' => $token,
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Logout telah berhasil',
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|string|email|unique:users',
           
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Mascot::create([
        'user_id' => $user->id,
        'level' => 1,
        'xp' => 0
]);

        return response([
            'user'  => $user,
            'token' => $user->createToken('auth_token')->plainTextToken,
        ], 200);
    }
}
