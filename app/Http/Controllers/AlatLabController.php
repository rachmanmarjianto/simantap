<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AlatLabController extends Controller
{
    public function index() {
        $menu = 'alat_lab';
        $submenu = '';

        $alat = DB::table('aset as a')
                ->join('aucc.unit_kerja as uk', 'a.idunit_kerja', 'uk.id_unit_kerja')
                ->join('simba.ruang as r', 'a.idruang', 'r.id')
                ->join('simba.gedung as g', 'r.id_gedung', 'g.id')
                ->join('simba.kampus as k', 'g.id_kampus', 'k.id')
                ->select('a.kode_barang_aset', 'a.nama_barang', 'a.merk_barang', 'a.keterangan', 'a.kondisi_barang', 
                        'uk.nm_unit_kerja', 'uk.id_unit_kerja', 'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus')
                ->where('a.status', 'true')
                ->whereIn('a.kondisi_barang', [1,2])
                ->where('a.public', 'true')
                ->get();

        // dd($alat);

        return view('alat_lab.index', compact('menu', 'submenu', 'alat'));
    }
}
