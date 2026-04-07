<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Services\SimantapService;

class LoginController extends Controller
{
	public function index()
	{
		return view('login');
	}

	public function masuk(Request $request){
		$validator = \Validator::make($request->all(), [
            'username' => 'required|numeric',
            'password' => 'required|string|max:255',
            'captcha' => 'required|captcha',
        ]);

		if ($validator->fails()) {
            // return redirect()->back()->withErrors($validator)->withInput();
            $errors = $validator->errors()->messages(); // Mendapatkan semua error dalam bentuk array
            $errorCodes = $validator->failed(); // Mendapatkan kode validasi yang gagal

            // dd($errors, $errorCodes);

            if(isset($errors['captcha'])){
                return redirect()->back()->with([
                        'status' => 'danger',
                        'message' => 'Captcha tidak sesuai. Silakan coba lagi.'
                    ]);
            }
            else{
                return redirect()->back()->with([
                        'status' => 'danger',
                        'message' => 'NIP/NIM atau Password salah'
                    ]);
            }
        }

		date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://apicybercampus.unair.ac.id/api/auth/login',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('LoginForm[username]' => $validator->validated()['username'],'LoginForm[password]' => $validator->validated()['password']),
			CURLOPT_HTTPHEADER => array(
				'Cookie: _csrf=26f508d094ffac1a03420541815ba6e835e252bb34be156d7d8f1ef8f1606851a%3A2%3A%7Bi%3A0%3Bs%3A5%3A%22_csrf%22%3Bi%3A1%3Bs%3A32%3A%22ZdzFGZeNiCJDeA_Wiln4ldOl3rwa39rT%22%3B%7D; uacc-session=fvgeqjors9i2pqte2ibh4vpjmm'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		
		$result = json_decode($response, true);

		if(isset($result['status']) && $result['status'] == 'success'){
			// Proses login berhasil
			$pengguna = User::select('user.*', 'role_user.idrole', 'role.nama_role', 'uk.nm_unit_kerja', 'role_user.idunit_kerja', 'uks.layanan', 'uks.penelitian', 'uks.praktikum')
				->join('role_user', 'user.iduser', '=', 'role_user.iduser')
				->join('role', 'role_user.idrole', '=', 'role.idrole')
				->join('aucc.unit_kerja as uk', 'role_user.idunit_kerja', '=', 'uk.id_unit_kerja')
				->join('unit_kerja_simantap as uks', 'role_user.idunit_kerja', '=', 'uks.idunit_kerja_simantap')
				->where('user.nipnik', $validator->validated()['username'])
				->where('role_user.status', 't')
				->where('role_user.is_delete', 0)
				->first();

			// dd($validator->validated(),$pengguna);
				
			if ($pengguna)
			{
				// Pengguna sudah ada di database lokal, langsung login
			}
			else
			{
				// return redirect()->back()->with([
				// 	'status' => 'danger',
				// 	'message' => 'Login gagal. Silakan coba lagi.'
				// ]);

				$user = DB::table('aucc.pengguna as p')
								->where('p.username', $validator->validated()['username'])
								->select('p.id_pengguna', 'p.username as nipnik', 'p.nm_pengguna', 'p.gelar_depan', 'p.gelar_belakang', 'p.join_table')
								->first();

				if($result['data']['join_table'] == 2 ){
					$idprogram_studi = $result['data']['dosen']['ID_PROGRAM_STUDI'];	
					$role = 5;				
				}
				else if($result['data']['join_table'] == 3){
					$idprogram_studi = $result['data']['mahasiswa']['ID_PROGRAM_STUDI'];
					$role = 6;
				}
				else{
					$idprogram_studi = null;

				}

				//=== cek unit kerja sudah ada atau blm ditabel simantap
				$cek_uk_simantap = DB::table('unit_kerja_simantap')->where('idunit_kerja_simantap', $result['data']['homebase_induk']['ID_UNIT_KERJA'])->first();
				if(!$cek_uk_simantap){
					DB::table('unit_kerja_simantap')->insert([
						'idunit_kerja_simantap' => $result['data']['homebase_induk']['ID_UNIT_KERJA'],
						'layanan' => 0,
						'penelitian' => 1,
						'praktikum' => 1,
					]);
				}

				$user_insert = array(
						'nipnik' => $validator->validated()['username'],
						'nama' => $result['data']['name'],
						'gelar_depan' => $result['data']['gelar_depan'] ?? '',
						'gelar_belakang' => $result['data']['gelar_belakang'] ?? '',
						'status'=>'true',
						'join_table' => $result['data']['join_table'],
						'created_at' => $ts,
						'id_pengguna_cyber' => $user->id_pengguna,
						'internal' => 'true',
						'idprogram_studi' => $idprogram_studi,
					);
				
				$simantapService = new SimantapService();
				$cekunit_kerja = $simantapService->cek_unit_kerja($idunitkerja);

				if($cekunit_kerja['code'] !== 200){
					return redirect()->back()->with([
						'status' => 'danger',
						'message' => $cekunit_kerja['message']
					]);
				}

				try {
					DB::beginTransaction();
					
					$iduser = DB::table('user')->insertGetId($user_insert, 'iduser');

					DB::table('role_user')->insert([
						'iduser' => $iduser,
						'idrole' => $role,
						'idunit_kerja' => $result['data']['homebase_induk']['ID_UNIT_KERJA'],
						'status' => 't',
						'is_delete' => 0,
						'created_at' => $ts
					]);
					
					DB::commit();
					
				} catch (\Exception $e) {
					DB::rollBack();
					return redirect()->back()->with([
						'status' => 'danger',
						'message' => 'Login gagal, DB Error. Silakan coba lagi.'
					]);
				}

				$pengguna = User::select('user.*', 'role_user.idrole', 'role.nama_role', 'uk.nm_unit_kerja', 'role_user.idunit_kerja', 'uks.layanan', 'uks.penelitian', 'uks.praktikum')
								->join('role_user', 'user.iduser', '=', 'role_user.iduser')
								->join('role', 'role_user.idrole', '=', 'role.idrole')
								->join('aucc.unit_kerja as uk', 'role_user.idunit_kerja', '=', 'uk.id_unit_kerja')
								->join('unit_kerja_simantap as uks', 'role_user.idunit_kerja', '=', 'uks.idunit_kerja_simantap')
								->where('user.nipnik', $validator->validated()['username'])
								->where('role_user.status', 't')
								->where('role_user.is_delete', 0)
								->first();	
			}

			

			Auth::login($pengguna);

			session([
				'userdata' => array(
					'iduser' => $pengguna->iduser,
					'nipnik' => $pengguna->nipnik,
					'nama' => $pengguna->nama,
					'gelar_depan' => $pengguna->gelar_depan,
					'gelar_belakang' => $pengguna->gelar_belakang,
					'idunit_kerja' => $pengguna->idunit_kerja,
					'idrole' => $pengguna->idrole,
					'nama_role' => $pengguna->nama_role,
					'nama_unit_kerja' => $pengguna->nm_unit_kerja,
					'layanan' => $pengguna->layanan,
					'penelitian' => $pengguna->penelitian,
					'praktikum' => $pengguna->praktikum,
					'idprogram_studi' => $pengguna->idprogram_studi,
				)
			]);

			return redirect()->intended('/home')->with('success', 'Login successful!');

		}
		else
		{
			return redirect()->back()->with([
				'status' => 'danger',
				'message' => 'Username/Password Salah'
			]);
		}
	}

	public function masuk_old(Request $request)
	{
		$validatedData = $request->validate([
			'username' => ['required', 'integer', 'min:0'],
			'password' => ['required', 'min:4', 'max:255'],
		]);

		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_URL => "https://apicybercampusgo.unair.ac.id/auth/login-username",
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => [
				'username' => $validatedData['username'],
				'password' => $validatedData['password'],
			],
			CURLOPT_RETURNTRANSFER => true,
		]);
		$response = curl_exec($curl);
		curl_close($curl);
		if ( empty($response) )
		{
			return redirect()->back()->with([
				'status' => 'danger',
				'message' => 'Username/Password Salah'
			]);
		}
		else
		{
			$result = json_decode($response, true);
			if ( isset($result['message']) and $result['message'] == 'Login berhasil!' )
			{
				
				// Proses login berhasil
				$pengguna = User::select('user.*', 'role_user.idrole', 'role.nama_role', 'uk.nm_unit_kerja', 'role_user.idunit_kerja', 'uks.layanan', 'uks.penelitian', 'uks.praktikum')
					->join('role_user', 'user.iduser', '=', 'role_user.iduser')
					->join('role', 'role_user.idrole', '=', 'role.idrole')
					->join('aucc.unit_kerja as uk', 'role_user.idunit_kerja', '=', 'uk.id_unit_kerja')
					->join('unit_kerja_simantap as uks', 'role_user.idunit_kerja', '=', 'uks.idunit_kerja_simantap')
					->where('user.nipnik', $validatedData['username'])
					->where('role_user.status', 't')
					->where('role_user.is_delete', 0)
					->first();
					
				if ($pengguna)
				{


					Auth::login($pengguna);

					// Set session untuk role pengguna
					session([
						'userdata' => array(
							'iduser' => $pengguna->iduser,
							'nipnik' => $pengguna->nipnik,
							'nama' => $pengguna->nama,
							'gelar_depan' => $pengguna->gelar_depan,
							'gelar_belakang' => $pengguna->gelar_belakang,
							'idunit_kerja' => $pengguna->idunit_kerja,
							'idrole' => $pengguna->idrole,
							'nama_role' => $pengguna->nama_role,
							'nama_unit_kerja' => $pengguna->nm_unit_kerja,
							'layanan' => $pengguna->layanan,
							'penelitian' => $pengguna->penelitian,
							'praktikum' => $pengguna->praktikum,
						)
					]);

					// dd(session()->get('userdata'));

					// return redirect()->route('home');
					return redirect()->intended('/home')->with('success', 'Login successful!');
				}
				else
				{
					dd($result);
				}
			}
			else
			{
				return redirect()->back()->with([
					'status' => 'danger',
					'message' => 'Username/Password Salah'
				]);
			}
		}
	}

  public function register()
	{
		return view('register');
	}

	public function login(Request $request)
	{
		// dd($request);
		// try {
		//     $request->validate([
		//         'captcha' => 'required|captcha'
		//     ]);
		// } catch (ValidationException $e) {
		//     return redirect()->back()->with(['status' => 'danger', 'message' => 'Captcha Invalid!']);
		// }

		// function ($attribute, $value, $fail) {
		//     if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
		//         return true; // Email valid
		//     } elseif (!preg_match('/^[A-Za-z0-9_-]+$/', $value)) {
		//         $fail('The ' . $attribute . ' must be a valid username with letters, numbers, dashes, and underscores.');
		//     }
		// }

		$validatedData = $request->validate([
			'username' => ['required', 'regex:/^[A-Za-z0-9_-]+$/'],
			'password_pengguna' => ['required', 'min:4', 'max:255'],
		]);
		// $password = Hash::make($validatedData['password']);

		// if (Auth::attempt($validatedData)) {
		$pengguna = User::where('username', $validatedData['username'])->where('password_hash', sha1($validatedData['password_pengguna']) )->first();

		if ($pengguna)
		{
			// $pengguna = User::where('username', $validatedData['username'])->first();
			// if ($pengguna && Hash::check($validatedData['password_pengguna'], $pengguna->password_pengguna)) {  

			// if ($pengguna instanceof Pengguna) {
				Auth::login($pengguna);
				// $user = Auth::user();  // Mengambil data pengguna yang sedang login
				// dd($user);
				// echo "sampe sini";
				// die;

				// $role = DB::table('AUCC.ROLE_PENGGUNA')->where('ID_PENGGUNA', auth()->user()->id_pengguna)->first();
				// $role = DB::table('AUCC.ROLE')->where('ID_ROLE', $role->id_role)->first();
				// $path = $role->path_v2 . '.index';
				// return redirect(route($path));

				// $role = DB::table('AUCC.ROLE_PENGGUNA')->where('ID_PENGGUNA', auth()->user()->id_pengguna)->first();
				$role = DB::table('AUCC.ROLE')->where('ID_ROLE', auth()->user()->id_role)->first();
				/*
				$role = DB::table('AUCC.ROLE')
						->whereIn('ID_ROLE', function ($query) {
							$query->select('ID_ROLE')
								->from('AUCC.ROLE_PENGGUNA')
								->where('ID_PENGGUNA', auth()->user()->id_pengguna);
						})
						->first();
				*/
				//dd($role);
				session(['user_role' => $role]);
				// $userRole = Session::get('user_role');
				// dd($userRole);
				return redirect()->route('index');
			// }            
		} else {
			echo "data tidak ada";
			die;

			$withMessage = [
				'status' => 'danger',
				'message' => 'Username/Password Salah'
			];

			return redirect()->back()->with($withMessage);
		}

		$withMessage = [
			'status' => 'danger',
			'message' => 'Penukaran tiket telah ditutup'
		];

		return redirect()->back()->with($withMessage);
	}

	public function logout()
	{
		//session()->forget('user_role');
		Auth::logout();

		return redirect(route('login'));
	}

	public function setRole($id_role)
	{
		$role = DB::table('AUCC.ROLE')->where('ID_ROLE', $id_role)->first();
		if ($role)
		{
			$has_role = DB::table('AUCC.ROLE_PENGGUNA')->where('ID_PENGGUNA', auth()->user()->id_pengguna)->where('ID_ROLE', $id_role)->exists();
			if ($has_role)
			{
				//pengguna memiliki role ini
				session(['user_role' => $role]);
				DB::table('AUCC.PENGGUNA')
					->where('ID_PENGGUNA', auth()->user()->id_pengguna)
					->update(['ID_ROLE' => $role->id_role]);
				return redirect('/dashboard');
			}
			else
			{
				return redirect()->back()->with('error', 'Anda tidak memiliki hak atas role ini.');
			}
		}
		else
		{
			return redirect()->back()->with('error', 'Role tidak ditemukan.');
		}
	}

	public function loginUnairsatu ($token)
	{
		//echo "coba"; die();
		if ( !empty($token) )
		{
			//dd($_REQUEST);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://unairsatu.unair.ac.id/token/cek_token_perantara");
			curl_setopt($ch, CURLOPT_POST, 1);
			$param = array('satu' => $token, 'dua' => 'cyberv1', 'tiga' => 'cyBerv1@4Ks3s', 'devel' => '56789tyuio');
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$isi_token = curl_exec($ch);
			curl_close($ch);

			if ( $isi_token == 'Expired' )
			{
				echo 'Expired'; die();
			}
			else
			{
				$tmp_3 = explode("&", $isi_token);
				$tmp_4 = explode("=", $tmp_3[1]);
				$tmp_5 = explode("=", $tmp_3[2]);
				$tmp_6 = explode("=", $tmp_3[3]);
				$tmp_nim = $tmp_4[1];
				$tmp_pass = $tmp_5[1];
				$token = $tmp_6[1];

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,"https://unairsatu.unair.ac.id/token/validasi_token_cak");
				curl_setopt($ch, CURLOPT_POST, 1);
				$param = array('satu' => $token, 'dua' => $tmp_nim, 'tiga' => $tmp_pass, 'devel' => '56789tyuio');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$hasil_cek_token = curl_exec($ch);
				curl_close($ch);

				if ( $hasil_cek_token == 'True' or $hasil_cek_token == '1' )
				{
					//echo 'Pass : '.$tmp_pass; die();
					$pengguna = User::where('username', $tmp_nim)->where('password_hash', $tmp_pass )->first();

					if ($pengguna)
					{
						$kueri = "update aucc.pengguna set id_role = 11 where id_pengguna = '".$pengguna->id_pengguna."'";
						DB::update($kueri);
						$pengguna = User::where('username', $tmp_nim)->where('password_hash', $tmp_pass )->first();
						Auth::login($pengguna);
						$role = DB::table('AUCC.ROLE')->where('ID_ROLE', auth()->user()->id_role)->first();
						session(['user_role' => $role]);
						return redirect()->route('index');
					}
					else
					{
						echo "data tidak ada"; die;
					}
				}
				else
				{
					echo 'Gagal'; die();
				}
			}
		}
		else
		{
			echo 'Token Kosong'; die();
		}
	}

	public function publik_login()
	{
		return view('home.login_publik');
	}

	public function publik_register()
	{
		return view('home.register_publik');
	}

}
