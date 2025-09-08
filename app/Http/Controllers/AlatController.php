<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Alat;
use Illuminate\Support\Facades\Session;

class AlatController extends Controller
{
	public function index()
	{
		$list_alat = Alat::all();
		return view('alat.index', compact('list_alat') );
	}

	public function tambah()
	{
		$unit = DB::table('unit')->select('id', 'nama_unit')->get();
		return view('alat.tambah', compact('unit') );
	}

	public function tambah_simpan (Request $request)
	{
		$validatedData = $request->validate([
			'kode' => ['required'],
			'nama' => ['required'],
			'unit_id' => ['required'],
			'is_aktif' => ['required', 'in:Y,T'],
		], [
			'kode.required' => 'Kode alat wajib diisi.',
			'nama.required' => 'Nama alat wajib diisi.',
			'unit_id.required' => 'Unit alat wajib diisi.',
			'is_aktif.required' => 'Status aktif wajib dipilih.',
			'is_aktif.in' => 'Status aktif harus Y atau T.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		Alat::create([
			'kode' => $request->kode,
			'nama' => $request->nama,
			'unit_id' => $request->unit_id,
			'lokasi_ruangan' => $request->lokasi_ruangan,
			'keterangan' => $request->keterangan,
			'is_aktif' => $request->is_aktif,
		]);
		Session::flash('status', [
			'status' => 'success',
			'message' => 'Alat berhasil ditambahkan'
		]);

		return redirect()->route('alat_index');
	}

	public function hapus ($id)
	{
		$Alat = Alat::find($id);
		if ($Alat)
		{
			$Alat->delete();
			Session::flash('status', [
				'status' => 'success',
				'message' => 'Alat berhasil dihapus'
			]);
		}
		else
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Alat tidak ditemukan'
			]);
		}

		return redirect()->route('alat_index');
	}

	public function edit ($id)
	{
		$alat = Alat::find($id);
		$unit = DB::table('unit')->select('id', 'nama_unit')->get();
		return view('alat.edit', compact('alat', 'id', 'unit') );
	}

	public function edit_simpan (Request $request, $id)
	{
		$validatedData = $request->validate([
			'kode' => ['required'],
			'nama' => ['required'],
			'unit_id' => ['required'],
			'is_aktif' => ['required', 'in:Y,T'],
		], [
			'kode.required' => 'Kode alat wajib diisi.',
			'nama.required' => 'Nama alat wajib diisi.',
			'unit_id.required' => 'Unit alat wajib diisi.',
			'is_aktif.required' => 'Status aktif wajib dipilih.',
			'is_aktif.in' => 'Status aktif harus Y atau T.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		$Alat_cek = Alat::where('kode', $request->kode)
			->where('id', '!=', $request->id)
			->first();
		if ($Alat_cek)
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Kode Alat sudah ada'
			]);
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		// jika tidak ada error, simpan data
		$Alat = Alat::find($id);
		if (!$Alat)
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Alat tidak ditemukan'
			]);
			return redirect()->route('Alat_index');
		}
		$Alat->kode = $request->kode;
		$Alat->nama = $request->nama;
		$Alat->unit_id = $request->unit_id;
		$Alat->lokasi_ruangan = $request->lokasi_ruangan;
		$Alat->keterangan = $request->keterangan;
		$Alat->is_aktif = $request->is_aktif;
		$Alat->save();

		Session::flash('status', [
			'status' => 'success',
			'message' => 'Edit Alat berhasil'
		]);

		return redirect()->route('alat_index');
	}

}
