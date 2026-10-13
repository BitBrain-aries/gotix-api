<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class FilmTerfavoriteController extends Controller
{
    public function index()
    {
        $films = Transaction::select('movie_title', DB::raw('COUNT(id) as qty'), DB::raw('SUM(total_price) as revenue'))
            ->whereIn('status', ['success', 'lunas'])
            ->groupBy('movie_title')
            ->orderBy('qty', 'DESC')
            ->limit(5)
            ->get();

        $dataFilm = [];

        foreach ($films as $index => $film) {
            $namaFilm = $film->movie_title;
            $posterUrl = '';

            if (file_exists(public_path('images/' . $namaFilm . '.jpg'))) {
                $posterUrl = asset('images/' . $namaFilm . '.jpg');
            } elseif (file_exists(public_path('images/' . $namaFilm . '.png'))) {
                $posterUrl = asset('images/' . $namaFilm . '.png');
            } elseif (file_exists(public_path('images/' . $namaFilm . '.jpeg'))) {
                $posterUrl = asset('images/' . $namaFilm . '.jpeg');
            } else {
                $posterUrl = 'https://via.placeholder.com/300x450/161616/ff8c00?text=' . urlencode($namaFilm);
            }

            $sinopsis = 'Saksikan keseruan aksi memukau dan pengalaman sinematik tak terlupakan dalam film "' . $film->movie_title . '" yang berhasil menduduki peringkat atas penjualan tiket terbanyak di Go-TIX minggu ini.';
            $rating = '4.' . (9 - $index);

            $dataFilm[] = [
                'rank'     => '#' . ($index + 1),
                'title'    => $film->movie_title,
                'qty'      => number_format($film->qty, 0, ',', '.'),
                'revenue'  => 'Rp ' . number_format($film->revenue, 0, ',', '.'),
                'admin'    => 'Sistem Go-TIX',
                'poster'   => $posterUrl,
                'synopsis' => $sinopsis,
                'rating'   => $rating
            ];
        }

        return response()->json(['dataFilm' => $dataFilm]);
    }
}