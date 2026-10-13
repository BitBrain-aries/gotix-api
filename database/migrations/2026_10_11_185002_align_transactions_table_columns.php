<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'movie_title')) {
                $table->string('movie_title')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'cinema_branch')) {
                $table->string('cinema_branch')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'schedule_day')) {
                $table->string('schedule_day')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'schedule_time')) {
                $table->string('schedule_time')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'seats')) {
                $table->string('seats')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'foods')) {
                $table->json('foods')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'total_price')) {
                $table->unsignedBigInteger('total_price')->default(0);
            }
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (!Schema::hasColumn('transactions', 'status')) {
                $table->string('status')->default('paid');
            }
        });

        // Kolom lama yang tidak dipakai lagi dibuat nullable supaya insert tidak ditolak
        foreach (['movie_id', 'showtime_id', 'schedule_id'] as $oldColumn) {
            if (Schema::hasColumn('transactions', $oldColumn)) {
                Schema::table('transactions', function (Blueprint $table) use ($oldColumn) {
                    $table->unsignedBigInteger($oldColumn)->nullable()->change();
                });
            }
        }
    }

    public function down(): void
    {
        // dibiarkan kosong agar data tidak hilang saat rollback
    }
};