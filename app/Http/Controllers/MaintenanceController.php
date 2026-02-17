<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

class MaintenanceController extends Controller
{
    public function index()
    {
        $menu = 'master';
        $submenu = 'maintenance_alat';

        if(session('userdata')['idrole'] == 1){
			$unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user GROUP BY idunit_kerja) as q1'))
						->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
						->leftJoin('aset as a', 'q1.idunit_kerja', '=', 'a.idunit_kerja')
						->select('q1.idunit_kerja', 'uk.nm_unit_kerja', DB::raw('COUNT(a.kode_barang_aset) as jumlah_aset'))
						->groupBy('q1.idunit_kerja', 'uk.nm_unit_kerja')
						->orderBy('uk.nm_unit_kerja', 'asc')
						->get();

			$unitkerja_alat_maintenance = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user GROUP BY idunit_kerja) as q1'))
											->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
											->leftJoin('aset as a', function($join) {
												$join->on('q1.idunit_kerja', '=', 'a.idunit_kerja')
													 ->whereRaw('(a.terjadwal_maintenance = true OR a.terjadwal_kalibrasi = true)');
											})
											->select('q1.idunit_kerja', DB::raw('SUM(CASE WHEN a.terjadwal_maintenance = true THEN 1 ELSE 0 END) as jumlah_aset_maintenance'), 
															DB::raw('SUM(CASE WHEN a.terjadwal_kalibrasi = true THEN 1 ELSE 0 END) as jumlah_aset_kalibrasi'))
											->groupBy('q1.idunit_kerja')
											->orderBy('q1.idunit_kerja', 'asc')
											->get();

			// dd($unitkerja_alat_maintenance);

			// dd($unitkerja);

