<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

class ReportController extends Controller
{
    public function penggunaanalat(){
        $menu = 'report';
        $submenu = 'report_alat';

        if(session('userdata')['idrole'] == 1){
            $unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user GROUP BY idunit_kerja) as q1'))
                        ->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
                        ->select('q1.idunit_kerja', 'uk.nm_unit_kerja')
                        ->orderBy('uk.nm_unit_kerja', 'asc')
                        ->get();
        } else {
            $unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user where status = true GROUP BY idunit_kerja) as q1'))
                        ->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
                        ->select('q1.idunit_kerja', 'uk.nm_unit_kerja')
                        ->orderBy('uk.nm_unit_kerja', 'asc')
                        ->get();
        }

        return view('report.report_alat_index', compact('menu', 'submenu', 'unitkerja'));
    }

    public function report_penggunaanalat($id){
        $menu = 'report';
        $submenu = 'report_alat';

        $idunitkerja = Crypt::decrypt($id);

        if(!session('tanggal')){
            date_default_timezone_set('Asia/Jakarta');
            $tgl_akhir = date('Y-m-d');
            $tgl_awal = date('Y-m-d', strtotime('-1 month', strtotime($tgl_akhir)));
            
        }
        else{
            $tgl_awal = session('tanggal')['tgl_awal'];
            $tgl_akhir = session('tanggal')['tgl_akhir'];            
        }

        $awal = new \DateTime($tgl_awal);
        $akhir = new \DateTime($tgl_akhir);
        $diff = $awal->diff($akhir);

        $jumlah_hari = $diff->days;
        

        $unitkerja = DB::table('aucc.unit_kerja')
                        ->where('id_unit_kerja', $idunitkerja)
                        ->first();

        $sumary = DB::table('layanan as l')
                    ->join('permintaan_layanan as pl', 'l.idlayanan', '=', 'pl.idlayanan')
                    ->join('riwayat_pemakaian_aset as rpa', 'pl.idpermintaan_layanan', '=', 'rpa.idpermintaan_layanan')
                    ->join('aset as a', 'rpa.kode_barang_aset', '=', 'a.kode_barang_aset')
                    ->leftJoin('kapasitas_max as km', function($join) {
                        $join->on('a.kode_barang_aset', '=', 'km.kode_barang_aset')
                             ->where('km.status', true);
                    })
                    ->where('l.idunit_kerja', $idunitkerja)
                    ->where('rpa.timestamp_mulai', '>=', $tgl_awal)
                    ->where('rpa.timestamp_mulai', '<=', $tgl_akhir)
                    ->whereNotNull('rpa.timestamp_mulai')
                    ->whereNotNull('rpa.timestamp_akhir')
                    ->groupBy('a.kode_barang_aset', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset', 'kapasitas_max')
                    ->select(
                        'a.kode_barang_aset',
                        'a.nama_barang',
                        'a.merk_barang',
                        'a.tahun_aset',
                        'a.keterangan',
                        DB::raw('SUM(EXTRACT(EPOCH FROM rpa.timestamp_akhir - rpa.timestamp_mulai)) AS durasi_detik'),
                        DB::raw('(COALESCE(km.kapasitas_max * '.$jumlah_hari.', 1) ) AS kapasitas_max'),
                    )
                    ->get();
        
        // dd($sumary);
        
        return view('report.report_alat_unitkerja', compact('menu', 'submenu', 'sumary', 'idunitkerja', 'unitkerja', 'tgl_awal', 'tgl_akhir'));
    }

    public function set_tanggal(Request $req){

		$datehelp = explode(' - ', $req->rangetanggal);
		$tgl_awal = $datehelp[0];
		$tgl_akhir = $datehelp[1];

        $routename = $req->routename;

        $route_array = [];
        if(isset($req->idunitkerja) && !empty($req->idunitkerja)){
            $route_array['id'] = Crypt::encrypt($req->idunitkerja);
        } 

        if(isset($req->kode_barang) && !empty($req->kode_barang)){
            $route_array['kode_barang'] = Crypt::encrypt($req->kode_barang);
        }

        if(isset($req->iduser) && !empty($req->iduser)){
            $route_array['iduser'] = Crypt::encrypt($req->iduser);
        }

		// dd($tgl_awal, $tgl_akhir);

		session(['tanggal' =>[
				'tgl_awal' => $tgl_awal,
				'tgl_akhir' => $tgl_akhir
			]]);

		return redirect()->route($routename, $route_array);
		
	}

    public function report_penggunaanalat_detail($id, $kode_barang){
        $menu = 'report';
        $submenu = 'report_alat';

        $idunitkerja = Crypt::decrypt($id);
        $kode_barang_aset = Crypt::decrypt($kode_barang);

        if(!session('tanggal')){
            date_default_timezone_set('Asia/Jakarta');
            $tgl_akhir = date('Y-m-d');
            $tgl_awal = date('Y-m-d', strtotime('-1 month', strtotime($tgl_akhir)));
        }
        else{
            $tgl_awal = session('tanggal')['tgl_awal'];
            $tgl_akhir = session('tanggal')['tgl_akhir'];
        }

        $barang = DB::table('aset as a')
                        ->join('simba.ruang as r', 'a.idruang', '=', 'r.id')
                        ->join('simba.gedung as g', 'r.id_gedung', '=', 'g.id')
                        ->join('simba.kampus as k', 'g.id_kampus', '=', 'k.id')
                        ->where('a.kode_barang_aset', $kode_barang_aset)
                        ->select('a.kode_barang_aset', 'a.nama_barang', 'a.merk_barang', 'a.tahun_aset', 'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus', 'a.keterangan')
                        ->first();

        $det_pemakaian = DB::table('layanan as l')
                            ->join('permintaan_layanan as pl', 'l.idlayanan', '=', 'pl.idlayanan')
                            ->join('riwayat_pemakaian_aset as rpa', 'pl.idpermintaan_layanan', '=', 'rpa.idpermintaan_layanan')
                            ->join('aset as a', 'rpa.kode_barang_aset', '=', 'a.kode_barang_aset')
                            ->where('l.idunit_kerja', $idunitkerja)
                            ->where('rpa.timestamp_mulai', '>=', $tgl_awal)
                            ->where('rpa.timestamp_mulai', '<=', $tgl_akhir)
                            ->whereNotNull('rpa.timestamp_mulai')
                            ->whereNotNull('rpa.timestamp_akhir')
                            ->where('a.kode_barang_aset', $kode_barang_aset)
                            ->groupBy('pl.idpermintaan_layanan', 'pl.idlayanan_aplikasi_asal', 'l.nama_layanan', 'pl.ts_req_masuk_aplikasi_asal',
                                    'rpa.timestamp_mulai', 'rpa.timestamp_akhir')
                            ->select('pl.idpermintaan_layanan', 'pl.idlayanan_aplikasi_asal', 'l.nama_layanan', 'pl.ts_req_masuk_aplikasi_asal',
                                    'rpa.timestamp_mulai', 'rpa.timestamp_akhir',
                                    DB::raw('SUM(EXTRACT(EPOCH FROM rpa.timestamp_akhir - rpa.timestamp_mulai)) AS durasi_detik'))
                            ->orderBy('rpa.timestamp_mulai', 'desc')
                            ->get();

        // dd($barang);

        return view('report.report_alat_unitkerja_detail', compact('menu', 'submenu', 'det_pemakaian', 'idunitkerja', 'kode_barang_aset', 'barang','tgl_awal', 'tgl_akhir'));
    }

    public function operator(){
        $menu = 'report';
        $submenu = 'report_operator';

        if(session('userdata')['idrole'] == 1){
            $unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user GROUP BY idunit_kerja) as q1'))
                        ->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
                        ->select('q1.idunit_kerja', 'uk.nm_unit_kerja')
                        ->orderBy('uk.nm_unit_kerja', 'asc')
                        ->get();
        } else {
            $unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user where status = true GROUP BY idunit_kerja) as q1'))
                        ->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
                        ->select('q1.idunit_kerja', 'uk.nm_unit_kerja')
                        ->orderBy('uk.nm_unit_kerja', 'asc')
                        ->get();
        }

        return view('report.report_operator_index', compact('menu', 'submenu', 'unitkerja'));
    }

    public function report_operator($id){
        $menu = 'report';
        $submenu = 'report_operator';

        $idunitkerja = Crypt::decrypt($id);

        if(!session('tanggal')){
            date_default_timezone_set('Asia/Jakarta');
            $tgl_akhir = date('Y-m-d');
            $tgl_awal = date('Y-m-d', strtotime('-1 month', strtotime($tgl_akhir)));
        }
        else{
            $tgl_awal = session('tanggal')['tgl_awal'];
            $tgl_akhir = session('tanggal')['tgl_akhir'];
        }

        $unitkerja = DB::table('aucc.unit_kerja')
                        ->where('id_unit_kerja', $idunitkerja)
                        ->first();

        $sumary = DB::table('riwayat_pemakaian_aset as rpa')
                    ->join('permintaan_layanan as pl', 'rpa.idpermintaan_layanan', '=', 'pl.idpermintaan_layanan')
                    ->join('layanan as l', 'pl.idlayanan', '=', 'l.idlayanan')
                    ->join('user as u', 'pl.updated_by', '=', 'u.iduser')
                    ->where('l.idunit_kerja', $idunitkerja)
                    ->where('rpa.timestamp_mulai', '>=', $tgl_awal)
                    ->where('rpa.timestamp_mulai', '<=', $tgl_akhir)
                    ->whereNotNull('rpa.timestamp_mulai')
                    ->whereNotNull('rpa.timestamp_akhir')
                    ->where('pl.status', 3)
                    ->groupBy('u.nama', 'u.gelar_depan', 'u.gelar_belakang', 'u.iduser', 'u.nipnik')
                    ->select(
                        'u.nama', 'u.gelar_depan', 'u.gelar_belakang', 'u.iduser', 'u.nipnik',
                        DB::raw('COUNT(rpa.idriwayat_pemakaian_aset) AS total_pemakaian'),
                        DB::raw('SUM(EXTRACT(EPOCH FROM rpa.timestamp_akhir - rpa.timestamp_mulai)) AS durasi_detik')
                    )
                    ->get();

        // dd($sumary);

        return view('report.report_operator_unitkerja', compact('menu', 'submenu', 'sumary', 'idunitkerja', 'unitkerja', 'tgl_awal', 'tgl_akhir'));
    }

    public function operator_detail($id, $iduser){
        $menu = 'report';
        $submenu = 'report_operator';

        $idunitkerja = Crypt::decrypt($id);
        $iduser = Crypt::decrypt($iduser);

        if(!session('tanggal')){
            date_default_timezone_set('Asia/Jakarta');
            $tgl_akhir = date('Y-m-d');
            $tgl_awal = date('Y-m-d', strtotime('-1 month', strtotime($tgl_akhir)));
        }
        else{
            $tgl_awal = session('tanggal')['tgl_awal'];
            $tgl_akhir = session('tanggal')['tgl_akhir'];
        }

        $operator = DB::table('user')
                        ->where('iduser', $iduser)
                        ->select('iduser', 'nama', 'gelar_depan', 'gelar_belakang', 'nipnik')
                        ->first();

        $det_pemakaian = DB::table('riwayat_pemakaian_aset as rpa')
                            ->join('permintaan_layanan as pl', 'rpa.idpermintaan_layanan', '=', 'pl.idpermintaan_layanan')
                            ->join('layanan as l', 'pl.idlayanan', '=', 'l.idlayanan')
                            ->join('aset as a', 'rpa.kode_barang_aset', '=', 'a.kode_barang_aset')
                            ->where('l.idunit_kerja', $idunitkerja)
                            ->where('rpa.timestamp_mulai', '>=', $tgl_awal)
                            ->where('rpa.timestamp_mulai', '<=', $tgl_akhir)
                            ->whereNotNull('rpa.timestamp_mulai')
                            ->whereNotNull('rpa.timestamp_akhir')
                            ->where('pl.status', 3)
                            ->where('pl.updated_by', $iduser)
                            ->groupBy(
                                'pl.idpermintaan_layanan',
                                'pl.idlayanan_aplikasi_asal',
                                'a.kode_barang_aset',
                                'a.nama_barang',
                                'a.merk_barang',
                                'a.tahun_aset',
                                'l.nama_layanan',
                                'pl.ts_req_masuk_aplikasi_asal',
                                'rpa.timestamp_mulai',
                                'rpa.timestamp_akhir',
                                'a.keterangan'
                            )
                            ->select(
                                'pl.idpermintaan_layanan',
                                'pl.idlayanan_aplikasi_asal',
                                'a.kode_barang_aset',
                                'a.nama_barang',
                                'a.merk_barang',
                                'a.tahun_aset',
                                'l.nama_layanan',
                                'pl.ts_req_masuk_aplikasi_asal',
                                'rpa.timestamp_mulai',
                                'rpa.timestamp_akhir',
                                'pl.detail_layanan',
                                'a.keterangan',
                                DB::raw('SUM(EXTRACT(EPOCH FROM rpa.timestamp_akhir - rpa.timestamp_mulai)) AS durasi_detik')
                            )
                            ->orderBy('rpa.timestamp_mulai', 'desc')
                            ->get();
        // dd($det_pemakaian);
        return view('report.report_operator_unitkerja_detail', compact('menu', 'submenu', 'det_pemakaian', 'idunitkerja', 'iduser', 'operator', 'tgl_awal', 'tgl_akhir'));
    }
}