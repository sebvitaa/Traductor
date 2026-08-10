<?php

use App\Http\Controllers\TraductorController;

Route::view('/', 'traductor')->name('home');        // muestra la página
Route::post('/traducir', [TraductorController::class, 'traducir'])->name('traducir');