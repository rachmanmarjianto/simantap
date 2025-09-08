<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LayananAsetController extends Controller
{
	protected $setting_folder_view, $setting_route_prefix, $setting_judul, $setting_nama_model;
	public function __construct()
	{
		$this->setting_folder_view = 'layanan-aset';
		$this->setting_route_prefix = 'layanan_aset_';
		$this->setting_judul = 'Layanan Aset';
		$this->setting_nama_model = \App\Models\LayananAset::class;
	}

	public function index()
	{
		$menu = 'master';
		$submenu = 'layanan_aset';

		if(session('userdata')['idrole'] == 1){
			$q2 = DB::table('layanan as l')
					->join('layanan_aset as al', 'l.idlayanan', '=', 'al.idlayanan')
					->select('l.idlayanan', 'l.idunit_kerja')
					->where('al.is_deleted', false)
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
					->join('layanan_aset as al', 'l.idlayanan', '=', 'al.idlayanan')
					->select('l.idlayanan', 'l.idunit_kerja')
					->where('al.is_deleted', false)
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

	public function mapinglayananunitkerja($iduk){
		$idunitkerja = \Crypt::decrypt($iduk);
		$menu = 'master';
		$submenu = 'layanan_aset';

		$unitkerja = DB::table('aucc.unit_kerja')
						->where('id_unit_kerja', $idunitkerja)
						->select('id_unit_kerja', 'nm_unit_kerja')
						->first();

		$layanan = DB::table('layanan as l')
						->leftJoin('layanan_aset as al', function($join) {
							$join->on('l.idlayanan', '=', 'al.idlayanan')
								->where('al.is_deleted', 0);
						})
						->select('l.nama_layanan', 'l.idlayanan', DB::raw('COUNT(al.idlayanan_aset) as jumlah_alat'))
						->groupBy('l.nama_layanan', 'l.idlayanan')
						->orderBy('l.nama_layanan', 'asc')
						->get();

		// dd($layanan);

		return view($this->setting_folder_view.'.mapinglayananunitkerja', compact('menu', 'submenu', 'unitkerja', 'idunitkerja', 'layanan') );
	}

	public function mapingalatkelayanan($iduk, $idlayanan){
		$idunitkerja = \Crypt::decrypt($iduk);
		$idlayanan = \Crypt::decrypt($idlayanan);
		$menu = 'master';
		$submenu = 'layanan_aset';

		// dd($req->all(), $idunitkerja);
		$alatlab = DB::table('aset as a')
						->join('simba.ruang as r', 'a.idruang', '=', 'r.id')
						->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
						->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')						
						->select('a.*', 'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus')
						->where('r.id_unit_kerja', $idunitkerja)
						->get();

		$layanan = DB::table('layanan as l')
					->where('l.idlayanan', $idlayanan)
					->select('l.nama_layanan', 'l.idlayanan')
					->first();

		$alatlabmapped = DB::table('layanan_aset as la')
							->join('aset as a', 'la.kode_barang_aset', '=', 'a.kode_barang_aset')
							->join('simba.ruang as r', 'a.idruang', '=', 'r.id')
							->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
							->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')						
							->select('a.*', 'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus', 'la.is_deleted', 
									'la.waktu_penggunaan_ideal_min', 'la.idlayanan_aset', 'la.no_urut')
							->where('la.idlayanan', $idlayanan)
							->orderBy('la.no_urut', 'asc')
							->get();

		// dd($alatlabmapped);

		return view($this->setting_folder_view.'.mapingalatkelayanan', compact('menu', 'submenu', 'idunitkerja', 'idlayanan','alatlab', 'layanan', 'alatlabmapped') );
	}

	public function prosesmapingalatkelayanan(Request $req){
		// dd($req->all());

		$cek = DB::table('layanan_aset as la')
					->where('la.kode_barang_aset', $req->kode_barang)
					->where('la.idlayanan', $req->idlayanan)
					->get();
		if($cek->count() > 0){
			return response()->json([
				'code' => 400,
				'status' => 'danger',
				'message' => 'Alat sudah terdaftar pada layanan ini'
			]);
		}

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		$jumlah = DB::table('layanan_aset')
			->where('idlayanan', $req->idlayanan)
			->count();

		$arrinsert = array(
			'kode_barang_aset' => $req->kode_barang,
			'idlayanan' => $req->idlayanan,
			'waktu_penggunaan_ideal_min' => 0,
			'created_at' => $ts,
			'no_urut' => $jumlah + 1,
		);

		try {
			DB::table('layanan_aset')->insert($arrinsert);
			return response()->json([
				'code' => 200,
				'status' => 'success',
				'message' => 'Mapping alat ke layanan berhasil disimpan'
			]);
		} catch (\Exception $e) {
			return response()->json([
				'code' => 500,
				'status' => 'danger',
				'message' => 'Terjadi kesalahan saat menyimpan: ' . $e->getMessage()
			]);
			
		}
	}

	public function simpanmapingalatkelayanan(Request $req){
		$nourut = $req->nourut;
		$idlayanan_aset = $req->idlayanan_aset;
		$waktu_ideal = $req->waktu_ideal;

		$update_nourut = '';
		$update_waktu_ideal = '';

		$wherein = '(';
		$i=0;
		foreach($idlayanan_aset as $key => $id){
			$update_nourut .= 'WHEN '.$id.' THEN '.$nourut[$key].' ';
			$update_waktu_ideal .= 'WHEN '.$id.' THEN '.$waktu_ideal[$key].' ';

			if($i > 0)
				$wherein .= ',';
			$wherein .= $id;
			$i++;
		}

		$wherein .= ')';

		$query = 'UPDATE layanan_aset 
					SET no_urut = CASE idlayanan_aset '.$update_nourut.' END, 
						waktu_penggunaan_ideal_min = CASE idlayanan_aset '.$update_waktu_ideal.' END
					WHERE idlayanan_aset IN '.$wherein;

		try {
			DB::select($query);
			session::flash('status', [
				'status' => 'success',
				'message' => 'Mapping alat ke layanan berhasil diperbarui'
			]);
		} catch (\Exception $e) {
			session::flash('status', [
				'status' => 'danger',
				'message' => 'Terjadi kesalahan saat memperbarui mapping: ' . $e->getMessage()
			]);
		}

		return redirect()->back();
	}

	public function hapusmapingalatkelayanan(Request $req){
		$idlayanan_aset = $req->idlayanan_aset;
		$is_deleted = $req->is_deleted;

		try {
			DB::table('layanan_aset')
				->where('idlayanan_aset', $idlayanan_aset)
				->update(['is_deleted' => $is_deleted]);
			return response()->json([
				'code' => 200,
				'status' => 'success',
				'message' => 'Status mapping alat ke layanan berhasil diperbarui'
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'code' => 500,
				'status' => 'danger',
				'message' => 'error: ' . $e->getMessage()
			], 200);
		}
	}

	public function tambah()
	{
		$sroute_prefix = $this->setting_route_prefix;
		$sjudul = $this->setting_judul;

		$aset = \App\Models\Aset::all();
		$layanan = \App\Models\Layanan::all();

		return view($this->setting_folder_view.'.tambah', compact('sroute_prefix', 'sjudul', 'aset', 'layanan') );
	}

	public function tambah_simpan (Request $request)
	{
		$validatedData = $request->validate([
			'kode_barang_aset' => ['required', 'numeric'],
			'idlayanan' => ['required', 'numeric'],
			'waktu_penggunaan_ideal_min' => ['required', 'numeric'],
		], [
			'kode_barang_aset.required' => 'Barang Aset wajib diisi.',
			'idlayanan.required' => 'Layanan wajib diisi.',
			'waktu_penggunaan_ideal_min.required' => 'Waktu penggunaan ideal wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}

		$model_cek = $this->setting_nama_model::where('kode_barang_aset', $request->kode_barang_aset)
			->where('idlayanan', $request->idlayanan)
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
		
		$this->setting_nama_model::create([
			'kode_barang_aset' => $request->kode_barang_aset,
			'idlayanan' => $request->idlayanan,
			'waktu_penggunaan_ideal_min' => $request->waktu_penggunaan_ideal_min,
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
		$aset = \App\Models\Aset::all();
		$layanan = \App\Models\Layanan::all();

		return view($this->setting_folder_view.'.edit', compact('sroute_prefix', 'sjudul', 'data', 'id', 'aset', 'layanan') );
	}

	public function edit_simpan (Request $request, $id)
	{
		$validatedData = $request->validate([
			'kode_barang_aset' => ['required', 'numeric'],
			'idlayanan' => ['required', 'numeric'],
			'waktu_penggunaan_ideal_min' => ['required', 'numeric'],
		], [
			'kode_barang_aset.required' => 'Barang Aset wajib diisi.',
			'idlayanan.required' => 'Layanan wajib diisi.',
			'waktu_penggunaan_ideal_min.required' => 'Waktu penggunaan ideal wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		$model_cek = $this->setting_nama_model::where('kode_barang_aset', $request->kode_barang_aset)
			->where('idlayanan', $request->idlayanan)
			->where('idlayanan_aset', '!=', $id)
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
		$model->kode_barang_aset = $request->kode_barang_aset;
		$model->idlayanan = $request->idlayanan;
		$model->waktu_penggunaan_ideal_min = $request->waktu_penggunaan_ideal_min;
		$model->save();

		Session::flash('status', [
			'status' => 'success',
			'message' => 'Edit '.$this->setting_judul.' berhasil'
		]);

		return redirect()->route($this->setting_route_prefix.'index');
	}

}
