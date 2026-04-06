<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PenelitianMahasiswaController;
use App\Http\Controllers\PraktikumMahasiswaController;
use App\Http\Controllers\AlatLabController;

Route::middleware(['akses:All'])->group(function () {
    Route::get('/penelitian/', [PenelitianMahasiswaController::class, 'index'])->name('penelitian_mhs_index');
    Route::get('/penelitian/create/', [PenelitianMahasiswaController::class, 'create'])->name('penelitian_mhs_create');
    Route::post('/penelitian/store/', [PenelitianMahasiswaController::class, 'store'])->name('penelitian_mhs_store');
    Route::get('/penelitian/edit/{id}', [PenelitianMahasiswaController::class, 'edit'])->name('penelitian_mhs_edit');    
    Route::post('/penelitian/getform_penelitian', [PenelitianMahasiswaController::class, 'getform_penelitian'])->name('penelitian_mhs_get_form_penelitian');
    Route::post('/penelitian/get_form_template', [PenelitianMahasiswaController::class, 'get_form_template'])->name('penelitian_mhs_get_form_template');
    Route::post('/penelitian/exec_tambah_penelitian', [PenelitianMahasiswaController::class, 'exec_tambah_penelitian'])->name('penelitian_mhs_exec_tambah_penelitian');
    Route::post('/penelitian/cari_dosen', [PenelitianMahasiswaController::class, 'cari_dosen'])->name('penelitian_mhs_cari_dosen');
    Route::post('/penelitian/cek_simpan_dosen', [PenelitianMahasiswaController::class, 'cek_simpan_dosen'])->name('penelitian_mhs_cek_simpan_dosen');
    Route::post('/penelitian/get_data_aset_ruangan', [PenelitianMahasiswaController::class, 'get_data_aset_ruangan'])->name('penelitian_mhs_get_data_aset_ruangan');
    Route::get('/penelitian/show/{id}', [PenelitianMahasiswaController::class, 'show'])->name('penelitian_mhs_show');
    Route::post('/penelitian/cek_waktu', [PenelitianMahasiswaController::class, 'cek_waktu'])->name('penelitian_mhs_cek_waktu');
    Route::post('/penelitian/hapus_waktu', [PenelitianMahasiswaController::class, 'hapus_waktu'])->name('penelitian_mhs_hapus_waktu');
    Route::post('/penelitian/upload_dokumen', [PenelitianMahasiswaController::class, 'upload_dokumen'])->name('penelitian_mhs_upload_dokumen');
    Route::get('/penelitian/download_dokumen/{id}', [PenelitianMahasiswaController::class, 'download_dokumen'])->name('penelitian_mhs_download_dokumen');
    Route::get('/penelitian/hapus_dokumen/{id}', [PenelitianMahasiswaController::class, 'hapus_dokumen'])->name('penelitian_mhs_hapus_dokumen');
    Route::post('/penelitian/cek_simpan_ruangan', [PenelitianMahasiswaController::class, 'cek_simpan_ruangan'])->name('penelitian_mhs_cek_simpan_ruangan');
    Route::post('/penelitian/cek_simpan_aset', [PenelitianMahasiswaController::class, 'cek_simpan_aset'])->name('penelitian_mhs_cek_simpan_aset');
    Route::post('/penelitian/cek_hapus_aset', [PenelitianMahasiswaController::class, 'cek_hapus_aset'])->name('penelitian_mhs_hapus_aset');
    Route::post('/penelitian/cek_simpan_waktu', [PenelitianMahasiswaController::class, 'cek_simpan_waktu'])->name('penelitian_mhs_cek_simpan_waktu');
    Route::post('/penelitian/edit_tujuan_instrumen', [PenelitianMahasiswaController::class, 'edit_tujuan_instrumen'])->name('penelitian_mhs_edit_tujuan_instrumen');
    Route::post('/penelitian/cek_hapus_ruangan', [PenelitianMahasiswaController::class, 'cek_hapus_ruangan'])->name('penelitian_mhs_hapus_ruangan');
    Route::post('/penelitian/update_topik', [PenelitianMahasiswaController::class, 'update_topik'])->name('penelitian_mhs_update_topik');
    Route::post('/penelitian/edit_jadwal', [PenelitianMahasiswaController::class, 'edit_jadwal'])->name('penelitian_mhs_edit_jadwal');
    Route::post('/penelitian/hapus_jadwal', [PenelitianMahasiswaController::class, 'hapus_jadwal'])->name('penelitian_mhs_hapus_jadwal');
    Route::post('/penelitian/hapus_bahan', [PenelitianMahasiswaController::class, 'hapus_bahan'])->name('penelitian_mhs_hapus_bahan');
    
    Route::post('/penelitian/update_ajuan', [PenelitianMahasiswaController::class, 'update_ajuan'])->name('penelitian_mhs_update_ajuan');

    Route::get('/praktikum/', [PraktikumMahasiswaController::class, 'index'])->name('praktikum_mhs_index');


    Route::get('/alat_lab/', [AlatLabController::class, 'index'])->name('alat_lab_index');
});