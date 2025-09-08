<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitkerjaController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RoleUserController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\JenjangController;
use App\Http\Controllers\ProgramStudiController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\LayananAsetController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\PermintaanLayananController;
use App\Http\Controllers\PemakaianAsetController;
//use App\Http\Controllers\TransaksiAlatController;
//use App\Http\Controllers\TransaksiAlatOperatorController;

include_once "administrator.php";
include_once "admin_uk.php";

Route::get('/', function () {
	//return view('welcome');
	return redirect()->route('login');
});

Route::get('/login/', [LoginController::class, 'index'])->name('login');
Route::post('/login/', [LoginController::class, 'masuk'])->name('login_masuk');

Route::get('/get-layanan', [TransaksiAlatController::class, 'getLayanan'])->name('get.layanan');
Route::get('/get-alat', [TransaksiAlatController::class, 'getAlat'])->name('get.alat');

Route::middleware(['akses:All'])->group(function () {
	Route::get('/logout/', [LoginController::class, 'logout'])->name('logout');
	Route::get('/home/', [HomeController::class, 'index'])->name('home');
	Route::get('/ubah_password/', [HomeController::class, 'ubah_password'])->name('ubah_password');
	Route::post('/ubah_password/simpan', [HomeController::class, 'ubah_password_simpan'])->name('ubah_password_simpan');
	Route::get('/ubah_role/', [HomeController::class, 'ubah_role'])->name('ubah_role');
	Route::post('/ubah_role/simpan', [HomeController::class, 'ubah_role_simpan'])->name('ubah_role_simpan');
});

Route::get('/manajemen-alat/', [HomeController::class, 'alat'])->name('manajemen_alat_index');
Route::get('/manajemen-alat-input/', [HomeController::class, 'alat'])->name('manajemen_alat_input');




