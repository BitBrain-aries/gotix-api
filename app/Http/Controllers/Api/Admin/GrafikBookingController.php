<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GrafikBookingController extends Controller
{
    public function index()
    {
        $weeklyValues = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->startOfWeek()->addDays($i)->format('Y-m-d');
            $weeklyValues[] = Transaction::whereDate('created_at', $date)->count();
        }

        $genreData = Transaction::select('movie_title as label', DB::raw('COUNT(*) as total'))
            ->groupBy('movie_title')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();

        $genreLabels = $genreData->pluck('label')->toArray();
        $genreValues = $genreData->pluck('total')->toArray();

        $hourData = Transaction::select('schedule_time', DB::raw('COUNT(*) as total'))
            ->groupBy('schedule_time')
            ->orderBy('schedule_time', 'asc')
            ->get();

        $hourLabels = $hourData->pluck('schedule_time')->toArray();
        $hourValues = $hourData->pluck('total')->toArray();

        $data = [
            'tiket_terjual_hari_ini'   => Transaction::whereDate('created_at', Carbon::today())->count(),
            'tiket_terjual_minggu_ini' => Transaction::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
            'film_paling_diminati'     => Transaction::select('movie_title')->groupBy('movie_title')->orderByRaw('COUNT(*) DESC')->value('movie_title') ?? '-',
            'jam_sibuk'                => Transaction::select('schedule_time')->groupBy('schedule_time')->orderByRaw('COUNT(*) DESC')->value('schedule_time') ?? '-',
            'chart_weekly'             => $weeklyValues,
            'chart_genre_labels'       => count($genreLabels) > 0 ? $genreLabels : ['Belum ada data'],
            'chart_genre_values'       => count($genreValues) > 0 ? $genreValues : [1],
            'chart_hour_labels'        => count($hourLabels) > 0 ? $hourLabels : ['Belum ada data'],
            'chart_hour_values'        => count($hourValues) > 0 ? $hourValues : [0],
        ];

        return response()->json(['data' => $data]);
    }
}