<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\RoleUser;
use Illuminate\Support\Facades\Session;

class RoleUserController extends Controller
{
	protected $setting_folder_view, $setting_route_prefix, $setting_judul;
	public function __construct()
	{
		$this->setting_folder_view = 'role-user';
		$this->setting_route_prefix = 'role_user_';
		$this->setting_judul = 'Role User';
	}

	public function index()
	{
		$sroute_prefix = $this->setting_route_prefix;
		$sjudul = $this->setting_judul;

		$data = RoleUser::all();

		return view($this->setting_folder_view.'.index', compact('sroute_prefix', 'sjudul', 'data') );
	}

	public function tambah()
	{
		$sroute_prefix = $this->setting_route_prefix;
		$sjudul = $this->setting_judul;

		$user = DB::table('user')->select('iduser', 'nipnik', 'nama')->orderBy('nipnik')->get();
		$role = DB::table('role')->select('idrole', 'nama_role')->orderBy('nama_role')->get();

		return view($this->setting_folder_view.'.tambah', compact('sroute_prefix', 'sjudul', 'user', 'role') );
	}

	public function tambah_simpan (Request $request)
	{
		$validatedData = $request->validate([
			'iduser' => ['required', 'numeric'],
			'idrole' => ['required', 'numeric'],
			'status' => ['required', 'in:0,1'],
		], [
			'iduser.required' => 'User wajib diisi.',
			'idrole.required' => 'Role wajib diisi.',
			'status.required' => 'Status wajib diisi.',
			'status.in' => 'Status aktif harus 0 atau 1.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}

		$role_user_cek = RoleUser::where('iduser', $request->iduser)
			->where('idrole', $request->idrole)
			->first();
		if ($role_user_cek)
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Role User sudah ada'
			]);
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		if ( $request->status == '1' )
		{
			RoleUser::where('iduser', $request->iduser)->update(['status' => 0]);	
		}
		
		RoleUser::create([
			'iduser' => $request->iduser,
			'idrole' => $request->idrole,
			'status' => $request->status,
		]);
		Session::flash('status', [
			'status' => 'success',
			'message' => 'Role User berhasil ditambahkan'
		]);

		return redirect()->route($this->setting_route_prefix.'index');
	}

	public function hapus ($id)
	{
		$role = RoleUser::find($id);
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

		$user = DB::table('user')->select('iduser', 'nipnik', 'nama')->orderBy('nipnik')->get();
		$role = DB::table('role')->select('idrole', 'nama_role')->orderBy('nama_role')->get();
		$role_user = RoleUser::find($id);

		return view($this->setting_folder_view.'.edit', compact('sroute_prefix', 'sjudul', 'role_user', 'id', 'role', 'user') );
	}

	public function edit_simpan (Request $request, $id)
	{
		$validatedData = $request->validate([
			'iduser' => ['required', 'numeric'],
			'idrole' => ['required', 'numeric'],
			'status' => ['required', 'in:0,1'],
		], [
			'iduser.required' => 'User wajib diisi.',
			'idrole.required' => 'Role wajib diisi.',
			'status.required' => 'Status wajib diisi.',
			'status.in' => 'Status aktif harus 0 atau 1.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		$role_user_cek = RoleUser::where('iduser', $request->iduser)
			->where('idrole', $request->idrole)
			->where('idrole_user', '!=', $id)
			->first();
		if ($role_user_cek)
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
		$role_user = RoleUser::find($id);
		if (!$role_user)
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => $this->setting_judul.' tidak ditemukan'
			]);
			return redirect()->route('role_user_index');
		}

		if ( $request->status == '1' )
		{
			RoleUser::where('iduser', $request->iduser)->update(['status' => 0]);	
		}

		$role_user->iduser = $request->iduser;
		$role_user->idrole = $request->idrole;
		$role_user->status = $request->status;
		$role_user->save();

		Session::flash('status', [
			'status' => 'success',
			'message' => 'Edit '.$this->setting_judul.' berhasil'
		]);

		return redirect()->route($this->setting_route_prefix.'index');
	}

}
