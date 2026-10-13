<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('movie_title');
            $table->string('cinema_branch')->nullable();
            $table->string('schedule_day')->nullable();
            $table->string('schedule_time');
            $table->string('seats');
            $table->json('foods')->nullable();
            $table->unsignedBigInteger('total_price');
            $table->string('payment_method');
            $table->string('status')->default('paid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};