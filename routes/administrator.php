<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\LayananAsetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ApiAplikasiController;
use App\Http\Controllers\LayananOperatorController;
use App\Http\Controllers\UnitkerjaController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\PjRuangController;
use App\Http\Controllers\PraktikumController;
use App\Http\Controllers\PenelitianController;
use App\Http\Controllers\FormMaintenanceController;


Route::middleware(['akses:1,2'])->group(function () {
	$tmp_prefix = 'role';
	Route::get('/'.$tmp_prefix.'/', [RoleController::class, 'index'])->name($tmp_prefix.'_index');
	Route::get('/'.$tmp_prefix.'/tambah', [RoleController::class, 'tambah'])->name($tmp_prefix.'_tambah');
	Route::post('/'.$tmp_prefix.'/tambah-simpan', [RoleController::class, 'tambah_simpan'])->name($tmp_prefix.'_simpan');
	Route::get('/'.$tmp_prefix.'/hapus/{id}', [RoleController::class, 'hapus'])->name($tmp_prefix.'_hapus');
	Route::get('/'.$tmp_prefix.'/edit/{id}', [RoleController::class, 'edit'])->name($tmp_prefix.'_edit');
	Route::post('/'.$tmp_prefix.'/edit/{id}/simpan', [RoleController::class, 'edit_simpan'])->name($tmp_prefix.'_edit_simpan');
	
	Route::get('/user/', [UserController::class, 'index'])->name('user_index');
	Route::get('/user/tambah', [UserController::class, 'tambah'])->name('user_tambah');
	Route::post('/user/tambah-simpan', [UserController::class, 'tambah_simpan'])->name('user_simpan');
	Route::get('/user/hapus/{id}', [UserController::class, 'hapus'])->name('user_hapus');
	Route::get('/user/reset-password/{id}', [UserController::class, 'reset_password'])->name('user_reset_password');
	Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('user_edit');
	Route::post('/user/edit/{id}/simpan', [UserController::class, 'edit_simpan'])->name('user_edit_simpan');
    Route::post('/user/getuser', [UserController::class, 'getuser'])->name('user_get_user');
    Route::post('/user/ubahstatusroleuser', [UserController::class, 'ubahstatusroleuser'])->name('user_ubah_status_role_user');
    Route::post('/user/ubahdeleteroleuser', [UserController::class, 'ubahdeleteroleuser'])->name('user_hapus_role_user');
    Route::post('/user/tambahroleuser', [UserController::class, 'tambahroleuser'])->name('user_tambah_role_user');

	Route::get('/role-user/', [RoleUserController::class, 'index'])->name('role_user_index');
	Route::get('/role-user/tambah', [RoleUserController::class, 'tambah'])->name('role_user_tambah');
	Route::post('/role-user/tambah-simpan', [RoleUserController::class, 'tambah_simpan'])->name('role_user_simpan');
	Route::get('/role-user/hapus/{id}', [RoleUserController::class, 'hapus'])->name('role_user_hapus');
	Route::get('/role-user/edit/{id}', [RoleUserController::class, 'edit'])->name('role_user_edit');
	Route::post('/role-user/edit/{id}/simpan', [RoleUserController::class, 'edit_simpan'])->name('role_user_edit_simpan');

	Route::get('/report/penggunaanalat/', [ReportController::class, 'penggunaanalat'])->name('report_penggunaan_alat');
	Route::get('/report/report_penggunaanalat/{id}', [ReportController::class, 'report_penggunaanalat'])->name('report_penggunaan_alat_summary');
	Route::post('/report/set_tanggal', [ReportController::class, 'set_tanggal'])->name('report_set_tanggal');
	Route::get('/report/penggunaanalat_detail/{id}/{kode_barang}', [ReportController::class, 'report_penggunaanalat_detail'])->name('report_penggunaan_alat_detail');
	Route::get('/report/operator', [ReportController::class, 'operator'])->name('report_operator');
	Route::get('/report/report_operator/{id}', [ReportController::class, 'report_operator'])->name('report_operator_summary');
	Route::get('/report/operator_detail/{id}/{iduser}', [ReportController::class, 'operator_detail'])->name('report_operator_detail');

	Route::get('/apiaplikasi/', [ApiAplikasiController::class, 'index'])->name('api_aplikasi_index');
	Route::get('/apiaplikasi/list_aplikasi/{id}', [ApiAplikasiController::class, 'list_aplikasi'])->name('api_aplikasi_list');
	Route::get('/apiaplikasi/set_api_aplikasi/{idaplikasi}', [ApiAplikasiController::class, 'set_api_aplikasi'])->name('api_aplikasi_set');
	Route::get('/apiaplikasi/tambahaplikasi/{id}', [ApiAplikasiController::class, 'tambahaplikasi'])->name('api_aplikasi_tambah_aplikasi');
	Route::post('/apiaplikasi/setstatusaplikasi', [ApiAplikasiController::class, 'set_status_aplikasi'])->name('api_aplikasi_set_status');
	Route::post('/apiaplikasi/simpanaplikasi', [ApiAplikasiController::class, 'simpan_aplikasi'])->name('api_aplikasi_store');
	Route::post('/apiaplikasi/simpan_tambah_endpoint', [ApiAplikasiController::class, 'simpan_tambah_endpoint'])->name('api_aplikasi_tambah_endpoint');
	Route::post('/apiaplikasi/set_status_endpoint', [ApiAplikasiController::class, 'set_status_endpoint'])->name('api_aplikasi_set_status_endpoint');

	$tmp_prefix = 'fakultas';
	Route::get('/'.$tmp_prefix.'/', [FakultasController::class, 'index'])->name($tmp_prefix.'_index');
	Route::get('/'.$tmp_prefix.'/tambah', [FakultasController::class, 'tambah'])->name($tmp_prefix.'_tambah');
	Route::post('/'.$tmp_prefix.'/tambah-simpan', [FakultasController::class, 'tambah_simpan'])->name($tmp_prefix.'_simpan');
	Route::get('/'.$tmp_prefix.'/hapus/{id}', [FakultasController::class, 'hapus'])->name($tmp_prefix.'_hapus');
	Route::get('/'.$tmp_prefix.'/edit/{id}', [FakultasController::class, 'edit'])->name($tmp_prefix.'_edit');
	Route::post('/'.$tmp_prefix.'/edit/{id}/simpan', [FakultasController::class, 'edit_simpan'])->name($tmp_prefix.'_edit_simpan');

	$tmp_prefix = 'jenjang';
	Route::get('/'.$tmp_prefix.'/', [JenjangController::class, 'index'])->name($tmp_prefix.'_index');
	Route::get('/'.$tmp_prefix.'/tambah', [JenjangController::class, 'tambah'])->name($tmp_prefix.'_tambah');
	Route::post('/'.$tmp_prefix.'/tambah-simpan', [JenjangController::class, 'tambah_simpan'])->name($tmp_prefix.'_simpan');
	Route::get('/'.$tmp_prefix.'/hapus/{id}', [JenjangController::class, 'hapus'])->name($tmp_prefix.'_hapus');
	Route::get('/'.$tmp_prefix.'/edit/{id}', [JenjangController::class, 'edit'])->name($tmp_prefix.'_edit');
	Route::post('/'.$tmp_prefix.'/edit/{id}/simpan', [JenjangController::class, 'edit_simpan'])->name($tmp_prefix.'_edit_simpan');

	$tmp_prefix = 'program_studi';
	Route::get('/'.$tmp_prefix.'/', [ProgramStudiController::class, 'index'])->name($tmp_prefix.'_index');
	Route::get('/'.$tmp_prefix.'/tambah', [ProgramStudiController::class, 'tambah'])->name($tmp_prefix.'_tambah');
	Route::post('/'.$tmp_prefix.'/tambah-simpan', [ProgramStudiController::class, 'tambah_simpan'])->name($tmp_prefix.'_simpan');
	Route::get('/'.$tmp_prefix.'/hapus/{id}', [ProgramStudiController::class, 'hapus'])->name($tmp_prefix.'_hapus');
	Route::get('/'.$tmp_prefix.'/edit/{id}', [ProgramStudiController::class, 'edit'])->name($tmp_prefix.'_edit');
	Route::post('/'.$tmp_prefix.'/edit/{id}/simpan', [ProgramStudiController::class, 'edit_simpan'])->name($tmp_prefix.'_edit_simpan');

	Route::get('/layanan/', [LayananController::class, 'index'])->name('layanan_index');
	Route::get('/layanan/tambah', [LayananController::class, 'tambah'])->name('layanan_tambah');
	Route::post('/layanan/tambah-simpan', [LayananController::class, 'tambah_simpan'])->name('layanan_simpan');
	Route::get('/layanan/hapus/{id}', [LayananController::class, 'hapus'])->name('layanan_hapus');
	Route::get('/layanan/edit/{id}', [LayananController::class, 'edit'])->name('layanan_edit');
	Route::post('/layanan/edit/{id}/simpan', [LayananController::class, 'edit_simpan'])->name('layanan_edit_simpan');
    Route::get('/layanan/layananunitkerja/{id}', [LayananController::class, 'layananunitkerja'])->name('layanan_unit_kerja_detail');
    Route::post('/layanan/tarik_master_layanan', [LayananController::class, 'tarik_master_layanan'])->name('layanan_tarik_master_layanan');

	

	$tmp_prefix = 'layanan_aset';
	Route::get('/'.$tmp_prefix.'/', [LayananAsetController::class, 'index'])->name($tmp_prefix.'_index');
	Route::get('/'.$tmp_prefix.'/tambah', [LayananAsetController::class, 'tambah'])->name($tmp_prefix.'_tambah');
	Route::post('/'.$tmp_prefix.'/tambah-simpan', [LayananAsetController::class, 'tambah_simpan'])->name($tmp_prefix.'_simpan');
	Route::get('/'.$tmp_prefix.'/hapus/{id}', [LayananAsetController::class, 'hapus'])->name($tmp_prefix.'_hapus');
	Route::get('/'.$tmp_prefix.'/edit/{id}', [LayananAsetController::class, 'edit'])->name($tmp_prefix.'_edit');
	Route::post('/'.$tmp_prefix.'/edit/{id}/simpan', [LayananAsetController::class, 'edit_simpan'])->name($tmp_prefix.'_edit_simpan');
    Route::get('/'.$tmp_prefix.'/mapinglayananunitkerja/{iduk}', [LayananAsetController::class, 'mapinglayananunitkerja'])->name($tmp_prefix.'_maping_layanan_unitkerja');
    Route::get('/'.$tmp_prefix.'/mapingalatkelayanan/{iduk}/{idlayanan}', [LayananAsetController::class, 'mapingalatkelayanan'])->name($tmp_prefix.'_maping_layanan_unitkerja_detail');
    Route::post('/'.$tmp_prefix.'/prosesmapingalatkelayanan', [LayananAsetController::class, 'prosesmapingalatkelayanan'])->name($tmp_prefix.'_maping_layanan_unitkerja_tambah_alat');
    Route::post('/'.$tmp_prefix.'/simpanmapingalatkelayanan', [LayananAsetController::class, 'simpanmapingalatkelayanan'])->name($tmp_prefix.'_maping_layanan_unitkerja_simpan');
    Route::post('/'.$tmp_prefix.'/hapusmapingalatkelayanan', [LayananAsetController::class, 'hapusmapingalatkelayanan'])->name($tmp_prefix.'_maping_layanan_unitkerja_hapus_alat');
	
	$tmp_prefix = 'layanan_operator';
	Route::get('/'.$tmp_prefix.'/', [LayananOperatorController::class, 'index'])->name($tmp_prefix.'_index');
	Route::get('/'.$tmp_prefix.'/mapping/{iduk}', [LayananOperatorController::class, 'mapping'])->name($tmp_prefix.'_maping_operator');
	Route::get('/'.$tmp_prefix.'/mapping/{iduk}/{idlayanan}', [LayananOperatorController::class, 'mapping_detail'])->name($tmp_prefix.'_maping_operator_detail');
	Route::post('/'.$tmp_prefix.'/tambah_operator', [LayananOperatorController::class, 'tambahoperator'])->name($tmp_prefix.'_tambah_operator');
	Route::post('/'.$tmp_prefix.'/hapus_operator', [LayananOperatorController::class, 'hapusoperator'])->name($tmp_prefix.'_hapus_operator');
	Route::post('/'.$tmp_prefix.'/ubah_status', [LayananOperatorController::class, 'ubahstatus'])->name($tmp_prefix.'_ubah_status');

	Route::get('/unit_kerja/', [UnitkerjaController::class, 'index'])->name('unit_kerja_index');
	Route::get('/unit_kerja/tambah', [UnitkerjaController::class, 'tambah'])->name('unit_kerja_tambah');
	Route::post('/unit_kerja/tambah-simpan', [UnitkerjaController::class, 'tambah_simpan'])->name('unit_kerja_simpan');
	Route::get('/unit_kerja/hapus/{id}', [UnitkerjaController::class, 'hapus'])->name('unit_kerja_hapus');
	Route::get('/unit_kerja/edit/{id}', [UnitkerjaController::class, 'edit'])->name('unit_kerja_edit');
	Route::post('/unit_kerja/edit/{id}/simpan', [UnitkerjaController::class, 'edit_simpan'])->name('unit_kerja_edit_simpan');

	$tmp_prefix = 'ruang';
	Route::get('/'.$tmp_prefix.'/', [RuangController::class, 'index'])->name($tmp_prefix.'_index');
	Route::get('/'.$tmp_prefix.'/tambah', [RuangController::class, 'tambah'])->name($tmp_prefix.'_tambah');
	Route::post('/'.$tmp_prefix.'/tambah-simpan', [RuangController::class, 'tambah_simpan'])->name($tmp_prefix.'_simpan');
	Route::get('/'.$tmp_prefix.'/hapus/{id}', [RuangController::class, 'hapus'])->name($tmp_prefix.'_hapus');
	Route::get('/'.$tmp_prefix.'/edit/{id}', [RuangController::class, 'edit'])->name($tmp_prefix.'_edit');
	Route::post('/'.$tmp_prefix.'/edit/{id}/simpan', [RuangController::class, 'edit_simpan'])->name($tmp_prefix.'_edit_simpan');

	

	$tmp_prefix = 'pemakaian_aset';
	Route::get('/'.$tmp_prefix.'/', [PemakaianAsetController::class, 'index'])->name($tmp_prefix.'_index');
	Route::get('/'.$tmp_prefix.'/tambah', [PemakaianAsetController::class, 'tambah'])->name($tmp_prefix.'_tambah');
	Route::post('/'.$tmp_prefix.'/tambah-simpan', [PemakaianAsetController::class, 'tambah_simpan'])->name($tmp_prefix.'_simpan');
	Route::get('/'.$tmp_prefix.'/hapus/{id}', [PemakaianAsetController::class, 'hapus'])->name($tmp_prefix.'_hapus');
	Route::get('/'.$tmp_prefix.'/edit/{id}', [PemakaianAsetController::class, 'edit'])->name($tmp_prefix.'_edit');
	Route::post('/'.$tmp_prefix.'/edit/{id}/simpan', [PemakaianAsetController::class, 'edit_simpan'])->name($tmp_prefix.'_edit_simpan');

	/*
	Route::get('/unit/', [UnitController::class, 'index'])->name('unit_index');
	Route::get('/unit/tambah', [UnitController::class, 'tambah'])->name('unit_tambah');
	Route::post('/unit/tambah-simpan', [UnitController::class, 'tambah_simpan'])->name('unit_simpan');
	Route::get('/unit/hapus/{id}', [UnitController::class, 'hapus'])->name('unit_hapus');
	Route::get('/unit/edit/{id}', [UnitController::class, 'edit'])->name('unit_edit');
	Route::post('/unit/edit/{id}/simpan', [UnitController::class, 'edit_simpan'])->name('unit_edit_simpan');

	Route::get('/layanan/', [LayananController::class, 'index'])->name('layanan_index');
	Route::get('/layanan/tambah', [LayananController::class, 'tambah'])->name('layanan_tambah');
	Route::post('/layanan/tambah-simpan', [LayananController::class, 'tambah_simpan'])->name('layanan_simpan');
	Route::get('/layanan/hapus/{id}', [LayananController::class, 'hapus'])->name('layanan_hapus');
	Route::get('/layanan/edit/{id}', [LayananController::class, 'edit'])->name('layanan_edit');
	Route::post('/layanan/edit/{id}/simpan', [LayananController::class, 'edit_simpan'])->name('layanan_edit_simpan');

	Route::get('/alat/', [AlatController::class, 'index'])->name('alat_index');
	Route::get('/alat/tambah', [AlatController::class, 'tambah'])->name('alat_tambah');
	Route::post('/alat/tambah-simpan', [AlatController::class, 'tambah_simpan'])->name('alat_simpan');
	Route::get('/alat/hapus/{id}', [AlatController::class, 'hapus'])->name('alat_hapus');
	Route::get('/alat/edit/{id}', [AlatController::class, 'edit'])->name('alat_edit');
	Route::post('/alat/edit/{id}/simpan', [AlatController::class, 'edit_simpan'])->name('alat_edit_simpan');
	*/
	Route::get('/transaksi-alat/', [TransaksiAlatController::class, 'index'])->name('transaksi_alat_index');
	Route::get('/transaksi-alat/tambah', [TransaksiAlatController::class, 'tambah'])->name('transaksi_alat_tambah');
	Route::post('/transaksi-alat/tambah-simpan', [TransaksiAlatController::class, 'tambah_simpan'])->name('transaksi_alat_simpan');
	Route::get('/transaksi-alat/hapus/{id}', [TransaksiAlatController::class, 'hapus'])->name('transaksi_alat_hapus');
	Route::get('/transaksi-alat/reset-password/{id}', [TransaksiAlatController::class, 'reset_password'])->name('transaksi_alat_reset_password');
	Route::get('/transaksi-alat/edit/{id}', [TransaksiAlatController::class, 'edit'])->name('transaksi_alat_edit');
	Route::post('/transaksi-alat/edit/{id}/simpan', [TransaksiAlatController::class, 'edit_simpan'])->name('transaksi_alat_edit_simpan');

	Route::get('/unitkerja/', [UnitkerjaController::class, 'index'])->name('unitkerja_index');
	Route::post('/unitkerja/ubahstatus', [UnitkerjaController::class, 'ubahstatus'])->name('unitkerja_ubahstatus');

});

