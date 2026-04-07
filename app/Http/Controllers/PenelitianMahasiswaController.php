<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use App\Services\UACCService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Services\FileService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\LockTimeoutException;


class PenelitianMahasiswaController extends Controller
{
    public function index(){
        $menu = 'penelitian';
        $submenu = 'sub_penelitian';

        $list_ajuan_penelitian = DB::table('penelitian as p')
                                        ->join('tim_mahasiswa as tm', 'p.idpenelitian', '=', 'tm.idpenelitian')
                                        ->leftJoin('user as u', 'p.dosen_pembimbing_utama', '=', 'u.iduser')
                                        ->join('aucc.unit_kerja as uk', 'p.idunit_kerja', 'uk.id_unit_kerja')
                                        ->where('tm.iduser_mahasiswa', session('userdata')['iduser'])
                                        ->select('p.*', 'u.nama as dosen_pembimbing', 'u.gelar_depan', 'u.gelar_belakang', 'uk.nm_unit_kerja as unit_kerja')
                                        ->orderBy('p.idpenelitian', 'desc')
                                        ->get();

        $whereIn = array();
        foreach($list_ajuan_penelitian as $ajuan){
            $whereIn[] = $ajuan->idpenelitian;
        }

        $lab_digunakan_q = DB::table('lab_penelitian as lp')
                            ->join('simba.ruang as r', 'lp.idruang', 'r.id')
                            ->join('simba.gedung as g', 'r.id_gedung', 'g.id')
                            ->select('lp.idpenelitian', 'r.nama_ruang', 'g.nama_gedung')
                            ->whereIn('lp.idpenelitian', $whereIn)
                            ->get();

        $lab_digunakan = array();
        foreach($lab_digunakan_q as $lab){
            $lab_digunakan[$lab->idpenelitian][] = $lab->nama_ruang . '#' . $lab->nama_gedung;
        }

        // dd($list_ajuan_penelitian);

        return view('mahasiswa.index', compact('menu', 'submenu', 'list_ajuan_penelitian', 'lab_digunakan'));
    }

    public function create(){
        $menu = 'penelitian';
        $submenu = 'sub_penelitian';

        // dd($request->all(), session('userdata'));

        $unitfak = DB::table('aset as a')
                        ->join('aucc.unit_kerja as uk', 'a.idunit_kerja', 'uk.id_unit_kerja')
                        ->where('public', 1)
                        ->select('uk.id_unit_kerja', 'uk.nm_unit_kerja', 'uk.type_unit_kerja')
                        ->groupBy('uk.id_unit_kerja', 'uk.nm_unit_kerja', 'uk.type_unit_kerja')
                        ->get();


        return view('mahasiswa.tambah_penelitian', compact('menu', 'submenu', 'unitfak'));
    }

