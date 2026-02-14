<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Jenssegers\Agent\Agent;
use Carbon\Carbon;

class PermintaanLayananController extends Controller
{
	protected $setting_folder_view, $setting_route_prefix, $setting_judul, $setting_nama_model;
	public function __construct()
	{
		$this->setting_folder_view = 'permintaan-layanan';
		$this->setting_route_prefix = 'permintaan_layanan_';
		$this->setting_judul = 'Permintaan Layanan';
		$this->setting_nama_model = \App\Models\PermintaanLayanan::class;
	}

	public function index_admin()
	{
		// $sroute_prefix = $this->setting_route_prefix;
		// $sjudul = $this->setting_judul;

		$menu = 'transaksi';
		$submenu = 'permintaan_layanan';

		// dd(session('tanggal'));

		if(!isset(session('tanggal')['tgl_awal']) ){

			date_default_timezone_set('Asia/Jakarta');
			$today = date('Y-m-d');

			$tgl_awal = $today;
			$tgl_akhir = $today;
			$waktu_awal = $today.' 00:00:00';
			$waktu_akhir = $today.' 23:59:59';
		}
		else{
			$tgl_awal = session('tanggal')['tgl_awal'];
			$tgl_akhir = session('tanggal')['tgl_akhir'];
			$waktu_awal = $tgl_awal.' 00:00:00';
			$waktu_akhir = $tgl_akhir.' 23:59:59';
		}
		
		if(session('userdata')['idrole'] == 4){
			$permintaan_layanan = DB::table('permintaan_layanan as pl')
								->join('layanan as l', 'pl.idlayanan', '=', 'l.idlayanan')
								->join('operator_layanan as ol', 'pl.idlayanan', '=', 'ol.idlayanan')
								->join('layanan_aset as la', 'pl.idlayanan', '=', 'la.idlayanan')
								->join('aset as a', 'la.kode_barang_aset', '=', 'a.kode_barang_aset')
								->join('pj_ruang as pr', 'a.idruang', '=', 'pr.idruang')
								->select('pl.idpermintaan_layanan', 'pl.idlayanan', 'pl.created_at', 'pl.status', 'pl.idlayanan_aplikasi_asal', 'pl.detail_layanan', 'pl.ts_req_masuk_aplikasi_asal', 
											'l.nama_layanan', 'l.idunit_kerja')
								->where('pl.ts_req_masuk_aplikasi_asal', '>=' ,'\''.$waktu_awal.'\'')
								->where('pl.ts_req_masuk_aplikasi_asal', '<=' ,'\''.$waktu_akhir.'\'')
								->where('pr.iduser', session('userdata')['iduser'])
								->where('pr.status', true)
								->where('ol.is_deleted', false)
								->orderBy('pl.created_at', 'desc')
								->groupBy('pl.idpermintaan_layanan', 'pl.idlayanan', 'pl.created_at', 'pl.status', 'pl.idlayanan_aplikasi_asal', 'pl.detail_layanan', 'pl.ts_req_masuk_aplikasi_asal',
											'l.nama_layanan', 'l.idunit_kerja')
								->get();

								// dd('hallo');
		}
		else{
			$permintaan_layanan = DB::table('permintaan_layanan as pl')
								->join('layanan as l', 'pl.idlayanan', '=', 'l.idlayanan')
								->join('operator_layanan as ol', 'pl.idlayanan', '=', 'ol.idlayanan')
								->select('pl.idpermintaan_layanan', 'pl.idlayanan', 'pl.created_at', 'pl.status', 'pl.idlayanan_aplikasi_asal', 'pl.detail_layanan', 'pl.ts_req_masuk_aplikasi_asal',
											'l.nama_layanan', 'l.idunit_kerja')
								->where('pl.ts_req_masuk_aplikasi_asal', '>=' ,'\''.$waktu_awal.'\'')
								->where('pl.ts_req_masuk_aplikasi_asal', '<=' ,'\''.$waktu_akhir.'\'')
								->where('l.idunit_kerja', session('userdata')['idunit_kerja'])
								->where('ol.iduser', session('userdata')['iduser'])
								->where('ol.is_deleted', false)
								->orderBy('pl.created_at', 'desc')
								->groupBy('pl.idpermintaan_layanan', 'pl.idlayanan', 'pl.created_at', 'pl.status', 'pl.idlayanan_aplikasi_asal', 'pl.detail_layanan', 'pl.ts_req_masuk_aplikasi_asal',
											'l.nama_layanan', 'l.idunit_kerja')
								->get();
		}
		
		// dd($permintaan_layanan);
								
		$agent = new Agent();

		if ($agent->isMobile()) {
			// dd('mobile');
			
			return view($this->setting_folder_view.'.index_admin_mobile', compact('menu','submenu','waktu_awal', 'waktu_akhir', 'tgl_awal','tgl_akhir', 'permintaan_layanan') );
			
			
		} else {
			// dd('web view');
			
			return view($this->setting_folder_view.'.index_admin', compact('menu','submenu','waktu_awal', 'waktu_akhir', 'tgl_awal','tgl_akhir', 'permintaan_layanan') );
			
			
		}

		
	}

