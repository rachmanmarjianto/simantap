<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password; // Untuk aturan kekuatan password yang lebih modern
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
	public function index()
	{
		$menu = 'home';
		$submenu = '';

		try {
			$response = Http::get('https://zenquotes.io/api/today');
			$quote = $response->json()[0]['q'] ?? 'Stay inspired!';
			$author = $response->json()[0]['a'] ?? 'Anonymous';
		} catch (\Exception $e) {
			$response = null;
			$quote = 'Stay inspired!';
			$author = 'Anonymous';
		}
		

		// dd($quote, $author);

		$titel = [
			"Quote of the Day to make your day",
			"A quote of the day to brighten your day",
			"A daily Inspiration to keep you motivated",
			"Quote of the Day to inspire you",
			"A daily Quote to uplift your spirits",
			"Your quote of the day — for a better day ahead",
			"Quote of the Day to spark your creativity",
			"A daily dose of inspiration to fuel your passion",
			"Quote of the Day to ignite your enthusiasm",
			"A daily Quote to encourage your journey",
			"Quote of the Day to empower your dreams",
			"Here’s a quote of the day to inspire you"
		];

		$len = count($titel);
		$randIndex = rand(0, $len - 1);

		$today_quote = $titel[$randIndex];

		return view('home_index', compact('menu', 'submenu', 'quote', 'author', 'today_quote') );
	}

	public function ubah_password()
	{
		return view('ubah_password');
	}

	public function ubah_password_simpan( Request $request )
	{
		$validatedData = $request->validate([
			'pass_lama' => ['required'],
			'pass_baru_1' => ['required', 'string',
				Password::min(8)
				->mixedCase() // Harus mengandung huruf besar dan kecil
				->numbers() // Harus mengandung angka
				->symbols(), // Harus mengandung simbol
			],
			'pass_baru_2' => ['required', 'string', 'same:pass_baru_1'],
		], [
			'pass_lama.required' => 'Password Lama wajib diisi.',
			'pass_baru_1.required' => 'Password Baru wajib diisi.',
			'pass_baru_1.min' => 'Password baru minimal :min karakter.',
			'pass_baru_1.password.mixed' => 'Password baru harus mengandung huruf besar dan kecil.',
			'pass_baru_1.password.numbers' => 'Password baru harus mengandung angka.',
			'pass_baru_1.password.symbols' => 'Password baru harus mengandung simbol.',
			'pass_baru_2.required' => 'Password Baru (Ulangi) wajib diisi.',
			'pass_baru_2.same' => 'Konfirmasi password baru tidak cocok.', // Pesan khusus untuk 'same'
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		else
		{
			$user = User::find(Auth::user()->id);
			if ($user && Hash::check($request->pass_lama, $user->password)) // password lama sesuai
			{
				$user->password = Hash::make($request->pass_baru_1);
				$user->save();

				Session::flash('status', [
					'status' => 'success',
					'message' => 'Password berhasil diubah'
				]);
			}
			else
			{
				Session::flash('status', [
					'status' => 'danger',
					'message' => 'Password lama tidak sesuai'
				]);
			}

			return redirect()->route('ubah_password');
		}

	}

	public function ubah_role()
	{
		$menu = '';
		$submenu = '';
		// $kueri = "
		// 	select b.idrole, b.nama_role
		// 	from role_user a
		// 		join role b on a.idrole = b.idrole
		// 	where a.iduser = ?
		// ";
		// $role = DB::select($kueri, [Auth::user()->iduser]);

		$role_user = DB::table('role_user as ru')
							->join('role as r', 'ru.idrole', '=', 'r.idrole')
							->join('aucc.unit_kerja as uk', 'ru.idunit_kerja', '=', 'uk.id_unit_kerja')
							->where('ru.iduser', session('userdata')['iduser'])
							->select(
								'ru.idrole',
								'r.nama_role',
								'uk.id_unit_kerja as idunit_kerja',
								'uk.nm_unit_kerja as nama_unit_kerja',
								'ru.status',
								'ru.idrole_user'
							)
							->where('ru.is_delete', 0)
							->orderBy('ru.idrole', 'asc')
							->orderBy('uk.nm_unit_kerja', 'asc')
							->get();

		return view('ubah_role', compact('menu','submenu','role_user') );
	}

	public function ubah_role_simpan( Request $req )
	{
		// dd($request->all());

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
				

				Session::flash('status', [
					'status' => 'success',
					'message' => 'Role user berhasil diubah'					
				]);

				$pengguna = User::select('user.*', 'role_user.idrole', 'role.nama_role', 'uk.nm_unit_kerja', 'role_user.idunit_kerja', 'uks.layanan', 'uks.penelitian', 'uks.praktikum')
								->join('role_user', 'user.iduser', '=', 'role_user.iduser')
								->join('role', 'role_user.idrole', '=', 'role.idrole')
								->leftJoin('aucc.unit_kerja as uk', 'role_user.idunit_kerja', '=', 'uk.id_unit_kerja')
								->join('unit_kerja_simantap as uks', 'role_user.idunit_kerja', '=', 'uks.idunit_kerja_simantap')
								->where('user.nipnik', session('userdata')['nipnik'])
								->where('role_user.status', 1)
								->where('role_user.is_delete', 0)
								->first();

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



				DB::commit();

			} catch (\Exception $e) {
				DB::rollBack();

				Session::flash('status', [
					'status' => 'danger',
					'message' => 'Terjadi kesalahan saat mengubah role user: ' . $e->getMessage()					
				]);
			}	
			
			return redirect()->back();
		}
		else{
			try {
				DB::table('role_user')
					->where('idrole_user', $req->idroleuser)
					->update([
						'status' => $req->status,
					]);
					
				Session::flash('status', [
					'status' => 'success',
					'message' => 'Role user berhasil diubah'				
				]);

			} catch (\Exception $e) {

				Session::flash('status', [
					'status' => 'danger',
					'message' => 'Terjadi kesalahan saat mengubah role user: ' . $e->getMessage()				
				]);
			}
			return redirect()->back();
		}
		

	}

	public function halamanpublik()
	{
		$aset = DB::table('aset as a')
					->join('aucc.unit_kerja as uk', 'a.idunit_kerja', '=', 'uk.id_unit_kerja')
					->where('a.public', 1)
					->select(
						'a.kode_barang_aset',
						'a.nama_barang',
						'a.merk_barang',
						'a.keterangan',
						'uk.nm_unit_kerja as nama_unit_kerja',
						'a.kode_barang_aset',
					)
					->get();

		// dd($aset);

		// $max = count($aset_q);

		// $randomNumbers = array_rand(range(0, $max - 1), 4);

		// foreach ($randomNumbers as $index) {
		// 	$aset[] = $aset_q[$index];
		// }

		return view('home.halaman_publik', compact('aset'));
	}

}
