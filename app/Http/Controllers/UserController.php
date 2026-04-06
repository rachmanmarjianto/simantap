<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use App\Services\Simantap_service;

class UserController extends Controller
{
	public function index()
	{
		$menu = 'master';
		$submenu = 'user';

		if(session('userdata')['idrole'] == 1){
			// Jika role adalah superadmin, tampilkan semua user
			$list_user = User::all();
		} else {
			// Jika bukan superadmin, tampilkan hanya user yang memiliki role dengan idunit_kerja yang sama dengan role user yang login
			$idunit_kerja = session('userdata')['idunit_kerja'];
			$list_user = DB::table('user as u')
				->join('role_user as ru', 'u.iduser', '=', 'ru.iduser')
				->join('role as r', 'ru.idrole', '=', 'r.idrole')
				->where('ru.idunit_kerja', $idunit_kerja)
				->select('u.iduser', 'u.nipnik', 'u.nama', 'u.gelar_depan', 'u.gelar_belakang', 'u.join_table', 'u.status')
				->groupBy('u.iduser', 'u.nipnik', 'u.nama', 'u.gelar_depan', 'u.gelar_belakang', 'u.join_table', 'u.status')
				->get();
		}
		return view('user.index', compact('menu', 'submenu', 'list_user') );
	}

	public function tambah()
	{
		$menu = 'master';
		$submenu = 'user';

		if(session('userdata')['idrole'] == 1){
			$unit_kerja = DB::table('aucc.unit_kerja')
							->select('id_unit_kerja as idunit_kerja', 'nm_unit_kerja as nama_unit_kerja', 'type_unit_kerja')
							->where('status_aktif', 1)
							->get();
		} else {
			$unit_kerja = DB::table('aucc.unit_kerja')
							->select('id_unit_kerja as idunit_kerja', 'nm_unit_kerja as nama_unit_kerja', 'type_unit_kerja')
							->where('status_aktif', 1)
							->where('id_unit_kerja', session('userdata')['idunit_kerja'])
							->get();
		}

		$role = DB::table('role')
					->get();

		// dd($unit_kerja);
		return view('user.tambah', compact('menu', 'submenu', 'unit_kerja', 'role') );
	}