            $pj = DB::table('aset as a')
							->leftJoin('pj_maintenance as pm', 'a.kode_barang_aset', '=', 'pm.kode_barang_aset')
							->select('a.idunit_kerja', 'a.kode_barang_aset', 'a.terjadwal_maintenance', 
										DB::raw('SUM(CASE WHEN pm.status = true AND pm.jenis_maintenance = \'2\' THEN 1 ELSE 0 END) as juml_pj_maintenance'),
										DB::raw('SUM(CASE WHEN pm.status = true AND pm.jenis_maintenance = \'1\' THEN 1 ELSE 0 END) as juml_pj_kalibrasi'))
							->whereRaw(('a.terjadwal_maintenance = true OR a.terjadwal_kalibrasi = true'))
							->where('pm.status', true)
							->where('a.status', true)
							->groupBy('a.idunit_kerja', 'a.kode_barang_aset', 'a.terjadwal_maintenance')
							->orderBy('a.idunit_kerja', 'asc')
							->get();
		}
		else if(session('userdata')['idrole'] == 4){
			$unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user where status = true GROUP BY idunit_kerja) as q1'))
						->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
						->leftJoin(DB::raw('(SELECT a.* from aset as a
											join pj_ruang as pr on a.idruang = pr.idruang
											where pr.iduser = '.session('userdata')['iduser'].' and pr.status = true) as a'), 'q1.idunit_kerja', '=', 'a.idunit_kerja')
						->select('q1.idunit_kerja', 'uk.nm_unit_kerja' , DB::raw('COUNT(a.kode_barang_aset) as jumlah_aset'))
						->where('q1.idunit_kerja', session('userdata')['idunit_kerja'])
						->groupBy('q1.idunit_kerja', 'uk.nm_unit_kerja')
						->orderBy('uk.nm_unit_kerja', 'asc')
						->get();

			$unitkerja_alat_maintenance = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user GROUP BY idunit_kerja) as q1'))
											->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
											->leftJoin('aset as a', function($join) {
												$join->on('q1.idunit_kerja', '=', 'a.idunit_kerja')
													 ->whereRaw('(a.terjadwal_maintenance = true OR a.terjadwal_kalibrasi = true)');
											})
											->select('q1.idunit_kerja', DB::raw('SUM(CASE WHEN a.terjadwal_maintenance = true THEN 1 ELSE 0 END) as jumlah_aset_maintenance'), 
															DB::raw('SUM(CASE WHEN a.terjadwal_kalibrasi = true THEN 1 ELSE 0 END) as jumlah_aset_kalibrasi'))
											->where('a.idunit_kerja', session('userdata')['idunit_kerja'])
											->groupBy('q1.idunit_kerja')
											->orderBy('q1.idunit_kerja', 'asc')
											->get();

			// dd($unitkerja_alat_maintenance);

			$pj = DB::table('aset as a')
							->leftJoin('pj_maintenance as pm', 'a.kode_barang_aset', '=', 'pm.kode_barang_aset')
							->select('a.idunit_kerja', 'a.kode_barang_aset', 'a.terjadwal_maintenance', 'a.terjadwal_kalibrasi',
										DB::raw('SUM(CASE WHEN pm.status = true AND pm.jenis_maintenance = \'2\' THEN 1 ELSE 0 END) as juml_pj_maintenance'),
										DB::raw('SUM(CASE WHEN pm.status = true AND pm.jenis_maintenance = \'1\' THEN 1 ELSE 0 END) as juml_pj_kalibrasi'))
							->where('a.idunit_kerja', session('userdata')['idunit_kerja'])
							->whereRaw(('a.terjadwal_maintenance = true OR a.terjadwal_kalibrasi = true'))
							->where('pm.status', true)
							->where('a.status', true)
							->groupBy('a.idunit_kerja', 'a.kode_barang_aset', 'a.terjadwal_maintenance')
							->orderBy('a.idunit_kerja', 'asc')
							->get();
		}
		else{
			$unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user where status = true GROUP BY idunit_kerja) as q1'))
						->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
						->leftJoin('aset as a', 'q1.idunit_kerja', '=', 'a.idunit_kerja')
						->select('q1.idunit_kerja', 'uk.nm_unit_kerja' , DB::raw('COUNT(a.kode_barang_aset) as jumlah_aset'))
						->where('q1.idunit_kerja', session('userdata')['idunit_kerja'])
						->groupBy('q1.idunit_kerja', 'uk.nm_unit_kerja')
						->orderBy('uk.nm_unit_kerja', 'asc')
						->get();

			$unitkerja_alat_maintenance = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user GROUP BY idunit_kerja) as q1'))
											->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
											->leftJoin('aset as a', function($join) {
												$join->on('q1.idunit_kerja', '=', 'a.idunit_kerja')
													 ->whereRaw('(a.terjadwal_maintenance = true OR a.terjadwal_kalibrasi = true)');
											})
											->select('q1.idunit_kerja', DB::raw('SUM(CASE WHEN a.terjadwal_maintenance = true THEN 1 ELSE 0 END) as jumlah_aset_maintenance'), 
															DB::raw('SUM(CASE WHEN a.terjadwal_kalibrasi = true THEN 1 ELSE 0 END) as jumlah_aset_kalibrasi'))
											->where('a.idunit_kerja', session('userdata')['idunit_kerja'])
											->groupBy('q1.idunit_kerja')
											->orderBy('q1.idunit_kerja', 'asc')
											->get();

			// dd($unitkerja_alat_maintenance);

			$pj = DB::table('aset as a')
							->leftJoin('pj_maintenance as pm', 'a.kode_barang_aset', '=', 'pm.kode_barang_aset')
							->select('a.idunit_kerja', 'a.kode_barang_aset', 'a.terjadwal_maintenance', 'a.terjadwal_kalibrasi',
										DB::raw('SUM(CASE WHEN pm.status = true AND pm.jenis_maintenance = \'2\' THEN 1 ELSE 0 END) as juml_pj_maintenance'),
										DB::raw('SUM(CASE WHEN pm.status = true AND pm.jenis_maintenance = \'1\' THEN 1 ELSE 0 END) as juml_pj_kalibrasi'))
							->where('a.idunit_kerja', session('userdata')['idunit_kerja'])
							->whereRaw(('a.terjadwal_maintenance = true OR a.terjadwal_kalibrasi = true'))
							->where('pm.status', true)
							->where('a.status', true)
							->groupBy('a.idunit_kerja', 'a.kode_barang_aset', 'a.terjadwal_maintenance')
							->orderBy('a.idunit_kerja', 'asc')
							->get();
		}

		// dd($pj);

        $pj_maintenance = [];
		$pj_kalibrasi = [];
		foreach ($pj as $row) {
			if(!array_key_exists($row->idunit_kerja, $pj_maintenance)){
				$pj_maintenance[$row->idunit_kerja] = 0;
				$pj_kalibrasi[$row->idunit_kerja] = 0;
			}

			if($row->juml_pj_maintenance > 0)
				$pj_maintenance[$row->idunit_kerja] ++;

			if($row->juml_pj_kalibrasi > 0)
				$pj_kalibrasi[$row->idunit_kerja] ++;

		}

		// dd($pj, $pj_maintenance, $pj_kalibrasi);

		$jumlah_aset_maintenance = [];
		$jumlah_aset_kalibrasi = [];
		foreach ($unitkerja_alat_maintenance as $item) {
			$jumlah_aset_maintenance[$item->idunit_kerja] = $item->jumlah_aset_maintenance;
			$jumlah_aset_kalibrasi[$item->idunit_kerja] = $item->jumlah_aset_kalibrasi;
		}

		// dd($jumlah_aset_maintenance);

		// dd($pj, $pj_maintenance, $jumlah_aset_maintenance, $pj_kalibrasi, $jumlah_aset_kalibrasi);

		// $list_layanan = Layanan::all();
		return view('maintenance.index', compact('menu', 'submenu', 'unitkerja', 'pj_maintenance', 'jumlah_aset_maintenance', 'jumlah_aset_kalibrasi', 'pj_kalibrasi') );
    }

	public function maintenance_unit_kerja($id)
	{
		$menu = 'master';
		$submenu = 'maintenance_alat';

		$idunit_kerja = Crypt::decrypt($id);

		// dd($idunit_kerja);

		if(session('userdata')['idrole'] == 4){
			$aset = DB::table('aset as a')
					->leftJoin('pj_maintenance as pm', function($join){
								$join->on('a.kode_barang_aset', '=', 'pm.kode_barang_aset');
								$join->where('pm.status', true);
							})
					->join('simba.ruang as r', 'a.idruang', '=', 'r.id')
					->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
					->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
					->join('pj_ruang as pr', function($join) {
						$join->on('r.id', '=', 'pr.idruang')
							 ->where('pr.iduser', session('userdata')['iduser'])
							 ->where('pr.status', true);
					})
					->leftJoin('satuan_maintenance as sm_maintenance', 'a.satuan_jarak_maintenance', '=', 'sm_maintenance.idsatuan_maintenance')
					->leftJoin('satuan_maintenance as sm_kalibrasi', 'a.satuan_jarak_kalibrasi', '=', 'sm_kalibrasi.idsatuan_maintenance')
					->select('a.kode_barang_aset', 'a.idunit_kerja', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset', 'a.terjadwal_maintenance', 'a.jarak_maintenance', 'a.satuan_jarak_maintenance', 'a.keterangan',
								'a.terjadwal_kalibrasi', 'a.jarak_kalibrasi', 'a.satuan_jarak_kalibrasi', 'sm_maintenance.satuan as satuan_maintenance', 'sm_kalibrasi.satuan as satuan_kalibrasi', 'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus', 
								DB::raw('SUM(CASE WHEN pm.jenis_maintenance = \'1\' THEN 1 ELSE 0 END) as jumlah_pj_kalibrasi'),
								DB::raw('SUM(CASE WHEN pm.jenis_maintenance = \'2\' THEN 1 ELSE 0 END) as jumlah_pj_maintenance'))
					->where('a.idunit_kerja', $idunit_kerja)
					->where('a.status', true)
					->whereRaw('(a.terjadwal_maintenance = true OR a.terjadwal_kalibrasi = true)')
					->groupBy('a.kode_barang_aset', 'a.idunit_kerja', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset', 'a.jarak_maintenance', 'a.satuan_jarak_maintenance', 'a.keterangan',
								'a.terjadwal_kalibrasi', 'a.jarak_kalibrasi', 'a.satuan_jarak_kalibrasi', 'sm_maintenance.satuan', 'sm_kalibrasi.satuan',
								'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus')
					->orderBy('a.kode_barang_aset', 'asc')
					->get();

			
		}
		else{
			$aset = DB::table('aset as a')
					->leftJoin('pj_maintenance as pm', function($join){
								$join->on('a.kode_barang_aset', '=', 'pm.kode_barang_aset');
								$join->where('pm.status', true);
							})
					->join('simba.ruang as r', 'a.idruang', '=', 'r.id')
					->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
					->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
					->leftJoin('satuan_maintenance as sm_maintenance', 'a.satuan_jarak_maintenance', '=', 'sm_maintenance.idsatuan_maintenance')
					->leftJoin('satuan_maintenance as sm_kalibrasi', 'a.satuan_jarak_kalibrasi', '=', 'sm_kalibrasi.idsatuan_maintenance')
					->select('a.kode_barang_aset', 'a.idunit_kerja', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset', 'a.terjadwal_maintenance', 'a.jarak_maintenance', 'a.satuan_jarak_maintenance', 'a.keterangan',
								'a.terjadwal_kalibrasi', 'a.jarak_kalibrasi', 'a.satuan_jarak_kalibrasi', 'sm_maintenance.satuan as satuan_maintenance', 'sm_kalibrasi.satuan as satuan_kalibrasi', 'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus', 
								DB::raw('SUM(CASE WHEN pm.jenis_maintenance = \'1\' THEN 1 ELSE 0 END) as jumlah_pj_kalibrasi'),
								DB::raw('SUM(CASE WHEN pm.jenis_maintenance = \'2\' THEN 1 ELSE 0 END) as jumlah_pj_maintenance'))
					->where('a.idunit_kerja', $idunit_kerja)
					->where('a.status', true)
					->whereRaw('(a.terjadwal_maintenance = true OR a.terjadwal_kalibrasi = true)')
					->groupBy('a.kode_barang_aset', 'a.idunit_kerja', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset', 'a.jarak_maintenance', 'a.satuan_jarak_maintenance', 'a.keterangan',
								'a.terjadwal_kalibrasi', 'a.jarak_kalibrasi', 'a.satuan_jarak_kalibrasi', 'sm_maintenance.satuan', 'sm_kalibrasi.satuan',
								'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus')
					->orderBy('a.kode_barang_aset', 'asc')
					->get();
		}

		// dd($aset);

		$personil = DB::table('role_user as ru')
					->join('user as u', 'ru.iduser', '=', 'u.iduser')
					->where('ru.idunit_kerja', $idunit_kerja)
					->where('ru.status', true)
					->select('u.iduser', 'u.nama', 'u.gelar_depan', 'u.gelar_belakang', 'u.nipnik')
					->orderBy('u.nama', 'asc')
					->get();

		$unitkerja = DB::table('aucc.unit_kerja')->where('id_unit_kerja', $idunit_kerja)->first();

		// dd($idunit_kerja, $unitkerja);

		return view('maintenance.asetunitkerja', compact('menu', 'submenu', 'aset', 'unitkerja', 'idunit_kerja', 'personil') );
	}

	public function tambah_alat($idunitkerja)
	{
		$menu = 'master';
		$submenu = 'maintenance_alat';

		$idunit_kerja = Crypt::decrypt($idunitkerja);

		$unitkerja = DB::table('aucc.unit_kerja')->where('id_unit_kerja', $idunit_kerja)->first();

		if(session('userdata')['idrole'] == 4){
			$aset = DB::table('aset as a')
					->join('simba.ruang as r', 'a.idruang', '=', 'r.id')
					->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
					->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
					->join('pj_ruang as pr', function($join) {
						$join->on('r.id', '=', 'pr.idruang')
							 ->where('pr.iduser', session('userdata')['iduser'])
							 ->where('pr.status', true);
					})
					->select('a.kode_barang_aset', 'a.idunit_kerja', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset', 'a.keterangan',
								'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus')
					->where('a.idunit_kerja', $idunit_kerja)
					->where(DB::RAW('a.terjadwal_maintenance = false  OR a.terjadwal_maintenance IS NULL'))
					->where(DB::RAW('a.terjadwal_kalibrasi = false OR a.terjadwal_kalibrasi IS NULL'))
					->where('a.status', true)
					->orderBy('a.kode_barang_aset', 'asc')
					->groupBy('a.kode_barang_aset', 'a.idunit_kerja', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset','a.keterangan',
								'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus')
					->get();
		}
		else{
			$aset = DB::table('aset as a')
					->join('simba.ruang as r', 'a.idruang', '=', 'r.id')
					->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
					->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
					->select('a.kode_barang_aset', 'a.idunit_kerja', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset', 'a.keterangan',
								'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus')
					->where('a.idunit_kerja', $idunit_kerja)
					->where(DB::RAW('a.terjadwal_maintenance = false  OR a.terjadwal_maintenance IS NULL'))
					->where(DB::RAW('a.terjadwal_kalibrasi = false OR a.terjadwal_kalibrasi IS NULL'))
					->where('a.status', true)
					->orderBy('a.kode_barang_aset', 'asc')
					->groupBy('a.kode_barang_aset', 'a.idunit_kerja', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset','a.keterangan',
								'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus')
					->get();
		}

		// $aset = DB::table('aset as a')
		// 			->leftJoin('pj_maintenance as pm', 'a.kode_barang_aset', '=', 'pm.kode_barang_aset')
		// 			->join('simba.ruang as r', 'a.idruang', '=', 'r.id')
		// 			->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
		// 			->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
		// 			->select('a.kode_barang_aset', 'a.idunit_kerja', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset', 'a.jarak_maintenance_hari', 
		// 						'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus')
		// 			->where('a.idunit_kerja', $idunit_kerja)
		// 			->where('a.terjadwal_maintenance', false)
		// 			->orderBy('a.kode_barang_aset', 'asc')
		// 			->get();

		return view('maintenance.tambahalat_unitkerja', compact('menu', 'submenu', 'unitkerja', 'idunit_kerja', 'aset') );
	}

	public function aktifkan_maintenance_alat(Request $request)
	{
		$kode_barang = $request->input('kode_barang');

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		
		DB::beginTransaction();
		try {
			$update = DB::table('aset')
						->where('kode_barang_aset', $kode_barang)
						->update([
							'terjadwal_maintenance' => true,
							'jarak_maintenance' => 0,
							'terjadwal_kalibrasi' => true,
							'jarak_kalibrasi' => 0
						]);

			$arr_insert = array(
				array(
					'kode_barang_aset' => $kode_barang,
					'dirubah_oleh' => session('userdata')['iduser'],
					'jarak_baru' => 0,
					'timestamp' => $ts,
					'satuan_baru' => 3,
					'jenis_maintenance' => 1
				),
				array(
					'kode_barang_aset' => $kode_barang,
					'dirubah_oleh' => session('userdata')['iduser'],
					'jarak_baru' => 0,
					'timestamp' => $ts,
					'satuan_baru' => 3,
					'jenis_maintenance' => 2
				)
			);

			DB::table('log_perubahan_jadwal_maintenance')
				->insert($arr_insert);

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			$update = false;
		}

		if($update){
			return response()->json([
				'code' => 200,
				'status' => 'sukses',
				'message' => 'Alat berhasil ditambahkan ke jadwal maintenance.'
			], 200);
		}
		else{
			return response()->json([
				'code' => 404,
				'status' => 'danger',
				'message' => 'Kode barang tidak ditemukan'
			], 200);
		}
	}

	public function get_jarak_maintenance(Request $request)
	{
		$kode_barang = $request->input('kodeaset');
		$jenis = $request->input('jenis');

		$aset = DB::table('aset as a')
				->where('kode_barang_aset', $kode_barang)
				->first();
		$history = DB::table('log_perubahan_jadwal_maintenance as log_perubahan_maintenance')
					->join('user', 'log_perubahan_maintenance.dirubah_oleh', '=', 'user.iduser')
					->join('satuan_maintenance as sm', 'log_perubahan_maintenance.satuan_baru', '=', 'sm.idsatuan_maintenance')
					->select('log_perubahan_maintenance.jarak_baru', 'user.nama', 'user.gelar_depan', 'user.gelar_belakang','user.nipnik','log_perubahan_maintenance.timestamp',
							'sm.satuan', 'log_perubahan_maintenance.jenis_maintenance')
					->where('log_perubahan_maintenance.kode_barang_aset', $kode_barang)
					->where('log_perubahan_maintenance.jenis_maintenance', $jenis)
					->orderBy('log_perubahan_maintenance.timestamp', 'desc')
					->get();

		$retval = [
			'aset' => $aset,
			'history' => $history
		];

		if($aset){
			return response()->json([
				'code' => 200,
				'status' => 'sukses',
				'message' => 'Data ditemukan',
				'data' => $retval
			], 200);
		}
		else{
			return response()->json([
				'code' => 404,
				'status' => 'danger',
				'message' => 'Kode barang tidak ditemukan',
				'data' => []
			], 200);
		}
	}

	public function jarak_hari_simpan(Request $request)
	{
		// dd($request->all());
		$kode_barang = $request->input('kodeaset');
		$jarak_hari = $request->input('jarak_maintenance');
		$satuan_jarak = $request->input('satuan_jarak_maintenance');
		$jenis = $request->input('jenis');

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		DB::beginTransaction();
		try {

			if($jenis == '1'){
				$update = DB::table('aset')
							->where('kode_barang_aset', $kode_barang)
							->update([
								'jarak_kalibrasi' => $jarak_hari,
								'satuan_jarak_kalibrasi' => $satuan_jarak
							]);
			}
			else{
				$update = DB::table('aset')
							->where('kode_barang_aset', $kode_barang)
							->update([
								'jarak_maintenance' => $jarak_hari,
								'satuan_jarak_maintenance' => $satuan_jarak
							]);
			}

			DB::table('log_perubahan_jadwal_maintenance')
				->insert([
					'kode_barang_aset' => $kode_barang,
					'dirubah_oleh' => session('userdata')['iduser'],
					'jarak_baru' => $jarak_hari,
					'satuan_baru' => $satuan_jarak,
					'timestamp' => $ts,
					'jenis_maintenance' => $jenis
				]);

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			$update = false;
		}

		if($update){
			Session::flash('status', [
				'status' => 'success',
				'message' => 'Jarak maintenance berhasil diupdate.'
			]);

		}
		else{
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Gagal mengupdate jarak maintenance.'
			]);
		}

		return redirect()->back();
	}

	public function get_pj_maintenance(Request $request)
	{
		$kode_barang = $request->input('kodeaset');
		$jenis = $request->input('jenis');

		$aset = DB::table('aset')
				->where('kode_barang_aset', $kode_barang)
				->first();

		$pj_maintenance = DB::table('pj_maintenance as pm')
					->join('user as u', 'pm.iduser', '=', 'u.iduser')
					->select('pm.idpj_maintenance', 'u.iduser', 'u.nama', 'u.gelar_depan', 'u.gelar_belakang','u.nipnik','pm.status')
					->where('pm.kode_barang_aset', $kode_barang)
					->where('pm.jenis_maintenance', $jenis)
					->orderBy('u.nama', 'desc')
					->get();

		$retval = [
			'aset' => $aset,
			'pj' => $pj_maintenance
		];

		if($aset){
			return response()->json([
				'code' => 200,
				'status' => 'sukses',
				'message' => 'Data ditemukan',
				'data' => $retval
			], 200);
		}
		else{
			return response()->json([
				'code' => 404,
				'status' => 'danger',
				'message' => 'Kode barang tidak ditemukan',
				'data' => []
			], 200);
		}
	}

	public function pj_simpan(Request $request)
	{
		// dd($request->all());
		$kode_barang = $request->input('kodeaset');
		$iduser = $request->input('iduser');
		$jenis = $request->input('jenis');

		$idtelegram = DB::table('user as u')
						->join('aucc.pengguna as p', 'u.nipnik', '=', 'p.username')
						->where('u.iduser', $iduser)
						->value('p.id_telegram');

		if(empty($idtelegram)){
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Gagal menambahkan penanggung jawab maintenance. User tidak memiliki ID Telegram.'
			]);
			return redirect()->back();
		}

		$cek = DB::table('pj_maintenance')
					->where('kode_barang_aset', $kode_barang)
					->where('iduser', $iduser)
					->where('jenis_maintenance', $jenis)
					->first();

		if($cek){
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Gagal menambahkan penanggung jawab maintenance. User sudah terdaftar.'
			]);
			return redirect()->back();
		}

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		DB::beginTransaction();
		try {
			$insert = DB::table('pj_maintenance')
						->insert([
							'kode_barang_aset' => $kode_barang,
							'iduser' => $iduser,
							'status' => true,
							'jenis_maintenance' => $jenis,
							'created_at' => $ts,
							'created_by' => session('userdata')['iduser']
						]);

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			$insert = false;
		}

		if($insert){
			Session::flash('status', [
				'status' => 'success',
				'message' => 'Penanggung jawab maintenance berhasil ditambahkan.'
			]);

		}
		else{
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Gagal menambahkan penanggung jawab maintenance.'
			]);
		}

		return redirect()->back();
	}

	public function pj_ubah_status(Request $request)
	{
		$idpj_maintenance = $request->input('idpj_maintenance');
		$status = $request->input('status');

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		DB::beginTransaction();
		try {
			$update = DB::table('pj_maintenance')
						->where('idpj_maintenance', $idpj_maintenance)
						->update([
							'status' => $status,
							'updated_at' => $ts,
							'updated_by' => session('userdata')['iduser']
						]);

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			$update = false;
		}

		if($update){
			return response()->json([
				'code' => 200,
				'status' => 'sukses',
				'message' => 'Status penanggung jawab maintenance berhasil diubah.'
			], 200);
		}
		else{
			return response()->json([
				'code' => 404,
				'status' => 'danger',
				'message' => 'Gagal mengubah status penanggung jawab maintenance.'
			], 200);
		}
	}

	public function aktifasi_kalibrasi_maintenance(Request $req){
		$kode_barang = $req->input('kodeaset');

		$aset = DB::table('aset')
				->where('kode_barang_aset', $kode_barang)
				->select('terjadwal_kalibrasi', 'terjadwal_maintenance', 'nama_barang', 'merk_barang', 'tahun_aset', 'kode_barang_aset')
				->get();

		return response()->json([
			'code' => 200,
			'status' => 'sukses',
			'message' => 'Data ditemukan',
			'data' => $aset
		], 200);

	}

	public function ubahstatus_kalibrasi_maintenance(Request $req){
		$kode_barang = $req->input('kodeaset');
		$status = $req->input('status');
		$jenis = $req->input('jenis');

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		$kolom = 'terjadwal_' . $jenis;

		if($jenis = 'kalibrasi'){
			$jenis_flag = 1;
		}
		else{
			$jenis_flag = 2;
		}
		

		DB::beginTransaction();
		try {
			$update = DB::table('aset')
						->where('kode_barang_aset', $kode_barang)
						->update([
							$kolom => $status,
						]);

			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			$update = false;
		}

		if($update){
			return response()->json([
				'code' => 200,
				'status' => 'sukses',
				'message' => 'Status kalibrasi berhasil diubah.'
			], 200);
		}
		else{
			return response()->json([
				'code' => 404,
				'status' => 'danger',
				'message' => 'Gagal mengubah status kalibrasi.'
			], 200);
		}
	}
}