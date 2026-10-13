<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    // Mengambil semua daftar film
    public function index()
    {
        return response()->json([
            'message' => 'Katalog film berhasil diambil',
            'data'    => Movie::all()
        ], 200);
    }

    // Mengambil detail satu film berdasarkan ID
    public function show($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json([
                'message' => 'Film tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail film berhasil diambil',
            'data'    => $movie
        ], 200);
    }
}