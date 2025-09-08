<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LayananOperatorController extends Controller
{
	protected $setting_folder_view, $setting_route_prefix, $setting_judul, $setting_nama_model;
	public function __construct()
	{
		$this->setting_folder_view = 'layanan-operator';
		$this->setting_route_prefix = 'layanan_operator_';
		$this->setting_judul = 'Layanan Operator';
		// $this->setting_nama_model = \App\Models\LayananAset::class;
	}

	public function index()
	{
		$menu = 'master';
		$submenu = 'layanan_operator';

		if(session('userdata')['idrole'] == 1){
			$q2 = DB::table('layanan as l')
					->join('operator_layanan as ol', 'l.idlayanan', '=', 'ol.idlayanan')
					->select('l.idlayanan', 'l.idunit_kerja')
					->where('ol.is_deleted', false)
					->groupBy('l.idlayanan', 'l.idunit_kerja');

			$unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user GROUP BY idunit_kerja) as q1'))
						->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
						->leftJoinSub($q2, 'q2', function ($join) {
							$join->on('q1.idunit_kerja', '=', 'q2.idunit_kerja');
						})
						->select('uk.id_unit_kerja', 'uk.nm_unit_kerja', DB::raw('COUNT(q2.idlayanan) as jumlah_layanan'))
						->groupBy('uk.id_unit_kerja','uk.nm_unit_kerja')
						->orderBy('uk.nm_unit_kerja', 'asc')
						->get();

			$layanan_q = DB::table('layanan as l')
							->select('idunit_kerja', DB::raw('COUNT(idlayanan) as jumlah_layanan'))
							->groupBy('idunit_kerja')
							->orderBy('idunit_kerja', 'asc')
							->get();
		}
		else{
			$q2 = DB::table('layanan as l')
					->join('operator_layanan as ol', 'l.idlayanan', '=', 'ol.idlayanan')
					->select('l.idlayanan', 'l.idunit_kerja')
					->where('ol.is_deleted', false)
					->groupBy('l.idlayanan', 'l.idunit_kerja');

			$unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user where status = true GROUP BY idunit_kerja) as q1'))
						->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
						->leftJoinSub($q2, 'q2', function ($join) {
							$join->on('q1.idunit_kerja', '=', 'q2.idunit_kerja');
						})
						->select('uk.id_unit_kerja', 'uk.nm_unit_kerja', DB::raw('COUNT(q2.idlayanan) as jumlah_layanan'))
						->groupBy('uk.id_unit_kerja','uk.nm_unit_kerja')
						->orderBy('uk.nm_unit_kerja', 'asc')
						->get();

			$layanan_q = DB::table('layanan as l')
							->select('idunit_kerja', DB::raw('COUNT(idlayanan) as jumlah_layanan'))
							->where('idunit_kerja', session('userdata')['idunit_kerja'])
							->groupBy('idunit_kerja')
							->orderBy('idunit_kerja', 'asc')
							->get();
		}
		
		
		$layanan = [];
		foreach ($layanan_q as $item) {
			$layanan[$item->idunit_kerja] = $item->jumlah_layanan;
		}

		return view($this->setting_folder_view.'.index', compact('menu','submenu','unitkerja', 'layanan') );
	}

	public function mapping($iduk){
		$idunitkerja = \Crypt::decrypt($iduk);
		$menu = 'master';
		$submenu = 'layanan_operator';

		$unitkerja = DB::table('aucc.unit_kerja')
						->where('id_unit_kerja', $idunitkerja)
						->select('id_unit_kerja', 'nm_unit_kerja')
						->first();

		$operator = DB::table('layanan as l')
						->leftJoin('operator_layanan as ol', function($join) {
							$join->on('l.idlayanan', '=', 'ol.idlayanan')
								->where('ol.is_deleted', 0);
						})
						->select('l.nama_layanan', 'l.idlayanan', DB::raw('COUNT(ol.idoperator_layanan) as jumlah_operator'))
						->groupBy('l.nama_layanan', 'l.idlayanan')
						->orderBy('l.nama_layanan', 'asc')
						->get();

		// dd($layanan);

		return view($this->setting_folder_view.'.mapinglayananunitkerja', compact('menu', 'submenu', 'unitkerja', 'idunitkerja', 'operator') );
	}

	public function mapping_detail($iduk, $idlayanan){
		$iduk = \Crypt::decrypt($iduk);
		$idlayanan = \Crypt::decrypt($idlayanan);

		$idunitkerja = $iduk;

		$menu = 'master';
		$submenu = 'layanan_operator';

		$layanan = DB::table('layanan as l')
					->where('l.idlayanan', $idlayanan)
					->select('l.nama_layanan', 'l.idlayanan')
					->first();

		$operator = DB::table('role_user as ru')
						->join('user as u', 'ru.iduser', '=', 'u.iduser')
						->where('ru.idunit_kerja', $iduk)
						->where('ru.is_delete', false)
						// ->where('ru.status', true)
						->select('u.nama', 'u.iduser', 'u.gelar_depan', 'u.gelar_belakang', 'u.nipnik')
						->groupBy('u.nama', 'u.iduser', 'u.gelar_depan', 'u.gelar_belakang', 'u.nipnik')
						->get();

		// dd($operator);
	
		$operatorlayanan = DB::table('operator_layanan as ol')
							->join('user as u', 'ol.iduser', '=', 'u.iduser')
							->where('ol.idlayanan', $idlayanan)
							->where('ol.is_deleted', false)
							->select('u.nama', 'u.iduser', 'u.gelar_depan', 'u.gelar_belakang', 'u.nipnik', 'ol.status')
							->get();

		return view(''.$this->setting_folder_view.'.mapingoperatorkelayanan', compact('menu', 'submenu', 'layanan', 'idunitkerja', 'idlayanan', 'operator', 'operatorlayanan') );
	}

	public function tambahoperator(Request $req){
		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		// dd(session('userdata'));

		$test = DB::table('operator_layanan')
						->where('idlayanan', $req->idlayanan)
						->where('iduser', $req->idoperator)
						->where('is_deleted', false)
						->count();

		if($test > 0){
			return response()->json([
				'status' => 'error',
				'message' => 'Operator sudah terdaftar',
				'code' => 400,
				'data' => null
			]);
		}

		$arr_insert = [
			'idlayanan' => $req->idlayanan,
			'iduser' => $req->idoperator,
			'created_at' => $ts,
			'created_by' => session('userdata')['iduser'],
			'status' => true
		];

		try {
			DB::table('operator_layanan')->insert($arr_insert);
		} catch (\Exception $e) {
			// Handle the exception as needed, for example:
			// return back()->with('error', 'Gagal menambah operator: ' . $e->getMessage());
			return response()->json([
				'status' => 'error', 
				'message' => 'Gagal menambah operator: ' . $e->getMessage(),
				'code' => 500,
				'data' => null
			]);
		}

		$operator = DB::table('operator_layanan')
						->join('user as u', 'operator_layanan.iduser', '=', 'u.iduser')
						->where('operator_layanan.idlayanan', $req->idlayanan)
						->where('operator_layanan.is_deleted', false)
						->where('operator_layanan.iduser', $req->idoperator)
						->select('u.nama', 'u.iduser', 'u.gelar_depan', 'u.gelar_belakang', 'u.nipnik', 'operator_layanan.status')
						->get();

		return response()->json([
			'status' => 'success', 
			'message' => 'Operator berhasil ditambahkan',
			'code' => 200,
			'data' => $operator
		]);
	}

	public function hapusoperator(Request $req){
		

		try {
			DB::table('operator_layanan')
				->where('idlayanan', $req->idlayanan)
				->where('iduser', $req->idoperator)
				->update([
					'is_deleted' => true,
					
				]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error', 
				'message' => 'Gagal menghapus operator: ' . $e->getMessage(),
				'code' => 500,
				'data' => null
			]);
		}

		return response()->json([
			'status' => 'success', 
			'message' => 'Operator berhasil dihapus',
			'code' => 200,
			'data' => null
		]);
	}

	public function ubahstatus(Request $req){
		try {
			DB::table('operator_layanan')
				->where('idlayanan', $req->idlayanan)
				->where('iduser', $req->idoperator)
				->update([
					'status' => $req->status,
				]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error', 
				'message' => 'Gagal mengubah status operator: ' . $e->getMessage(),
				'code' => 500,
				'data' => null
			]);
		}

		return response()->json([
			'status' => 'success', 
			'message' => 'Status operator berhasil diubah',
			'code' => 200,
			'data' => null
		]);
	}

	

}
