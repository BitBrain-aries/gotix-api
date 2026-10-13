<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    // 1. Menampilkan daftar riwayat transaksi milik user yang sedang login
    public function index()
    {
        $transactions = Transaction::with('movie')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json([
            'message' => 'Riwayat transaksi berhasil diambil',
            'data'    => $transactions
        ], 200);
    }

    // 2. Menyimpan pesanan tiket baru (Checkout)
    public function store(Request $request)
    {
        $request->validate([
            'movie_id'       => 'required|exists:movies,id',
            'selected_seats' => 'required|string',
            'show_time'      => 'required|string',
            'total_price'    => 'required|numeric',
        ]);

        $transaction = Transaction::create([
            'user_id'        => Auth::id(),
            'movie_id'       => $request->movie_id,
            'selected_seats' => $request->selected_seats,
            'show_time'      => $request->show_time,
            'total_price'    => $request->total_price,
            'status'         => 'success',
        ]);

        return response()->json([
            'message' => 'Tiket berhasil dipesan!',
            'data'    => $transaction->load('movie')
        ], 201);
    }

    // 3. Menampilkan detail satu transaksi / e-ticket berdasarkan ID
    public function show($id)
    {
        $transaction = Transaction::with('movie', 'user')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$transaction) {
            return response()->json([
                'message' => 'Transaksi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Detail e-ticket berhasil diambil',
            'data'    => $transaction
        ], 200);
    }
}