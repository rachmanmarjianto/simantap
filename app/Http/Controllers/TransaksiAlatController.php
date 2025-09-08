<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\TransaksiAlat;
use Illuminate\Support\Facades\Session;
use App\Models\Layanan;
use App\Models\Alat;

class TransaksiAlatController extends Controller
{
	public function index()
	{
		$list_transaksi_alat = TransaksiAlat::all();
		return view('transaksi_alat.index', compact('list_transaksi_alat') );
	}

	public function tambah()
	{
		$unit = DB::table('unit')->select('id', 'nama_unit')->get();
		$layanan = DB::table('layanan')->select('id', 'nama')->get();
		return view('transaksi_alat.tambah', compact('unit', 'layanan') );
	}

	public function tambah_simpan (Request $request)
	{
		$validatedData = $request->validate([
			'unit_id' => ['required', 'integer'],
			'alat_id' => ['required', 'integer'],
			'layanan_id' => ['required', 'integer'],
			'waktu_pakai_alat_mulai' => ['required'],
			'waktu_pakai_alat_selesai' => ['required'],
			'biaya_pakai_alat' => ['required'],
		], [
			'unit_id.required' => 'Unit wajib diisi.',
			'alat_id.required' => 'Alat wajib diisi.',
			'layanan_id.required' => 'Layanan wajib diisi.',
			'waktu_pakai_alat_mulai.required' => 'Waktu Mulai wajib diisi.',
			'waktu_pakai_alat_mulai.required' => 'Waktu Selesai wajib diisi.',
			'biaya_pakai_alat.required' => 'Biaya pakai wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		TransaksiAlat::create([
			'unit_id' => $request->unit_id,
			'alat_id' => $request->alat_id,
			'layanan_id' => $request->layanan_id,
			'waktu_pakai_alat_mulai' => $request->waktu_pakai_alat_mulai,
			'waktu_pakai_alat_selesai' => $request->waktu_pakai_alat_selesai,
			'biaya_pakai_alat' => $request->biaya_pakai_alat,
			'user_operator_alat_id' => Auth::user()->id,
		]);
		Session::flash('status', [
			'status' => 'success',
			'message' => 'Transaksi alat berhasil ditambahkan'
		]);

		return redirect()->route('transaksi_alat_index');
	}

	public function hapus ($id)
	{
		$TransaksiAlat = TransaksiAlat::find($id);
		if ($TransaksiAlat)
		{
			$TransaksiAlat->delete();
			Session::flash('status', [
				'status' => 'success',
				'message' => 'Transaksi alat berhasil dihapus'
			]);
		}
		else
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Transaksi alat tidak ditemukan'
			]);
		}

		return redirect()->route('transaksi_alat_index');
	}

	public function edit ($id)
	{
		$transaksi_alat = TransaksiAlat::find($id);
		$unit = DB::table('unit')->select('id', 'nama_unit')->get();
		$layanan = DB::table('layanan')->select('id', 'nama')->where('unit_id', $transaksi_alat->unit_id)->get();
		$alat = DB::table('alat')->select('id', 'nama')->where('unit_id', $transaksi_alat->unit_id)->get();
		return view('transaksi_alat.edit', compact('transaksi_alat', 'id', 'unit', 'layanan', 'alat') );
	}

	public function edit_simpan (Request $request, $id)
	{
		$validatedData = $request->validate([
			'unit_id' => ['required', 'integer'],
			'alat_id' => ['required', 'integer'],
			'layanan_id' => ['required', 'integer'],
			'waktu_pakai_alat_mulai' => ['required'],
			'waktu_pakai_alat_selesai' => ['required'],
			'biaya_pakai_alat' => ['required'],
		], [
			'unit_id.required' => 'Unit wajib diisi.',
			'alat_id.required' => 'Alat wajib diisi.',
			'layanan_id.required' => 'Layanan wajib diisi.',
			'waktu_pakai_alat_mulai.required' => 'Waktu Mulai wajib diisi.',
			'waktu_pakai_alat_mulai.required' => 'Waktu Selesai wajib diisi.',
			'biaya_pakai_alat.required' => 'Biaya pakai wajib diisi.',
		] );

		if ($validatedData === false)
		{
			return redirect()->back()
				->withErrors($request->validator)
				->withInput();
		}
		
		// jika tidak ada error, simpan data
		$TransaksiAlat = TransaksiAlat::find($id);
		if (!$TransaksiAlat)
		{
			Session::flash('status', [
				'status' => 'danger',
				'message' => 'Transaksi Alat tidak ditemukan'
			]);
			return redirect()->route('Alat_index');
		}
		$TransaksiAlat->unit_id = $request->unit_id;
		$TransaksiAlat->alat_id = $request->alat_id;
		$TransaksiAlat->layanan_id = $request->layanan_id;
		$TransaksiAlat->waktu_pakai_alat_mulai = $request->waktu_pakai_alat_mulai;
		$TransaksiAlat->waktu_pakai_alat_mulai = $request->waktu_pakai_alat_mulai;
		$TransaksiAlat->biaya_pakai_alat = $request->biaya_pakai_alat;
		$TransaksiAlat->save();

		Session::flash('status', [
			'status' => 'success',
			'message' => 'Edit Transaksi Alat berhasil'
		]);

		return redirect()->route('transaksi_alat_index');
	}

	public function getLayanan(Request $request)
	{
		$unit_id = $request->input('unit_id');
		$layanan = Layanan::where('unit_id', $unit_id)->get();
		return response()->json($layanan);
	}

	public function getAlat(Request $request)
	{
		$unit_id = $request->input('unit_id');
		$alat = Alat::where('unit_id', $unit_id)->get();
		return response()->json($alat);
	}

}
