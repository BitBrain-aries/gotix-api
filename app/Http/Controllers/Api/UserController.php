<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // Sudah diperbaiki 'Http' kapital
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Simpan atau Update Profil User yang sedang Login
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'nama_lengkap'  => 'required|string|max:255',
            'domisili'      => 'required|string|max:255',
            'no_telepon'    => 'nullable|string',
            'jenis_kelamin' => 'nullable|string',
            'tgl_lahir'     => 'nullable|date',
            'bio'           => 'nullable|string',
            'foto_profil'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // 2. Ambil user yang sedang login via Bearer Token
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated / Token tidak valid.'
            ], 401);
        }

        // 3. Handle Upload Foto Profil (Jika ada)
        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('profiles', 'public');
            $validated['foto_profil'] = $path;
        }

        // 4. Simpan / Update ke Database
        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        // 5. Kembalikan Respon JSON Murni (Khusus API)
        return response()->json([
            'message' => 'Profil berhasil disimpan!',
            'data'    => $profile
        ], 200);
    }

    /**
     * Ambil Data Profil User
     */
    public function show(Request $request)
    {
        $user = $request->user()->load('profile');

        return response()->json([
            'message' => 'Data profil berhasil diambil',
            'data'    => $user
        ], 200);
    }
}