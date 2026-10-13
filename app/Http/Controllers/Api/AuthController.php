<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cek Autentikasi ke Database
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // 3. Ambil Data User & Buat Bearer Token (Sanctum)
        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Kembalikan Respon JSON murni
        return response()->json([
            'message'      => 'Login berhasil',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'is_admin'     => (bool) $user->is_admin,
            'has_profile'  => (bool) $user->profile, // memverifikasi relasi profile user
            'user'         => $user
        ], 200);
    }

    public function register(Request $request)
    {
        // 1. Validasi Input Registrasi
        $request->validate([
            'nama_lengkap'          => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|confirmed',
        ]);

        // 2. Simpan User Baru ke Database
        $user = User::create([
            'name'     => $request->nama_lengkap,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => false,
        ]);

        // 3. Buat Bearer Token
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Kembalikan Respon JSON
        return response()->json([
            'message'      => 'Registrasi berhasil',
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'is_admin'     => false,
            'has_profile'  => false,
            'user'         => $user
        ], 201);
    }

    public function logout(Request $request)
    {
        // Hapus token akses yang sedang digunakan saat ini
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }

        return response()->json([
            'message' => 'Anda telah berhasil keluar (Logout API)'
        ], 200);
    }
}