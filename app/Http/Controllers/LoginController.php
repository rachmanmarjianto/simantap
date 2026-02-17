<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
	public function index()
	{
		return view('login');
	}

	public function masuk(Request $request)
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
					return redirect()->back()->with([
						'status' => 'danger',
						'message' => 'Pengguna tidak ditemukan'
					]);
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

}
