<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransaksiAlatOperatorController;
use App\Http\Controllers\PermintaanLayananController;
use App\Http\Controllers\ProsesMaintenanceController;

Route::middleware(['akses:2,3,4'])->group(function () {
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

    Route::get('/proses-maintenance/', [ProsesMaintenanceController::class, 'index'])->name('proses_maintenance_index');
    Route::post('/proses-maintenance/tarikdataaset_uk', [ProsesMaintenanceController::class, 'tarik_maintenance_aset'])->name('prosesmaintenance_tarik_aset_uk');
    Route::get('/proses-maintenance/tambah_maintenance/{kodeaset}', [ProsesMaintenanceController::class, 'tambah_maintenance'])->name('prosesmaintenance_tambah_maintenance');
    Route::post('/proses-maintenance/simpan_ts_maintenance', [ProsesMaintenanceController::class, 'simpan_ts_maintenance'])->name('prosesmaintenance_simpan_ts_maintenance');
    Route::post('/proses-maintenance/get_riwayat_maintenance', [ProsesMaintenanceController::class, 'get_riwayat_maintenance'])->name('prosesmaintenance_lihat_riwayat_maintenance');
    Route::post('/proses-maintenance/get_form', [ProsesMaintenanceController::class, 'get_form'])->name('prosesmaintenance_get_form');
    Route::post('/proses-maintenance/simpan_mulai_proses', [ProsesMaintenanceController::class, 'simpan_mulai_proses'])->name('prosesmaintenance_simpan_mulai_maintenance_aset');
    Route::get('/proses-maintenance/edit_maintenance_aset/{idmaintenance}', [ProsesMaintenanceController::class, 'edit_maintenance_aset'])->name('prosesmaintenance_edit_maintenance_aset');
    Route::get('/proses-maintenance/view_maintenance_aset/{idmaintenance}', [ProsesMaintenanceController::class, 'view_maintenance_aset'])->name('prosesmaintenance_view_maintenance_aset');
    Route::post('/proses-maintenance/get_form_template', [ProsesMaintenanceController::class, 'get_form_template'])->name('prosesmaintenance_get_form_template');
    Route::post('/proses-maintenance/form_submit_maintenance_proses', [ProsesMaintenanceController::class, 'submit_form_proses_maintenance'])->name('form_submit_maintenance_proses');
    Route::post('/proses-maintenance/form_batal_ajuan', [ProsesMaintenanceController::class, 'form_batal_ajuan'])->name('form_batal_ajuan');
    Route::post('/proses-maintenance/get_pengajuan_verifikasi', [ProsesMaintenanceController::class, 'get_pengajuan_verifikasi'])->name('prosesmaintenance_get_pengajuan_verifikasi');

});