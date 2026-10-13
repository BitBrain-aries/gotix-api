<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| WEB ROUTES (GOTIX-API)
|--------------------------------------------------------------------------
| Project ini hanya backend API. Semua endpoint ada di routes/api.php.
| Halaman tampilan (login, beranda, struk, dll) ada di project gotix-app.
*/

// ✅ DIPERBAIKI: semua route frontend dihapus (controller-nya tidak ada di project API)

// Cek cepat apakah API hidup: buka http://127.0.0.1:8000/
Route::get('/', function () {
    return response()->json([
        'app'    => 'Go-Tix API',
        'status' => 'running',
    ]);
});

// ✅ DITAMBAHKAN: route bernama "login" supaya request tanpa token tidak error
// "Route [login] not defined" (Laravel mengarahkan user tak terautentikasi ke route ini).
Route::get('/login', function () {
    return response()->json(['message' => 'Unauthenticated.'], 401);
})->name('login');