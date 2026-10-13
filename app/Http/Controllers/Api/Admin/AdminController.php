<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;

class AdminController extends Controller
{
    /**
     * GET /api/admin/dashboard : ringkasan data untuk admin (JSON)
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'total_users'        => User::where('is_admin', false)->count(),
                'total_transactions' => Transaction::count(),
                'total_revenue'      => (int) Transaction::sum('total_price'),
                'today_transactions' => Transaction::whereDate('created_at', today())->count(),
                'latest_transactions' => Transaction::with('user:id,name,email')
                    ->latest()
                    ->take(5)
                    ->get(),
            ],
        ]);
    }
}