Route::middleware(['akses:1,2,4'])->group(function () {
	$tmp_prefix = 'aset';
	Route::get('/'.$tmp_prefix.'/', [AsetController::class, 'index'])->name($tmp_prefix.'_index');
	Route::get('/'.$tmp_prefix.'/tambah', [AsetController::class, 'tambah'])->name($tmp_prefix.'_tambah');
	Route::post('/'.$tmp_prefix.'/tambah-simpan', [AsetController::class, 'tambah_simpan'])->name($tmp_prefix.'_simpan');
	Route::get('/'.$tmp_prefix.'/hapus/{id}', [AsetController::class, 'hapus'])->name($tmp_prefix.'_hapus');
	Route::get('/'.$tmp_prefix.'/edit/{id}', [AsetController::class, 'edit'])->name($tmp_prefix.'_edit');
	Route::post('/'.$tmp_prefix.'/edit/{id}/simpan', [AsetController::class, 'edit_simpan'])->name($tmp_prefix.'_edit_simpan');
    Route::get('/'.$tmp_prefix.'/asetuk/{id}', [AsetController::class, 'asetunitkerja'])->name($tmp_prefix.'_unit_kerja_index');
    Route::get('/'.$tmp_prefix.'/tambah/{idunitkerja}', [AsetController::class, 'tambahalat_unitkerja'])->name($tmp_prefix.'_unitkerja_tambah');
    Route::post('/'.$tmp_prefix.'/tarik_master_aset', [AsetController::class, 'tarik_master_aset'])->name($tmp_prefix.'_unitkerja_tarik');
	Route::post('/'.$tmp_prefix.'/kapasitas_max_get', [AsetController::class, 'get_kapasitas_max'])->name($tmp_prefix.'_kapasitas_max_get');
	Route::post('/'.$tmp_prefix.'/kapasitas_max_simpan', [AsetController::class, 'simpan_kapasitas_max'])->name($tmp_prefix.'_kapasitas_max_simpan');

	Route::get('/maintenance-alat/', [MaintenanceController::class, 'index'])->name('maintenance_alat_index');
	Route::get('/maintenance-alat/unitkerja/{id}', [MaintenanceController::class, 'maintenance_unit_kerja'])->name('maintenance_unit_kerja');
	Route::get('/maintenance-alat/tambahalat/{idunitkerja}', [MaintenanceController::class, 'tambah_alat'])->name('maintenance_unitkerja_tambah');
	Route::post('/maintenance-alat/aktifkanmaintenance_alat', [MaintenanceController::class, 'aktifkan_maintenance_alat'])->name('maintenance_unitkerja_aktifkan');
	Route::post('/maintenance-alat/get_jarak', [MaintenanceController::class, 'get_jarak_maintenance'])->name('maintenance_jarak_hari_get');
	Route::post('/maintenance-alat/jarak_hari_simpan', [MaintenanceController::class, 'jarak_hari_simpan'])->name('maintenance_jarak_hari_simpan');
	Route::post('/maintenance-alat/get_pj', [MaintenanceController::class, 'get_pj_maintenance'])->name('maintenance_pj_maintenance_get');
	Route::post('/maintenance-alat/pj_simpan', [MaintenanceController::class, 'pj_simpan'])->name('maintenance_pj_maintenance_simpan');
	Route::post('/maintenance-alat/pj_ubah_status', [MaintenanceController::class, 'pj_ubah_status'])->name('maintenance_pj_maintenance_ubah_status');
	Route::post('/maintenance-alat/aktifasi_kalibrasi_maintenance', [MaintenanceController::class, 'aktifasi_kalibrasi_maintenance'])->name('maintenance_aktifasi_kalibrasi_maintenance_get');
	Route::post('/maintenance-alat/ubahstatus_kalibrasi_maintenance', [MaintenanceController::class, 'ubahstatus_kalibrasi_maintenance'])->name('ubah_status_maintenance_kalibrasi');

	Route::get('/pjruang/{idrole_user}/{iduser}', [PjRuangController::class, 'edit_ruang'])->name('pj_ruang_edit_ruang');
	Route::post('/pjruang/tambahruangpj', [PjRuangController::class, 'tambahruangpj'])->name('user_tambah_ruang_pj');
	Route::post('/pjruang/ubahstatusruangpj', [PjRuangController::class, 'ubahstatusruangpj'])->name('user_ubah_status_ruang_pj');
	Route::get('/pjruang/ruanganpj', [PjRuangController::class, 'ruanganpj'])->name('pj_ruang_ruanganpj');

	// $tmp_prefix = 'permintaan_layanan';
	// Route::get('/'.$tmp_prefix.'/', [PermintaanLayananController::class, 'index'])->name($tmp_prefix.'_index');
	// Route::get('/'.$tmp_prefix.'/tambah', [PermintaanLayananController::class, 'tambah'])->name($tmp_prefix.'_tambah');
	// Route::post('/'.$tmp_prefix.'/tambah-simpan', [PermintaanLayananController::class, 'tambah_simpan'])->name($tmp_prefix.'_simpan');
	// Route::get('/'.$tmp_prefix.'/hapus/{id}', [PermintaanLayananController::class, 'hapus'])->name($tmp_prefix.'_hapus');
	// Route::get('/'.$tmp_prefix.'/edit/{id}', [PermintaanLayananController::class, 'edit'])->name($tmp_prefix.'_edit');
	// Route::post('/'.$tmp_prefix.'/edit/{id}/simpan', [PermintaanLayananController::class, 'edit_simpan'])->name($tmp_prefix.'_edit_simpan');

	Route::get('/penelitian/', [PenelitianController::class, 'index'])->name('penelitian_index');

	Route::get('/praktikum/', [PraktikumController::class, 'index'])->name('praktikum_index');

	Route::get('/form_maintenance/', [FormMaintenanceController::class, 'index'])->name('form_maintenance_index');
	Route::get('/form_maintenance/unit_kerja/{id}', [FormMaintenanceController::class, 'detail_unit_kerja'])->name('form_maintenance_unit_kerja');
	Route::get('/form_maintenance/buatformbaru/{idunit_kerja}', [FormMaintenanceController::class, 'buat_formbaru'])->name('form_maintenance_create');
	Route::post('/form_maintenance/simpan_form_baru', [FormMaintenanceController::class, 'simpan_form_baru'])->name('form_maintenance_simpan_form_baru');
	Route::get('/form_maintenance/editform/{idform}', [FormMaintenanceController::class, 'edit_form'])->name('form_maintenance_edit_form');
	Route::post('/form_maintenance/ubahstatus_template', [FormMaintenanceController::class, 'ubahstatus_template'])->name('form_maintenance_ganti_status_template');
	Route::post('/form_maintenance/edit_nama_template', [FormMaintenanceController::class, 'edit_nama_template'])->name('form_maintenance_edit_nama_template');
	Route::post('/form_maintenance/ubah_jenis_form', [FormMaintenanceController::class, 'ubah_jenis_form'])->name('form_maintenance_ubah_jenis_form');
	Route::post('/form_maintenance/tambah_elemen', [FormMaintenanceController::class, 'tambah_elemen_baru'])->name('form_maintenance_mdl_tambah_elemen');
	Route::post('/form_maintenance/get_parent_element', [FormMaintenanceController::class, 'get_parent_element'])->name('get_parent_element');
	Route::post('/form_maintenance/simpan_nilai_default', [FormMaintenanceController::class, 'simpan_nilai_default'])->name('form_maintenance.edit.simpan_nilai_default');
	Route::post('/form_maintenance/get_elemen_template', [FormMaintenanceController::class, 'get_elemen_template'])->name('form_maintenance.edit.get_isi_template');
	Route::post('/form_maintenance/simpan_edit_elemen', [FormMaintenanceController::class, 'simpan_edit_elemen'])->name('form_maintenance.simpan_edit_elemen');
	Route::post('/form_maintenance/hapus_elemen_template', [FormMaintenanceController::class, 'hapus_elemen_template'])->name('form_maintenance.hapus_elemen_template');
});