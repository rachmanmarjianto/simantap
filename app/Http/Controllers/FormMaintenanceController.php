<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class FormMaintenanceController extends Controller
{
    public function index()
    {
        $menu = 'master';
        $submenu = 'form_maintenance';

        if(session('userdata')['idrole'] == 1){
            $unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user GROUP BY idunit_kerja) as q1'))
                            ->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
                            ->leftJoin('template_maintenance as tm', 'q1.idunit_kerja', '=', 'tm.idunit_kerja')
                            ->select('q1.idunit_kerja', 'uk.nm_unit_kerja', 
                                        DB::raw('COALESCE(SUM(CASE WHEN tm.jenis_maintenance = \'1\' THEN 1 ELSE NULL END), 0) as jumlah_form_kalibrasi'),
                                        DB::raw('COALESCE(SUM(CASE WHEN tm.jenis_maintenance = \'2\' THEN 1 ELSE NULL END), 0) as jumlah_form_maintenance'),
                                        DB::raw('COALESCE(SUM(CASE WHEN tm.jenis_maintenance = \'3\' THEN 1 ELSE NULL END), 0) as jumlah_form_penelitian'))
                            ->where('tm.status', true)
                            ->groupBy('q1.idunit_kerja', 'uk.nm_unit_kerja')
                            ->orderBy('uk.nm_unit_kerja', 'asc')
                            ->get();
        } else {
            $unitkerja = DB::table(DB::raw('(SELECT idunit_kerja FROM role_user GROUP BY idunit_kerja) as q1'))
                            ->join('aucc.unit_kerja as uk', 'q1.idunit_kerja', '=', 'uk.id_unit_kerja')
                            ->leftJoin('template_maintenance as tm', 'q1.idunit_kerja', '=', 'tm.idunit_kerja')
                            ->select('q1.idunit_kerja', 'uk.nm_unit_kerja', 
                                        DB::raw('COALESCE(SUM(CASE WHEN tm.jenis_maintenance = \'1\' THEN 1 ELSE NULL END), 0) as jumlah_form_kalibrasi'),
                                        DB::raw('COALESCE(SUM(CASE WHEN tm.jenis_maintenance = \'2\' THEN 1 ELSE NULL END), 0) as jumlah_form_maintenance'),
                                        DB::raw('COALESCE(SUM(CASE WHEN tm.jenis_maintenance = \'3\' THEN 1 ELSE NULL END), 0) as jumlah_form_penelitian'))
                            ->where('q1.idunit_kerja', session('userdata')['idunit_kerja'])
                            ->where('tm.status', true)
                            ->groupBy('q1.idunit_kerja', 'uk.nm_unit_kerja')
                            ->orderBy('uk.nm_unit_kerja', 'asc')
                            ->get();
        }

        // dd($unitkerja);

        return view('form_maintenance.index', compact('menu', 'submenu', 'unitkerja'));
    }

    public function ubahstatus_template(Request $request)
    {

        // dd($request->all());
        $idtemplate_maintenance = $request->input('idtemplate_maintenance');
        $status_baru = $request->input('status');

        if($status_baru == 1){
            $status_baru = true;
        } else {
            $status_baru = false;
        }

        // dd($idtemplate_maintenance, $status_baru);

        try {
            DB::table('template_maintenance')
            ->where('idtemplate_maintenance', $idtemplate_maintenance)
            ->update([
                'status' => $status_baru
            ]);
        } catch (\Exception $e) {
            return response()->json([
				'code' => 500,
				'status' => 'error',
				'message' => 'Gagal ubah status'
			], 200);
        }

        return response()->json([
				'code' => 200,
				'status' => 'sukses',
				'message' => 'status berhasil diubah'
			], 200);
    }

    public function detail_unit_kerja($id)
    {
        $menu = 'master';
        $submenu = 'form_maintenance';

        $idunit_kerja = decrypt($id);

        $unitkerja = DB::table('aucc.unit_kerja as uk')
                        ->leftJoin('template_maintenance as tm', 'uk.id_unit_kerja', '=', 'tm.idunit_kerja')
                        ->select('uk.nm_unit_kerja', 'uk.id_unit_kerja', 'tm.nama_template', 'tm.jenis_maintenance', 'tm.status', 'tm.idtemplate_maintenance')
                        ->where('uk.id_unit_kerja', $idunit_kerja)
                        ->get();

        // dd($unitkerja);

        return view('form_maintenance.form_unitkerja', compact('menu', 'submenu', 'idunit_kerja', 'unitkerja'));
    }

    public function buat_formbaru($idunit_kerja)
    {
        $menu = 'master';
        $submenu = 'form_maintenance';

        $idunit_kerja = decrypt($idunit_kerja);

        $unitkerja = DB::table('aucc.unit_kerja')
                        ->where('id_unit_kerja', $idunit_kerja)
                        ->first();

        return view('form_maintenance.form_baru', compact('menu', 'submenu', 'idunit_kerja', 'unitkerja'));
    }

    public function simpan_form_baru(Request $request)
    {
        // dd($request->all());
        // dd(session('userdata'));

        $idunit_kerja = $request->input('idunit_kerja');
        $nama_template = $request->input('nama_template');
        $jenis_maintenance = $request->input('jenis_maintenance');

        $now = Carbon::now('Asia/Jakarta');
        $ts = $now->format('Y-m-d H:i:s');

        $id = DB::table('template_maintenance')->insertGetId([
            'idunit_kerja' => $idunit_kerja,
            'nama_template' => $nama_template,
            'jenis_maintenance' => $jenis_maintenance,
            'status' => '1',
            'created_at' => $ts,
            'created_by' => session('userdata')['iduser']
        ], 'idtemplate_maintenance');



        return redirect()->route('form_maintenance_edit_form', ['idform' => encrypt($id)]);
    }

    public function edit_form($idform)
    {
        $menu = 'master';
        $submenu = 'form_maintenance';

        $idform = decrypt($idform);

        // dd($idform);

        $template = DB::table('template_maintenance as tm')
                        ->select('tm.idtemplate_maintenance', 'tm.idunit_kerja', 'tm.nama_template', 'tm.jenis_maintenance', 'tm.status')
                        ->where('tm.idtemplate_maintenance', $idform)
                        ->get();

        if($template[0]->idunit_kerja != session('userdata')['idunit_kerja']){
            echo "403 Forbidden";
            exit;
        }

        if($template[0]->jenis_maintenance == 3){
            return redirect()->route('form_maintenance_edit_form_penelitian', ['idform' => encrypt($idform)]);
        }

        $isitemplate = DB::table('isi_template')
                            ->where('idtemplate_maintenance', $idform)
                            ->where('is_deleted', false)
                            ->orderBy('level', 'asc')
                            ->orderBy('urutan', 'asc')
                            ->get();

        // dd($isitemplate);
        $level_max = 0;

        $layout = array();

        foreach($isitemplate as $el){
            if($el->level > $level_max){
                $level_max = $el->level;
            }

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

        $level_max += 1;

        // dd($layout); 
        

        return view('form_maintenance.form_edit', compact('menu', 'submenu', 'template', 'isitemplate', 'level_max', 'layout'));
    }

    public function edit_form_penelitian($idform)
    {
        $menu = 'master';
        $submenu = 'form_maintenance';

        $idform = decrypt($idform);

        $template = DB::table('template_maintenance as tm')
                        ->select('tm.idtemplate_maintenance', 'tm.idunit_kerja', 'tm.nama_template', 'tm.jenis_maintenance', 'tm.status')
                        ->where('tm.idtemplate_maintenance', $idform)
                        ->get();

        if($template[0]->idunit_kerja != session('userdata')['idunit_kerja']){
            echo "403 Forbidden";
            exit;
        }

        $isitemplate = DB::table('isi_template')
                            ->where('idtemplate_maintenance', $idform)
                            ->where('is_deleted', false)
                            ->orderBy('level', 'asc')
                            ->orderBy('urutan', 'asc')
                            ->get();

        // dd($isitemplate);
        $level_max = 0;

        $layout = array();

        foreach($isitemplate as $el){
            if($el->level > $level_max){
                $level_max = $el->level;
            }

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

        $level_max += 1;

        $syarat_penelitian = DB::table('syarat_ajuan_penelitian')
                                ->where('idtemplate_maintenance', $idform)
                                ->orderBy('idsyarat_ajuan_penelitian', 'asc')
                                ->get();

        // dd($template); 
        

        return view('form_maintenance.form_edit_penelitian', compact('menu', 'submenu', 'template', 'isitemplate', 'level_max', 'layout', 'syarat_penelitian'));
    }

    public function edit_nama_template(Request $request)
    {
        // dd($request->all());

        $idtemplate_maintenance = $request->input('idtemplate_maintenance');
        $nama_template_baru = $request->input('nama_template');

        try {
            DB::table('template_maintenance')
            ->where('idtemplate_maintenance', $idtemplate_maintenance)
            ->update([
                'nama_template' => $nama_template_baru,
                'updated_at' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_by' => session('userdata')['iduser']
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('status', [
                'code' => 500,
				'status' => 'danger',
				'message' => 'Gagal mengubah nama form'
            ]);
        }

        return redirect()->back()->with('status', [
            'code' => 200,
            'status' => 'success',
            'message' => 'Nama form berhasil diubah'
        ]);
    }

    public function ubah_jenis_form(Request $request)
    {
        // dd($request->all());

        $idtemplate_maintenance = $request->input('idtemplate_maintenance');
        $jenis_maintenance_baru = $request->input('jenis_maintenance');

        try {
            DB::table('template_maintenance')
            ->where('idtemplate_maintenance', $idtemplate_maintenance)
            ->update([
                'jenis_maintenance' => $jenis_maintenance_baru,
                'updated_at' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                'updated_by' => session('userdata')['iduser']
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('status', [
                'code' => 500,
                'status' => 'danger',
                'message' => 'Gagal mengubah jenis form'
            ]);

        }

        return redirect()->back()->with('status', [
            'code' => 200,
            'status' => 'success',
            'message' => 'Jenis form berhasil diubah'
        ]);
    }

    public function tambah_elemen_baru(Request $request)
    {
        // dd($request->all());

        

        $idtemplate_maintenance = $request->input('idtemplate_maintenance');
        $jenis_isi = $request->input('jenis_element');
        $level = $request->input('level_element');
        $nilai_tampil = $request->input('nilai_tampil');
        $urutan = $request->input('urutan');

        $parent_id = null;
        if(intval($level) > 1){
            $parent_id = (int)$request->input('parent_element');
        }

        // dd($parent_id);


        $now = Carbon::now('Asia/Jakarta');
        $ts = $now->format('Y-m-d H:i:s');

        try {
            DB::table('isi_template')->insert([
                'idtemplate_maintenance' => $idtemplate_maintenance,
                'jenis_isi' => $jenis_isi,
                'level' => $level,
                'nilai_tampil' => $nilai_tampil,
                'urutan' => $urutan,
                'parent_id' => $parent_id
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('status', [
                'code' => 500,
                'status' => 'danger',
                'message' => 'Gagal menambah elemen baru'
            ]);
                
        }

        return redirect()->back()->with('status', [
            'code' => 200,
            'status' => 'success',
            'message' => 'Elemen baru berhasil ditambahkan'
        ]);
    }

    public function get_parent_element(Request $request)
    {
        // dd($request->all());
        $level = $request->input('level');
        $idtemplate_maintenance = $request->input('idtemplate_maintenance');

        $parents = DB::table('isi_template')
                        ->where('idtemplate_maintenance', $idtemplate_maintenance)
                        ->where('level', $level)
                        ->where('jenis_isi', 1)
                        ->orderBy('urutan', 'asc')
                        ->get();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $parents
        ], 200);
    }

    public function simpan_edit_elemen(Request $request)
    {
        // dd($request->all());

        // Validasi input
        $validated = $request->validate([
                        'idisi_template' => 'required|integer|exists:isi_template,idisi_template',
                        'urutan' => 'required|integer'
                    ]);

        $idisi_template = $request->input('idisi_template');
        $nilai_tampil_baru = $request->input('nilai_tampil');
        $urutan_baru = $request->input('urutan');

        try {
            DB::table('isi_template')
            ->where('idisi_template', $idisi_template)
            ->update([
                'nilai_tampil' => $nilai_tampil_baru,
                'urutan' => $urutan_baru
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('status', [
                'code' => 500,
                'status' => 'danger',
                'message' => 'Gagal menyimpan perubahan elemen'
            ]);
        }

        return redirect()->back()->with('status', [
            'code' => 200,
            'status' => 'success',
            'message' => 'Perubahan elemen berhasil disimpan'
        ]);
    }

    public function get_elemen_template(Request $request)
    {
        // dd($request->all());
        $idisi_template = $request->input('idisi_template');

        $elemen = DB::table('isi_template')
                    ->where('idisi_template', $idisi_template)
                    ->first();
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $elemen
        ], 200);
    }

    public function hapus_elemen_template(Request $request)
    {
        // dd($request->all());
        $idisi_template = $request->input('idisi_template');

        //cek apakah punya children
        $children = DB::table('isi_template')
                        ->where('parent_id', $idisi_template)
                        ->get();

        $arr_idisitemplate = array();
        array_push($arr_idisitemplate, $idisi_template);
        if(count($children) > 0){
            foreach($children as $ch){
                array_push($arr_idisitemplate, $ch->idisi_template);
            }
        }


        try {
            DB::table('isi_template')
            ->whereIn('idisi_template', $arr_idisitemplate)
            ->update(
                ['is_deleted' => true]
            );
        } catch (\Exception $e) {
            return redirect()->back()->with('status', [
                'code' => 500,
                'status' => 'danger',
                'message' => 'Gagal menghapus elemen template'
            ]);
        }

        return redirect()->back()->with('status', [
            'code' => 200,
            'status' => 'success',
            'message' => 'Elemen template berhasil dihapus'
        ]);
    }

    public function simpan_nilai_default(Request $request)
    {
        // dd($request->all());

        $idtemplate_maintenance = $request->input('idtemplate_maintenance');

        $ids = array();
        $nilaicase = '';

        foreach($request->input('default') as $key => $value){
            array_push($ids, $key);

            $nilaicase .= " WHEN ".$key." THEN '".$value."' ";
        }

        $sql = "UPDATE isi_template SET nilai_default = CASE idisi_template ".$nilaicase." END WHERE idisi_template IN (".implode(',', $ids).")";

        
        try {
            DB::statement($sql);
        } catch (\Exception $e) {
            return redirect()->back()->with('status', [
            'code' => 500,
            'status' => 'danger',
            'message' => 'Gagal menyimpan nilai default'
            ]);
        }

        return redirect()->back()->with('status', [
            'code' => 200,
            'status' => 'success',
            'message' => 'Nilai default berhasil disimpan'
        ]);
    }

    public function tambah_syarat_penelitian(Request $request)
    {
        // dd($request->all());

        $idtemplate_maintenance = $request->input('idtemplate_maintenance');
        $nama_syarat = $request->input('nama_syarat');

        $now = Carbon::now('Asia/Jakarta');
        $ts = $now->format('Y-m-d H:i:s');

        try {
            $idsyarat = DB::table('syarat_ajuan_penelitian')->insertGetId([
                                        'idtemplate_maintenance' => $idtemplate_maintenance,
                                        'nama_syarat' => $nama_syarat,
                                        'status' => true
                                    ], 'idsyarat_ajuan_penelitian');
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'status' => 'danger',
                'message' => 'Gagal menambah syarat penelitian baru',
                'data' => []
            ], 500);
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Syarat penelitian baru berhasil ditambahkan',
            'data' => [
                'idsyarat_ajuan_penelitian' => $idsyarat,
                'nama_syarat' => $nama_syarat
            ]
        ], 200);
    }

    public function ganti_status_syarat_penelitian(Request $request)
    {
        // dd($request->all());

        $idsyarat_ajuan_penelitian = $request->input('idsyarat_ajuan_penelitian');
        $status_baru = $request->input('status');

        if($status_baru == 1){
            $status_baru = true;
        } else {
            $status_baru = false;
        }

        try {
            DB::table('syarat_ajuan_penelitian')
            ->where('idsyarat_ajuan_penelitian', $idsyarat_ajuan_penelitian)
            ->update([
                'status' => $status_baru
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'status' => 'danger',
                'message' => 'Gagal mengubah status syarat penelitian',
                'data' => []
            ], 500);
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Status syarat penelitian berhasil diubah',
            'data' => [
                'idsyarat_ajuan_penelitian' => $idsyarat_ajuan_penelitian,
                'status_baru' => $status_baru
            ]
        ], 200);
    }
}