<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

class AsetController extends Controller
{
	protected $setting_folder_view, $setting_route_prefix, $setting_judul, $setting_nama_model;
	public function __construct()
	{
		$this->setting_folder_view = 'aset';
		$this->setting_route_prefix = 'aset_';
		$this->setting_judul = 'Aset';
		$this->setting_nama_model = \App\Models\Aset::class;
	}

	public function index()
	{
		$menu = 'master';
		$submenu = 'alatlab';

		// dd(session('userdata')['idrole']);


		if(session('userdata')['idrole'] == 1){
			$unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user GROUP BY idunit_kerja) as q1'))
						->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
						->leftJoin('aset as a', 'q1.idunit_kerja', '=', 'a.idunit_kerja')
						->select('q1.idunit_kerja', 'uk.nm_unit_kerja', DB::raw('COUNT(a.kode_barang_aset) as jumlah_aset'))
						->groupBy('q1.idunit_kerja', 'uk.nm_unit_kerja')
						->orderBy('uk.nm_unit_kerja', 'asc')
						->get();
		}
		else if(session('userdata')['idrole'] == 4){
			$unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user where status = true GROUP BY idunit_kerja) as q1'))
						->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
						->leftJoin(DB::raw('(SELECT a.* from aset as a
											join pj_ruang as pr on a.idruang = pr.idruang
											where pr.iduser = '.session('userdata')['iduser'].' and pr.status = true) as a'), 'q1.idunit_kerja', '=', 'a.idunit_kerja')
						->select('q1.idunit_kerja', 'uk.nm_unit_kerja', DB::raw('COUNT(a.kode_barang_aset) as jumlah_aset'))
						->where('q1.idunit_kerja', session('userdata')['idunit_kerja'])
						->groupBy('q1.idunit_kerja', 'uk.nm_unit_kerja')
						->orderBy('uk.nm_unit_kerja', 'asc')
						->get();
		}
		else{
			$unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user where status = true GROUP BY idunit_kerja) as q1'))
						->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
						->leftJoin('aset as a', 'q1.idunit_kerja', '=', 'a.idunit_kerja')
						->select('q1.idunit_kerja', 'uk.nm_unit_kerja', DB::raw('COUNT(a.kode_barang_aset) as jumlah_aset'))
						->where('q1.idunit_kerja', session('userdata')['idunit_kerja'])
						->groupBy('q1.idunit_kerja', 'uk.nm_unit_kerja')
						->orderBy('uk.nm_unit_kerja', 'asc')
						->get();
		}
		

		// $list_layanan = Layanan::all();
		return view('aset.index', compact('menu', 'submenu', 'unitkerja') );
	}

	public function asetunitkerja($id){
		$menu = 'master';
		$submenu = 'alatlab';


		$idunitkerja = Crypt::decrypt($id);

		$unitkerja = DB::table('aucc.unit_kerja')
					->where('id_unit_kerja', $idunitkerja)
					->select('id_unit_kerja', 'nm_unit_kerja')
					->first();

		if(session('userdata')['idrole'] == 4){
			$aset = DB::table('aset as a')
					->join('simba.ruang as r', 'a.idruang', '=', 'r.id')
					->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
					->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
					->join('pj_ruang as pr', function($join) {
						$join->on('r.id', '=', 'pr.idruang')
							->where('pr.iduser', session('userdata')['iduser'])
							 ->where('pr.status', '=', true);
					})
					->leftjoin('kapasitas_max as km', function($join){
						$join->on('a.kode_barang_aset', '=', 'km.kode_barang_aset')
							->where('km.status', '=', true);
					})
					->where('a.idunit_kerja', $idunitkerja)
					->select('a.kode_barang_aset', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset', 'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus', 
							'a.kondisi_barang', 'a.keterangan',
							DB::raw('COALESCE(km.kapasitas_max, 0) as kapasitas_max'))
					->get();
		}
		else{
			$aset = DB::table('aset as a')
					->join('simba.ruang as r', 'a.idruang', '=', 'r.id')
					->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
					->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
					->leftjoin('kapasitas_max as km', function($join){
						$join->on('a.kode_barang_aset', '=', 'km.kode_barang_aset')
							->where('km.status', '=', true);
					})
					->where('a.idunit_kerja', $idunitkerja)
					->select('a.kode_barang_aset', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset', 'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus', 'a.kondisi_barang', 'a.keterangan',
							DB::raw('COALESCE(km.kapasitas_max, 0) as kapasitas_max'))
					->get();

		}

		

		return view($this->setting_folder_view.'.asetunitkerja', compact('menu', 'submenu', 'aset', 'unitkerja', 'idunitkerja') );
	}

	public function simpan_keterangan(Request $req){
		$kode_barang_aset = $req->kodeaset;
		$keterangan = $req->keterangan;

		try {
			DB::table('aset')
				->where('kode_barang_aset', $kode_barang_aset)
				->update([
					'keterangan' => $keterangan
				]);
		} catch (\Exception $e) {
			return back()->with('status', [
				'status' => 'danger',
				'message' => 'Gagal menyimpan keterangan: ' . $e->getMessage()
			]);
		}

		return back()->with('status', [
			'status' => 'success',
			'message' => 'Keterangan berhasil disimpan'
		]);
	}

	public function tambahalat_unitkerja($idunitkerja){
		$menu = 'master';
		$submenu = 'alatlab';

		$idunitkerja_plain = Crypt::decrypt($idunitkerja);

		$unitkerja = DB::table('aucc.unit_kerja')
					->where('id_unit_kerja', $idunitkerja_plain)
					->select('id_unit_kerja', 'nm_unit_kerja')
					->first();

		if(session('userdata')['idrole'] == 4){
			$aset = DB::table('simba.barang_ruang_det as brd')
					->join('simba.barang as b', 'brd.id_barang', '=', 'b.id')
					->join('simba.ruang as r', 'brd.id_ruang', '=', 'r.id')
					->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
					->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
					->join('pj_ruang as pr', function($join) {
						$join->on('r.id', '=', 'pr.idruang')
							 ->where('pr.iduser', session('userdata')['iduser'])
							 ->where('pr.status', '=', true);
					})
					->where('brd.id_unit_kerja', $idunitkerja_plain)
					->select('brd.kode_barang', 'b.nama_barang', 'brd.merk_barang', 'brd.tahun_aset', 'r.nama_ruang', 'g.nama_gedung', 
							'k.nama_kampus', 'r.id as idruang', 'brd.status_barang as kondisi_barang')
					->get();
		}
		else{
			$aset = DB::table('simba.barang_ruang_det as brd')
					->join('simba.barang as b', 'brd.id_barang', '=', 'b.id')
					->join('simba.ruang as r', 'brd.id_ruang', '=', 'r.id')
					->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
					->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
					->where('brd.id_unit_kerja', $idunitkerja_plain)
					->select('brd.kode_barang', 'b.nama_barang', 'brd.merk_barang', 'brd.tahun_aset', 'r.nama_ruang', 'g.nama_gedung', 
								'k.nama_kampus', 'r.id as idruang', 'brd.status_barang as kondisi_barang')
					->get();
		}

		

		$alatlab_q = DB::table('aset as a')
					->join('simba.ruang as r', 'a.idruang', '=', 'r.id')
					->where('r.id_unit_kerja', $idunitkerja_plain)
					->select('a.kode_barang_aset')
					->get();

		$alatlab = [];
		foreach ($alatlab_q as $item) {
			$alatlab[] = $item->kode_barang_aset;
		}

		return view($this->setting_folder_view.'.tambahalat_unitkerja', compact('menu', 'submenu', 'aset', 'idunitkerja_plain', 'unitkerja', 'alatlab') );
	}

	public function tarik_master_aset(Request $req){
		$idunitkerja = $req->idunitkerja;
		$kode_barang = $req->kode_barang;

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		$aset = DB::table('simba.barang_ruang_det as brd')
					->join('simba.barang as b', 'brd.id_barang', '=', 'b.id')
					->join('simba.ruang as r', 'brd.id_ruang', '=', 'r.id')
					->where('brd.kode_barang', $kode_barang)
					->select('brd.kode_barang', 'b.nama_barang', 'brd.merk_barang', 'brd.tahun_aset', 'r.id as idruang', 'brd.id_unit_kerja', 'brd.status_barang')
					->first();

		if(!$aset) {
			return response()->json([
				'code' => 404,
				'status' => 'error',
				'message' => 'Kode barang tidak ditemukan'
			], 200);
		}

		$arr_insert = array(
			'kode_barang_aset' => $aset->kode_barang,
			'nama_barang' => $aset->nama_barang,
			'merk_barang' => $aset->merk_barang,
			'tahun_aset' => $aset->tahun_aset,
			'idruang' => $aset->idruang,
			'created_at' => $ts,
			'idunit_kerja' => $aset->id_unit_kerja,
			'kondisi_barang' => $aset->status_barang,
		);

		try {
			DB::table('aset')->insert($arr_insert);
			return response()->json([
				'code' => 200,
				'status' => 'success',
				'message' => 'Aset berhasil ditambahkan'
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'code' => 500,
				'status' => 'error',
				'message' => 'Terjadi kesalahan saat menambahkan aset: ' . $e->getMessage()
			], 500);
		}

		
	}

	public function tambah()
	{
		$sroute_prefix = $this->setting_route_prefix;
		$sjudul = $this->setting_judul;

		$ruang = \App\Models\Ruang::all();

		return view($this->setting_folder_view.'.tambah', compact('sroute_prefix', 'sjudul', 'ruang') );
	}

	public function tambah_simpan (Request $request)
	{
		$validatedData = $request->validate([
			'nama_barang' => ['required', 'string'],
			'merk_barang' => ['required', 'string'],
			'tahun_aset' => ['required', 'numeric'],
			'idruang' => ['required', 'numeric'],
		], [
			'nama_barang.required' => 'Nama barang wajib diisi.',
			'merk_barang.required' => 'Merk barang wajib diisi.',
			'tahun_aset.required' => 'Tahun Aset wajib diisi.',
			'idruang.required' => 'Ruang wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}

		$model_cek = $this->setting_nama_model::where('nama_barang', $request->nama_barang)->first();
		if ($model_cek)
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => $this->setting_judul.' sudah ada'
			]);
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		$this->setting_nama_model::create([
			'nama_barang' => $request->nama_barang,
			'merk_barang' => $request->merk_barang,
			'tahun_aset' => $request->tahun_aset,
			'idruang' => $request->idruang,
		]);
		Session::flash('status', [
			'status' => 'success',
			'message' => $this->setting_judul.' berhasil ditambahkan'
		]);

		return redirect()->route($this->setting_route_prefix.'index');
	}

	public function hapus ($id)
	{
		$role = $this->setting_nama_model::find($id);
		if ($role)
		{
			$role->delete();
			Session::flash('status', [
				'status' => 'success',
				'message' => $this->setting_judul.' berhasil dihapus'
			]);
		}
		else
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => $this->setting_judul.' tidak ditemukan'
			]);
		}

		return redirect()->route($this->setting_route_prefix.'index');
	}

	public function edit ($id)
	{
		$sroute_prefix = $this->setting_route_prefix;
		$sjudul = $this->setting_judul;

		$data = $this->setting_nama_model::find($id);
		$ruang = \App\Models\Ruang::all();

		return view($this->setting_folder_view.'.edit', compact('sroute_prefix', 'sjudul', 'data', 'id', 'ruang') );
	}

	public function edit_simpan (Request $request, $id)
	{
		$validatedData = $request->validate([
			'nama_barang' => ['required', 'string'],
			'merk_barang' => ['required', 'string'],
			'tahun_aset' => ['required', 'numeric'],
			'idruang' => ['required', 'numeric'],
		], [
			'nama_barang.required' => 'Nama barang wajib diisi.',
			'merk_barang.required' => 'Merk barang wajib diisi.',
			'tahun_aset.required' => 'Tahun Aset wajib diisi.',
			'idruang.required' => 'Ruang wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		$model_cek = $this->setting_nama_model::where('nama_barang', $request->nama_barang)
			->where('kode_barang_aset', '!=', $id)
			->first();
		if ($model_cek)
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => $this->setting_judul.' sudah ada'
			]);
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		// jika tidak ada error, simpan data
		$model = $this->setting_nama_model::find($id);
		if (!$model)
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => $this->setting_judul.' tidak ditemukan'
			]);
			return redirect()->route($this->setting_route_prefix.'index');
		}
		$model->nama_barang = $request->nama_barang;
		$model->merk_barang = $request->merk_barang;
		$model->tahun_aset = $request->tahun_aset;
		$model->idruang = $request->idruang;
		$model->save();

		Session::flash('status', [
			'status' => 'success',
			'message' => 'Edit '.$this->setting_judul.' berhasil'
		]);

		return redirect()->route($this->setting_route_prefix.'index');
	}

	function get_kapasitas_max(Request $req){
		$kode_barang_aset = $req->kodeaset;

		$kapasitas_max = DB::table('kapasitas_max')
			->where('kode_barang_aset', $kode_barang_aset)
			->select('kapasitas_max', 'created_at', 'status')
			->orderBy('created_at', 'desc')
			->get();
		

		return response()->json([
			'code' => 200,
			'status' => 'success',
			'kapasitas_max' => $kapasitas_max
		], 200);
	}

	function simpan_kapasitas_max(Request $req){
		$kode_barang_aset = $req->kodeaset;
		$kapasitas_max = $req->kapasitas_max;

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		DB::beginTransaction();
		try {
			DB::table('kapasitas_max')
				->where('kode_barang_aset', $kode_barang_aset)
				->update(['status' => false]);

			DB::table('kapasitas_max')->insert([
				'kode_barang_aset' => $kode_barang_aset,
				'kapasitas_max' => $kapasitas_max,
				'status' => true,
				'created_at' => $ts,
				'created_by' => session('userdata')['iduser']
			]);
			DB::commit();
		} catch (\Exception $e) {
			DB::rollBack();
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Gagal menyimpan Kapasitas Max: ' . $e->getMessage()
			]);
			return back();
		}

		Session::flash('status', [
				'status' => 'success',
				'message' => 'Kapasitas Max berhasil disimpan'
			]);

		return back();
		
	}

}
