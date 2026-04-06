<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

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
                        ->where('q1.idunit_kerja', session('userdata')['idunit_kerja'])
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
                            ->leftJoin('auth_type as at', 'e.id_auth_type', '=', 'at.id_auth_type')
                            ->select('au.*', 'e.idendpoint', 'e.nama_endpoint', 'e.link', 'e.method', 
                                        'e.status as status_endpoint', 'je.nama_jenis_endpoint', 'e.idjenis_endpoint', 'at.nama_auth')
                            ->where('au.idaplikasi_uk', $idaplikasi_uk)
                            ->orderBy('e.idjenis_endpoint', 'asc')
                            ->orderBy('e.nama_endpoint', 'asc')
                            ->get();

        $jenis_endpoint = DB::table('jenis_endpoint')
                            ->select('idjenis_endpoint', 'nama_jenis_endpoint')
                            ->get();
                
        $idunitkerja = $aplikasi->idunit_kerja;

        $auth_type_q = DB::table('auth_type as at')
                        ->join('komponen_auth as ka', 'at.id_auth_type', '=', 'ka.idauth_type')
                        ->where('at.status', 1)
                        ->where('ka.status', 1)
                        ->get();

        // dd($auth_type_q);

        $auth_type = [];
        $auth_komponen = [];
        foreach ($auth_type_q as $item) {
            $auth_type[$item->id_auth_type] = $item->nama_auth;
            $auth_komponen[$item->id_auth_type][] = array(
                'nama_komponen_auth' => $item->nama_komponen_auth,
                'idkomponen_auth' => $item->idkomponen_auth
            );
        }
        

        return view('apiaplikasi.api_set_api', compact('menu', 'submenu', 'api_aplikasi', 'jenis_endpoint', 'idunitkerja', 'aplikasi', 'auth_type', 'auth_komponen'));
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

        // dd($auth_type);

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

        $validated = $req->validate([
            'idaplikasi' => 'required|integer',
            'nama_endpoint' => 'required|string|max:255',
            'link' => 'required|string|max:500',
            'method' => 'required|string|max:10',
            'jenis_endpoint' => 'required|integer',
            'auth_type' => 'required|integer'
        ]);

        $idaplikasi_uk = $validated['idaplikasi'];
        $nama_endpoint = $validated['nama_endpoint'];
        $link = $validated['link'];
        $method = $validated['method'];
        $idjenis_endpoint = $validated['jenis_endpoint'];
        $auth_type = $validated['auth_type'];
        $auth_header = 'false';

        if($auth_type != 0){
            $auth_header = 'true';
        }
        

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

        

        DB::beginTransaction();
        try {
            $idendpoint = DB::table('endpoint')->insertGetId([
                    'idaplikasi_uk' => $idaplikasi_uk,
                    'nama_endpoint' => $nama_endpoint,
                    'link' => $link,
                    'method' => $method,
                    'idjenis_endpoint' => $idjenis_endpoint,
                    'status' => 1,
                    'id_auth_type' => $auth_type,
                    'auth_header' => $auth_header
                    ], 'idendpoint');

            if($auth_type != 0){
                $arr_ins = array();

                foreach($req->komponen_auth as $key => $ka){
                    $arr_ins[] = array(
                    'idendpoint' => $idendpoint,
                    'idkomponen_auth' => $key,
                    'nilai' => $ka
                    );
                }

                DB::table('nilai_komponen_auth')->insert($arr_ins);
            }

            DB::commit();
            Session::flash('status', [
                'status' => 'success',
                'message' => 'Endpoint berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
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

    public function get_endpoint_detail(Request $req){
        $validated = $req->validate([
            'idendpoint' => 'required|integer'
        ]);

        $idendpoint = $validated['idendpoint'];

        $endpoint = DB::table('endpoint as e')
                    ->join('jenis_endpoint as je', 'e.idjenis_endpoint', '=', 'je.idjenis_endpoint')
                    ->leftJoin('auth_type as at', 'e.id_auth_type', '=', 'at.id_auth_type')
                    ->select('e.*', 'je.nama_jenis_endpoint', 'at.nama_auth')
                    ->where('e.idendpoint', $idendpoint)
                    ->first();

        if($endpoint){
            $komponen_auth = DB::table('nilai_komponen_auth as nka')
                            ->join('komponen_auth as ka', 'nka.idkomponen_auth', '=', 'ka.idkomponen_auth')
                            ->select('ka.nama_komponen_auth', 'nka.nilai', 'ka.idkomponen_auth')
                            ->where('nka.idendpoint', $idendpoint)
                            ->get();

            $endpoint->komponen_auth = $komponen_auth;
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Detail endpoint berhasil diambil',
            'data' => $endpoint
        ], 200);
    }

    public function edit_endpoint(Request $req){
        // dd($req->all());
        $validated = $req->validate([
            'idaplikasi' => 'required|integer',
            'nama_endpoint' => 'required|string|max:255',
            'link' => 'required|string|max:500',
            'method' => 'required|string|max:10',
            'jenis_endpoint' => 'required|integer',
            'auth_type' => 'required|integer',
            'idendpoint' => 'required|integer'
        ]);

        $idaplikasi_uk = $validated['idaplikasi'];
        $nama_endpoint = $validated['nama_endpoint'];
        $link = $validated['link'];
        $method = $validated['method'];
        $idjenis_endpoint = $validated['jenis_endpoint'];
        $auth_type = $validated['auth_type'];
        $auth_header = 'false';
        $idendpoint = $validated['idendpoint'];

        if($auth_type != 0){
            $auth_header = 'true';
        }

        $cek_authtype = DB::table('endpoint')
                    ->where('idendpoint', $idendpoint)
                    ->select('id_auth_type')
                    ->first();

        DB::beginTransaction();
        try {
            DB::table('endpoint')
                ->where('idendpoint', $idendpoint)
                ->update([
                    'nama_endpoint' => $nama_endpoint,
                    'link' => $link,
                    'method' => $method,
                    'idjenis_endpoint' => $idjenis_endpoint,
                    'id_auth_type' => $auth_type,
                    'auth_header' => $auth_header
                ]);

            if($cek_authtype->id_auth_type != $auth_type){
                DB::table('nilai_komponen_auth')
                    ->where('idendpoint', $idendpoint)
                    ->delete();

                if($auth_type != 0){
                    $arr_ins = array();

                    foreach($req->komponen_auth as $key => $ka){
                        $arr_ins[] = array(
                        'idendpoint' => $idendpoint,
                        'idkomponen_auth' => $key,
                        'nilai' => $ka
                        );
                    }

                    DB::table('nilai_komponen_auth')->insert($arr_ins);
                }
            } else {
                if($auth_type != 0){
                    foreach($req->komponen_auth as $key => $ka){
                        DB::table('nilai_komponen_auth')
                            ->where('idendpoint', $idendpoint)
                            ->where('idkomponen_auth', $key)
                            ->update(['nilai' => $ka]);
                    }
                }
            }

            DB::commit();
            return back()->with('status', [
                'status' => 'success',
                'message' => 'Endpoint berhasil diubah'
            ]);

        }
        catch (\Exception $e) {
            DB::rollBack();
            return back()->with('status', [
                'status' => 'danger',
                'message' => 'error: ' . $e->getMessage()
            ]);
        }


    }
}