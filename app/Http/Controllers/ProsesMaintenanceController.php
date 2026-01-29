<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class ProsesMaintenanceController extends Controller
{
    public function index(){
        $menu = 'proses_maintenance';
        $submenu = 'maintenance_aset';

        $ts = Carbon::now('Asia/Jakarta');
        $tnow = Carbon::parse($ts);

        $aset = DB::table('pj_maintenance as pm')
                ->join('aset as a', 'pm.kode_barang_aset', 'a.kode_barang_aset')
                ->join('simba.ruang as r', 'a.idruang', 'r.id')
                ->join('simba.gedung as g', 'r.id_gedung', 'g.id')
                ->join('simba.kampus as k', 'g.id_kampus', 'k.id')
                ->join('satuan_maintenance as sm', 'a.satuan_jarak_maintenance', 'sm.idsatuan_maintenance')
                ->leftJoin(DB::raw('(select kode_barang_aset, max(waktu_maintenance) as last_maintenance
                                                from maintenance_aset as ma
                                                where jenis_maintenance = \'2\'
                                                group by kode_barang_aset) as q1'), 'a.kode_barang_aset', '=', 'q1.kode_barang_aset')
                ->select('pm.idpj_maintenance', 'a.nama_barang', 'a.kode_barang_aset', 'a.merk_barang', 'a.tahun_aset', 'a.jarak_maintenance',
                        'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus', 'q1.last_maintenance', 'sm.satuan as satuan_maintenance')
                ->where('pm.status', 'true')
                ->where('pm.iduser', session('userdata')['iduser'])
                ->where('a.terjadwal_maintenance', 'true')
                ->orderBy('a.kode_barang_aset', 'asc')
                ->get();

        // dd($aset);

        $data_maintenance = [];
        $warning = [];
        foreach($aset as $item){

            if(!empty($item->last_maintenance)){

                $tlast = Carbon::parse($item->last_maintenance);
                $jarak_hari_dari_last_maintenance = $tlast->diffInDays($tnow);
                
                if($jarak_hari_dari_last_maintenance >= $item->jarak_maintenance){
                    $warning[$item->kode_barang_aset] = [
                        "warna" => "danger",
                        "pesan" => "Sudah Melewati Jarak Maintenance ".intval($jarak_hari_dari_last_maintenance)." Hari",
                        "maintenance_pernah" => true
                    ];
                }
                else{
                    $warning[$item->kode_barang_aset] = [
                        "warna" => "none",
                        "pesan" => "",
                        "maintenance_pernah" => true
                    ];
                }

            }
            else{
                $warning[$item->kode_barang_aset] = [
                    "warna" => "warning",
                    "pesan" => "Belum Pernah dilakukan maintenance",
                    "maintenance_pernah" => false
                ];
            }

            
        }

        return view('proses_maintenance.index', compact('menu', 'submenu', 'aset',  'warning'));
    }

    public function tarik_maintenance_aset(Request $req){
        $iduser = $req->iduser;
		$datehelp = explode(' - ', $req->rangetanggal);
		$tgl_awal = $datehelp[0];
		$tgl_akhir = $datehelp[1];

		session(['tanggal' => [
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir
        ]]);

        return redirect()->route('proses_maintenance_index');

	}

    public function tambah_maintenance($kodeaset){
        $menu = 'proses_maintenance';
        $submenu = 'maintenance_aset';

        $decrypted_kode_aset = Crypt::decrypt($kodeaset);

        $aset = DB::table('aset as a')
                ->join('simba.ruang as r', 'a.idruang', 'r.id')
                ->join('simba.gedung as g', 'r.id_gedung', 'g.id')
                ->join('simba.kampus as k', 'g.id_kampus', 'k.id')
                ->select('a.nama_barang', 'a.kode_barang_aset', 'a.merk_barang', 'a.tahun_aset', 
                        'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus')
                ->where('a.kode_barang_aset', $decrypted_kode_aset)
                ->first();
                

        return view('proses_maintenance.tambah_maintenance', compact('menu', 'submenu', 'aset'));
    }

    public function simpan_ts_maintenance(Request $req){
        
        date_default_timezone_set('Asia/Jakarta');
        $ts = date('Y-m-d H:i:s');
        $today = date('Y-m-d');

        $kodeaset = $req->kode_barang_aset;
        $waktu_maintenance = $req->timestamp;
        $tanggal_maintenance = date('Y-m-d', strtotime($waktu_maintenance));

        $maintenance = DB::table('maintenance_aset')
                        ->select('waktu_maintenance')
                        ->where('kode_barang_aset', $kodeaset)
                        ->orderBy('waktu_maintenance', 'desc')
                        ->first();

        if(empty($maintenance))
        {
            $selisih_hari = 0;
        }
        else{
            $selisih_hari = (strtotime($tanggal_maintenance) - strtotime($today)) / (60 * 60 * 24);
        }

        $data_insert = [
            'kode_barang_aset' => $kodeaset,
            'waktu_maintenance' => $waktu_maintenance,
            'catatan' => '',
            'ketepatan_jadwal_hari' => $selisih_hari,
            'created_by' => session('userdata')['iduser'],
            'created_at' => $ts
        ];

        $insert = DB::table('maintenance_aset')->insert($data_insert);
        if($insert){
            return response()->json([
                'code' => 200,
                'message' => 'Sukses menambahkan data maintenance aset',
                'data' => []
            ], 200);
        }
        else{
            return response()->json([
                'code' => 500,
                'message' => 'Gagal menambahkan data maintenance aset',
                'data' => []
            ], 200);
        }
    }

    public function get_riwayat_maintenance(Request $req){
        $kodeaset = $req->kode_aset;

        $riwayat = DB::table('maintenance_aset as ma')
                    ->join('aset as a', 'ma.kode_barang_aset', 'a.kode_barang_aset')
                    ->join('user as u', 'ma.created_by', 'u.iduser')
                    ->select('ma.idmaintenance_aset', 'ma.waktu_maintenance', 'ma.ketepatan_jadwal_hari', 
                            DB::raw('CASE WHEN ma.jenis_maintenance = \'1\' THEN \'Kalibrasi Internal\' 
                                          WHEN ma.jenis_maintenance = \'2\' THEN \'Kalibrasi Eksternal\' 
                                          WHEN ma.jenis_maintenance = \'3\' THEN \'Maintenance Internal\' 
                                          WHEN ma.jenis_maintenance = \'4\' THEN \'Maintenance Eksternal\'
                                          ELSE \'\' END AS jenis_maintenance'),
                            'ma.ketepatan_jadwal_hari', 'rekom_kondisi_aset', 'ma.status', 'u.nipnik', 'u.nama','u.gelar_depan', 'u.gelar_belakang',
                            'ma.created_at', 'a.nama_barang', 'a.merk_barang')
                    ->where('ma.kode_barang_aset', $kodeaset)
                    ->orderBy('ma.idmaintenance_aset', 'desc')
                    ->get();

        // dd($riwayat);
        $riwayat_arr = array();

        foreach($riwayat as $item){
            $riwayat_arr[] = [
                'idmaintenance_aset' => $item->idmaintenance_aset,
                'waktu_maintenance' => $item->waktu_maintenance,
                'ketepatan_jadwal_hari' => $item->ketepatan_jadwal_hari,
                'jenis_maintenance' => $item->jenis_maintenance,                
                'rekom_kondisi_aset' => $item->rekom_kondisi_aset,
                'status' => $item->status,
                'nipnik' => $item->nipnik,
                'nama' => $item->nama,
                'gelar_depan' => $item->gelar_depan,
                'gelar_belakang' => $item->gelar_belakang,
                'created_at' => $item->created_at,
                'nama_barang' => $item->nama_barang,
                'merk_barang' => $item->merk_barang,
                'idmaintenance_encrypted' => Crypt::encrypt($item->idmaintenance_aset)
            ];
        }
        
            

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $riwayat_arr
        ], 200);
    }

    public function get_form(Request $req){
        $jenis_maintenance = $req->jenis_maintenance;

        $form_options = DB::table('template_maintenance')
                        ->select('idtemplate_maintenance', 'nama_template')
                        ->where('jenis_maintenance', $jenis_maintenance)
                        ->where('status', 'true')
                        ->where('idunit_kerja', session('userdata')['idunit_kerja'])
                        ->orderBy('nama_template', 'asc')
                        ->get();

        $options_html = array();
        foreach($form_options as $option){
            $options_html[] = [
                'id' => $option->idtemplate_maintenance,
                'nama' =>$option->nama_template
            ];
        }

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $options_html
        ], 200);
    }

    public function simpan_mulai_proses(Request $req){
        date_default_timezone_set('Asia/Jakarta');
        $ts = date('Y-m-d H:i:s');

        $kodeaset = $req->kode_barang_aset;
        $idtemplate_maintenance = $req->form;
        $jenis_maintenance = $req->jenis_maintenance;

        $data_insert = [
            'kode_barang_aset' => $kodeaset,
            'status' => 1,
            'created_by' => session('userdata')['iduser'],
            'created_at' => $ts,
            'jenis_maintenance' => $jenis_maintenance,
        ];

        try {
            $idmaintenance_aset = DB::table('maintenance_aset')->insertGetId($data_insert, 'idmaintenance_aset');

            DB::table('isi_maintenanceaset')->insertUsing(
                ['nilai', 'idmaintenance_aset', 'idisi_template'], // kolom tujuan
                DB::table('isi_template')
                    ->select('nilai_default', DB::raw($idmaintenance_aset), 'idisi_template')
                    ->where('idtemplate_maintenance', $idtemplate_maintenance)
            );

            DB::table('log_status_maintenance_aset')->insert([
                'idmaintenance_aset' => $idmaintenance_aset,
                'status' => 1,
                'updated_by' => session('userdata')['iduser'],
                'timestamp' => $ts
            ]);
            
            $insert = true;
        } catch (\Exception $e) {
            $insert = false;
        }


        $idencrypted = Crypt::encrypt($idmaintenance_aset);                   


        if($insert){
            return redirect()->route('prosesmaintenance_edit_maintenance_aset', ['idmaintenance' => $idencrypted])->with('status', [
                'code' => 200,
                'status' => 'success',
                'message' => 'Sukses memulai proses maintenance aset',
            ]);
        }
        else{
            return back()->with('status', [
                'code' => 500,
				'status' => 'danger',
				'message' => 'Gagal memulai proses maintenance aset',
            ]);
        }
    }

    public function edit_maintenance_aset($idmaintenance){

        // dd(session('status')['status']);
        // dd($idmaintenance);

        $idmaintenance_aset = Crypt::decrypt($idmaintenance);

        // dd($idmaintenance_decrp);
        
        $menu = 'proses_maintenance';
        $submenu = 'maintenance_aset';

        $maintenance_aset = DB::table('maintenance_aset as ma')
                            ->join('aset as a', 'ma.kode_barang_aset', 'a.kode_barang_aset')
                            ->join('simba.ruang as r', 'a.idruang', 'r.id')
                            ->join('simba.gedung as g', 'r.id_gedung', 'g.id')
                            ->join('simba.kampus as k', 'g.id_kampus', 'k.id')
                            ->select('ma.idmaintenance_aset', 'ma.kode_barang_aset', 'ma.waktu_maintenance', 'ma.jenis_maintenance', 'ma.status',
                                    'a.nama_barang', 'a.merk_barang', 'a.tahun_aset',
                                    'r.nama_ruang', 'g.nama_gedung', 'k.nama_kampus')
                            ->where('idmaintenance_aset', $idmaintenance_aset)
                            ->first();

        $isi_template = DB::table('isi_maintenanceaset as ima')
                        ->join('isi_template as it', 'ima.idisi_template', 'it.idisi_template')
                        ->join('template_maintenance as tm', 'it.idtemplate_maintenance', 'tm.idtemplate_maintenance')
                        ->select('ima.idisi_maintenanceaset', 'ima.nilai', 'ima.idmaintenance_aset', 'it.idisi_template', 'tm.jenis_maintenance',
                                'it.jenis_isi', 'it.level', 'it.nilai_tampil', 'it.parent_id', 'it.urutan', 'it.idtemplate_maintenance', 'it.nilai_default')
                        ->where('ima.idmaintenance_aset', $idmaintenance_aset)
                        ->orderBy('it.urutan', 'asc')
                        ->get();

        $layout = array();

        $jenis_maintenance = 0;

        foreach($isi_template as $el){
            $jenis_maintenance = $el->jenis_maintenance;
            if($el->level == 1){
                if(!key_exists($el->idmaintenance_aset, $layout)){
                    $layout[$el->idisi_template] = array(
                        'idisi_template' => $el->idisi_template,
                        'jenis_isi' => $el->jenis_isi,
                        'level' => $el->level,
                        'nilai_tampil' => $el->nilai_tampil,
                        'urutan' => $el->urutan,
                        'parent_id' => $el->parent_id,
                        'nilai_default' => $el->nilai_default,
                        'idisi_maintenanceaset' => $el->idisi_maintenanceaset,
                        'nilai' => $el->nilai,
                        'jenis_maintenance' => $el->jenis_maintenance,
                        'children' => array()
                    );
                }
            }
            else{
                $layout[$el->parent_id]['children'][] = array(
                    'idisi_template' => $el->idisi_template,
                    'jenis_isi' => $el->jenis_isi,
                    'level' => $el->level,
                    'nilai_tampil' => $el->nilai_tampil,
                    'urutan' => $el->urutan,
                    'parent_id' => $el->parent_id,
                    'nilai_default' => $el->nilai_default,
                    'idisi_maintenanceaset' => $el->idisi_maintenanceaset,
                    'nilai' => $el->nilai
                );
            }            
        }

        $log_status = DB::table('log_status_maintenance_aset as lsma')
                        ->join('user as u', 'lsma.updated_by', 'u.iduser')
                        ->select('lsma.status', 'lsma.timestamp','u.nipnik', 'u.nama', 'u.gelar_depan', 'u.gelar_belakang')
                        ->where('lsma.idmaintenance_aset', $idmaintenance_aset)
                        ->orderBy('lsma.idlog_status_maintenance_aset', 'asc')
                        ->get();
        
        // dd(session('userdata'));

        // dd($log_status);

        if($maintenance_aset->status == 1){
            return view('proses_maintenance.form_edit_proses', compact('menu', 'submenu', 'layout', 'jenis_maintenance', 'maintenance_aset', 'log_status'));
        }
        else{
            return view('proses_maintenance.form_edit_view', compact('menu', 'submenu', 'layout', 'jenis_maintenance', 'maintenance_aset', 'log_status'));
        }

        
    }

    public function view_maintenance_aset($idmaintenance){
        // dd('view maintenance aset');
        dd($idmaintenance);

        $menu = 'proses_maintenance';
        $submenu = 'maintenance_aset';

        return view('proses_maintenance.view_maintenance_aset', compact('menu', 'submenu'));
    }

    public function get_form_template(Request $req){
        $idtemplate_maintenance = $req->idform;

        $form_template = DB::table('isi_template as it')
                        ->join('template_maintenance as tm', 'it.idtemplate_maintenance', 'tm.idtemplate_maintenance')
                        ->select('it.idisi_template', 'it.jenis_isi', 'it.level', 'it.nilai_tampil', 'it.parent_id', 'it.urutan', 'it.nilai_default', 
                                    'it.idtemplate_maintenance', 'tm.jenis_maintenance')
                        ->where('it.idtemplate_maintenance', $idtemplate_maintenance)
                        ->where('tm.status', 'true')
                        ->where('it.is_deleted', 'false')
                        ->orderBy('it.urutan', 'asc')
                        ->get();

        $layout = array();
        foreach($form_template as $el){
            if($el->level == 1){
                if(!key_exists($el->idisi_template, $layout)){
                    $layout[$el->idisi_template] = array(
                        'idisi_template' => $el->idisi_template,
                        'idtemplate_maintenance' => $el->idtemplate_maintenance,
                        'jenis_isi' => $el->jenis_isi,
                        'level' => $el->level,
                        'nilai_tampil' => $el->nilai_tampil,
                        'urutan' => $el->urutan,
                        'parent_id' => $el->parent_id,
                        'nilai_default' => $el->nilai_default,
                        'children' => array()
                    );
                }
            }
            else{
                $layout[$el->parent_id]['children'][] = array(
                    'idisi_template' => $el->idisi_template,
                    'idtemplate_maintenance' => $el->idtemplate_maintenance,
                    'jenis_isi' => $el->jenis_isi,
                    'level' => $el->level,
                    'nilai_tampil' => $el->nilai_tampil,
                    'urutan' => $el->urutan,
                    'parent_id' => $el->parent_id,
                    'nilai_default' => $el->nilai_default
                );
            }            
        }

        if($form_template[0]->jenis_maintenance == 1){
            $jenis_maintenance_text = 'Kalibrasi';
        }
        else{
            $jenis_maintenance_text = 'Maintenance';
        }

        $form_html = '<h2 style="text-align: center">DATA ' . strtoupper($jenis_maintenance_text) . '</h2>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Kode Barang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{ Terisi otomatis }" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Nama Barang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{ Terisi otomatis }" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Merk Barang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{ Terisi otomatis }" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tanggal ' . $jenis_maintenance_text . '</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{ Terisi otomatis }" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tempat ' . $jenis_maintenance_text . '</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{ Terisi otomatis }" readonly>
                                </div>
                        </div>';
        
        foreach($layout as $el){
            if($el['jenis_isi'] == 1){
                $form_html .= '<div class="form-group row">
                                            <div class="col-sm-12">
                                                <h5 style="font-weight: bold;">' . $el['nilai_tampil'] . '</h5>
                                            </div>
                                        </div>';

                if(count($el['children']) > 0){
                    foreach($el['children'] as $child){
                        if($child['jenis_isi'] == 2){
                            $form_html .= '<div class="form-group row">
                                                <label class="col-sm-2 col-form-label">' . $child['nilai_tampil'] . '</label>
                                                <div class="col-sm-10">
                                                    <input type="text" name="default[' . $child['idisi_template'] . ']" class="form-control" value="' . $child['nilai_default'] . '" readonly>
                                                </div>
                                            </div>';
                        }
                        else if($child['jenis_isi'] == 3){
                            $form_html .= '<div class="form-group row">
                                                <div class="col-sm-12">
                                                    <textarea  class="summernote" name="default[' . $child['idisi_template'] . ']" disabled> ' . $child['nilai_default'] . ' </textarea>
                                                </div>
                                            </div>';
                        }
                    }
                }
            }
            elseif($el['jenis_isi'] == 2){
                $form_html .= '<div class="form-group row">
                                    <label class="col-sm-2 col-form-label">' . $el['nilai_tampil'] . '</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="default[' . $el['idisi_template'] . ']" class="form-control" value="' . $el['nilai_default'] . '" readonly>
                                    </div>
                                </div>';
            }
            
        }

        $form_html .= '<div class="form-group row">
                            <label class="col-sm-2 col-form-label">Personil Pelaksana</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{ Terisi otomatis }" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Penanggung Jawab Ruang</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" value="{ Terisi otomatis }" readonly>
                            </div>
                        </div>';

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $form_html
        ], 200);
    }

    public function submit_form_proses_maintenance(Request $req){
        // dd($req->all());

        date_default_timezone_set('Asia/Jakarta');
        $ts = date('Y-m-d H:i:s');


        $inputs = array();
        foreach($req->default as $idisi_maintenance => $nilai){
            $inputs[] = [
                'idisi_maintenanceaset' => $idisi_maintenance,
                'nilai' => $nilai,
                'idmaintenance_aset' => $req->idmaintenance_aset,
                // 'idisi_template' => $idisi_template
            ];
        }

        $return = 0;

        try {
            DB::beginTransaction(); 

            DB::table('isi_maintenanceaset')->upsert(
                $inputs,
                ['idisi_maintenanceaset'],
                ['nilai']
            );

            DB::table('maintenance_aset')
                ->where('idmaintenance_aset', $req->idmaintenance_aset)
                ->update([
                    'status' => $req->status,
                    'created_by' => session('userdata')['iduser'],
                ]);

            DB::table('log_status_maintenance_aset')->insert([
                'idmaintenance_aset' => $req->idmaintenance_aset,
                'status' => $req->status,
                'updated_by' => session('userdata')['iduser'],
                'timestamp' => $ts
            ]);

            DB::commit();
        }
        catch (\Exception $e) {
            $return = 1;
            DB::rollBack();
        }

        if($return == 0){
            if($req->status == 1){
                return back()->with('status', [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Sukses menyimpan data proses maintenance aset'
                ]);
            }
            else if($req->status == 2){
                return redirect()->route('proses_maintenance_index')->with('status', [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Sukses mengajukan verifikasi proses maintenance aset'
                ]);
            }
            
        }
        else{
            return back()->with('status', [
                'code' => 500,
                'status' => 'danger',
                'message' => 'Gagal menyimpan data proses maintenance aset',
            ]);
        }
    }

    public function form_batal_ajuan(Request $req){
        date_default_timezone_set('Asia/Jakarta');
        $ts = date('Y-m-d H:i:s');

        $return = 0;

        try {
            DB::beginTransaction(); 

            DB::table('maintenance_aset')
                ->where('idmaintenance_aset', $req->idmaintenance_aset)
                ->update([
                    'status' => $req->status,
                    'created_by' => session('userdata')['iduser'],
                ]);

            DB::table('log_status_maintenance_aset')->insert([
                'idmaintenance_aset' => $req->idmaintenance_aset,
                'status' => $req->status,
                'updated_by' => session('userdata')['iduser'],
                'timestamp' => $ts
            ]);

            DB::commit();
        }
        catch (\Exception $e) {
            $return = 1;
            DB::rollBack();
        }

        if($return == 0){
            if($req->status == 1){
                return back()->with('status', [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Pembatalan ajuan sukses'
                ]);
            } 
            else if($req->status == 4){
                return redirect()->route('proses_maintenance_index')->with('status', [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Draft berhasil dibatalkan'
                ]);
            }           
        }
        else{
            return back()->with('status', [
                'code' => 500,
                'status' => 'danger',
                'message' => 'Gagal membatalkan ajuan',
            ]);
        }
    }
}