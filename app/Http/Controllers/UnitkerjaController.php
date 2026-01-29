<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\UnitKerja;
use Illuminate\Support\Facades\Session;

class UnitkerjaController extends Controller
{
	public function index()
	{
		$menu = 'home';
        $submenu = 'unitkerja';

		$unit_kerja = DB::table('unit_kerja_simantap as uks')
							->join('aucc.unit_kerja as uk', 'uks.idunit_kerja_simantap', '=', 'uk.id_unit_kerja')
							->select('uks.idunit_kerja_simantap', 'uk.nm_unit_kerja', 'uk.type_unit_kerja',  'uks.layanan', 'uks.penelitian', 'uks.praktikum')
							->get();

		return view('unit_kerja.index', compact('menu', 'submenu', 'unit_kerja') );
	}

	public function ubahstatus(Request $request){
		try {
			
			$idunit = $request->idunit;

			if($request->statusbaru == 1){
				$status_baru = 'true';
			} else {
				$status_baru = 'false';
			}

			DB::table('unit_kerja_simantap')
					->where('idunit_kerja_simantap', $idunit)
					->update([$request->jenis => $status_baru]);

			return response()->json([
					'code' => 200,
					'status' => 'success', 
					'message' => 'Status layanan berhasil diubah.'
				], 200);
			
		} catch (\Exception $e) {
			return response()->json([
					'code' => 500,
					'status' => 'error', 
					'message' => 'Gagal mengubah status layanan.'
				], 200);
		}
	}

	// public function tambah()
	// {
	// 	$fakultas = DB::table('fakultas')->select('idfakultas', 'nama_fakultas')->get();
	// 	$program_studi = DB::table('program_studi')->select('idprogram_studi', 'nama_program_studi')->get();
	// 	return view('unit_kerja.tambah', compact('fakultas', 'program_studi') );
	// }

	// public function tambah_simpan (Request $request)
	// {
	// 	$validatedData = $request->validate([
	// 		'nama_unit_kerja' => ['required', 'string'],
	// 		'type_unit_kerja' => ['required', 'string'],
	// 		'status' => ['required', 'in:0,1'],
	// 		'idfakultas' => ['nullable', 'numeric'],
	// 		'idprogram_studi' => ['nullable', 'numeric'],
	// 	], [
	// 		'nama_unit_kerja.required' => 'Nama wajib diisi.',
	// 		'type_unit_kerja.required' => 'Nama wajib diisi.',
	// 		'status.required' => 'Status aktif wajib dipilih.',
	// 		'status.in' => 'Status aktif harus 0 atau 1.',
	// 	] );

	// 	if ($validatedData === false)
	// 	{
	// 		return redirect()->back()
	// 			->withErrors($request->validator)
	// 			->withInput();
	// 	}
		
	// 	UnitKerja::create([
	// 		'nama_unit_kerja' => $request->nama_unit_kerja,
	// 		'type_unit_kerja' => $request->type_unit_kerja,
	// 		'status' => $request->status,
	// 		'idfakultas' => $request->idfakultas,
	// 		'idprogram_studi' => $request->idprogram_studi,
	// 	]);
	// 	Session::flash('status', [
	// 		'status' => 'success',
	// 		'message' => 'Unit berhasil ditambahkan'
	// 	]);

	// 	return redirect()->route('unit_kerja_index');
	// }

	// public function hapus ($id)
	// {
	// 	$Unit = UnitKerja::find($id);
	// 	if ($Unit)
	// 	{
	// 		$Unit->delete();
	// 		Session::flash('status', [
	// 			'status' => 'success',
	// 			'message' => 'Unit berhasil dihapus'
	// 		]);
	// 	}
	// 	else
	// 	{
	// 		Session::flash('status', [
	// 			'status' => 'danger',
	// 			'message' => 'Unit tidak ditemukan'
	// 		]);
	// 	}

	// 	return redirect()->route('unit_kerja_index');
	// }

	// public function edit ($id)
	// {
	// 	$unit_kerja = UnitKerja::find($id);
	// 	$fakultas = DB::table('fakultas')->select('idfakultas', 'nama_fakultas')->get();
	// 	$program_studi = DB::table('program_studi')->select('idprogram_studi', 'nama_program_studi')->get();
	// 	return view('unit_kerja.edit', compact('unit_kerja', 'id', 'fakultas', 'program_studi') );
	// }

	// public function edit_simpan (Request $request, $id)
	// {
	// 	$validatedData = $request->validate([
	// 		'nama_unit_kerja' => ['required', 'string'],
	// 		'type_unit_kerja' => ['required', 'string'],
	// 		'status' => ['required', 'in:0,1'],
	// 		'idfakultas' => ['nullable', 'numeric'],
	// 		'idprogram_studi' => ['nullable', 'numeric'],
	// 	], [
	// 		'nama_unit_kerja.required' => 'Nama wajib diisi.',
	// 		'type_unit_kerja.required' => 'Nama wajib diisi.',
	// 		'status.required' => 'Status aktif wajib dipilih.',
	// 		'status.in' => 'Status aktif harus 0 atau 1.',
	// 	] );

	// 	if ($validatedData === false)
	// 	{
	// 		return redirect()->back()
	// 			->withErrors($request->validator)
	// 			->withInput();
	// 	}
	// 	// cek apakah Unitname sudah ada, selain Unitname yang sedang diedit
	// 	$Unit_cek = UnitKerja::where('nama_unit_kerja', $request->nama_unit_kerja)
	// 		->where('idunit_kerja', '!=', $request->id)
	// 		->first();
	// 	if ($Unit_cek)
	// 	{
	// 		Session::flash('status', [
	// 			'status' => 'danger',
	// 			'message' => 'Nama Unit sudah ada'
	// 		]);
	// 		return redirect()->back()
	// 			->withErrors($request->validator)
	// 			->withInput();
	// 	}
	// 	// jika tidak ada error, simpan data
	// 	$Unit = UnitKerja::find($id);
	// 	if (!$Unit)
	// 	{
	// 		Session::flash('status', [
	// 			'status' => 'danger',
	// 			'message' => 'Unit tidak ditemukan'
	// 		]);
	// 		return redirect()->route('unit_kerja_index');
	// 	}
	// 	$Unit->nama_unit_kerja = $request->nama_unit_kerja;
	// 	$Unit->type_unit_kerja = $request->type_unit_kerja;
	// 	$Unit->status = $request->status;
	// 	$Unit->idfakultas = $request->idfakultas;
	// 	$Unit->idprogram_studi = $request->idprogram_studi;
	// 	$Unit->save();

	// 	Session::flash('status', [
	// 		'status' => 'success',
	// 		'message' => 'Edit Unit berhasil'
	// 	]);

	// 	return redirect()->route('unit_kerja_index');
	// }

}
