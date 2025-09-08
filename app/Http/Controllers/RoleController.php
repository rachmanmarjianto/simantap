<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class RoleController extends Controller
{
	protected $setting_folder_view, $setting_route_prefix, $setting_judul, $setting_nama_model;
	public function __construct()
	{
		$this->setting_folder_view = 'role';
		$this->setting_route_prefix = 'role_';
		$this->setting_judul = 'Role';
		$this->setting_nama_model = \App\Models\Role::class;
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

		return view($this->setting_folder_view.'.tambah', compact('sroute_prefix', 'sjudul') );
	}

	public function tambah_simpan (Request $request)
	{
		$validatedData = $request->validate([
			'nama_role' => ['required'],
		], [
			'nama_role.required' => 'Nama role wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}

		$model_cek = $this->setting_nama_model::where('nama_role', $request->nama_role)->first();
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
			'nama_role' => $request->nama_role,
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

		return view($this->setting_folder_view.'.edit', compact('sroute_prefix', 'sjudul', 'data', 'id') );
	}

	public function edit_simpan (Request $request, $id)
	{
		$validatedData = $request->validate([
			'nama_role' => ['required'],
		], [
			'nama_role.required' => 'Nama role wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		$model_cek = $this->setting_nama_model::where('nama_role', $request->nama_role)
			->where('idrole', '!=', $id)
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
		$model->nama_role = $request->nama_role;
		$model->save();

		Session::flash('status', [
			'status' => 'success',
			'message' => 'Edit '.$this->setting_judul.' berhasil'
		]);

		return redirect()->route($this->setting_route_prefix.'index');
	}

}
