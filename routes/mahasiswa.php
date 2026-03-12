<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PenelitianMahasiswaController;
use App\Http\Controllers\PraktikumMahasiswaController;

Route::middleware(['akses:All'])->group(function () {
    Route::get('/penelitian/', [PenelitianMahasiswaController::class, 'index'])->name('penelitian_mhs_index');
    Route::get('/praktikum/', [PraktikumMahasiswaController::class, 'index'])->name('praktikum_mhs_index');
});