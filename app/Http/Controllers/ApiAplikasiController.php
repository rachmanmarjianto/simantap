<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;

class ApiAplikasiController extends Controller
{
    public function index()
    {
        $menu = 'master';
        $submenu = 'api_aplikasi';

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

        return view('apiaplikasi.api_index', compact('menu', 'submenu', 'unitkerja'));
    }

    public function list_aplikasi($id){
        $menu = 'master';
        $submenu = 'api_aplikasi';

        $idunitkerja = Crypt::decrypt($id);

        $aplikasi = DB::table('aplikasi_uk as au')
                            ->where('au.idunit_kerja', $idunitkerja)
                            ->orderBy('au.nama_aplikasi', 'asc')
                            ->get();

        $unitkerja = DB::table('aucc.unit_kerja')
                            ->where('id_unit_kerja', $idunitkerja)
                            ->first();

        // dd($aplikasi);

        return view('apiaplikasi.api_set', compact('menu', 'submenu', 'idunitkerja', 'aplikasi', 'unitkerja'));
    }

    public function set_api_aplikasi($idaplikasi){
        $menu = 'master';
        $submenu = 'api_aplikasi';

        $idaplikasi_uk = Crypt::decrypt($idaplikasi);

        $aplikasi = DB::table('aplikasi_uk')
                            ->where('idaplikasi_uk', $idaplikasi_uk)
                            ->first();

        $api_aplikasi = DB::table('aplikasi_uk as au')
                            ->join('endpoint as e', 'au.idaplikasi_uk', '=', 'e.idaplikasi_uk')
                            ->join('jenis_endpoint as je', 'e.idjenis_endpoint', '=', 'je.idjenis_endpoint')
                            ->select('au.*', 'e.idendpoint', 'e.nama_endpoint', 'e.link', 'e.method', 
                                        'e.status as status_endpoint', 'je.nama_jenis_endpoint', 'e.idjenis_endpoint')
                            ->where('au.idaplikasi_uk', $idaplikasi_uk)
                            ->orderBy('e.idjenis_endpoint', 'asc')
                            ->orderBy('e.nama_endpoint', 'asc')
                            ->get();

        $jenis_endpoint = DB::table('jenis_endpoint')
                            ->select('idjenis_endpoint', 'nama_jenis_endpoint')
                            ->get();
                
        $idunitkerja = $aplikasi->idunit_kerja;

        // dd($api_aplikasi);
        

        return view('apiaplikasi.api_set_api', compact('menu', 'submenu', 'api_aplikasi', 'jenis_endpoint', 'idunitkerja', 'aplikasi'));
    }

    public function tambahaplikasi($id){
        $menu = 'master';
        $submenu = 'api_aplikasi';

        $idunitkerja = Crypt::decrypt($id);

        $unitkerja = DB::table('aucc.unit_kerja')
                            ->where('id_unit_kerja', $idunitkerja)
                            ->first();

        $aplikasi = DB::table('aplikasi_uk')
                            ->where('idunit_kerja', $idunitkerja)
                            ->where('status', 1)
                            ->get();

        // dd($aplikasi);

        if(count($aplikasi) > 0){
            return view('apiaplikasi.api_tambah_aplikasi_forbidden', compact('menu', 'submenu', 'idunitkerja', 'unitkerja'));
        } else {
            return view('apiaplikasi.api_tambah_aplikasi', compact('menu', 'submenu', 'idunitkerja', 'unitkerja'));
        }

        
    }

    public function set_status_aplikasi(Request $req){

        $idunitkerja = $req->idunitkerja;
        $idaplikasi_uk = $req->idaplikasi_uk;

        try {
            DB::table('aplikasi_uk')
            ->where('idunit_kerja', $idunitkerja)
            ->update(['status' => 0]); 

            DB::table('aplikasi_uk')
            ->where('idaplikasi_uk', $idaplikasi_uk)
            ->update(['status' => $req->status]); 

            
        } catch (\Exception $e) {
            Session::flash('status', [
				'status' => 'danger',
				'message' => 'error: ' . $e->getMessage()
			]);
        }

        return redirect()->route('api_aplikasi_list', ['id' => Crypt::encrypt($idunitkerja)]);
    }

    public function simpan_aplikasi(Request $req){
        // dd($req->all());

        $idunitkerja = $req->idunitkerja;
        $nama_aplikasi = $req->nama_aplikasi;
        $ipaddress = $req->ip_address;

        try {
            DB::table('aplikasi_uk')->insert([
                'idunit_kerja' => $idunitkerja,
                'nama_aplikasi' => $nama_aplikasi,
                'ipaddress' => $ipaddress,
                'status' => 1
            ]);
            Session::flash('status', [
                'status' => 'success',
                'message' => 'Aplikasi berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            Session::flash('status', [
                'status' => 'danger',
                'message' => 'error: ' . $e->getMessage()
            ]);
            
        }

        return redirect()->route('api_aplikasi_list', ['id' => Crypt::encrypt($idunitkerja)]);
    }

    public function simpan_tambah_endpoint(Request $req){
        // dd($req->all());

        $idaplikasi_uk = $req->idaplikasi;
        $nama_endpoint = $req->nama_endpoint;
        $link = $req->link;
        $method = $req->method;
        $idjenis_endpoint = $req->jenis_endpoint;

        $cek = DB::table('endpoint')
                    ->where('idaplikasi_uk', $idaplikasi_uk)
                    ->where('idjenis_endpoint', $idjenis_endpoint)
                    ->where('status', 1)
                    ->get();

        if(count($cek) > 0){
            Session::flash('status', [
                'status' => 'danger',
                'message' => 'Endpoint untuk jenis endpoint ini sudah ada yang aktif'
            ]);
            return redirect()->route('api_aplikasi_set', ['idaplikasi' => Crypt::encrypt($idaplikasi_uk)]);
        }

        

        try {
            DB::table('endpoint')->insert([
                'idaplikasi_uk' => $idaplikasi_uk,
                'nama_endpoint' => $nama_endpoint,
                'link' => $link,
                'method' => $method,
                'idjenis_endpoint' => $idjenis_endpoint,
                'status' => 1
            ]);
            Session::flash('status', [
                'status' => 'success',
                'message' => 'Endpoint berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            Session::flash('status', [
                'status' => 'danger',
                'message' => 'error: ' . $e->getMessage()
            ]);
            
        }

        return redirect()->route('api_aplikasi_set', ['idaplikasi' => Crypt::encrypt($idaplikasi_uk)]);
    }

    public function set_status_endpoint(Request $req){
        // dd($req->all());

        $idendpoint = $req->idendpoint;
        $status = $req->status;
        $idaplikasi_uk = $req->idaplikasi_uk;
        $idjenis_endpoint = $req->idjenis_endpoint;

        if($status == 1){
            $status = 'true';
        } else {
            $status = 'false';
        }

        
        DB::beginTransaction();
        try {
            DB::table('endpoint')
                ->where('idaplikasi_uk', $idaplikasi_uk)
                ->where('idjenis_endpoint', $idjenis_endpoint)
                ->update(['status' => 'false']);

            DB::table('endpoint')
                ->where('idendpoint', $idendpoint)
                ->update(['status' => $status]); 

            DB::commit();

            return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Status endpoint berhasil diubah'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
            'code' => 500,
            'status' => 'error',
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
        
    }
}