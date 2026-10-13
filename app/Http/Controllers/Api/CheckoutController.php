<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * ✅ DIPERBAIKI TOTAL: sebelumnya berisi kode frontend yang memanggil API sendiri.
     * Sekarang langsung menyimpan transaksi ke database API.
     */
    public function process(Request $request)
    {
        $data = $request->validate([
            'movie_title'    => 'required|string|max:255',
            'cinema_branch'  => 'nullable|string|max:255',
            'schedule_day'   => 'nullable|string|max:100',
            'schedule_time'  => 'required|string|max:50',
            'seats'          => 'required|string',
            'foods'          => 'nullable',
            'total_price'    => 'required|numeric|min:0',
            'payment_method' => 'required|string|max:100',
        ]);

        // foods boleh dikirim sebagai array atau string JSON
        $foods = $data['foods'] ?? [];
        if (is_string($foods)) {
            $foods = json_decode($foods, true);
        }
        if (!is_array($foods)) {
            $foods = [];
        }

        try {
            $transaction = Transaction::create([
                'user_id'        => $request->user()->id,
                'movie_title'    => $data['movie_title'],
                'cinema_branch'  => $data['cinema_branch'] ?? null,
                'schedule_day'   => $data['schedule_day'] ?? null,
                'schedule_time'  => $data['schedule_time'],
                'seats'          => $data['seats'],
                'foods'          => $foods,
                'total_price'    => (int) round($data['total_price']),
                'payment_method' => $data['payment_method'],
                'status'         => 'paid',
            ]);
        } catch (\Throwable $e) {
            Log::error('API checkout gagal: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success'        => true,
            'message'        => 'Transaksi pemesanan tiket berhasil!',
            'transaction_id' => $transaction->id,
            'data'           => $transaction,
        ], 201);
    }
}