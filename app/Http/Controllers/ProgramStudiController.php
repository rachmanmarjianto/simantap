<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ProgramStudiController extends Controller
{
	protected $setting_folder_view, $setting_route_prefix, $setting_judul, $setting_nama_model;
	public function __construct()
	{
		$this->setting_folder_view = 'program-studi';
		$this->setting_route_prefix = 'program_studi_';
		$this->setting_judul = 'Program Studi';
		$this->setting_nama_model = \App\Models\ProgramStudi::class;
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

		$fakultas = \App\Models\Fakultas::all();
		$jenjang = \App\Models\Jenjang::all();

		return view($this->setting_folder_view.'.tambah', compact('sroute_prefix', 'sjudul', 'fakultas', 'jenjang') );
	}

	public function tambah_simpan (Request $request)
	{
		$validatedData = $request->validate([
			'nama_program_studi' => ['required', 'string'],
			'status' => ['required', 'in:0,1'],
			'idfakultas' => ['required', 'numeric'],
			'idjenjang' => ['required', 'numeric'],
		], [
			'nama_program_studi.required' => 'Nama Program Studi wajib diisi.',
			'status.required' => 'Status wajib diisi.',
			'status.in' => 'Status aktif harus 0 atau 1.',
			'idfakultas.required' => 'Fakultas wajib diisi.',
			'idjenjang.required' => 'Jenjang wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}

		$model_cek = $this->setting_nama_model::where('nama_program_studi', $request->nama_program_studi)->first();
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
			'nama_program_studi' => $request->nama_program_studi,
			'status' => $request->status,
			'idfakultas' => $request->idfakultas,
			'idjenjang' => $request->idjenjang,
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
		$fakultas = \App\Models\Fakultas::all();
		$jenjang = \App\Models\Jenjang::all();

		return view($this->setting_folder_view.'.edit', compact('sroute_prefix', 'sjudul', 'data', 'id', 'fakultas', 'jenjang') );
	}

	public function edit_simpan (Request $request, $id)
	{
		$validatedData = $request->validate([
			'nama_program_studi' => ['required', 'string'],
			'status' => ['required', 'in:0,1'],
			'idfakultas' => ['required', 'numeric'],
			'idjenjang' => ['required', 'numeric'],
		], [
			'nama_program_studi.required' => 'Nama Program Studi wajib diisi.',
			'status.required' => 'Status wajib diisi.',
			'status.in' => 'Status aktif harus 0 atau 1.',
			'idfakultas.required' => 'Fakultas wajib diisi.',
			'idjenjang.required' => 'Jenjang wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		$model_cek = $this->setting_nama_model::where('nama_program_studi', $request->nama_program_studi)
			->where('idprogram_studi', '!=', $id)
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
		$model->nama_program_studi = $request->nama_program_studi;
		$model->status = $request->status;
		$model->idfakultas = $request->idfakultas;
		$model->idjenjang = $request->idjenjang;
		$model->save();

		Session::flash('status', [
			'status' => 'success',
			'message' => 'Edit '.$this->setting_judul.' berhasil'
		]);

		return redirect()->route($this->setting_route_prefix.'index');
	}

}
