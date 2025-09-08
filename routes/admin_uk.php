<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiAlatOperatorController;
use App\Http\Controllers\PermintaanLayananController;

Route::middleware(['akses:2,3'])->group(function () {
    Route::get('/permintaan-layanan/', [PermintaanLayananController::class, 'index_admin'])->name('permintaan_layanan_index_admin');
    Route::post('/permintaan-layanan/tarikdatalayanan_uk', [PermintaanLayananController::class, 'tarik_layanan_uk'])->name('permintaanlayanan_tarik_permintaan_admin');
    Route::post('/permintaan-layanan/getdatalayanan_uk', [PermintaanLayananController::class, 'get_layanan_uk'])->name('permintaanlayanan_get_permintaan_admin');
    Route::get('/permintaan-layanan/detailpermintaanlayanan/{id}', [PermintaanLayananController::class, 'detailpermintaanlayanan'])->name('permintaanlayanan_detail_admin');
    Route::post('/permintaan-layanan/simpantslayanan', [PermintaanLayananController::class, 'simpantslayanan'])->name('permintaanlayanan_save_ts_admin');
    Route::post('/permintaan-layanan/setstatuspermintaanlayanan', [PermintaanLayananController::class, 'setstatuspermintaanlayanan'])->name('permintaan_layanan_set_status_admin');

    Route::get('/pemakaian-alat/', [TransaksiAlatOperatorController::class, 'index'])->name('pemakaian_alat_index');
	Route::get('/pemakaian-alat/tambah', [TransaksiAlatOperatorController::class, 'tambah'])->name('pemakaian_alat_tambah');
	Route::post('/pemakaian-alat/tambah-simpan', [TransaksiAlatOperatorController::class, 'tambah_simpan'])->name('pemakaian_alat_simpan');
	Route::get('/pemakaian-alat/hapus/{id}', [TransaksiAlatOperatorController::class, 'hapus'])->name('pemakaian_alat_hapus');
	Route::get('/pemakaian-alat/reset-password/{id}', [TransaksiAlatOperatorController::class, 'reset_password'])->name('pemakaian_alat_reset_password');
	Route::get('/pemakaian-alat/edit/{id}', [TransaksiAlatOperatorController::class, 'edit'])->name('pemakaian_alat_edit');
	Route::post('/pemakaian-alat/edit/{id}/simpan', [TransaksiAlatOperatorController::class, 'edit_simpan'])->name('pemakaian_alat_edit_simpan');
});