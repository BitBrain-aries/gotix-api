<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Disamakan dengan daftar admin di web
    private array $adminEmails = [
        'amruiqbal@gmail.com',
        'agungslamet@gmail.com',
        'masucup@gmail.com',
        'gilangyuda@gmail.com',
        'saifilmubarok@gmail.com',
        'brilianniko@gmail.com',
    ];

    /**
     * GET /api/transactions : riwayat transaksi milik user yang login
     */
    public function index(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $transactions,
        ]);
    }

    /**
     * GET /api/transactions/{id} : detail e-ticket
     */
    public function show(Request $request, $id)
    {
        $transaction = Transaction::with('user')->findOrFail($id);

        $isOwner = $transaction->user_id === $request->user()->id;
        $isAdmin = in_array($request->user()->email, $this->adminEmails);

        if (!$isOwner && !$isAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Ini bukan tiket kamu.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data'    => $transaction,
        ]);
    }
}