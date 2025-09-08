<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class JenjangController extends Controller
{
	protected $setting_folder_view, $setting_route_prefix, $setting_judul, $setting_nama_model;
	public function __construct()
	{
		$this->setting_folder_view = 'jenjang';
		$this->setting_route_prefix = 'jenjang_';
		$this->setting_judul = 'Jenjang';
		$this->setting_nama_model = \App\Models\Jenjang::class;
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
			'nama_jenjang' => ['required', 'string'],
			'status' => ['required', 'in:0,1'],
		], [
			'nama_jenjang.required' => 'Nama Jenjang wajib diisi.',
			'status.required' => 'Status wajib diisi.',
			'status.in' => 'Status aktif harus 0 atau 1.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}

		$model_cek = $this->setting_nama_model::where('nama_jenjang', $request->nama_jenjang)->first();
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
			'nama_jenjang' => $request->nama_jenjang,
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

		return view($this->setting_folder_view.'.edit', compact('sroute_prefix', 'sjudul', 'data', 'id') );
	}

	public function edit_simpan (Request $request, $id)
	{
		$validatedData = $request->validate([
			'nama_jenjang' => ['required', 'string'],
			'status' => ['required', 'in:0,1'],
		], [
			'nama_jenjang.required' => 'Nama Jenjang wajib diisi.',
			'status.required' => 'Status wajib diisi.',
			'status.in' => 'Status aktif harus 0 atau 1.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		$model_cek = $this->setting_nama_model::where('nama_jenjang', $request->nama_jenjang)
			->where('idjenjang', '!=', $id)
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
		$model->nama_jenjang = $request->nama_jenjang;
		$model->status = $request->status;
		$model->save();

		Session::flash('status', [
			'status' => 'success',
			'message' => 'Edit '.$this->setting_judul.' berhasil'
		]);

		return redirect()->route($this->setting_route_prefix.'index');
	}

}
