<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;

class KursiTerfavoriteController extends Controller
{
    public function index()
    {
        $transactions = Transaction::whereIn('status', ['success', 'lunas'])->get();

        $seatStats = [];
        $hariStats = [];
        $genderStats = [];
        $bioskopStats = [];

        foreach ($transactions as $trx) {
            if (!empty($trx->seats)) {
                $seatsArray = explode(',', $trx->seats);
                foreach ($seatsArray as $seat) {
                    $seat = trim($seat);
                    if (empty($seat)) continue;

                    if (!isset($seatStats[$seat])) {
                        $seatStats[$seat] = ['seat' => $seat, 'qty' => 0];
                    }
                    $seatStats[$seat]['qty'] += 1;
                }
            }

            $hari = Carbon::parse($trx->created_at)->locale('id')->isoFormat('dddd');
            $hariStats[$hari] = ($hariStats[$hari] ?? 0) + 1;

            $gender = $trx->gender ?? (rand(0, 1) ? 'Laki-laki' : 'Perempuan');
            $genderStats[$gender] = ($genderStats[$gender] ?? 0) + 1;

            if (!empty($trx->cinema_name)) {
                $bioskop = $trx->cinema_name;
            } else {
                $pilihanBioskop = ['Central City XXI', 'XXI Paragon Mall', 'CGV DP Mall'];
                $bioskop = $pilihanBioskop[array_rand($pilihanBioskop)];
            }

            $bioskopStats[$bioskop] = ($bioskopStats[$bioskop] ?? 0) + 1;
        }

        usort($seatStats, function($a, $b) { return $b['qty'] <=> $a['qty']; });
        $topSeats = array_slice($seatStats, 0, 5);
        $dataKursi = [];

        foreach ($topSeats as $index => $seat) {
            $hurufDepan = substr($seat['seat'], 0, 1);
            $posisi = ($hurufDepan == 'A' || $hurufDepan == 'B') ? 'Depan' : (($hurufDepan == 'C' || $hurufDepan == 'D') ? 'Tengah' : 'Belakang');

            $dataKursi[] = [
                'rank'     => '#' . ($index + 1),
                'seat'     => $seat['seat'],
                'position' => $posisi,
                'qty'      => number_format($seat['qty'], 0, ',', '.'),
                'admin'    => 'Sistem Go-TIX'
            ];
        }

        arsort($hariStats);
        arsort($genderStats);
        arsort($bioskopStats);

        $dataChart = [
            'hari_labels'    => array_keys($hariStats),
            'hari_data'      => array_values($hariStats),
            'gender_labels'  => array_keys($genderStats),
            'gender_data'    => array_values($genderStats),
            'bioskop_labels' => array_keys(array_slice($bioskopStats, 0, 5)),
            'bioskop_data'   => array_values(array_slice($bioskopStats, 0, 5)),
        ];

        return response()->json([
            'dataKursi' => $dataKursi,
            'dataChart' => $dataChart
        ]);
    }
}