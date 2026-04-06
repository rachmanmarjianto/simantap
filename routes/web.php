<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UnitkerjaController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\LayananAsetController;
use App\Http\Controllers\PermintaanLayananController;
use App\Http\Controllers\PemakaianAsetController;
use App\Http\Controllers\FilestorageController;
use App\Http\Controllers\TransaksiAlatController;
//use App\Http\Controllers\TransaksiAlatOperatorController;

include_once "administrator.php";
include_once "admin_uk.php";
include_once "mahasiswa.php";

// Route::get('/', function () {
// 	//return view('welcome');
// 	return redirect()->route('login');
// });

Route::get('/', [HomeController::class, 'halamanpublik'])->name('publik_home');
Route::get('/publik/login', [LoginController::class, 'publik_login'])->name('publik_login');
Route::get('/publik/register', [LoginController::class, 'publik_register'])->name('publik_register');

Route::get('/reload-captcha', function () {
    return response()->json([
        'url' => captcha_src(6) . '&_=' . time(),
    ])->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
      ->header('Pragma', 'no-cache');
})->name('captcha.refresh');



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


	Route::get('/filestorage/get/{id}', [FilestorageController::class, 'get_file'])->name('filestorage_get');
	Route::post('/filestorage/hapus', [FilestorageController::class, 'hapus_file'])->name('filestorage_hapus');
});

Route::get('/manajemen-alat/', [HomeController::class, 'alat'])->name('manajemen_alat_index');
Route::get('/manajemen-alat-input/', [HomeController::class, 'alat'])->name('manajemen_alat_input');




