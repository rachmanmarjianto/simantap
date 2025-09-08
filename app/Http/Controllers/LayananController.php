<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Layanan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

class LayananController extends Controller
{
	public function index()
	{
		$menu = 'master';
		$submenu = 'layanan';

		if(session('userdata')['idrole'] == 1){
			// Admin
			$unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user GROUP BY idunit_kerja) as q1'))
							->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
							->leftJoin('layanan as l', 'uk.id_unit_kerja', '=', 'l.idunit_kerja')
							->select('uk.nm_unit_kerja', 'q1.idunit_kerja', DB::raw('COUNT(l.idlayanan) as jumlah_layanan'))
							->groupBy('uk.nm_unit_kerja', 'q1.idunit_kerja')
							->get();
		} else {
			// Bukan admin
			$idunitkerja = session('userdata')['idunit_kerja'];
			$unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user where status = true GROUP BY idunit_kerja) as q1'))
							->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
							->leftJoin('layanan as l', 'uk.id_unit_kerja', '=', 'l.idunit_kerja')
							->select('uk.nm_unit_kerja', 'q1.idunit_kerja', DB::raw('COUNT(l.idlayanan) as jumlah_layanan'))
							->groupBy('uk.nm_unit_kerja', 'q1.idunit_kerja')
							->get();
		}
		
		

		// $list_layanan = Layanan::all();
		return view('layanan.index', compact('menu', 'submenu', 'unitkerja') );
	}

	public function layananunitkerja($id)
	{
		$menu = 'master';
		$submenu = 'layanan';

		$idunitkerja = Crypt::decrypt($id);

		$unitkerja = DB::table('aucc.unit_kerja')
						->where('id_unit_kerja', $idunitkerja)
						->select('id_unit_kerja', 'nm_unit_kerja')
						->first();

		$layanan = DB::table('layanan as l')
					->join('aucc.unit_kerja as uk', 'l.idunit_kerja', '=', 'uk.id_unit_kerja')
					->leftJoin('aplikasi_uk as au', 'l.idaplikasi_uk', '=', 'au.idaplikasi_uk')
					->where('l.idunit_kerja', $idunitkerja)
					->select('l.idlayanan', 'l.nama_layanan', 'l.status', 'uk.nm_unit_kerja', 'l.idlayanan_unit_kerja', 'au.nama_aplikasi', 'au.idaplikasi_uk')
					->get();

		$appuk = DB::table('aplikasi_uk as au')
					->where('au.idunit_kerja', $idunitkerja)
					->where('au.status', 1)
					->get();
		
		// dd($appuk);

		return view('layanan.layananunitkerja', compact('menu', 'submenu', 'unitkerja', 'layanan', 'appuk', 'idunitkerja') );
	}

	public function tarik_master_layanan(Request $req){
		$idunitkerja = $req->idunitkerja;

		//---ambil API tipe 1 (ambil data layanan dari API)
		try {
			$api_layanan = DB::table('endpoint as e')
								->join('aplikasi_uk as au', 'e.idaplikasi_uk', '=', 'au.idaplikasi_uk')
								->where('e.idjenis_endpoint', 1)
								->where('e.idaplikasi_uk', $req->idaplikasi)
								->where('au.status', 1)
								->where('e.status', 1)
								->select('e.link')
								->first();
		} catch (\Exception $e) {
			return response()->json([
				'code' => 500,
				'status' => 'error',
				'message' => 'Gagal mengambil endpoint API: '.$e->getMessage(),
				'data' => null
			], 500);
		}

		// dd($api_layanan);

		if( !$api_layanan || !$api_layanan->link) {
			return response()->json([
				'code' => 404,
				'status' => 'error',
				'message' => 'Endpoint API tidak ditemukan',
				'data' => null
			], 200); //sengaja di kasih 200, kalo ndak larinya ke yg error dan ndak bisa diambil response message nya. ya aku msh blm tau carane, so little time too much to do boyy....
		}
		
		
		try {
			$curl = curl_init();

			curl_setopt_array($curl, array(
				CURLOPT_URL => $api_layanan->link,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
			));

			$response = curl_exec($curl);

			if (curl_errno($curl)) {
				throw new \Exception(curl_error($curl));
			}

			curl_close($curl);

		} catch (\Exception $e) {
			return response()->json([
				'code' => 500,
				'status' => 'error',
				'message' => 'Tarik data error: '.$e->getMessage(),
				'data' => null
			], 500);
		}

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		// dd($response);

		$data_layanan = json_decode($response, true);

		$whereIn = [];
		foreach ($data_layanan as $layanan) {
			$whereIn[] = $layanan['idlayanan'];
		}

		$existing_layanan = DB::table('layanan')
			->whereIn('idlayanan_unit_kerja', $whereIn)
			->where('idunit_kerja', $idunitkerja)
			->select('idlayanan', 'idlayanan_unit_kerja')
			->get();

		$existing_layanan_arr = [];
		foreach ($existing_layanan as $layanan) {
			$existing_layanan_arr[$layanan->idlayanan_unit_kerja] = $layanan->idlayanan;
		}

		$insert_layanan = [];
		$update_layanan = [];
		$nm_layanan_case = '';
		$levelCases = '';
		$whereInExisting = '(';
		$exist_id = 0;
		$index = 0;

		foreach($data_layanan as $layanan){
			if(array_key_exists($layanan['idlayanan'], $existing_layanan_arr)){
				// Update existing layanan
				$exist_id = $existing_layanan_arr[$layanan['idlayanan']];
				$update_layanan[$layanan['idlayanan']] = [
					'nama_layanan' => $layanan['nama_layanan']
				];
				
				$nm_layanan_case .= 'WHEN '.$exist_id.' THEN \''.$layanan['nama_layanan'].'\' ';

				if($index > 0)
					$whereInExisting .= ',';

				$whereInExisting .= $exist_id;
				$index++;

			} else {
				// Insert new layanan
				$insert_layanan[] = [
					'nama_layanan' => $layanan['nama_layanan'],
					'idunit_kerja' => $idunitkerja,
					'idlayanan_unit_kerja' => $layanan['idlayanan'],
					'status' => 1,
					'created_at' => $ts,
					'idaplikasi_uk' => $req->idaplikasi ?? null,
				];
			}
		}

		$whereInExisting .= ')';	
		
		

		if($exist_id > 0){
			$query = 'UPDATE layanan 
							SET nama_layanan = CASE idlayanan '.$nm_layanan_case.' END 
							WHERE idlayanan IN '.$whereInExisting;

			// echo $query;die;

			try {
				DB::select($query);
			} catch (\Exception $e) {
				return response()->json([
					'code' => 500,
					'status' => 'error',
					'message' => 'Update data error: '.$e->getMessage(),
					'data' => null
				], 500);
			}
		}

		if(count($insert_layanan) > 0){
			try {
				DB::table('layanan')->insert($insert_layanan);
			} catch (\Exception $e) {
				return response()->json([
					'code' => 500,
					'status' => 'error',
					'message' => 'Insert data error: '.$e->getMessage(),
					'data' => null
				], 500);
			}
		}

		return response()->json([
			'code' => 200,
			'status' => 'success',
			'message' => 'Tarik data layanan berhasil',
			'data' => [
				'total_inserted' => count($insert_layanan),
				'total_updated' => count($update_layanan)
			]
		], 200);



	}

	public function tambah()
	{
		$unit_kerja = DB::table('unit_kerja')->select('idunit_kerja', 'nama_unit_kerja')->get();
		return view('layanan.tambah', compact('unit_kerja') );
	}

	public function tambah_simpan (Request $request)
	{
		$validatedData = $request->validate([
			'nama_layanan' => ['required', 'string'],
			'idunit_kerja' => ['required', 'numeric'],
			'status' => ['required', 'in:0,1'],
			'idlayanan_unit_kerja' => ['nullable', 'string'],
		], [
			'nama_layanan.required' => 'Nama layanan wajib diisi.',
			'idunit_kerja.required' => 'Unit Kerja layanan wajib diisi.',
			'status.required' => 'Status wajib dipilih.',
			'status.in' => 'Status harus 0 atau 1.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		Layanan::create([
			'nama_layanan' => $request->nama_layanan,
			'idunit_kerja' => $request->idunit_kerja,
			'idunit_kerja' => $request->idunit_kerja,
			'idlayanan_unit_kerja' => $request->idlayanan_unit_kerja,
		]);
		Session::flash('status', [
			'status' => 'success',
			'message' => 'Layanan berhasil ditambahkan'
		]);

		return redirect()->route('layanan_index');
	}

	public function hapus ($id)
	{
		$Layanan = Layanan::find($id);
		if ($Layanan)
		{
			$Layanan->delete();
			Session::flash('status', [
				'status' => 'success',
				'message' => 'Layanan berhasil dihapus'
			]);
		}
		else
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Layanan tidak ditemukan'
			]);
		}

		return redirect()->route('layanan_index');
	}

	public function edit ($id)
	{
		$layanan = Layanan::find($id);
		$unit_kerja = DB::table('unit_kerja')->select('idunit_kerja', 'nama_unit_kerja')->get();
		return view('layanan.edit', compact('layanan', 'id', 'unit_kerja') );
	}

	public function edit_simpan (Request $request, $id)
	{
		$validatedData = $request->validate([
			'nama_layanan' => ['required', 'string'],
			'idunit_kerja' => ['required', 'numeric'],
			'status' => ['required', 'in:0,1'],
			'idlayanan_unit_kerja' => ['nullable', 'string'],
		], [
			'nama_layanan.required' => 'Nama layanan wajib diisi.',
			'idunit_kerja.required' => 'Unit Kerja layanan wajib diisi.',
			'status.required' => 'Status wajib dipilih.',
			'status.in' => 'Status harus 0 atau 1.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		$Layanan = Layanan::find($id);
		if (!$Layanan)
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Layanan tidak ditemukan'
			]);
			return redirect()->route('Layanan_index');
		}
		$Layanan->nama_layanan = $request->nama_layanan;
		$Layanan->idunit_kerja = $request->idunit_kerja;
		$Layanan->status = $request->status;
		$Layanan->idlayanan_unit_kerja = $request->idlayanan_unit_kerja;
		$Layanan->save();

		Session::flash('status', [
			'status' => 'success',
			'message' => 'Edit Layanan berhasil'
		]);

		return redirect()->route('layanan_index');
	}

}
