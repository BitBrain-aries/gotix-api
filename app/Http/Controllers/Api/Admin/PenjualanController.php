<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

class PenjualanController extends Controller
{
    public function index()
    {
        $hariIni = Transaction::query()
            ->whereDate('created_at', Carbon::today())
            ->whereIn('status', ['success', 'lunas'])
            ->sum('total_price');

        $bulanIni = Transaction::query()
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereIn('status', ['success', 'lunas'])
            ->sum('total_price');

        $data = [
            'total_pendapatan_hari_ini'  => 'Rp ' . number_format($hariIni, 0, ',', '.'),
            'total_pendapatan_bulan_ini' => 'Rp ' . number_format($bulanIni, 0, ',', '.'),
            'transaksi_sukses'           => Transaction::query()->whereIn('status', ['success', 'lunas'])->count(),
            'transaksi_pending'          => Transaction::query()->where('status', 'pending')->count()
        ];

        $transactions = Transaction::with('user')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'data'         => $data,
            'transactions' => $transactions
        ]);
    }
}