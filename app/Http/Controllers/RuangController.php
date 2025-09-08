<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class RuangController extends Controller
{
	protected $setting_folder_view, $setting_route_prefix, $setting_judul, $setting_nama_model;
	public function __construct()
	{
		$this->setting_folder_view = 'ruang';
		$this->setting_route_prefix = 'ruang_';
		$this->setting_judul = 'Ruang';
		$this->setting_nama_model = \App\Models\Ruang::class;
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

		$unit_kerja = \App\Models\UnitKerja::all();

		return view($this->setting_folder_view.'.tambah', compact('sroute_prefix', 'sjudul', 'unit_kerja') );
	}

	public function tambah_simpan (Request $request)
	{
		$validatedData = $request->validate([
			'nama_ruang' => ['required', 'string'],
			'tipe_ruang' => ['required', 'string'],
			'nama_gedung' => ['required', 'string'],
			'nama_kampus' => ['required', 'string'],
			'idunit_kerja' => ['required', 'numeric'],
		], [
			'nama_ruang.required' => 'Nama Ruang wajib diisi.',
			'tipe_ruang.required' => 'Tipe Ruang wajib diisi.',
			'nama_gedung.required' => 'Nama Gedung wajib diisi.',
			'nama_kampus.required' => 'Nama Kampus wajib diisi.',
			'idunit_kerja.required' => 'Unit Kerja wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}

		$model_cek = $this->setting_nama_model::where('nama_ruang', $request->nama_ruang)->first();
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
			'nama_ruang' => $request->nama_ruang,
			'tipe_ruang' => $request->tipe_ruang,
			'nama_gedung' => $request->nama_gedung,
			'nama_kampus' => $request->nama_kampus,
			'idunit_kerja' => $request->idunit_kerja,
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
		$unit_kerja = \App\Models\UnitKerja::all();

		return view($this->setting_folder_view.'.edit', compact('sroute_prefix', 'sjudul', 'data', 'id', 'unit_kerja') );
	}

	public function edit_simpan (Request $request, $id)
	{
		$validatedData = $request->validate([
			'nama_ruang' => ['required', 'string'],
			'tipe_ruang' => ['required', 'string'],
			'nama_gedung' => ['required', 'string'],
			'nama_kampus' => ['required', 'string'],
			'idunit_kerja' => ['required', 'numeric'],
		], [
			'nama_ruang.required' => 'Nama Ruang wajib diisi.',
			'tipe_ruang.required' => 'Tipe Ruang wajib diisi.',
			'nama_gedung.required' => 'Nama Gedung wajib diisi.',
			'nama_kampus.required' => 'Nama Kampus wajib diisi.',
			'idunit_kerja.required' => 'Unit Kerja wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		$model_cek = $this->setting_nama_model::where('nama_ruang', $request->nama_ruang)
			->where('nama_gedung', $request->nama_gedung)
			->where('idruang', '!=', $id)
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
		$model->nama_ruang = $request->nama_ruang;
		$model->tipe_ruang = $request->tipe_ruang;
		$model->nama_gedung = $request->nama_gedung;
		$model->nama_kampus = $request->nama_kampus;
		$model->idunit_kerja = $request->idunit_kerja;
		$model->save();

		Session::flash('status', [
			'status' => 'success',
			'message' => 'Edit '.$this->setting_judul.' berhasil'
		]);

		return redirect()->route($this->setting_route_prefix.'index');
	}

}
