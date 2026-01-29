<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;


class PjRuangController extends Controller
{
	public function edit_ruang($idrole_user, $iduser)
	{
		$menu = 'master';
		$submenu = 'user';

        $idrole_user = Crypt::decrypt($idrole_user);
        $iduser = Crypt::decrypt($iduser);
        // dd($idrole_user_decrypt);

        $user = DB::table('user')->where('iduser', $iduser)->first();

        $role_user = DB::table('role_user as ru')
                        ->leftJoin('pj_ruang as pr', 'ru.iduser', '=', 'pr.iduser')
                        ->leftJoin('simba.ruang as r', 'pr.idruang', '=', 'r.id')
                        ->leftJoin('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
                        ->leftJoin('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
                        ->leftJoin('aucc.unit_kerja as uk', 'ru.idunit_kerja', '=', 'uk.id_unit_kerja')
                        ->where('ru.idrole_user', $idrole_user)
                        ->select(
                            'ru.idrole_user',
                            'ru.iduser',
                            'pr.idpj_ruang',
                            'r.nama_ruang',
                            'g.nama_gedung',
                            'k.nama_kampus',
                            'ru.idunit_kerja',
                            'pr.status',
                            'uk.nm_unit_kerja',
                        )
                        ->orderBy('r.nama_ruang', 'asc')
                        ->get();

        $ruang_list = DB::table('simba.ruang as r')
                        ->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
                        ->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
                        ->join('aset as a', 'r.id', '=', 'a.idruang')
                        ->where('r.id_unit_kerja', $role_user[0]->idunit_kerja)
                        ->select(
                            'r.id',
                            'r.nama_ruang',
                            'g.nama_gedung',
                            'k.nama_kampus'
                        )
                        ->groupBy('r.id', 'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus')
                        ->get();

        // dd($role_user, $ruang_list);

        return view('pjruang.edit_ruang', compact('menu', 'submenu', 'user', 'idrole_user', 'role_user', 'ruang_list', 'iduser'));
    }

    public function tambahruangpj(Request $request)
    {

        $request->validate([
            'iduser' => 'required|integer',
            'idruang' => 'required|integer',
        ]);

        // Set timezone to Jakarta
        $now = Carbon::now('Asia/Jakarta');
        $ts = $now->format('Y-m-d H:i:s');



        // Cek apakah sudah ada pj ruang dengan ruang yang sama
        $existing = DB::table('pj_ruang')
                        ->where('iduser', $request->iduser)
                        ->where('idruang', $request->idruang)
                        ->first();

        if ($existing) {
            return back()->with(['status'=>['status' => 'error', 'message' => 'PJ Ruang dengan ruang tersebut sudah ada.']]);
        }

        // Simpan data pj ruang baru
        DB::table('pj_ruang')->insert([
            'iduser' => $request->iduser,
            'idruang' => $request->idruang,
            'status' => 't', // Atur status default
            'created_at' => $ts,
            'created_by' => session('userdata')['iduser'],
        ]);

        return back()->with(['status'=>['status' => 'success', 'message' => 'PJ Ruang berhasil ditambahkan.']]);
    }

    public function ubahstatusruangpj(Request $request)
    {
        $request->validate([
            'idpj_ruang' => 'required|integer',
            'status' => 'required|in:0,1',
        ]);

        // Set timezone to Jakarta
        $now = Carbon::now('Asia/Jakarta');
        $ts = $now->format('Y-m-d H:i:s');

        // Update status pj ruang
        try {
            DB::table('pj_ruang')
            ->where('idpj_ruang', $request->idpj_ruang)
            ->update([
                'status' => $request->status == 1 ? 't' : 'f',
                'updated_at' => $ts,
                'updated_by' => session('userdata')['iduser'],
            ]);

            return response()->json(['code' => 200,
				'status' => 'success',
				'message' => 'Status Ruang berhasil diubah.'
            ],200);
        } catch (\Exception $e) {
            return response()->json(['code' => 500,
				'status' => 'error',
				'message' => 'Status Ruang gagal diubah.'
            ],200);
        }

        
    }

    public function ruanganpj()
    {
        $menu = 'master';
        $submenu = 'ruanganpj';

        $ruang = DB::table('pj_ruang as pr')
                    ->join('simba.ruang as r', 'pr.idruang', '=', 'r.id')
                    ->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
                    ->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
                    ->select(
                        'pr.idpj_ruang',
                        'r.nama_ruang',
                        'g.nama_gedung',
                        'k.nama_kampus',
                        'pr.status',
                        'pr.idruang'
                    )
                    ->where('r.id_unit_kerja', session('userdata')['idunit_kerja'])
                    ->where('pr.iduser', session('userdata')['iduser'])
                    ->where('pr.status', 't')
                    ->orderBy('r.nama_ruang', 'asc')
                    ->get();

        // dd($ruang);

        $layanan = DB::table('layanan as l')
                    ->join('layanan_aset as la', 'l.idlayanan', '=', 'la.idlayanan')
                    ->join('aset as a', 'la.kode_barang_aset', '=', 'a.kode_barang_aset')
                    // ->join('simba.ruang as r', 'a.idruang', '=', 'r.id')
                    ->join('operator_layanan as ol', 'l.idlayanan', '=', 'ol.idlayanan')
                    ->join('user as u', 'ol.iduser', '=', 'u.iduser')
                    ->join('pj_ruang as pr', 'a.idruang', '=', 'pr.idruang')
                    ->select('l.nama_layanan', 'l.idlayanan', 'a.idruang', 'u.nama', 'u.iduser', 'u.gelar_depan', 'u.gelar_belakang', 'ol.status as status_operator', 'ol.is_deleted as is_deleted_operator')
                    ->where('ol.status', 't')
                    ->where('ol.is_deleted', 'f')
                    ->where('pr.iduser', session('userdata')['iduser'])
                    ->where('pr.status', 't')
                    ->groupBy('l.nama_layanan', 'l.idlayanan', 'a.idruang', 'u.nama', 'u.iduser', 'u.gelar_depan', 'u.gelar_belakang', 'ol.status', 'ol.is_deleted')
                    ->get();

        // dd($ruang, $layanan);

        $aset =  DB::table('layanan as l')
                    ->join('layanan_aset as la', 'l.idlayanan', '=', 'la.idlayanan')
                    ->join('operator_layanan as ol', 'l.idlayanan', '=', 'ol.idlayanan')
                    ->join('aset as a', 'la.kode_barang_aset', '=', 'a.kode_barang_aset')
                    ->join('pj_ruang as pr', 'a.idruang', '=', 'pr.idruang')
                    ->where('pr.iduser', session('userdata')['iduser'])
                    ->where('la.is_deleted', false)
                    ->where('ol.status', 't')
                    ->where('ol.is_deleted', 'f')
                    ->select('l.idlayanan', 'a.kode_barang_aset', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset')
                    ->groupBy('l.idlayanan', 'a.kode_barang_aset', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset')
                    ->get();

        $layanan_ruang = [];
        foreach($ruang as $r){
            $layanan_ruang[$r->idruang] = [];
        }

        
        foreach ($layanan as $item) {

            if(!array_key_exists($item->idlayanan, $layanan_ruang[$item->idruang])){
                $layanan_ruang[$item->idruang][$item->idlayanan] = array(
                    'idlayanan' => $item->idlayanan,
                    'nama_layanan' => $item->nama_layanan,
                    'operator' => array()
                );
            }

            $layanan_ruang[$item->idruang][$item->idlayanan]['operator'][] = array(
                'iduser' => $item->iduser,
                'nama' => $item->nama,
                'gelar_depan' => $item->gelar_depan,
                'gelar_belakang' => $item->gelar_belakang,
            );
        }

        $aset_layanan = [];
        foreach ($aset as $item) {
            $aset_layanan[$item->idlayanan][] = [
                'idlayanan' => $item->idlayanan,
                'kode_barang_aset' => $item->kode_barang_aset,
                'nama_barang' => $item->nama_barang,
                'merk_barang' => $item->merk_barang,
                'tahun_aset' => $item->tahun_aset,
            ];
        }

        // dd($layanan_ruang, $aset_layanan);



        return view('pjruang.ruangpj', compact('menu', 'submenu', 'ruang', 'layanan_ruang', 'aset_layanan'));
    }
}