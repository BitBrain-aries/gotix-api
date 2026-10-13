<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom yang dipakai API sekarang (tidak diubah)
        $keep = [
            'id', 'user_id', 'movie_title', 'cinema_branch', 'schedule_day',
            'schedule_time', 'seats', 'foods', 'total_price', 'payment_method',
            'status', 'created_at', 'updated_at',
        ];

        // Kolom lama lain yang wajib diisi (NOT NULL tanpa default) dijadikan boleh kosong
        foreach (DB::select('SHOW COLUMNS FROM transactions') as $col) {
            if (in_array($col->Field, $keep)) {
                continue;
            }

            $wajibIsi = $col->Null === 'NO'
                && $col->Default === null
                && stripos($col->Extra, 'auto_increment') === false;

            if ($wajibIsi) {
                DB::statement("ALTER TABLE transactions MODIFY `{$col->Field}` {$col->Type} NULL");
            }
        }
    }

    public function down(): void
    {
        // dibiarkan kosong agar data tidak hilang saat rollback
    }
};