	public function tambah_simpan (Request $request)
	{
		// dd($request->all());

		$validatedData = $request->validate([
			'nipnik' => ['required', 'regex:/^[0-9]+$/'],
			'nama' => ['required', 'string'],
			'gelar_depan' => ['nullable', 'string'],
			'gelar_belakang' => ['nullable', 'string'],
			'join_table' => ['required', 'in:1,2'],
			'id_cyber' => ['required', 'numeric'],
			'role' => ['required', 'numeric'],
			'unit_kerja' => ['nullable', 'numeric'],
		], [
			'nipnik.required' => 'Nama wajib diisi.',
			'nipnik.regex' => 'Nama hanya boleh berisi angka saja.',
			'join_table.required' => 'Join Table wajib diisi.',
			'join_table.in' => 'Status aktif harus 1 / 2.',
			'role.required' => 'Role wajib diisi.',
			'unit_kerja.required' => 'Unit kerja wajib diisi.',
			'unit_kerja.numeric' => 'Unit kerja harus berupa angka.',
		] );

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator);
		}

		$cek = DB::table('user as u')
					->join('role_user as ru', 'u.iduser', '=', 'ru.iduser')
					->where('u.nipnik', $request->nipnik)
					->get();


		if (count($cek) > 0) {
			$arr_roles = [];
			foreach ($cek as $item) {
				if(!array_key_exists($item->idrole, $arr_roles)){
					$arr_roles[$item->idrole] = array();
				}
				$arr_roles[$item->idrole][] = $item->idunit_kerja;
			}

			if (array_key_exists($request->role, $arr_roles)) {
				if(in_array($request->unit_kerja, $arr_roles[$request->role])){
					Session::flash('status', [
						'status' => 'danger',
						'message' => 'User dengan NIP/NIK tersebut sudah ada dengan role yang sama dan unit kerja yang sama'
					]);
					return redirect()->back()->withInput();
				}
			}

			$iduser = $cek[0]->iduser;

			DB::table('role_user')
				->where('iduser', $iduser)
				->update([
					'status' => false
				]);
		}
		else{
			$curl = curl_init();

			curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apicybercampus.unair.ac.id/api/token/ambil-token-v2',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('user' => config('services.proj_variable.api_get_token_user'),'key' => config('services.proj_variable.api_get_token_key')),
			CURLOPT_HTTPHEADER => array(
				'Cookie: _csrf=803c33edb6f5136fcef3ee81cda393ced5ae03c78513b0645a780ce5044e1c96a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22qwoqSEOXxtPGfPwWdqPqszm0oL5_srUf%22%3B%7D; uacc-session=fvgeqjors9i2pqte2ibh4vpjmm'
			),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			
			$token = str_replace('"', '', $response);

			if($request->join_table == 1){

				$curl = curl_init();

				curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://apicybercampus.unair.ac.id/api/pengguna/info-singkat-pegawai',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => array('token' => $token,'nip' => $request->nipnik),
				CURLOPT_HTTPHEADER => array(
					'Cookie: _csrf=803c33edb6f5136fcef3ee81cda393ced5ae03c78513b0645a780ce5044e1c96a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22qwoqSEOXxtPGfPwWdqPqszm0oL5_srUf%22%3B%7D; uacc-session=fvgeqjors9i2pqte2ibh4vpjmm'
				),
				));

				$response = curl_exec($curl);

				curl_close($curl);
				
				$result = json_decode($response, true);
				$idprogram_studi = $result[0]['ID_PROGRAM_STUDI'];

			}
			else if($request->join_table == 2){

				$curl = curl_init();

				curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://apicybercampus.unair.ac.id/api/pengguna/info-singkat-dosen',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => array('token' => $token,'nip' => $request->nipnik),
				CURLOPT_HTTPHEADER => array(
					'Cookie: _csrf=803c33edb6f5136fcef3ee81cda393ced5ae03c78513b0645a780ce5044e1c96a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22qwoqSEOXxtPGfPwWdqPqszm0oL5_srUf%22%3B%7D; uacc-session=fvgeqjors9i2pqte2ibh4vpjmm'
				),
				));

				$response = curl_exec($curl);

				curl_close($curl);
				
				$result = json_decode($response, true);
				$idprogram_studi = $result[0]['ID_PROGRAM_STUDI'];

			}
			else if($request->join_table == 3){

				$curl = curl_init();

				curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://apicybercampus.unair.ac.id/api/pengguna/info-singkat-mahasiswa',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => array('token' => $token,'nim' => $request->nipnik),
				CURLOPT_HTTPHEADER => array(
					'Cookie: _csrf=803c33edb6f5136fcef3ee81cda393ced5ae03c78513b0645a780ce5044e1c96a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22qwoqSEOXxtPGfPwWdqPqszm0oL5_srUf%22%3B%7D; uacc-session=fvgeqjors9i2pqte2ibh4vpjmm'
				),
				));

				$response = curl_exec($curl);

				curl_close($curl);
				
				$result = json_decode($response, true);
				$idprogram_studi = $result[0]['ID_PROGRAM_STUDI'];

			}
			else{
				Session::flash('status', [
					'status' => 'danger',
					'message' => 'Join table tidak valid'
				]);
				return redirect()->back()->withInput();
				// Jika join table tidak valid, maka tampilkan error
				// return response()->json([
				// 	'code' => 400,
				// 	'status' => 'error',
				// 	'message' => 'Join table tidak valid'
				// ]);
			}

			

			$array_insert = array(
				'nama' => $request->nama,
				'nipnik' => $request->nipnik,
				'gelar_depan' => $request->gelar_depan,
				'gelar_belakang' => $request->gelar_belakang,
				'join_table' => $request->join_table,
				'id_pengguna_cyber' => $request->id_cyber,
				'created_at' => $ts,
				'status' => true,
				'idprogram_studi' => $idprogram_studi,
			);
			

			try {
				$iduser = DB::table('user')->insertGetId($array_insert, 'iduser');
			} catch (\Exception $e) {
				Session::flash('status', [
					'status' => 'danger',
					'message' => 'Terjadi kesalahan saat menambah user: ' . $e->getMessage()
				]);
				return redirect()->back()->withInput();
			}

		}

		$array_insert_role = array(
			'iduser' => $iduser,
			'idrole' => $request->role,
			'idunit_kerja' => $request->unit_kerja,
			'status' => true,
			'created_at' => $ts,
		);

		try {
			DB::table('role_user')->insert($array_insert_role);
		} catch (\Exception $e) {
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Terjadi kesalahan saat menambah role user: ' . $e->getMessage()
			]);
			return redirect()->back()->withInput();
		}

		


		Session::flash('status', [
			'status' => 'success',
			'message' => 'User berhasil ditambahkan'
		]);

		
		return redirect()->route('user_index');
	}

	// public function tambah_simpan (Request $request)
	// {
	// 	$validatedData = $request->validate([
	// 		'nipnik' => ['required', 'regex:/^[0-9]+$/'],
	// 		'nama' => ['required', 'string'],
	// 		'gelar_depan' => ['nullable', 'string'],
	// 		'gelar_belakang' => ['nullable', 'string'],
	// 		'join_table' => ['required', 'in:1,2'],
	// 		'status' => ['required', 'in:0,1'],
	// 		'role' => ['required', 'in:1,2'],
	// 		'idunit_kerja' => ['nullable', 'numeric'],
	// 	], [
	// 		'nipnik.required' => 'Nama wajib diisi.',
	// 		'nipnik.regex' => 'Nama hanya boleh berisi angka saja.',
	// 		'password.required' => 'Password wajib diisi.',
	// 		'password.min' => 'Password minimal 4 karakter.',
	// 		'password.max' => 'Password maksimal 255 karakter.',
	// 		'join_table.required' => 'Join Table wajib diisi.',
	// 		'join_table.in' => 'Status aktif harus 1 / 2.',
	// 		'status.required' => 'Status aktif wajib dipilih.',
	// 		'status.in' => 'Status aktif harus 0 atau 1.',
	// 		'role.required' => 'Role wajib diisi.',
	// 		'role.in' => 'Role harus 1 atau 2.',
	// 	] );

	// 	if ($validatedData === false)
	// 	{
	// 		return redirect()->back()
	// 			->withErrors($request->validator)
	// 			->withInput();
	// 	}
		
	// 	$user_baru = User::create([
	// 		'nama' => $request->nama,
	// 		'nipnik' => $request->nipnik,
	// 		'gelar_depan' => $request->gelar_depan,
	// 		'gelar_belakang' => $request->gelar_belakang,
	// 		'join_table' => $request->join_table,
	// 		'status' => $request->status,
	// 		'idunit_kerja' => ( ( empty($request->idunit_kerja) ) ? null : $request->idunit_kerja ),
	// 	]);
	// 	$role_user_baru = null;
	// 	if ( $user_baru )
	// 	{
	// 		$role_user_baru = DB::table('role_user')->insert([
	// 			'iduser' => $user_baru->iduser,
	// 			'idrole' => $request->role,
	// 			'status' => 1,
	// 		]);
	// 	}

	// 	if ( $user_baru and $role_user_baru )
	// 	{
	// 		Session::flash('status', [
	// 			'status' => 'success',
	// 			'message' => 'User berhasil ditambahkan'
	// 		]);
	// 	}
	// 	else
	// 	{
	// 		Session::flash('status', [
	// 			'status' => 'danger',
	// 			'message' => 'User gagal ditambahkan'
	// 		]);
	// 	}
	// 	return redirect()->route('user_index');
	// }

	public function hapus ($id)
	{
		$user = User::find($id);
		if ($user)
		{
			$user->delete();
			Session::flash('status', [
				'status' => 'success',
				'message' => 'User berhasil dihapus'
			]);
		}
		else
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'User tidak ditemukan'
			]);
		}

		return redirect()->route('user_index');
	}

	public function reset_password ($id)
	{
		$user = User::find($id);
		if ($user)
		{
			$user->password = Hash::make('12345678');
			$user->save();
			Session::flash('status', [
				'status' => 'success',
				'message' => 'Password user ('.$user->nipnik.') berhasil direset. Pass : 12345678'
			]);
		}
		else
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'User tidak ditemukan'
			]);
		}

		return redirect()->route('user_index');
	}

	public function edit($id)
	{
		$menu = 'master';
		$submenu = 'user';

		$iduser = Crypt::decrypt($id);

		$user = User::find($iduser);

		

		if(session('userdata')['idrole'] == 1){
			$unit_kerja = DB::table('aucc.unit_kerja')
							->select('id_unit_kerja as idunit_kerja', 'nm_unit_kerja as nama_unit_kerja', 'type_unit_kerja')
							->where('status_aktif', 1)
							->get();

			

			$role_user = DB::table('role_user as ru')
								->join('role as r', 'ru.idrole', '=', 'r.idrole')
								->join('aucc.unit_kerja as uk', 'ru.idunit_kerja', '=', 'uk.id_unit_kerja')
								->leftJoin('pj_ruang as pr', 'ru.iduser', '=', 'pr.iduser')
								->where('ru.iduser', $iduser)
								->select(
									'ru.idrole',
									'r.nama_role',
									'uk.id_unit_kerja as idunit_kerja',
									'uk.nm_unit_kerja as nama_unit_kerja',
									'ru.status',
									'ru.idrole_user',
									'ru.is_delete',
									DB::raw('COALESCE(COUNT(pr.idpj_ruang), 0) as jumlah_ruang')
								)
								->orderBy('ru.idrole', 'asc')
								->orderBy('uk.nm_unit_kerja', 'asc')
								->groupBy('ru.idrole', 'r.nama_role', 'uk.id_unit_kerja', 'uk.nm_unit_kerja', 'ru.status', 'ru.idrole_user', 'ru.is_delete')
								->get();
		} else {
			$unit_kerja = DB::table('aucc.unit_kerja')
							->select('id_unit_kerja as idunit_kerja', 'nm_unit_kerja as nama_unit_kerja', 'type_unit_kerja')
							->where('status_aktif', 1)
							->where('id_unit_kerja', session('userdata')['idunit_kerja'])
							->get();

			$role_user = DB::table('role_user as ru')
							->join('role as r', 'ru.idrole', '=', 'r.idrole')
							->join('aucc.unit_kerja as uk', 'ru.idunit_kerja', '=', 'uk.id_unit_kerja')
							->leftJoin('pj_ruang as pr', function($join) {
								$join->on('ru.iduser', '=', 'pr.iduser')
									 ->where('pr.status', '=', 1);
							})
							->where('ru.iduser', $iduser)
							->where('ru.idunit_kerja', session('userdata')['idunit_kerja'])
							->select(
								'ru.idrole',
								'r.nama_role',
								'uk.id_unit_kerja as idunit_kerja',
								'uk.nm_unit_kerja as nama_unit_kerja',
								'ru.status',
								'ru.idrole_user',
								'ru.is_delete',
								DB::raw('COALESCE(COUNT(pr.idpj_ruang), 0) as jumlah_ruang')
							)
							->orderBy('ru.idrole', 'asc')
							->orderBy('uk.nm_unit_kerja', 'asc')
							->groupBy('ru.idrole', 'r.nama_role', 'uk.id_unit_kerja', 'uk.nm_unit_kerja', 'ru.status', 'ru.idrole_user', 'ru.is_delete')
							->get();
		}

		$role = DB::table('role')
					->get();
							
		return view('user.edit', compact('menu','submenu','user', 'iduser', 'role_user', 'role', 'unit_kerja'));
	}

	public function tambahroleuser(Request $req){
		// dd($req->all());
		$validated = $req->validate([
            'iduser' => 'required|integer',
			'idrole' => 'required|integer',
			'idunitkerja' => 'required|integer',
        ]);

		$iduser = $validated['iduser'];
		$idrole = $validated['idrole'];
		$idunitkerja = $validated['idunitkerja'];


		$cek = DB::table('role_user')
					->where('iduser', $iduser)
					->where('idrole', $idrole)
					->where('idunit_kerja', $idunitkerja)
					->exists();

		if ($cek) {
			return response()->json([
				'code' => 400,
				'status' => 'error',
				'message' => 'Role user sudah ada'
			]);
		}

		$simantapService = new Simantap_service();
		$cekunit_kerja = $simantapService->cek_unit_kerja($idunitkerja);

		if($cekunit_kerja['code'] !== 200){
			return response()->json([
				'code' => 400,
				'status' => 'error',
				'message' => $cekunit_kerja['message']
			]);
		}		

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		DB::beginTransaction();
		try {
			DB::table('role_user')
				->where('iduser', $iduser)
				->update([
					'status' => 0,
				]);

			DB::table('role_user')
				->insert([
					'iduser' => $iduser,
					'idrole' => $idrole,
					'idunit_kerja' => $idunitkerja,
					'status' => 1,
					'created_at' => $ts,
				]);
			DB::commit();

			return response()->json([
				'code' => 200,
				'status' => 'success',
				'message' => 'Role user berhasil ditambahkan'
			]);
		} catch (\Exception $e) {
			DB::rollBack();
			return response()->json([
				'code' => 500,
				'status' => 'danger',
				'message' => 'Terjadi kesalahan saat menambah role user: ' . $e->getMessage()
			]);
		}

		
	}

	public function ubahstatusroleuser(Request $req){
		if($req->status == 1){
			DB::beginTransaction();
			try {
				DB::table('role_user')
					->where('iduser', $req->iduser)
					->update([
						'status' => 0,
					]);

				DB::table('role_user')
					->where('idrole_user', $req->idroleuser)
					->update([
						'status' => 1,
					]);
				DB::commit();

				return response()->json([
					'code' => 200,
					'status' => 'success',
					'message' => 'Status role user berhasil diubah'
				]);
			} catch (\Exception $e) {
				DB::rollBack();
				return response()->json([
					'code' => 500,
					'status' => 'danger',
					'message' => 'Terjadi kesalahan saat mengubah status role user: ' . $e->getMessage()
				]);
			}				
		}
		else{
			try {
				DB::table('role_user')
					->where('idrole_user', $req->idroleuser)
					->update([
						'status' => $req->status,
					]);
					
				$ret = [
					'code' => 200,
					'status' => 'success',
					'message' => 'Status role user berhasil diubah'
				];
			} catch (\Exception $e) {
				$ret = [
					'code' => 500,
					'status' => 'danger',
					'message' => 'Terjadi kesalahan saat mengubah status role user: ' . $e->getMessage()
				];
			}
			return response()->json($ret);
		}

		
	}

	public function ubahdeleteroleuser(Request $req){
		// dd($req->all());
		try {
			DB::table('role_user')
				->where('idrole_user', $req->idroleuser)
				->update([
					'is_delete' => $req->is_delete,
				]);
				
			$ret = [
				'code' => 200,
				'status' => 'success',
				'message' => 'Status delete role user berhasil diubah'
			];
		} catch (\Exception $e) {
			$ret = [
				'code' => 500,
				'status' => 'danger',
				'message' => 'Terjadi kesalahan saat mengubah status delete role user: ' . $e->getMessage()
			];
		}
		return response()->json($ret);
	}

	public function edit_simpan (Request $request, $id)
	{
		$iduser = Crypt::decrypt($id);
		// dd($request->all());

		try {
			DB::table('user')
				->where('iduser', $iduser)
				->update([
					'status' => $request->status,
				]);
		} catch (\Exception $e) {
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Terjadi kesalahan saat mengedit user: ' . $e->getMessage()
			]);
			return redirect()->back();
		}

		Session::flash('status', [
			'status' => 'success',
			'message' => 'Edit User berhasil'
		]);

		return redirect()->back();
	}

	public function getuser(Request $request)
	{
		$nipnik = $request->input('nipnik');

		// $cek = DB::table('user')
		// 	->where('nipnik', $nipnik)
		// 	->exists();

		$user = DB::table('aucc.pengguna')
					->where('username', $nipnik)
					->select('id_pengguna', 'username as nipnik', 'nm_pengguna', 'gelar_depan', 'gelar_belakang', 'join_table')
					->first();

		if (empty($user)) {
			$resp = [
				'code' => 404,
				'status' => 'error',
				'message' => 'User tidak ditemukan',
				'data' => null
			];
		} else {
			$resp = [
				'code' => 200,
				'status' => 'success',
				'message' => 'User ditemukan',
				'data' => $user
			];
		}

		

		return response()->json($resp);
	}

}