    public function store(Request $request){
        // dd($request->all(), session('userdata'));

        $validated = $request->validate([
            'jenis_penelitian' => 'required|string|max:255'
        ]);

        $now = Carbon::now('Asia/Jakarta');
        $ts = $now->format('Y-m-d H:i:s');

        try {
            DB::beginTransaction();

            $idpenelitian = DB::table('penelitian')->insertGetId([
                'internal' => $request->input('jenis_penelitian'),
                'diajukan_oleh' => session('userdata')['iduser'],
                'status_ajuan' => 1, // status awal: draft
                'created_at' => $ts,
                'idunit_kerja' => session('userdata')['idunit_kerja'],
                'tabel_ref_unit_kerja' => 'unit_kerja_simantap'
                ], 'idpenelitian');

            //mahasiswa
            if(session('userdata')['idrole'] == 6){
                // Simpan data ke tabel tim_mahasiswa
                DB::table('tim_mahasiswa')->insert([
                    'idpenelitian' => $idpenelitian,
                    'iduser_mahasiswa' => session('userdata')['iduser'],
                    'status' => "true",
                    'pegang_alat' => "false"
                ]);
            }

            DB::table('log_status_penelitian')->insert([
                'idpenelitian' => $idpenelitian,
                'status' => 1, // status awal: draft
                'timestamp' => $ts,
                'updated_by' => session('userdata')['iduser']
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->with('status', [
                'code' => 500,
				'status' => 'danger',
				'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ]);
        }

        

        return redirect()->route('penelitian_mhs_edit', ['id' => Crypt::encrypt($idpenelitian)])->with('status', [
                'code' => 200,
				'status' => 'success',
				'message' => 'Ajuan penelitian berhasil dibuat!'
            ]);
    }

    public function edit($id){
        $menu = 'penelitian';
        $submenu = 'sub_penelitian';

        $idpenelitian = Crypt::decrypt($id);

        $penelitian = DB::table('penelitian as p')
                        ->leftJoin('user as u', 'p.dosen_pembimbing_utama', 'u.iduser')
                        ->leftJoin('user as u2', 'p.diajukan_oleh', 'u2.iduser')
                        ->leftJoin('aucc.unit_kerja as uk', 'p.idunit_kerja', 'uk.id_unit_kerja')
                        ->select('p.idpenelitian', 'p.status_ajuan', 'p.topik', 'p.internal', 'u.nipnik as nipnik_dosen_pembimbing', 'u.nama as nama_dosen_pembimbing', 'u.gelar_depan as gelar_depan_dosen_pembimbing', 'u.gelar_belakang as gelar_belakang_dosen_pembimbing',
                                'u2.nipnik as nipnik_pengaju', 'u2.nama as nama_pengaju', 'u2.gelar_depan as gelar_depan_pengaju', 'u2.gelar_belakang as gelar_belakang_pengaju', 'p.idunit_kerja',
                                'uk.nm_unit_kerja', 'uk.type_unit_kerja')
                        ->where('p.idpenelitian', $idpenelitian)
                        ->get();

        // dd($penelitian);


        $lab_terpilih = DB::table('lab_penelitian as lp')
                                ->join('simba.ruang as r', 'lp.idruang', 'r.id')
                                ->join('simba.gedung as g', 'r.id_gedung', 'g.id')
                                ->select('lp.idlab_penelitian', 'r.id as idruang', 'r.nama_ruang', 'g.nama_gedung')
                                ->where('lp.idpenelitian', $idpenelitian)
                                ->get();

        $whereIn = array();

        $alat_lab_pilihan = array();

        foreach($lab_terpilih as $lab){
            $whereIn[] = $lab->idruang;
        }

        if(count($whereIn) > 0){
            $alat_lab_pilihan = DB::table('aset as a')
                                    ->join('simba.ruang as r', 'a.idruang', 'r.id')
                                    ->join('simba.gedung as g', 'r.id_gedung', 'g.id')
                                    ->select('a.kode_barang_aset', 'a.nama_barang', 'a.merk_barang', 'a.keterangan', 'a.tahun_aset', 'a.kondisi_barang', 'r.nama_ruang', 'g.nama_gedung', 'a.tahun_aset')
                                    ->whereIn('a.idruang', $whereIn)
                                    ->where('a.public', 1)
                                    ->whereIn('a.kondisi_barang', [1, 2])
                                    ->where('a.status', 1)
                                    ->orderBy('r.nama_ruang', 'asc')
                                    ->orderBy('g.nama_gedung', 'asc')
                                    ->orderBy('a.nama_barang', 'asc')
                                    ->get();
        }

        $ruang_lab = DB::table('aset as a')
                        ->join('simba.ruang as r', 'a.idruang', 'r.id')
                        ->join('simba.gedung as g', 'r.id_gedung', 'g.id')
                        ->select('r.id', 'r.nama_ruang', 'g.nama_gedung')
                        ->where('a.idunit_kerja', $penelitian[0]->idunit_kerja)
                        ->where('a.public', 1)
                        ->whereIn('a.kondisi_barang', [1, 2])
                        ->where('a.status', 1)
                        ->groupBy('r.id', 'r.nama_ruang', 'g.nama_gedung')
                        ->get();

        

        $tim_mhs = DB::table('tim_mahasiswa as tm')
                        ->join('user as u', 'tm.iduser_mahasiswa', 'u.iduser')
                        ->join('aucc.program_studi as ps', 'u.idprogram_studi', 'ps.id_program_studi')
                        ->join('aucc.unit_kerja as uk', 'u.idunit_kerja', 'uk.id_unit_kerja')
                        ->select('tm.iduser_mahasiswa', 'u.nipnik', 'u.nama', 'ps.nm_program_studi', 'uk.nm_unit_kerja', 'uk.type_unit_kerja',
                                'tm.status', 'tm.pegang_alat')
                        ->where('tm.idpenelitian', $idpenelitian)
                        ->get();

        $alat_digunakan_kodebarang_q = DB::table('alat_digunakan as ad')
                                        ->join('aset as a', 'ad.kode_barang_aset', 'a.kode_barang_aset')
                                        ->join('simba.ruang as r', 'a.idruang', 'r.id')
                                        ->join('simba.gedung as g', 'r.id_gedung', 'g.id')
                                        ->select('ad.idalat_digunakan', 'ad.tujuan', 'a.kode_barang_aset', 'a.nama_barang', 'a.merk_barang', 'a.keterangan', 
                                                'a.tahun_aset', 'a.kondisi_barang', 'r.nama_ruang', 'g.nama_gedung', 'ad.idwaktu')
                                        ->where('ad.id_kegiatan', $idpenelitian)
                                        ->where('ad.jenis_pemakaian', 3) // tipe kegiatan 3 untuk penelitian
                                        ->orderBy('ad.idwaktu', 'asc')
                                        ->orderBy('a.nama_barang', 'asc')
                                        ->get();

        $alat_digunakan_kodebarang = array();
        foreach($alat_digunakan_kodebarang_q as $alat){
            $alat_digunakan_kodebarang[$alat->idwaktu][] = $alat;
        }        

        $isi_template = DB::table('isi_form_penelitian as ifp')
                        ->join('isi_template as it', 'ifp.idisi_template', 'it.idisi_template')
                        ->join('template_maintenance as tm', 'it.idtemplate_maintenance', 'tm.idtemplate_maintenance')
                        ->select('ifp.idisi_form_penelitian', 'ifp.isi', 'ifp.idpenelitian', 'it.idisi_template', 'tm.jenis_maintenance',
                                'it.jenis_isi', 'it.level', 'it.nilai_tampil', 'it.parent_id', 'it.urutan', 'it.idtemplate_maintenance', 'it.nilai_default')
                        ->where('ifp.idpenelitian', $idpenelitian)
                        ->orderBy('it.urutan', 'asc')
                        ->get();

        $syarat_penelitian = DB::table('syarat_ajuan_penelitian as sap')
                                ->leftJoin('file_ajuan_penelitian as fap', function($join) use ($idpenelitian) {
                                    $join->on('sap.idsyarat_ajuan_penelitian', 'fap.idsyarat_ajuan_penelitian')
                                        ->where('fap.idpenelitian', $idpenelitian);
                                })
                                ->where('sap.idtemplate_maintenance', $isi_template[0]->idtemplate_maintenance)
                                ->where('sap.status', 'true')
                                ->orderBy('sap.idsyarat_ajuan_penelitian', 'asc')
                                ->select('sap.idsyarat_ajuan_penelitian', 'sap.nama_syarat', 'fap.idfile_ajuan_penelitian', 'fap.nama_file')
                                ->get();

        // dd($syarat_penelitian);

        $riwayat_ajuan = DB::table('log_status_penelitian as lsp')
                        ->join('user as u', 'lsp.updated_by', 'u.iduser')
                        ->select('u.nipnik', 'u.nama', 'u.gelar_depan', 'u.gelar_belakang')
                        ->where('lsp.idpenelitian', $idpenelitian)
                        ->orderBy('lsp.timestamp', 'desc')
                        ->get();

        $waktu_ajuan = DB::table('waktu')
                        ->where('tipe_pemakaian', '3')
                        ->where('id_kegiatan', $idpenelitian)
                        ->where('is_deleted', 'false')
                        ->orderBy('tanggal', 'asc')
                        ->orderBy('waktu_mulai', 'asc')
                        ->get();

        $whereIn = array();

        foreach($waktu_ajuan as $waktu){
            $waktu->tanggal = Carbon::createFromFormat('Y-m-d', $waktu->tanggal)->format('d/m/Y');
            $waktu->waktu_mulai = Carbon::createFromFormat('H:i:s', $waktu->waktu_mulai)->format('H:i');
            $waktu->waktu_akhir = Carbon::createFromFormat('H:i:s', $waktu->waktu_akhir)->format('H:i');
            $whereIn[] = $waktu->idwaktu;
        }

        

        // dd($penelitian, $tim_mhs, $alat_digunakan, $isi_template, $syarat_penelitian, $riwayat_ajuan);

        // dd($diajukan_oleh);

        $layout = array();

        foreach($isi_template as $el){
            if($el->level == 1){
                if(!key_exists($el->idisi_template, $layout)){
                    $layout[$el->idisi_template] = array(
                        'idisi_template' => $el->idisi_template,
                        'jenis_isi' => $el->jenis_isi,
                        'level' => $el->level,
                        'nilai_tampil' => $el->nilai_tampil,
                        'urutan' => $el->urutan,
                        'parent_id' => $el->parent_id,
                        'nilai_default' => $el->nilai_default,
                        'idisi_form_penelitian' => $el->idisi_form_penelitian,
                        'nilai' => $el->isi,
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
                    'idisi_form_penelitian' => $el->idisi_form_penelitian,
                    'nilai' => $el->isi
                );
            }            
        }

        // dd($layout);

        $satuan = DB::table('simba.satuan')
                    ->orderBy('nm_satuan', 'asc')
                    ->get();

        $satuan = json_encode($satuan);
        // dd($layout);

        $ajuan_bahan = DB::table('ajuan_bahan as ab')
                            ->where('idpenelitian', $idpenelitian)
                            ->get();

        if($penelitian[0]->status_ajuan == 1){
            return view('mahasiswa.edit_penelitian', compact('menu', 'submenu', 'penelitian', 'tim_mhs', 'alat_lab_pilihan',
                                                    'layout', 'syarat_penelitian', 'riwayat_ajuan', 'ruang_lab', 'idpenelitian', 
                                                    'satuan', 'waktu_ajuan', 'lab_terpilih', 'alat_digunakan_kodebarang', 'ajuan_bahan'));
        }
        else{
            return view('mahasiswa.view_penelitian', compact('menu', 'submenu', 'penelitian', 'tim_mhs', 'alat_lab_pilihan',
                                                    'layout', 'syarat_penelitian', 'riwayat_ajuan', 'ruang_lab', 'idpenelitian', 
                                                    'satuan', 'waktu_ajuan', 'lab_terpilih', 'alat_digunakan_kodebarang', 'ajuan_bahan'));
        }

                
        // dd(session('userdata'));
        // dd($maintenance_aset);
        // dd($isi_template, $layout);

        // if($maintenance_aset->status == 1 && $maintenance_aset->nipnik_creator == session('userdata')['nipnik']){          
        //     return view('proses_maintenance.form_edit_proses', compact('menu', 'submenu', 'layout', 'jenis_maintenance', 'maintenance_aset', 'log_status', 'files', 'diajukan_oleh'));
        // }
        // else if($maintenance_aset->status == 1 && $maintenance_aset->nipnik_creator != session('userdata')['nipnik']){
        //     return view('proses_maintenance.form_edit_view', compact('menu', 'submenu', 'layout', 'jenis_maintenance', 'maintenance_aset', 'log_status', 'files', 'diajukan_oleh'));
        // }
        // else{
        //     return view('proses_maintenance.form_edit_view', compact('menu', 'submenu', 'layout', 'jenis_maintenance', 'maintenance_aset', 'log_status', 'files', 'diajukan_oleh'));
        // }
    }

    public function getform_penelitian(Request $req){
        $validated = $req->validate([
            'internal' => 'required|string|max:10',
            'unitfak'  => 'nullable|integer'
        ], [
            'internal.required' => 'Jenis penelitian wajib diisi.',
            'internal.string'   => 'Jenis penelitian tidak valid.',
            'internal.max'      => 'Jenis penelitian maksimal 10 karakter.',
            'unitfak.integer'   => 'Unit/Fakultas tidak valid.'
        ]);

        $internal = $validated['internal'];
        $unitfak = $validated['unitfak'];

        $form_options = DB::table('template_maintenance')
                        ->select('idtemplate_maintenance', 'nama_template')
                        ->where('jenis_maintenance', 3)
                        ->where('internal', $internal)
                        ->where('status', 'true')
                        ->where('idunit_kerja', $unitfak)
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

    public function get_form_template(Request $req){
        $idtemplate_maintenance = $req->idform;

        $form_template = DB::table('isi_template as it')
                        ->join('template_maintenance as tm', 'it.idtemplate_maintenance', 'tm.idtemplate_maintenance')
                        ->select('it.idisi_template', 'it.jenis_isi', 'it.level', 'it.nilai_tampil', 'it.parent_id', 'it.urutan', 'it.nilai_default', 
                                    'it.idtemplate_maintenance', 'tm.jenis_maintenance', 'tm.internal')
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

        if($form_template[0]->jenis_maintenance == 3){
            $jenis_maintenance_text = 'Penelitian';
        }
        else{
            $jenis_maintenance_text = 'unknown';
        }

        $syarat_penelitian = DB::table('syarat_ajuan_penelitian')
                                ->where('idtemplate_maintenance', $idtemplate_maintenance)
                                ->orderBy('idsyarat_ajuan_penelitian', 'asc')
                                ->get();

        $internal_eksternal_text = $form_template[0]->internal == 1 ? 'Internal' : 'Eksternal';

        $form_html = '<h2 style="text-align: center">FROM ' . strtoupper($jenis_maintenance_text) . ' '.$internal_eksternal_text.'</h2>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Topik Penelitian</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{ Wajib diisi }" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Dosen Pembimbing</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{ Wajib diisi }" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Ruangan</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{ Wajib diisi }" readonly>
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

        $form_html .= '<div class="form-group col-md-12" style="padding-left:0px; padding-right:0px">
                            <label>Instrumen yang akan digunakan (wajib)</label>
                            <div class="col-sm-12">
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered verticle-middle table-responsive-sm" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Kode Barang</th>
                                                <th scope="col">Nama Barang</th>
                                                <th scope="col">Tujuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                                                            
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>

                        <div class="form-group col-md-12" style="padding-left:0px; padding-right:0px">
                            <label>Bahan padat / cair yang diajukan (wajib)</label>
                            <div class="col-sm-12">
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered verticle-middle table-responsive-sm" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Nama Bahan</th>
                                                <th scope="col">Spisifikasi</th>
                                                <th scope="col">Jumlah</th>
                                                <th scope="col">Satuan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                                                            
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>

                        <div class="form-group col-md-12" style="padding-left:0px; padding-right:0px">
                            <label>Tanggal dan Waktu Pelaksanaan (wajib)</label>
                            <div class="col-sm-12">
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered verticle-middle table-responsive-sm" width="100%">
                                        <thead>
                                            <tr>
                                                <th scope="col">No</th>
                                                <th scope="col">Tanggal</th>
                                                <th scope="col">Waktu</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                                                            
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>
                        
                        <div class="form-group col-md-12" style="padding-left:0px; padding-right:0px">
                            <div class="d-flex justify-content-between">
                                <b>Syarat Ajuan Pelaksanaan '.$jenis_maintenance_text.'</b>
                            </div>
                            <table class="table table-bordered verticle-middle table-responsive-sm" width="100%" id="tbl_syarat_penelitian">
                                <thead>
                                    <tr>
                                        <th scope="col">Syarat</th>
                                        <th scope="col">File</th>
                                    </tr>
                                </thead>
                                <tbody>';

            foreach($syarat_penelitian as $syarat){
                $form_html .='<tr>
                                <td>'. $syarat->nama_syarat .'</td>
                                <td >
                                    
                                </td>
                            </tr>';
            }
                

            $form_html .='       </tbody>
                            </table>
                        </div>';

        

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $form_html
        ], 200);
    }

    public function exec_tambah_penelitian(Request $req){
        // dd($req->all());

        $validated = $req->validate([
            'jenis_penelitian' => 'required|string|max:10',
            'unitfak' => 'required|integer',
            'form' => 'required|integer'
        ]);

        if ($validated === false) {
            // Ambil pesan error dari validator jika ada
            $errors = $req->validator ? $req->validator->errors()->all() : ['Validasi gagal.'];
            return back()->with('status', [
                'code' => 500,
                'status' => 'danger',
                'message' => implode(', ', $errors),
            ]);
        }

        $id_isi_template_q = DB::table('isi_template')
                                ->where('idtemplate_maintenance', $validated['form'])
                                ->where('is_deleted', 'false')
                                ->orderBy('urutan', 'asc')
                                ->select('idisi_template', 'nilai_default')
                                ->get();

        $id_syarat = DB::table('syarat_ajuan_penelitian')
                        ->where('idtemplate_maintenance', $validated['form'])
                        ->orderBy('idsyarat_ajuan_penelitian', 'asc')
                        ->pluck('idsyarat_ajuan_penelitian')
                        ->toArray();


        // dd($id_isi_template_q, $id_syarat);

        date_default_timezone_set('Asia/Jakarta');
		$ts = date('Y-m-d H:i:s');

        try {
            DB::beginTransaction();

            $arr_insert = array(
            'created_at' => $ts,
            'status_ajuan' => 1,
            'idunit_kerja' => $validated['unitfak'],
            'internal' => $validated['jenis_penelitian'],
            );

            $idpenelitian = DB::table('penelitian')->insertGetId($arr_insert, 'idpenelitian');

            $arr_insert_log = array(
            'idpenelitian' => $idpenelitian,
            'status' => 1,
            'timestamp' => $ts,
            'updated_by' => session('userdata')['iduser']
            );

            DB::table('log_status_penelitian')->insert($arr_insert_log);

            $insert_isi_template = array();
            foreach($id_isi_template_q as $isi_template){
                $insert_isi_template[] = array(
                    'idpenelitian' => $idpenelitian,
                    'idisi_template' => $isi_template->idisi_template,
                    'isi' => $isi_template->nilai_default
                );
            }

            DB::table('isi_form_penelitian')->insert($insert_isi_template);

            $insert_syarat = array();
            foreach($id_syarat as $id){
                $insert_syarat[] = array(
                    'idpenelitian' => $idpenelitian,
                    'idsyarat_ajuan_penelitian' => $id
                );
            }

            DB::table('file_ajuan_penelitian')->insert($insert_syarat);

            $insert_tim = array(
                'idpenelitian' => $idpenelitian,
                'iduser_mahasiswa' => session('userdata')['iduser'],
                'status' => "true",
                'pegang_alat' => "false"
            );

            DB::table('tim_mahasiswa')->insert($insert_tim);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('status', [
            'code' => 500,
            'status' => 'danger',
            'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage(),
            ]);
        }

        return redirect()->route('penelitian_mhs_edit', ['id' => Crypt::encrypt($idpenelitian)])->with('status', [
            'code' => 200,
            'status' => 'success',
            'message' => 'Ajuan penelitian berhasil dibuat!'
        ]);
    }

    public function cari_dosen(Request $req){
        $validated = $req->validate([
            'nip' => 'required|string'
        ]);

        $nip = $validated['nip'];

        $uacc = new UACCService();
        $dosen = $uacc->cari_civitas_akademik($nip, 2);

        if($dosen['code'] != 200){
            return response()->json([
                'code' => 404,
                'message' => 'Dosen tidak ditemukan',
                'data' => null
            ], 200);
        }

        $data_dosen = $dosen['data'];

        $uacc_data = DB::table('aucc.pengguna as p')
                        ->where('p.id_pengguna', $data_dosen['iduser'])
                        ->select('p.gelar_depan', 'p.gelar_belakang')
                        ->first();

        $data_dosen['gelar_depan'] = $uacc_data->gelar_depan;
        $data_dosen['gelar_belakang'] = $uacc_data->gelar_belakang;

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $data_dosen
        ], 200);
    }

    public function cek_simpan_dosen(Request $req){
        // dd($req->all());
        $validated = $req->validate([
            'iduser' => 'required|integer',
            'nama_dosen' => 'required|string',
            'gelar_depan' => 'nullable|string',
            'gelar_belakang' => 'nullable|string',
            'id_unit_kerja' => 'required|integer',
            'idprogram_studi' => 'required|integer',
            'nipnik' => 'required|string',
            'idpenelitian' => 'required|integer'
        ]);

        $iduser = $validated['iduser'];
        $nama = $validated['nama_dosen'];
        $gelar_depan = $validated['gelar_depan'];
        $gelar_belakang = $validated['gelar_belakang'];
        $id_unit_kerja = $validated['id_unit_kerja'];
        $idprogram_studi = $validated['idprogram_studi'];
        $nipnik = $validated['nipnik'];
        $idpenelitian = $validated['idpenelitian'];

        $cek = DB::table('user')
                ->where('nipnik', $nipnik)
                ->get();

        $now = Carbon::now('Asia/Jakarta');
        $ts = $now->format('Y-m-d H:i:s');

        
        try {
            if(count($cek) > 0){
                $iduser = $cek[0]->iduser;
            }
            else{
                $iduser = DB::table('user')->insertGetId(
                    [
                        'id_pengguna_cyber' => $iduser,
                        'nama' => $nama,
                        'gelar_depan' => $gelar_depan,
                        'gelar_belakang' => $gelar_belakang,
                        'idunit_kerja' => $id_unit_kerja,
                        'idprogram_studi' => $idprogram_studi,
                        'nipnik' => $nipnik,
                        'join_table' => 2,
                        'internal' => 'true',
                        'status' => 'true',
                        'created_at' => $ts,
                    ], 'iduser'
                );
            }

            DB::table('penelitian')
                ->where('idpenelitian', $idpenelitian)
                ->update([
                    'dosen_pembimbing_utama' => $iduser
                ]);

        } catch (\Exception $e) {
            return response()->json([
            'code' => 500,
            'message' => 'Gagal menyimpan dosen: ' . $e->getMessage(),
            'data' => null
            ], 200);
        }
        

        return response()->json([
            'code' => 200,
            'message' => 'Dosen berhasil disimpan',
            'data' => null
        ], 200);
    }

    public function get_data_aset_ruangan(Request $req){
        $validated = $req->validate([
            'idruang' => 'required|integer'
        ]);

        $idruang = $validated['idruang'];

        $aset_ruangan = DB::table('aset as a')
                        ->join('simba.ruang as r', 'a.idruang', 'r.id')
                        ->join('simba.gedung as g', 'r.id_gedung', 'g.id')
                        ->select('a.kode_barang_aset', 'a.nama_barang', 'a.merk_barang', 'a.keterangan', 'a.tahun_aset', 'a.kondisi_barang', 'r.nama_ruang', 'g.nama_gedung')
                        ->where('a.idruang', $idruang)
                        ->where('a.public', 1)
                        ->whereIn('a.kondisi_barang', [1, 2])
                        ->where('a.status', 1)
                        ->get();

        return response()->json([
            'code' => 200,
            'message' => 'success',
            'data' => $aset_ruangan
        ], 200);
    }

    public function show($id){
        $idpenelitian = Crypt::decrypt($id);

        $penelitian = DB::table(('penelitian'))
                        ->where('idpenelitian', $idpenelitian)
                        ->select('status_ajuan')
                        ->first();

        // if($penelitian->status_ajuan == 1){
        //     return redirect()->route('penelitian_mhs_edit', ['id' => Crypt::encrypt($idpenelitian)]);
        // }
        // else{
        //     return view('mahasiswa.show_penelitian', compact('idpenelitian'));
        // }
        return redirect()->route('penelitian_mhs_edit', ['id' => Crypt::encrypt($idpenelitian)]);
    }
    
    private function cek_aset_waktu($idpenelitian, $tanggal, $waktu_mulai, $waktu_akhir){
            // $bentrok = DB::table('waktu as w')
            //                 ->join('alat_digunakan as ad', function($join){
            //                     $join->on('w.id_kegiatan', '=', 'ad.idpenelitian');
            //                     $join->where('w.tipe_pemakaian', '3');
            //                 })
            //                 ->where('w.tanggal', Carbon::createFromFormat('d/m/Y', $tanggal)->format('Y-m-d'))
            //                 ->where('w.waktu_akhir', '>', Carbon::createFromFormat('H:i', $waktu_mulai)->format('H:i:s'))
            //                 ->where('w.waktu_mulai', '<', Carbon::createFromFormat('H:i', $waktu_akhir)->format('H:i:s'))
            //                 ->where('ad.kode_barang_aset', function($query) use ($idpenelitian){
            //                     $query->select('kode_barang_aset')
            //                         ->from('alat_digunakan')
            //                         ->where('idpenelitian', $idpenelitian);
            //                 })
            //                 ->count();
    
            return $bentrok > 0;
    }

    public function cek_waktu(Request $req){
        $validator = \Validator::make($req->all(), [
            'idpenelitian' => 'required|integer',
            'tanggal' => 'required|date_format:d/m/Y',
            'jam_mulai' => 'required',
            'jam_akhir' => 'required',
            'idwaktu' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => implode(', ', $validator->errors()->all()),
                'data' => null
            ], 422);
        }

        $validated = $validator->validated();

        // $totalBentrok = DB::table('waktu')
        //                 ->where('tanggal', '2026-03-20')
        //                 ->where('waktu_akhir', '>', '09:00:00')
        //                 ->where('waktu_mulai', '<', '11:00:00')
        //                 ->count();

        if(empty($validated['idwaktu'])){
            $now = Carbon::now('Asia/Jakarta');
            $ts = $now->format('Y-m-d H:i:s');

            $arr_insert = array(
                'id_kegiatan' => $validated['idpenelitian'],
                'tanggal' => Carbon::createFromFormat('d/m/Y', $validated['tanggal'])->format('Y-m-d'),
                'waktu_mulai' => $validated['jam_mulai'],
                'waktu_akhir' => $validated['jam_akhir'],
                'is_deleted' => 'false',
                'tipe_pemakaian' => '1',
                'created_by' => session('userdata')['iduser'],
                'created_at' => $ts
            );

            try {
                $idwaktu = DB::table('waktu')->insertGetId($arr_insert, 'idwaktu');
            } catch (\Exception $e) {
                return response()->json([
                    'code' => 500,
                    'message' => 'Gagal menyimpan waktu: ' . $e->getMessage(),
                    'data' => null
                ], 200);
            }

            return response()->json([
                'code' => 200,
                'message' => 'Waktu berhasil disimpan',
                'data' => array('idwaktu' => $idwaktu)
            ], 200);
        }
        else{
            $idwaktu = $validated['idwaktu'];

            $now = Carbon::now('Asia/Jakarta');
            $ts = $now->format('Y-m-d H:i:s');

            $arr_log = array(
                'idwaktu' => $idwaktu,
                'updated_by' => session('userdata')['iduser'],
                'updated_at' => $ts,
                'perubahan' => 'Tanggal: ' . Carbon::createFromFormat('d/m/Y', $validated['tanggal'])->format('Y-m-d') . ', Jam Mulai: ' . $validated['jam_mulai'] . ', Jam Akhir: ' . $validated['jam_akhir']
            );

            $arr_update = array(
                'tanggal' => Carbon::createFromFormat('d/m/Y', $validated['tanggal'])->format('Y-m-d'),
                'waktu_mulai' => $validated['jam_mulai'],
                'waktu_akhir' => $validated['jam_akhir']
            );

            try {
                DB::beginTransaction();

                DB::table('log_perubahan_waktu')->insert($arr_log);

                DB::table('waktu')
                    ->where('idwaktu', $idwaktu)
                    ->update($arr_update);

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'code' => 500,
                    'message' => 'Gagal memperbarui waktu: ' . $e->getMessage(),
                    'data' => null
                ], 200);
            }

            return response()->json([
                'code' => 200,
                'message' => 'Waktu berhasil diperbarui',
                'data' => null
            ], 200);
        }

        
    }

    // public function hapus_waktu(Request $req){
    //     // dd('hallo');
    //     $validator = \Validator::make($req->all(), [
    //         'idwaktu' => 'required|integer'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'code' => 422,
    //             'message' => implode(', ', $validator->errors()->all()),
    //             'data' => null
    //         ], 422);
    //     }

    //     $validated = $validator->validated();

    //     $now = Carbon::now('Asia/Jakarta');
    //     $ts = $now->format('Y-m-d H:i:s');

    //     $arr_update = array(
    //         'is_deleted' => 'true'
    //     );

    //     $arr_log_insert = array(
    //         'idwaktu' => $validated['idwaktu'],
    //         'updated_by' => session('userdata')['iduser'],
    //         'updated_at' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s'),
    //         'perubahan' => 'Waktu dihapus'
    //     );

    //     try {
    //         DB::beginTransaction();

    //         DB::table('log_perubahan_waktu')->insert($arr_log_insert);

    //         DB::table('waktu')
    //             ->where('idwaktu', $validated['idwaktu'])
    //             ->update($arr_update);

    //         DB::commit();
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'code' => 500,
    //             'message' => 'Gagal menghapus waktu: ' . $e->getMessage(),
    //             'data' => null
    //         ], 500);
    //     }

    //     return response()->json([
    //         'code' => 200,
    //         'message' => 'Waktu berhasil dihapus',
    //         'data' => null
    //     ], 200);
    // }

    public function upload_dokumen(Request $request){
        // dd($request->all());
        $rules = [
            'idfile_ajuan_penelitian' => 'required|integer',
            'nama_file'          => 'required|string',
            'file_dokumen'           => 'required|mimes:pdf|max:5120',
        ];

        $messages = [
            'idfile_ajuan_penelitian.required' => 'ID File Ajuan Penelitian wajib diisi.',
            'nama_file.required'          => 'Nama file tidak boleh kosong.',
            'file_dokumen.mimes'             => 'File harus berformat PDF.',
            'file_dokumen.max'               => 'Ukuran file maksimal adalah 5MB.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $allErrors = implode('<br>', $validator->errors()->all());

            return back()
                ->withErrors($validator) // Mengirim pesan error asli
                ->withInput()           // Agar input user tidak hilang
                ->with('status', [      // Custom report Anda
                    'code' => 422,
                    'status' => 'danger',
                    'message' => 'Validasi gagal: ' . $allErrors
                ]);
        }

        $validated = $validator->validated();

        $idfile_ajuan = $validated['idfile_ajuan_penelitian'];
        $file = $validated['file_dokumen'];
        $nama_file = $validated['nama_file'];

        date_default_timezone_set('Asia/Jakarta');
        $ts = date('Y-m-d H:i:s');
        $today = date('Y-m-d');

        //ubah nama jadi uuid untuk menghindari duplikasi nama file
        $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();

        if(!Storage::disk('local')->exists('private/upload/'.$today)){
            if(!Storage::disk('local')->makeDirectory('private/upload/' . $today)){
                return back()->with('status', [
                    'code' => 500,
                    'status' => 'danger',
                    'message' => 'Gagal membuat direktori penyimpanan'
                ]);
            }
        }

        $filePath = 'private/upload/'.$today.'/' . $filename;

        try {
            Storage::disk('local')->put($filePath, file_get_contents($file));

            DB::table('file_ajuan_penelitian')
                ->where('idfile_ajuan_penelitian', $idfile_ajuan)
                ->update([
                    'nama_file' => $nama_file,
                    'file_path' => $filePath,
                    'created_by' => session('userdata')['iduser'],
                    'created_at' => $ts
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'status' => 'danger',
                'message' => 'Gagal menyimpan data file maintenance',
                'data' => null
            ], 500);
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'File berhasil diunggah',
            'data' => array(
                'idfile_ajuan_penelitian' => Crypt::encrypt($idfile_ajuan),
                'plain_idfile_ajuan_penelitian' => $idfile_ajuan,
            )
        ], 200);
        
    }

    public function download_dokumen($id){
        $id = Crypt::decrypt($id);

        $file = new FileService();
        return $file->get_file($id, 'penelitian');
    }

    public function hapus_dokumen($id){
        $id = Crypt::decrypt($id);

        $file = new FileService();
        return $file->hapus_file($id, 'penelitian');
        
    }

    public function cek_simpan_ruangan(Request $req){
        $validated = $req->validate([
            'idpenelitian' => 'required|integer',
            'idruang' => 'required|integer'
        ]);

        $idpenelitian = $validated['idpenelitian'];
        $idruang = $validated['idruang'];

        $cek = DB::table('lab_penelitian')
                ->where('idpenelitian', $idpenelitian)
                ->where('idruang', $idruang)
                ->count();

        if($cek > 0){
            return response()->json([
                'code' => 400,
                'message' => 'Ruangan sudah ada',
                'data' => null
            ], 200);
        }

        $arr_insert = array(
            'idpenelitian' => $idpenelitian,
            'idruang' => $idruang
        );

        try {
            $idlab_penelitian = DB::table('lab_penelitian')->insertGetId($arr_insert, 'idlab_penelitian');
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Gagal menyimpan ruangan: ' . $e->getMessage(),
                'data' => null
            ], 200);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Ruangan berhasil disimpan',
            'data' => array('idlab_penelitian' => $idlab_penelitian)
        ], 200);
    }

    public function cek_simpan_aset_old(Request $req){
        $validated = $req->validate([
            'idpenelitian' => 'required|integer',
            'kode_barang_aset' => 'required|string',
            'idwaktu' => 'required|integer',
            'jenis_pemakaian' => 'required|string'
        ]);

        $idpenelitian = $validated['idpenelitian'];
        $kode_barang_aset = $validated['kode_barang_aset'];
        $idwaktu = $validated['idwaktu'];
        $jenis_pemakaian = $validated['jenis_pemakaian'];

        $cek = DB::table('alat_digunakan')
                ->where('id_kegiatan', $idpenelitian)
                ->where('kode_barang_aset', $kode_barang_aset)
                ->where('jenis_pemakaian', $jenis_pemakaian)
                ->where('idwaktu', $idwaktu)
                ->count();

        if($cek > 0){
            return response()->json([
                'code' => 400,
                'message' => $kode_barang_aset.' sudah ada',
                'data' => null
            ], 200);
        }

        $arr_insert = array(
            'id_kegiatan' => $idpenelitian,
            'kode_barang_aset' => $kode_barang_aset,
            'jenis_pemakaian' => $jenis_pemakaian, // tipe kegiatan 3 untuk penelitian
            'idwaktu' => $idwaktu,
        );

        try {
            $idalat_digunakan = DB::table('alat_digunakan')
                                ->insertGetId($arr_insert, 'idalat_digunakan');
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Gagal menyimpan aset: ' . $e->getMessage(),
                'data' => null
            ], 200);
        }

        
        return response()->json([
            'code' => 200,
            'message' => 'Aset berhasil disimpan',
            'data' => array('idalat_digunakan' => $idalat_digunakan)
        ], 200);

    }

    public function cek_simpan_aset(Request $req){
        $validated = $req->validate([
            'idpenelitian' => 'required|integer',
            'kode_barang_aset' => 'required|string',
            'idwaktu' => 'required|integer',
            'jenis_pemakaian' => 'required|string'
        ]);

        try {
            return Cache::lock('lock:cek_simpan_aset_global', 10)
                ->block(5, function () use ($validated) {

                    $idpenelitian = $validated['idpenelitian'];
                    $kode_barang_aset = $validated['kode_barang_aset'];
                    $idwaktu = $validated['idwaktu'];
                    $jenis_pemakaian = $validated['jenis_pemakaian'];

                    return DB::transaction(function () use ($idpenelitian, $kode_barang_aset, $idwaktu, $jenis_pemakaian) {

                        $cek = DB::table('alat_digunakan')
                            ->where('id_kegiatan', $idpenelitian)
                            ->where('kode_barang_aset', $kode_barang_aset)
                            ->where('jenis_pemakaian', $jenis_pemakaian)
                            ->where('idwaktu', $idwaktu)
                            ->count();

                        if ($cek > 0) {
                            return response()->json([
                                'code' => 400,
                                'message' => $kode_barang_aset . ' sudah ada',
                                'data' => null
                            ], 200);
                        }

                        $idalat_digunakan = DB::table('alat_digunakan')
                            ->insertGetId([
                                'id_kegiatan' => $idpenelitian,
                                'kode_barang_aset' => $kode_barang_aset,
                                'jenis_pemakaian' => $jenis_pemakaian,
                                'idwaktu' => $idwaktu,
                            ], 'idalat_digunakan');

                        return response()->json([
                            'code' => 200,
                            'message' => 'Aset berhasil disimpan',
                            'data' => ['idalat_digunakan' => $idalat_digunakan]
                        ], 200);
                    });
                });

        } catch (LockTimeoutException $e) {
            return response()->json([
                'code' => 409,
                'message' => 'Sedang ada proses reservasi lain yang berjalan. Silakan coba lagi.',
                'data' => null
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Gagal menyimpan aset: ' . $e->getMessage(),
                'data' => null
            ], 200);
        }
    }


    

    public function cek_hapus_aset(Request $req){
        $validated = $req->validate([
            'idalat_digunakan' => 'required|integer'
        ]);

        $idalat_digunakan = $validated['idalat_digunakan'];

        try {
            DB::table('alat_digunakan')
                ->where('idalat_digunakan', $idalat_digunakan)
                ->delete();
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Gagal menghapus aset: ' . $e->getMessage(),
                'data' => null
            ], 200);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Aset berhasil dihapus',
            'data' => null
        ], 200);
    }

    public function cek_simpan_waktu(Request $req){
        $validated = $req->validate([
            'idpenelitian' => 'required|integer',
            'tanggal' => 'required|date_format:d/m/Y',
            'jam_mulai' => 'required',
            'jam_akhir' => 'required'
        ]);

        $idpenelitian = $validated['idpenelitian'];
        $tanggal = Carbon::createFromFormat('d/m/Y', $validated['tanggal'])->format('Y-m-d');
        $jam_mulai = $validated['jam_mulai'];
        $jam_akhir = $validated['jam_akhir'];

        $cek = DB::table('waktu as w')
                ->where('w.tanggal', $tanggal)
                ->where('w.waktu_mulai', '<', $jam_akhir)
                ->where('w.waktu_akhir', '>', $jam_mulai)
                ->where('w.id_kegiatan', '=', $idpenelitian)
                ->where('w.tipe_pemakaian', '3') // tipe kegiatan 3 untuk penelitian
                ->count();

        if($cek > 0){
            return response()->json([
                'code' => 409,
                'message' => $tanggal.' '.$jam_mulai.'-'.$jam_akhir.' bentrok dengan jadwal lain',
                'data' => null
            ], 200);
        }

        $arr_insert = array(
            'id_kegiatan' => $idpenelitian,
            'tanggal' => $tanggal,
            'waktu_mulai' => $jam_mulai,
            'waktu_akhir' => $jam_akhir,
            'tipe_pemakaian' => '3', // tipe kegiatan 3 untuk penelitian
            'is_deleted' => 'false',
            'created_by' => session('userdata')['iduser'],
            'created_at' => Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s')
        );

        try {
            $idwaktu = DB::table('waktu')->insertGetId($arr_insert, 'idwaktu');
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Gagal menyimpan waktu: ' . $e->getMessage(),
                'data' => null
            ], 200);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Waktu berhasil disimpan',
            'data' => array('idwaktu' => $idwaktu)
        ], 200);

    }

    public function edit_tujuan_instrumen(Request $req){
        $validated = $req->validate([
            'idalat_digunakan' => 'required|integer',
            'tujuan' => 'required|string'
        ]);

        $idalat_digunakan = $validated['idalat_digunakan'];
        $tujuan = $validated['tujuan'];

        try {
            DB::table('alat_digunakan')
                ->where('idalat_digunakan', $idalat_digunakan)
                ->update(['tujuan' => $tujuan]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Gagal memperbarui tujuan: ' . $e->getMessage(),
                'data' => null
            ], 200);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Tujuan berhasil diperbarui',
            'data' => null
        ], 200);
    }

    public function cek_hapus_ruangan(Request $req){
        $validated = $req->validate([
            'idpenelitian' => 'required|integer',
            'idruang' => 'required|integer'
        ]);

        $idpenelitian = $validated['idpenelitian'];
        $idruang = $validated['idruang'];

        $idalat_digunakan = DB::table('alat_digunakan as ad')
                                ->join('aset as a', 'ad.kode_barang_aset', 'a.kode_barang_aset')
                                ->where('ad.jenis_pemakaian', '3') // tipe kegiatan 3 untuk penelitian
                                ->where('a.idruang', $idruang)
                                ->where('ad.id_kegiatan', $idpenelitian)
                                ->pluck('idalat_digunakan')
                                ->toArray();

        $whereIn = array();

        foreach($idalat_digunakan as $id){
            $whereIn[] = $id;
        }
                            

        try {
            DB::beginTransaction();

            DB::table('alat_digunakan')
                ->whereIn('idalat_digunakan', $whereIn)
                ->delete();

            DB::table('lab_penelitian')
                ->where('idpenelitian', $idpenelitian)
                ->where('idruang', $idruang)
                ->delete();

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 500,
                'message' => 'Gagal menghapus ruangan: ' . $e->getMessage(),
                'data' => null
            ], 200);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Ruangan berhasil dihapus',
            'data' => null
        ], 200);
    }

    public function update_topik(Request $req){
        $validated = $req->validate([
            'idpenelitian' => 'required|integer',
            'topik' => 'required|string'
        ]);

        $idpenelitian = $validated['idpenelitian'];
        $topik = $validated['topik'];

        try {
            DB::table('penelitian')
                ->where('idpenelitian', $idpenelitian)
                ->update(['topik' => $topik]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Gagal memperbarui topik: ' . $e->getMessage(),
                'data' => null
            ], 200);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Topik berhasil diperbarui',
            'data' => null
        ], 200);
    }

    public function edit_jadwal(Request $req){
        $validated = $req->validate([
            'idwaktu' => 'required|integer',
            'tanggal' => 'required|date_format:d/m/Y',
            'jam_mulai' => 'required',
            'jam_akhir' => 'required',
            'idpenelitian' => 'required|integer'
        ]);

        $idwaktu = $validated['idwaktu'];
        $tanggal = Carbon::createFromFormat('d/m/Y', $validated['tanggal'])->format('Y-m-d');
        $jam_mulai = $validated['jam_mulai'];
        $jam_akhir = $validated['jam_akhir'];
        $idpenelitian = $validated['idpenelitian'];

        $cek = DB::table('waktu as w')
                ->where('w.tanggal', $tanggal)
                ->where('w.waktu_mulai', '<', $jam_akhir)
                ->where('w.waktu_akhir', '>', $jam_mulai)
                ->where('w.id_kegiatan', '=', $idpenelitian)
                ->where('w.tipe_pemakaian', '3') // tipe kegiatan 3 untuk penelitian
                ->where('w.idwaktu', '!=', $idwaktu) // kecualikan jadwal yang sedang diedit
                ->count();

        if($cek > 0){
            return response()->json([
                'code' => 409,
                'message' => $tanggal.' '.$jam_mulai.'-'.$jam_akhir.' bentrok dengan jadwal lain',
                'data' => null
            ], 200);
        }

        try {
            DB::table('waktu')
                ->where('idwaktu', $idwaktu)
                ->update([
                    'tanggal' => $tanggal,
                    'waktu_mulai' => $jam_mulai,
                    'waktu_akhir' => $jam_akhir
                ]);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Gagal memperbarui jadwal: ' . $e->getMessage(),
                'data' => null
            ], 200);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Jadwal berhasil diperbarui',
            'data' => null
        ], 200);
    }

    public function hapus_jadwal(Request $req){
        $validated = $req->validate([
            'idwaktu' => 'required|integer'
        ]);

        $idwaktu = $validated['idwaktu'];

        try {
            DB::beginTransaction();

            DB::table('alat_digunakan')
                ->where('idwaktu', $idwaktu)
                ->delete();

            DB::table('waktu')
                ->where('idwaktu', $idwaktu)
                ->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'code' => 500,
                'message' => 'Gagal menghapus jadwal: ' . $e->getMessage(),
                'data' => null
            ], 200);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Jadwal berhasil dihapus',
            'data' => null
        ], 200);
    }

    public function update_ajuan(Request $req){
        // dd($req->all());

        $validated = $req->validate([
            'idpenelitian' => 'required|integer',
            'status_ajuan' => 'required|integer'
        ]);

        $idpenelitian = $validated['idpenelitian'];
        $status_ajuan = $validated['status_ajuan'];

        $arr_upsert = array();
        if(isset($req->default)){
            foreach($req->default as $key => $value){
                $arr_upsert[] = array(
                    'idisi_form_penelitian' => $key,
                    'isi' => $value
                );
            }
        }

        // dd($arr_upsert);

        $arr_insert_bahan = array();
        $arr_update_bahan = array();

        if(isset($req->id_bahan)){
            foreach($req->id_bahan as $key => $value){
                if($value == 0){
                    $arr_insert_bahan[] = array(
                        'idpenelitian' => $idpenelitian,
                        'nama_bahan' => $req->nama_bahan[$key],
                        'jumlah' => $req->jumlah_bahan[$key],
                        'spesifikasi' => $req->spesifikasi_bahan[$key],
                        'idsatuan' => $req->satuan_bahan[$key],
                        'jenis_data' => '1'
                    );
                }
                else{
                    $arr_update_bahan[] = array(
                        'idajuan_bahan' => $value,
                        'idpenelitian' => $idpenelitian,
                        'nama_bahan' => $req->nama_bahan[$key],
                        'jumlah' => $req->jumlah_bahan[$key],
                        'spesifikasi' => $req->spesifikasi_bahan[$key],
                        'idsatuan' => $req->satuan_bahan[$key],
                        'jenis_data' => '1'
                    );
                }
                
            }
        }

        // dd($arr_insert_bahan, $arr_update_bahan);

        try {
            DB::beginTransaction();

                if(!empty($arr_upsert)){
                    DB::table('isi_form_penelitian')->upsert($arr_upsert, ['idisi_form_penelitian'], ['isi']);
                }
                

                if(!empty($arr_update_bahan)){
                    DB::table('ajuan_bahan')->upsert($arr_update_bahan, ['idajuan_bahan'], ['nama_bahan', 'jumlah', 'spesifikasi', 'idsatuan']);
                }

                if(!empty($arr_insert_bahan)){
                    // dd($arr_insert_bahan);
                    DB::table('ajuan_bahan')->insert($arr_insert_bahan);
                }

                DB::table('penelitian')
                    ->where('idpenelitian', $idpenelitian)
                    ->update(['status_ajuan' => $status_ajuan]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('status', [
                'code' => 500,
                'status' => 'danger',
                'message' => 'Gagal memperbarui ajuan: ' . $e->getMessage()
            ]);
        }

        if($status_ajuan == 1){
            return back()->with('status', [
                'code' => 200,
                'status' => 'success',
                'message' => 'Ajuan berhasil diperbarui'
            ]);
        }
        else{
            return redirect()->route('penelitian_mhs_index')->with('status', [
                'code' => 200,
                'status' => 'success',
                'message' => 'Ajuan berhasil diperbarui'
            ]);
        }

        
    }

    public function hapus_bahan(Request $req){
        $validated = $req->validate([
            'idbahan' => 'required|integer'
        ]);

        $idbahan = $validated['idbahan'];

        try {
            DB::table('ajuan_bahan')
                ->where('idajuan_bahan', $idbahan)
                ->delete();
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Gagal menghapus bahan: ' . $e->getMessage(),
                'data' => null
            ], 200);
        }

        return response()->json([
            'code' => 200,
            'message' => 'Bahan berhasil dihapus',
            'data' => null
        ], 200);
    }
}