	public function tarik_layanan_uk(Request $req){
		$idunitkerja = $req->idunitkerja;
		$datehelp = explode(' - ', $req->rangetanggal);
		$tgl_awal = $datehelp[0];
		$tgl_akhir = $datehelp[1];

		

		$endpoint = DB::table('endpoint as e')
						->join('aplikasi_uk as au', 'e.idaplikasi_uk', '=', 'au.idaplikasi_uk')
						->where('au.idunit_kerja', $idunitkerja)
						->where('e.status', 1)
						->where('e.idjenis_endpoint', 2)
						->where('e.status', 1)
						->where('au.status', 1)
						->select('e.link')
						->first();

		// dd($tgl_awal, $tgl_akhir, $endpoint);

		if( !$endpoint )
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Endpoint layanan tidak ditemukan'
			]);
			return redirect()->back();
		}


		try {
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => $endpoint->link,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => array('awal' => $tgl_awal, 'akhir' => $tgl_akhir),
			));

			$response = curl_exec($curl);

			if (curl_errno($curl)) {
				throw new \Exception('Curl error: ' . curl_error($curl));
			}

			curl_close($curl);

			// echo $response;die;

			$result = json_decode($response, true);
			
		} catch (\Exception $e) {
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Terjadi kesalahan saat mengambil data layanan: ' . $e->getMessage()
			]);
			return redirect()->back();
		}

		if(count($result) == 0)
		{
			session(['tanggal' =>[
				'tgl_awal' => $tgl_awal,
				'tgl_akhir' => $tgl_akhir
			]]);

			Session::flash('status', [
				'status' => 'warning',
				'message' => 'Tidak ada data Permintaan layanan yang ditemukan untuk tanggal tersebut. '.$endpoint->link
			]);
			return redirect()->back();
		}
		else{
			$waktu_awal = $tgl_awal.' 00:00:00';
			$waktu_akhir = $tgl_akhir.' 23:59:59';

			$cur_permintaan = DB::table('permintaan_layanan as pl')
									->select('pl.idlayanan_aplikasi_asal')
									->join('layanan as l', 'pl.idlayanan', '=', 'l.idlayanan')
									->whereBetween('pl.ts_req_masuk_aplikasi_asal', [$waktu_awal, $waktu_akhir])
									->where('l.idunit_kerja', $idunitkerja)
									->get();

			$existing_ids = [];
			foreach ($cur_permintaan as $item) {
				$existing_ids[] = $item->idlayanan_aplikasi_asal;
			}

			$wherein = [];
			foreach ($result as $layanan) {
				$wherein[] = $layanan['id_master_layanan'];
			}

			$idlayanan_q = DB::table('layanan as l')
								->where('idunit_kerja', $idunitkerja)
								->whereIn('idlayanan_unit_kerja', $wherein)
								->select('idlayanan', 'idlayanan_unit_kerja')
								->get();

			$idlayanan = [];
			foreach ($idlayanan_q as $item) {
				$idlayanan[$item->idlayanan_unit_kerja] = $item->idlayanan;
			}

			date_default_timezone_set('Asia/Jakarta');
			$ts = date('Y-m-d H:i:s');

			// dd($existing_ids);

			$insert_data = [];
			foreach ($result as $layanan) {
				if (!in_array($layanan['id_permintaan_layanan'], $existing_ids)) {
					$insert_data[] = [
						'idlayanan_aplikasi_asal' => $layanan['id_permintaan_layanan'],
						'idlayanan' => $idlayanan[$layanan['id_master_layanan']],
						'status' => 1,
						'created_at' => $ts,
						'ts_req_masuk_aplikasi_asal' => $layanan['waktu_request'],
						'detail_layanan' => $layanan['detail_layanan'],
					];
				}
			}
			
			try {
				DB::table('permintaan_layanan')->insert($insert_data);
				Session::flash('status', [
					'status' => 'success',
					'message' => 'Data permintaan layanan berhasil disimpan.'
				]);
			} catch (\Exception $e) {
				Session::flash('status', [
					'status' => 'danger',
					'message' => 'Gagal menyimpan data permintaan layanan: ' . $e->getMessage()
				]);
			}

			session(['tanggal' =>[
				'tgl_awal' => $tgl_awal,
				'tgl_akhir' => $tgl_akhir
			]]);

			
			return redirect()->route('permintaan_layanan_index_admin');
		}

	}

	public function get_layanan_uk(Request $req){

		$datehelp = explode(' - ', $req->rangetanggal);
		$tgl_awal = $datehelp[0];
		$tgl_akhir = $datehelp[1];

		// dd($tgl_awal, $tgl_akhir);

		session(['tanggal' =>[
				'tgl_awal' => $tgl_awal,
				'tgl_akhir' => $tgl_akhir
			]]);

			
		return redirect()->route('permintaan_layanan_index_admin');
	}

	public function detailpermintaanlayanan($id){
		$menu = 'transaksi';
		$submenu = 'permintaan_layanan';

		$idpermintaan_layanan = Crypt::decrypt($id);

		$permintaan_layanan = DB::table('permintaan_layanan as pl')
								->join('layanan as l', 'pl.idlayanan', '=', 'l.idlayanan')
								->select('pl.*', 'l.nama_layanan', 'l.idunit_kerja')
								->where('pl.idpermintaan_layanan', $idpermintaan_layanan)
								->first();

		$alat_lab = DB::table('permintaan_layanan as pl')
						->join('layanan as l', 'pl.idlayanan', '=', 'l.idlayanan')
						->join('layanan_aset as la', 'pl.idlayanan', '=', 'la.idlayanan')
						->join('aset as a', 'la.kode_barang_aset', '=', 'a.kode_barang_aset')
						->join('simba.ruang as r', 'a.idruang', '=', 'r.id')
						->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
						->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
						->where('pl.idpermintaan_layanan', $idpermintaan_layanan)
						->where('la.is_deleted', 0)
						->select('a.kode_barang_aset', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset', 'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus', 'a.keterangan',
								'pl.*', 'l.nama_layanan', 'la.no_urut')
						->orderBy('la.no_urut', 'asc')
						->get();

		$timestamp_alat_q = DB::table('riwayat_pemakaian_aset as rap')
								->where('rap.idpermintaan_layanan', $idpermintaan_layanan)
								->select('rap.kode_barang_aset', 'rap.timestamp_mulai', 'rap.timestamp_akhir', 'rap.dimulai_oleh', 'rap.diakhiri_oleh')
								->get();

		$timestamp_alat = [];
		foreach ($timestamp_alat_q as $item) {
			$start = Carbon::parse($item->timestamp_mulai);
			$end   = Carbon::parse($item->timestamp_akhir);

			$diffInMinutes = $start->diffInMinutes($end); // total selisih dalam menit
			$hours = intdiv($diffInMinutes, 60);          // bagi 60 untuk dapat jam
			$minutes = $diffInMinutes % 60;               // sisa menit

			$timestamp_alat[$item->kode_barang_aset] = [
				'timestamp_mulai' => $item->timestamp_mulai,
				'timestamp_akhir' => $item->timestamp_akhir,
				'dimulai_oleh' => $item->dimulai_oleh,
				'diakhiri_oleh' => $item->diakhiri_oleh,
				'durasi' => sprintf('%02d Jam %02d Menit', $hours, $minutes)
			];
		}


		// dd( $timestamp_alat );

		$laboran = [];

		if(session('userdata')['idrole'] == 4){
			$laboran_q = DB::table('operator_layanan as ol')
							->join('user as u', 'ol.iduser', '=', 'u.iduser')
							->join('layanan as l', 'ol.idlayanan', '=', 'l.idlayanan')
							->join('permintaan_layanan as pl', 'l.idlayanan', '=', 'pl.idlayanan')
							->where('pl.idpermintaan_layanan', $idpermintaan_layanan)
							->where('ol.status', 't')
							->where('ol.is_deleted', 'f')
							->select('u.nama', 'u.gelar_depan', 'u.gelar_belakang', 'u.nipnik', 'u.iduser')
							->orderBy('u.nama', 'asc')
							->get();

			foreach ($laboran_q as $item) {
				$laboran[$item->iduser] = $item;
			}
		}

		// dd($laboran);

		

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		$agent = new Agent();

		if ($agent->isMobile()) {
			if(session('userdata')['idrole'] == 4){
				return view($this->setting_folder_view.'.detailpermintaanlayanan_mobile_pjruang', compact('menu', 'submenu', 'permintaan_layanan', 'alat_lab', 'ts', 'timestamp_alat', 'laboran') );
			}
			else{
				return view($this->setting_folder_view.'.detailpermintaanlayanan_mobile', compact('menu', 'submenu', 'permintaan_layanan', 'alat_lab', 'ts', 'timestamp_alat', 'laboran') );
			}
			
		}
		else{
			if(session('userdata')['idrole'] == 4){
				return view($this->setting_folder_view.'.detailpermintaanlayanan_pjruang', compact('menu', 'submenu', 'permintaan_layanan', 'alat_lab', 'ts', 'timestamp_alat', 'laboran') );
			}
			else{
				return view($this->setting_folder_view.'.detailpermintaanlayanan', compact('menu', 'submenu', 'permintaan_layanan', 'alat_lab', 'ts', 'timestamp_alat', 'laboran') );
			}
			
		}

		
	}

	public function setstatuspermintaanlayanan(Request $req){
		// dd($req->all());
		$idpermintaan_layanan = $req->idpermintaan_layanan;
		$status = $req->status;

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		try {
			DB::table('permintaan_layanan')
				->where('idpermintaan_layanan', $idpermintaan_layanan)
				->update([
					'status' => $status,
					'updated_at' => $ts,
					'updated_by' => session('userdata')['iduser'],
				]);
			Session::flash('status', [
				'status' => 'success',
				'message' => 'Status permintaan layanan berhasil diperbarui.'
			]);
		} catch (\Exception $e) {
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Gagal memperbarui status permintaan layanan: ' . $e->getMessage()
			]);
		}

		return redirect()->back();

	}

	public function simpantslayanan(Request $req)
	{
		$type = $req->type;
		$kode_barang_aset = $req->kode_barang_aset;
		$idpermintaan_layanan = $req->idpermintaan_layanan;
		$ts = $req->timestamp;

		date_default_timezone_set('Asia/Jakarta');
		$ts_now = date('Y-m-d H:i:s');

		$cek = DB::table('riwayat_pemakaian_aset as rap')
							->where('rap.idpermintaan_layanan', $idpermintaan_layanan)
							->where('rap.kode_barang_aset', $kode_barang_aset)
							->first();


		if($type == 1){	
			$kol_waktu = 'timestamp_mulai';
			$kol_oleh = 'dimulai_oleh';
			$ts_isi = 'ts_mulai_diisi';
		}
		else{
			$kol_waktu = 'timestamp_akhir';	
			$kol_oleh = 'diakhiri_oleh';
			$ts_isi = 'ts_akhir_diisi';
		}

		// date_default_timezone_set('Asia/Jakarta');
		// $ts = date('Y-m-d H:i:s');

		if($cek){
			try {
				DB::table('riwayat_pemakaian_aset')
					->where('idriwayat_pemakaian_aset', $cek->idriwayat_pemakaian_aset)
					->update([
						$kol_waktu => $ts,
						$kol_oleh => session('userdata')['iduser'],
						$ts_isi => $ts_now
					]);
			} catch (\Exception $e) {
				return response()->json([
					'code' => 500,
					'status' => 'error',
					'message' => 'Gagal memperbarui riwayat pemakaian aset: ' . $e->getMessage()
				], 500);
			}
		}
		else{
			try {
				DB::table('riwayat_pemakaian_aset')
					->insert([
						'idpermintaan_layanan' => $idpermintaan_layanan,
						'kode_barang_aset' => $kode_barang_aset,
						$kol_waktu => $ts,
						$kol_oleh => session('userdata')['iduser'],
						'ts_mulai_diisi' => $ts_now,
					]);
			} catch (\Exception $e) {
				return response()->json([
					'code' => 500,
					'status' => 'error',
					'message' => 'Gagal menyimpan riwayat pemakaian aset: ' . $e->getMessage()
				], 500);
			}
		}

		$jumlah_alat_q = DB::table('permintaan_layanan as pl')
							->join('layanan as l', 'pl.idlayanan', '=', 'l.idlayanan')
							->join('layanan_aset as la', 'pl.idlayanan', '=', 'la.idlayanan')
							->where('pl.idpermintaan_layanan', $idpermintaan_layanan)
							->where('la.is_deleted', 0)
							->select('la.kode_barang_aset', 'pl.status')
							->get();

		$jumlah_alat = count($jumlah_alat_q);

		// dd($jumlah_alat);

		$status_permintaanlayanan = $jumlah_alat_q[0]->status;

		$q_alat_dipakai = DB::table('riwayat_pemakaian_aset as rap')
									->where('rap.idpermintaan_layanan', $idpermintaan_layanan)
									->get();

		$juml_ts = 0;

		foreach ($q_alat_dipakai as $item) {
			if($item->timestamp_mulai != null){
				$juml_ts++;
			}

			if($item->timestamp_akhir != null){
				$juml_ts++;
			}
		}

		if($juml_ts >= $jumlah_alat * 2){
			try {
				DB::table('permintaan_layanan')
					->where('idpermintaan_layanan', $idpermintaan_layanan)
					->update([
						'status' => 3, // status selesai
						'updated_at' => $ts,
						'updated_by' => session('userdata')['iduser'],
					]);
			} catch (\Exception $e) {
				return response()->json([
					'code' => 500,
					'status' => 'error',
					'message' => 'Gagal memperbarui status permintaan layanan: ' . $e->getMessage()
				], 500);
			}

			return response()->json([
						'code' => 200,
						'status' => 'success',
						'message' => 'Riwayat pemakaian aset berhasil disimpan.',
						'status_permintaan' => 3
					], 200);
		}
		else{
			if($juml_ts > 0 && $status_permintaanlayanan == 1){
				try {
					DB::table('permintaan_layanan')
						->where('idpermintaan_layanan', $idpermintaan_layanan)
						->update([
							'status' => 2, // status sedang diproses,
							'updated_at' => $ts,
							'updated_by' => session('userdata')['iduser'],
						]);
					
				} catch (\Exception $e) {
					return response()->json([
						'code' => 500,
						'status' => 'error',
						'message' => 'Gagal memperbarui status permintaan layanan: ' . $e->getMessage()
					], 500);
				}
			}
			

			return response()->json([
					'code' => 200,
					'status' => 'success',
					'message' => 'Riwayat pemakaian aset berhasil disimpan.',
					'status_permintaan' => 2
				], 200);
		}

		return response()->json([
			'code' => 500,
			'status' => 'error',
			'message' => 'Jalur tidak diketahui',
			'status_permintaan' => $status_permintaanlayanan
		], 500);
						
		
		
	}

	public function index()
	{
		$sroute_prefix = $this->setting_route_prefix;
		$sjudul = $this->setting_judul;

		$data = $this->setting_nama_model::all();

		return view($this->setting_folder_view.'.index', compact('sroute_prefix', 'sjudul', 'data') );
	}

	public function tambah()
	{
		$sroute_prefix = $this->setting_route_prefix;
		$sjudul = $this->setting_judul;

		$layanan = \App\Models\Layanan::all();

		return view($this->setting_folder_view.'.tambah', compact('sroute_prefix', 'sjudul', 'layanan') );
	}

	public function tambah_simpan (Request $request)
	{
		$validatedData = $request->validate([
			'idlayanan' => ['required', 'numeric'],
			'status' => ['required', 'in:0,1'],
		], [
			'idlayanan.required' => 'Layanan wajib diisi.',
			'status.required' => 'Status aktif wajib dipilih.',
			'status.in' => 'Status aktif harus 0 atau 1.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}

		$model_cek = $this->setting_nama_model::where('idlayanan', $request->idlayanan)->first();
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
			'idlayanan' => $request->idlayanan,
			'status' => $request->status,
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
		$layanan = \App\Models\Layanan::all();

		return view($this->setting_folder_view.'.edit', compact('sroute_prefix', 'sjudul', 'data', 'id', 'layanan') );
	}

	public function edit_simpan (Request $request, $id)
	{
		$validatedData = $request->validate([
			'idlayanan' => ['required', 'numeric'],
			'status' => ['required', 'in:0,1'],
		], [
			'idlayanan.required' => 'Layanan wajib diisi.',
			'status.required' => 'Status aktif wajib dipilih.',
			'status.in' => 'Status aktif harus 0 atau 1.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		$model_cek = $this->setting_nama_model::where('idlayanan', $request->idlayanan)
			->where('idpermintaan_layanan', '!=', $id)
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
		$model->idlayanan = $request->idlayanan;
		$model->status = $request->status;
		$model->save();

		Session::flash('status', [
			'status' => 'success',
			'message' => 'Edit '.$this->setting_judul.' berhasil'
		]);

		return redirect()->route($this->setting_route_prefix.'index');
	}

}
