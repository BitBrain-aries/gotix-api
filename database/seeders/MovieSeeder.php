<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        // Kosongkan tabel movies agar tidak terjadi duplikasi data saat re-seed
        Movie::truncate();

        $movies = [
            [
                'title'   => 'Aladdin',
                'image'   => 'Aladin.jpg',
                'rating'  => '4.5',
                'summary' => 'Petualangan pemuda jalanan menemukan lampu ajaib dan jin yang mengubah hidupnya.'
            ],
            [
                'title'   => 'Aquaman',
                'image'   => 'Aquaman.jpg',
                'rating'  => '4.6',
                'summary' => 'Pewaris tahta Atlantis harus melangkah maju untuk memimpin rakyatnya dan menyelamatkan dunia.'
            ],
            [
                'title'   => 'Avengers Endgame',
                'image'   => 'Avanger End Game.jpg',
                'rating'  => '4.8',
                'summary' => 'Pertarungan pamungkas para pahlawan bumi melawan Thanos yang epik.'
            ],
            [
                'title'   => 'Avatar: The Last Airbender',
                'image'   => 'Avatar Tha Last Bender.jpg',
                'rating'  => '4.7',
                'summary' => 'Perjalanan Aang untuk menguasai empat elemen dan mengalahkan penguasa api.'
            ],
            [
                'title'   => 'Avatar: The Way Of Water',
                'image'   => 'Avatar The Way Of Water.jpg',
                'rating'  => '4.8',
                'summary' => 'Kembali ke Pandora untuk melindungi keluarga dan lautan yang sakral.'
            ],
            [
                'title'   => 'Captain Marvel',
                'image'   => 'Captain Marvel.jpg',
                'rating'  => '4.5',
                'summary' => 'Kisah asal usul salah satu pahlawan terkuat di alam semesta.'
            ],
            [
                'title'   => 'Doctor Strange',
                'image'   => 'Doctor Strange.jpg',
                'rating'  => '4.6',
                'summary' => 'Membuka gerbang dimensi yang penuh dengan kegilaan dan ancaman baru.'
            ],
            [
                'title'   => 'Frozen II',
                'image'   => 'Frozen II.jpg',
                'rating'  => '4.7',
                'summary' => 'Elsa dan Anna mencari asal usul kekuatan sihir ke hutan ajaib yang tersembunyi.'
            ],
            [
                'title'   => 'Frozen',
                'image'   => 'Frozen.jpg',
                'rating'  => '4.8',
                'summary' => 'Kisah dua saudari yang berusaha menyelamatkan kerajaan dari musim dingin abadi.'
            ],
            [
                'title'   => 'Jumanji',
                'image'   => 'Jumanji.jpg',
                'rating'  => '4.7',
                'summary' => 'Petualangan mematikan di dalam dunia game virtual yang tak tertebak.'
            ],
            [
                'title'   => 'Jumbo',
                'image'   => 'Jumbo.jpg',
                'rating'  => '4.4',
                'summary' => 'Animasi tentang persahabatan dan petualangan seru yang menghangatkan hati.'
            ],
            [
                'title'   => 'The Batman',
                'image'   => 'The Batman.jpg',
                'rating'  => '4.9',
                'summary' => 'Pembalasan dendam sang ksatria kegelapan di kota Gotham yang penuh korupsi.'
            ],
            [
                'title'   => 'War Lord',
                'image'   => 'War Lord.jpg',
                'rating'  => '4.3',
                'summary' => 'Kisah pertempuran epik dan intrik perebutan kekuasaan yang menegangkan.'
            ]
        ];

        foreach ($movies as $movie) {
            Movie::create($movie);
        }
    }
}