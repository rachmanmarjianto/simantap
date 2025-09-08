<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class PemakaianAsetController extends Controller
{
	protected $setting_folder_view, $setting_route_prefix, $setting_judul, $setting_nama_model;
	public function __construct()
	{
		$this->setting_folder_view = 'pemakaian-aset';
		$this->setting_route_prefix = 'pemakaian_aset_';
		$this->setting_judul = 'Pemakaian Aset';
		$this->setting_nama_model = \App\Models\RiwayatPemakaianAset::class;
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

		$user = \App\Models\User::all();
		$permintaan_layanan = \App\Models\PermintaanLayanan::all();
		$aset = \App\Models\Aset::all();

		return view($this->setting_folder_view.'.tambah', compact('sroute_prefix', 'sjudul', 'user', 'permintaan_layanan', 'aset') );
	}

	public function tambah_simpan (Request $request)
	{
		$validatedData = $request->validate([
			'timestamp_mulai' => ['required', 'date_format:Y-m-d H:i:s'],
			'dimulai_oleh' => ['required', 'numeric'],
			'timestamp_akhir' => ['required', 'date_format:Y-m-d H:i:s'],
			'diakhiri_oleh' => ['required', 'numeric'],
			'keterangan' => ['nullable', 'string'],
			'idpermintaan_layanan' => ['required', 'numeric'],
			'kode_barang_aset' => ['required', 'numeric'],
		], [
			'timestamp_mulai.required' => 'Waktu Mulai wajib diisi.',
			'dimulai_oleh.required' => 'User Mulai wajib diisi.',
			'timestamp_akhir.required' => 'Waktu Selesai wajib diisi.',
			'diakhiri_oleh.required' => 'User Selesai wajib diisi.',
			'idpermintaan_layanan.required' => 'Layanan wajib diisi.',
			'kode_barang_aset.required' => 'Barang wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}

		$model_cek = $this->setting_nama_model::where('kode_barang_aset', $request->kode_barang_aset)
			->where('timestamp_mulai', $request->timestamp_mulai)
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
		
		$this->setting_nama_model::create([
			'timestamp_mulai' => $request->timestamp_mulai,
			'dimulai_oleh' => $request->dimulai_oleh,
			'timestamp_akhir' => $request->timestamp_akhir,
			'diakhiri_oleh' => $request->diakhiri_oleh,
			'keterangan' => $request->keterangan,
			'idpermintaan_layanan' => $request->idpermintaan_layanan,
			'kode_barang_aset' => $request->kode_barang_aset,
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
		$user = \App\Models\User::all();
		$permintaan_layanan = \App\Models\PermintaanLayanan::all();
		$aset = \App\Models\Aset::all();

		return view($this->setting_folder_view.'.edit', compact('sroute_prefix', 'sjudul', 'data', 'id', 'user', 'permintaan_layanan', 'aset') );
	}

	public function edit_simpan (Request $request, $id)
	{
		$validatedData = $request->validate([
			'timestamp_mulai' => ['required', 'date_format:Y-m-d H:i:s'],
			'dimulai_oleh' => ['required', 'numeric'],
			'timestamp_akhir' => ['required', 'date_format:Y-m-d H:i:s'],
			'diakhiri_oleh' => ['required', 'numeric'],
			'keterangan' => ['nullable', 'string'],
			'idpermintaan_layanan' => ['required', 'numeric'],
			'kode_barang_aset' => ['required', 'numeric'],
		], [
			'timestamp_mulai.required' => 'Waktu Mulai wajib diisi.',
			'dimulai_oleh.required' => 'User Mulai wajib diisi.',
			'timestamp_akhir.required' => 'Waktu Selesai wajib diisi.',
			'diakhiri_oleh.required' => 'User Selesai wajib diisi.',
			'idpermintaan_layanan.required' => 'Layanan wajib diisi.',
			'kode_barang_aset.required' => 'Barang wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		$model_cek = $this->setting_nama_model::where('kode_barang_aset', $request->kode_barang_aset)
			->where('timestamp_mulai', $request->timestamp_mulai)
			->where('idriwayat_pemakaian_aset', '!=', $id)
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
		$model->timestamp_mulai = $request->timestamp_mulai;
		$model->dimulai_oleh = $request->dimulai_oleh;
		$model->timestamp_akhir = $request->timestamp_akhir;
		$model->diakhiri_oleh = $request->diakhiri_oleh;
		$model->keterangan = $request->keterangan;
		$model->idpermintaan_layanan = $request->idpermintaan_layanan;
		$model->kode_barang_aset = $request->kode_barang_aset;
		$model->save();

		Session::flash('status', [
			'status' => 'success',
			'message' => 'Edit '.$this->setting_judul.' berhasil'
		]);

		return redirect()->route($this->setting_route_prefix.'index');
	}

}
