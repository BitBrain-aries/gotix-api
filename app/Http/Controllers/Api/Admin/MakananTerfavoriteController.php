<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;

class MakananTerfavoriteController extends Controller
{
    public function index()
    {
        $transactions = Transaction::whereNotNull('foods')->whereIn('status', ['success', 'lunas'])->get();

        $foodStats = [];

        foreach ($transactions as $trx) {
            $foods = is_array($trx->foods) ? $trx->foods : json_decode($trx->foods, true);

            if (is_array($foods)) {
                foreach ($foods as $food) {
                    $name = $food['name'] ?? 'Unknown';
                    $qty = $food['qty'] ?? 1;
                    $total = $food['total'] ?? 0;

                    if (!isset($foodStats[$name])) {
                        $foodStats[$name] = ['name' => $name, 'qty' => 0, 'revenue' => 0];
                    }

                    $foodStats[$name]['qty'] += $qty;
                    $foodStats[$name]['revenue'] += $total;
                }
            }
        }

        usort($foodStats, function($a, $b) { return $b['qty'] <=> $a['qty']; });
        $topFoods = array_slice($foodStats, 0, 5);

        $dataMakanan = [];

        foreach ($topFoods as $index => $food) {
            $posterUrl = asset('images/' . $food['name'] . '.jpg');
            $deskripsi = 'Nikmati kesegaran dan kelezatan "' . $food['name'] . '" yang menjadi menu F&B pilihan paling favorit pelanggan setia Go-TIX minggu ini.';
            $rating = '4.' . (9 - $index);

            $dataMakanan[] = [
                'rank'     => '#' . ($index + 1),
                'name'     => $food['name'],
                'qty'      => number_format($food['qty'], 0, ',', '.'),
                'revenue'  => 'Rp ' . number_format($food['revenue'], 0, ',', '.'),
                'admin'    => 'Sistem Go-TIX',
                'poster'   => $posterUrl,
                'synopsis' => $deskripsi,
                'rating'   => $rating
            ];
        }

        return response()->json(['dataMakanan' => $dataMakanan]);
    }
}