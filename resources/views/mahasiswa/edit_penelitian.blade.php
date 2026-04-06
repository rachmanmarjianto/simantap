@extends('layout_home')

@section('title', 'Ajuan Penelitian')

@section('page-css')
    <link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('app-assets') }}/vendor/summernote/summernote.css" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.4/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-lg-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Edit Ajuan Penelitian</h4>
                    </div>
                </div>
                <div class="col-lg-6 p-md-0 justify-content-lg-end mt-2 mt-lg-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('form_maintenance_index') }}">Form</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Ajuan Penelitian</a></li>
                        
                    </ol>
                </div>
            </div>
            <!-- row -->

             @if(session('status'))
                <div class="alert alert-{{ session('status')['status'] }} solid alert-dismissible fade show">
                    <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
                    {{ session('status')['message'] }}
                </div>
            @endif
            <div class="row">
                
                <div class="col-12">
                    <div class="card">                       
                        <div class="card-header" >
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="flex-grow-1">
                                    
                                </div>
                                <div>
                                    <button class="btn btn-warning" onclick="window.location='{{ route('penelitian_mhs_index') }}'">Kembali</button>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <h2 style="text-align: center">FORM PELAKSANAAN PENELITIAN {{ $penelitian[0]->internal == 1 ? 'INTERNAL' : 'EKSTERNAL' }}</h2>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Status Ajuan</label>
                                <div class="col-lg-9">
                                    @php
                                        if($penelitian[0]->status_ajuan == 1){
                                            $status_ajuan_text = 'Draft';
                                            $warna = 'orange';
                                        }
                                        elseif($penelitian[0]->status_ajuan == 2){
                                            $status_ajuan_text = 'menungu verifikasi dosen pembimbing';
                                            $warna = 'blue';
                                        }
                                        elseif($penelitian[0]->status_ajuan == 3){
                                            $status_ajuan_text = 'menunggu verifikasi PJ Ruang';
                                            $warna = 'blue';
                                        }
                                        elseif($penelitian[0]->status_ajuan == 4){
                                            $status_ajuan_text = 'Diizinkan';
                                            $warna = 'green';
                                        }
                                        elseif($penelitian[0]->status_ajuan == 5){
                                            $status_ajuan_text = 'Ditolak';
                                            $warna = 'red';
                                        }
                                        elseif($penelitian[0]->status_ajuan == 6){
                                            $status_ajuan_text = 'Dibatalkan';
                                            $warna = 'grey';
                                        }
                                        else{
                                            $status_ajuan_text = '-';
                                            $warna = 'black';
                                        }

                                    @endphp
                                    <span class="form-control" style="color:{{ $warna }}" >{{ $status_ajuan_text }}</span>
                                </div>
                                <div class="col-lg-1" id="loader_status_ajuan">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Topik Penelitian</label>
                                <div class="col-lg-9">
                                    <input type="text" class="form-control" value="{{ $penelitian[0]->topik }}" form="form_update_ajuan" onkeyup="updateTopik(this)" required>
                                </div>
                                <div class="col-lg-1" id="loader_topik">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">NIP Dosen Pembimbing</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="main_nip_dosen_pembimbing" name="nip_dosen_pembimbing" value="{{ $penelitian[0]->nipnik_dosen_pembimbing }}" form="form_update_ajuan" readonly required>
                                </div>
                                <div class="col-lg-2">
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Nama Dosen Pembimbing</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="main_dosen_pembimbing" name="dosen_pembimbing" value="{{ $penelitian[0]->nama_dosen_pembimbing }}" form="form_update_ajuan" readonly required>
                                </div>
                                <div class="col-lg-2">
                                    <button class="btn btn-sm btn-primary" onclick="bukamdl_pilih_dosen()">Pilih Dosen</button>
                                </div>
                            </div>

                            <div class="form-group col-md-12" style="padding-left:0px; padding-right:0px">
                                <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                                    <div class="flex-grow-1">
                                        <b>Ruangan</b>
                                    </div>
                                    <div>
                                        <button class="btn btn-sm btn-primary" onclick="bukamdl_pilih_ruangan()">Pilih Ruangan</button>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    
                                    <div class="table-responsive">
                                        <table id="tabel_ruangan_lab" class="table table-bordered verticle-middle table-responsive-sm" width="100%">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Ruangan Lab</th>
                                                    <th scope="col">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($lab_terpilih as $lab)
                                                    <tr>
                                                        <td>
                                                            {{ $lab->nama_ruang }} # {{ $lab->nama_gedung }} 
                                                            <input type="hidden" form="form_update_ajuan" name="idruang[]" value="{{ $lab->idruang }}">
                                                        </td>
                                                        <td><button class="btn btn-sm btn-danger" onclick="hapusRuangan(this, {{ $penelitian[0]->idpenelitian }}, {{ $lab->idruang }})">Hapus</button></td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            @foreach($layout as $el)
                                @if($el['jenis_isi'] == 1)
                                    {{-- <div class="card-body"> --}}
                                    <div class="form-group row">
                                        <div class="col-lg-12">
                                            <h5 style="font-weight: bold;">{{ $el['nilai_tampil'] }}</h5>
                                        </div>
                                    </div>
                                    

                                    @if(count($el['children']) > 0)
                                        @php
                                            $flag = 1;
                                        @endphp
                                        @foreach($el['children'] as $child)
                                            @if($child['jenis_isi'] == 2)
                                                <div class="form-group row">
                                                    <label class="col-lg-2 col-form-label">{{ $child['nilai_tampil'] }}</label>
                                                    <div class="col-lg-10">
                                                        <input type="text" name="default[{{ $child['idisi_form_penelitian'] }}]" class="form-control" value="{{ $child['nilai'] }}" form="form_update_ajuan" required>
                                                    </div>
                                                </div>
                                            
                                            @elseif($child['jenis_isi'] == 3)
                                                <div class="form-group row">
                                                    <div class="col-lg-12">
                                                        <textarea  class="summernote" name="default[{{ $child['idisi_form_penelitian'] }}]" form="form_update_ajuan" required> {{ $child['nilai'] }} </textarea>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    {{-- </div> --}}
                                
                                @elseif($el['jenis_isi'] == 2)
                                    {{-- <div class="card-body"> --}}
                                        <div class="form-group row">
                                            <label class="col-lg-2 col-form-label">{{ $el['nilai_tampil'] }}</label>
                                            <div class="col-lg-10">
                                                <input type="text" name="default[{{ $el['idisi_form_penelitian'] }}]" class="form-control" value="{{ $el['nilai'] }}" form="form_update_ajuan" required>
                                            </div>
                                        </div>
                                    {{-- </div> --}}
                                @endif
                            @endforeach


                            <div class="form-group col-md-12" style="padding-left:0px; padding-right:0px">
                                
                                <div class="d-flex justify-content-between align-items-center w-100 mb-2">
                                    <div class="flex-grow-1">
                                        <b>Tanggal dan Waktu Beserta Instrumen</b>
                                        <button class="btn btn-sm btn-primary" onclick="range_tanggal()" style="margin-left:10px; " id="btn_range_tanggal">Range Tanggal</button>
                                    </div>
                                    <div>                                        
                                        <span style="float:right; color:green; cursor:pointer; font-size:20px;" onclick="tambahWaktu()" id="btn_tambah_waktu" ><i class="fa fa-plus-circle"></i></span>     
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    {{-- <div class="accordion accordion-left-indicator accordion-bordered" id="accordionWaktuInstrumen">
                                        
                                        <div class="accordion__item">
                                            <div class="accordion__header collapsed" data-toggle="collapse" data-target="#left-indicator_collapseOne">
                                                <span class="accordion__header--text">Jadwal 12/03/2026 15:00 - 17:00</span>
                                                <span class="accordion__header--indicator"></span>
                                            </div>
                                            <div id="left-indicator_collapseOne" class="collapse accordion__body" data-parent="#accordionWaktuInstrumen">
                                                <div class="accordion__body--text">
                                                    <div class="row mb-3">
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <button type="button" class="btn btn-sm btn-info">Edit Jadwal</button>
                                                            <button type="button" class="btn btn-sm btn-danger">Hapus Jadwal</button>
                                                            
                                                        </div>
                                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                                            <button type="button" class="btn btn-sm btn-success">Tambah Instrumen</button>
                                                        </div>
                                                    </div>
                                                    <b>Instrumen yang digunakan:</b>
                                                    <table id="tabel_instrumen_terpilih_000" class="table table-bordered verticle-middle table-responsive-sm" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th scope="col">Kode Barang</th>
                                                                <th scope="col">Nama Barang</th>
                                                                <th scope="col">Tujuan</th>
                                                                <th scope="col">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>KB001</td>
                                                                <td>Microscope</td>
                                                                <td>Untuk mengamati sampel <br><code style="cursor:pointer">Edit</code></td>
                                                                <td><button class="btn btn-sm btn-danger">Hapus</button></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div> --}}

                                    @foreach($waktu_ajuan as $waktu)
                                        <div class="accordion accordion-left-indicator accordion-bordered" id="accordionWaktuInstrumen_{{ $waktu->idwaktu }}">
                                            <div class="accordion__item">
                                                <div class="accordion__header collapsed" data-toggle="collapse" data-target="#left-indicator_collapseOne_{{ $waktu->idwaktu }}">
                                                    <span class="accordion__header--text">Jadwal {{ $waktu->tanggal }} {{ $waktu->waktu_mulai }} - {{ $waktu->waktu_akhir }} # <span id="jumlah_instrumen_{{ $waktu->idwaktu }}">{{ count($alat_digunakan_kodebarang[$waktu->idwaktu] ?? []) }}</span> Instrumen</span>
                                                    <span class="accordion__header--indicator"></span>
                                                    <input type="hidden" value="{{ $waktu->idwaktu }}" id="idwaktu_{{ $waktu->idwaktu }}">
                                                </div>
                                                <div id="left-indicator_collapseOne_{{ $waktu->idwaktu }}" class="collapse accordion__body" data-parent="#accordionWaktuInstrumen_{{ $waktu->idwaktu }}">
                                                    <div class="accordion__body--text">
                                                        <div class="row mb-3">
                                                            <div class="col-lg-3 col-md-4 col-sm-6">
                                                                <button type="button" class="btn btn-sm btn-info" onclick="edit_jadwal({{ $waktu->idwaktu }})">Edit Jadwal</button>
                                                                <button type="button" class="btn btn-sm btn-danger" onclick="hapus_jadwal(this, {{ $waktu->idwaktu }})">Hapus Jadwal</button>
                                                                
                                                            </div>
                                                            <div class="col-lg-5 col-md-6 col-sm-6">
                                                                <button type="button" class="btn btn-sm btn-success" onclick="tambah_instrumen({{ $waktu->idwaktu }})">Tambah Instrumen</button>
                                                                <button type="button" class="btn btn-sm btn-warning" onclick="copy_instrumen({{ $waktu->idwaktu }})">Copy Instrumen</button>
                                                            </div>
                                                        </div>
                                                        <b>Instrumen yang digunakan:</b>
                                                        <table id="tabel_instrumen_terpilih_{{ $waktu->idwaktu }}" class="table table-bordered verticle-middle table-responsive-sm" width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th scope="col">Kode Barang</th>
                                                                    <th scope="col">Nama Barang</th>
                                                                    <th scope="col">Tujuan</th>
                                                                    <th scope="col">Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach($alat_digunakan_kodebarang[$waktu->idwaktu] ?? [] as $alat)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $alat->kode_barang_aset }} 
                                                                            <input type="hidden" form="form_update_ajuan" name="aset[]" value="{{ $alat->kode_barang_aset }}">
                                                                            <input type="hidden" form="form_update_ajuan" name="idalat_digunakan[]" value="{{ $alat->idalat_digunakan }}">
                                                                        </td>
                                                                        <td>
                                                                            {{ $alat->nama_barang }} <br>
                                                                            {{ $alat->merk_barang }} <br>
                                                                            {{ $alat->keterangan }} <br>
                                                                            tahun {{ $alat->tahun_aset }} 
                                                                        </td>
                                                                        <td>
                                                                            <span id="tujuan_aset_{{ $alat->idalat_digunakan }}">{{ $alat->tujuan }}</span> <br>
                                                                            <code onclick="editTujuanInstrumen({{ $alat->idalat_digunakan }})" style="cursor:pointer">Edit</code>
                                                                        </td>
                                                                        <td><button class="btn btn-sm btn-danger" onclick="hapusAset(this, {{ $alat->idalat_digunakan }}, {{ $alat->idwaktu }})">Hapus</button></td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            

                            <div class="form-group col-md-12" style="padding-left:0px; padding-right:0px">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <div class="flex-grow-1">
                                        <b>Bahan padat / cair yang diajukan</b>
                                    </div>
                                    <div>
                                        <span style="float:right; color:green; cursor:pointer; font-size:20px" onclick="tambahBahan()"><i class="fa fa-plus-circle"></i></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    
                                    <div class="table-responsive">
                                        <table id="tabel_bahan_terpilih" class="table table-bordered verticle-middle table-responsive-sm" width="100%">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Nama Bahan</th>
                                                    <th scope="col">Spisifikasi</th>
                                                    <th scope="col">Jumlah</th>
                                                    <th scope="col">Satuan</th>
                                                    <th scope="col">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($ajuan_bahan as $index => $bahan)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <input type="text" name="nama_bahan[]" class="form-control" form="form_update_ajuan" value="{{ $bahan->nama_bahan }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="spesifikasi_bahan[]" class="form-control" form="form_update_ajuan" value="{{ $bahan->spesifikasi }}" required>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="jumlah_bahan[]" class="form-control" form="form_update_ajuan" value="{{ $bahan->jumlah }}" required>
                                                            <input type="hidden" name="id_bahan[]" form="form_update_ajuan" value="{{ $bahan->idajuan_bahan }}">
                                                        </td>
                                                        <td>
                                                            <select name="satuan_bahan[]" class="form-control" form="form_update_ajuan" required>
                                                                <option value="">Pilih Satuan</option>
                                                                @foreach(json_decode($satuan) as $satuan_option)
                                                                    <option value="{{ $satuan_option->id }}" {{ $bahan->idsatuan == $satuan_option->id ? 'selected' : '' }}>{{ $satuan_option->nm_satuan }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><button type="button" class="btn btn-sm btn-danger" onclick="hapusBahan(this, {{ $bahan->idajuan_bahan }})">Hapus</button></td>
                                                    </tr>
                                                @endforeach                                        
                                            </tbody>
                                        </table>
                                    </div>


                                </div>
                            </div>

                            

                            <div class="form-group col-md-12" style="padding-left:0px; padding-right:0px">
                                <div class="d-flex justify-content-between">
                                    <b>Syarat Ajuan Pelaksanaan Penelitian</b>
                                    
                                </div>
                                <table class="table table-bordered verticle-middle table-responsive-sm" width="100%" id="tbl_syarat_penelitian">
                                    <thead>
                                        <tr>
                                            <th scope="col">Syarat</th>
                                            <th scope="col">Dokumen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($syarat_penelitian as $syarat)
                                            <tr id="syarat_row_{{ $syarat->idfile_ajuan_penelitian }}">
                                                <td>{{ $syarat->nama_syarat }}</td>
                                                <td id="btn_status_syarat_{{ $syarat->idfile_ajuan_penelitian }}">
                                                    @if(!$syarat->nama_file)
                                                        <button class="btn btn-sm btn-primary" onclick="upload_dokumen({{ $syarat->idfile_ajuan_penelitian }})">Upload Dokumen</button>
                                                    @else
                                                        <a href="{{ route('penelitian_mhs_download_dokumen', ['id' => Crypt::encrypt($syarat->idfile_ajuan_penelitian)]) }}" class="btn btn-sm btn-success" target="_blank">Lihat Dokumen</a>
                                                        <button class="btn btn-sm btn-danger" onclick="hapus_dokumen(this, {{ $syarat->idfile_ajuan_penelitian }}, '{{ Crypt::encrypt($syarat->idfile_ajuan_penelitian) }}')">Hapus Dokumen</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach                                        
                                    </tbody>
                                </table>
                            </div>
                            
                        </div>
                        <div class="card-footer" >
                            <div class="row">
                                <div class="col-lg-4" style="text-align: left;">
                                    <button type="button" class="btn btn-danger" onclick="submitForm(this, 6)">Batalkan Ajuan</button>
                                </div>
                                <div class="col-lg-8" style="text-align: right;">
                                    <button type="button" class="btn btn-primary" onclick="submitForm(this, 1)">Simpan Sebagai Draft</button>
                                    <button type="button" class="btn btn-success" onclick="submitForm(this, 3)">Ajukan Permohonan</button>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mdl_edit_tujuan_instrumen">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tujuan Pemakaian Instrumen</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <textarea class="form-control" rows="4" id="mdl_edit_tujuan_instrumen_isi_tujuan"></textarea>
                    <input type="hidden" id="mdl_edit_tujuan_instrumen_idalat_digunakan">
                </div>
                <div class="modal-footer" id="mdl_edit_tujuan_instrumen_button">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="submitEditTujuanInstrumen()">Edit</button>
                </div>
                <div class="modal-footer" id="mdl_edit_tujuan_instrumen_button_loader" style="display:none;">
                    <button type="button" class="btn btn-secondary" disabled>Close</button>
                    <button type="button" class="btn btn-success" disabled>Proses...</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mdl_copy_instrumen">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mau Copy Dari Jadwal Yang mana?</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered verticle-middle table-responsive-sm" width="100%" id="tbl_copy_instrumen">
                            <tbody>
                                                                                   
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="mdl_upload_dokumen">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Dokumen</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- <form id="form_upload_dokumen" method="POST" action="{{ route('penelitian_mhs_upload_dokumen') }}" enctype="multipart/form-data"> --}}
                    <form id="form_upload_dokumen">
                        <input type="hidden" name="idfile_ajuan_penelitian" id="upload_idfile_ajuan_penelitian">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama File</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="mdl_upload_dokumen_nama_file" name="nama_file" placeholder="Nama File" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">File Dokumen</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control" id="mdl_upload_dokumen_file" name="file_dokumen" required>
                                <span style="font-size: 12px; color: red" id="mdl_loader_upload_dokumen"></span>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer" id="mdl_upload_dokumen_button">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="submituploadDokumen()">Upload</button>
                </div>
                <div class="modal-footer" id="mdl_upload_dokumen_button_loader" style="display:none;">
                    <button type="button" class="btn btn-secondary" disabled>Close</button>
                    <button type="button" class="btn btn-success" disabled>Proses...</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="mdl_satu_tanggal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Tanggal dan Waktu</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Tanggal</label>
                            <input type="text" class="form-control" placeholder="DD/MM/YYYY" id="mdl_satu_tanggal_awal">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Waktu Awal</label>
                            <input type="text" class="form-control" placeholder="HH:MM" id="mdl_satu_waktu_awal">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Waktu Akhir</label>
                            <input type="text" class="form-control" placeholder="HH:MM" id="mdl_satu_waktu_akhir">
                        </div>
                    </div>
                    <input type="hidden" id="mdl_satu_idwaktu">
                </div>
                <div class="modal-footer" id="mdl_satu_waktu_button">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="ex_edit_jadwal()">Proses</button>
                </div>
                <div class="modal-footer" id="mdl_satu_waktu_button_loader" style="display:none;">
                    <button type="button" class="btn btn-secondary" disabled>Close</button>
                    <button type="button" class="btn btn-success" disabled>Proses...</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" id="mdl_range_tanggal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Range Tanggal dan Waktu</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Tanggal Awal</label>
                            <input type="text" class="form-control" placeholder="DD/MM/YYYY" id="mdl_range_tanggal_awal">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Tanggal Akhir</label>
                            <input type="text" class="form-control" placeholder="DD/MM/YYYY" id="mdl_range_tanggal_akhir">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label>Waktu Awal</label>
                            <input type="text" class="form-control" placeholder="HH:MM" id="mdl_range_waktu_awal">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Waktu Akhir</label>
                            <input type="text" class="form-control" placeholder="HH:MM" id="mdl_range_waktu_akhir">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="mdl_pilih_waktu_button">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="pick_waktu()">Proses</button>
                </div>
                <div class="modal-footer" id="mdl_pilih_waktu_button_loader" style="display:none;">
                    <button type="button" class="btn btn-secondary" disabled>Close</button>
                    <button type="button" class="btn btn-success" disabled>Proses...</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-xl" id="mdl_pilih_aset">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Perangkat Lab</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-responsive-sm" width="100%" id="tbl_aset_lab">
                            <thead>
                                <tr>
                                    <th scope="col"><input type="checkbox" id="checkAll"></th>
                                    <th scope="col">Kode Barang</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Lab</th>
                                    <th scope="col">Tahun Aset</th>
                                    <th scope="col">Kondisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                                                                        
                            </tbody>
                        </table>
                    </div>
                    <input type="hidden" id="mdl_pilih_aset_idwaktu">
                </div>
                <div class="modal-footer" id="mdl_pilih_aset_button">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="pilih_instrumen()">Pilih Instrumen</button>
                </div>
                <div class="modal-footer" id="mdl_pilih_aset_button_loader" style="display:none;">
                    <button type="button" class="btn btn-secondary" disabled>Close</button>
                    <button type="button" class="btn btn-primary" disabled>Proses...</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="mdl_pilih_ruangan">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Ruangan</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Ruang Lab</label>
                        <select class="form-control" id="mdl_pilih_ruangan_id" name="idruang" onchange="get_data_aset()">
                            @foreach($ruang_lab as $rg)
                                <option value="{{ $rg->id }}">{{ $rg->nama_ruang }} # {{ $rg->nama_gedung }}</option>
                            @endforeach
                        </select>
                        <span style="font-size: 12px; color: red" id="mdl_loader_cari_ruangan"></span>
                    </div>

                    <div class="table-responsive">
                        <span style="font-size: 12px; color: red" id="mdl_loader_data_aset">Proses tarik data...</span>
                        <table class="table table-bordered verticle-middle table-responsive-sm" width="100%" id="tbl_aset_ruangan">
                            <thead>
                                <tr>
                                    <th scope="col">Kode Barang</th>
                                    <th scope="col">Nama Barang</th>
                                    <th scope="col">Tahun Aset</th>
                                    <th scope="col">Kondisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                                                                        
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer" id="mdl_pilih_ruangan_button">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="pick_ruangan()">Pilih Ruangan</button>
                </div>
                <div class="modal-footer" id="mdl_pilih_ruangan_button_loader" style="display:none;">
                    <button type="button" class="btn btn-secondary" disabled>Close</button>
                    <button type="button" class="btn btn-primary" disabled>Proses...</button>
                </div>
            </div>
        </div>
    </div>  

    <div class="modal fade bd-example-modal-lg" id="mdl_pilih_dosen">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pilih Dosen Pembimbing</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">NIP Dosen</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" id="mdl_pilih_dosen_nip" placeholder="Masukkan NIP Dosen">
                            <span style="font-size: 12px; color: red" id="mdl_loader_cari"></span>
                        </div>
                        <div class="col-lg-2">
                            <button class="btn btn-primary" onclick="cari_dosen()">Cari</button>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Nama Dosen</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="mdl_pilih_dosen_nama" readonly>
                            <input type="hidden" id="mdl_pilih_dosen_id">
                            <input type="hidden" id="mdl_pilih_dosen_nipnik">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Gelar Depan</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="mdl_pilih_dosen_gelar_depan" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Gelar Belakang</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="mdl_pilih_dosen_gelar_belakang" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-lg-2 col-form-label">Fakultas</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="mdl_pilih_dosen_fakultas" readonly>
                            <input type="hidden" id="mdl_pilih_dosen_id_unit_kerja">
                            <input type="hidden" id="mdl_pilih_dosen_idprogram_studi">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" id="mdl_pilih_dosen_button">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="pick_dosen()">Pilih Dosen</button>
                </div>
                <div class="modal-footer" id="mdl_pilih_dosen_button_loader" style="display:none;">
                    <button type="button" class="btn btn-secondary" disabled>Close</button>
                    <button type="button" class="btn btn-primary" disabled>Proses...</button>
                </div>
            </div>
        </div>
    </div>

    <form id="form_update_ajuan" method="POST" action="{{ route('penelitian_mhs_update_ajuan') }}">
        @csrf
        <input type="hidden" name="idpenelitian" value="{{ $penelitian[0]->idpenelitian }}">
        <input type="hidden" name="status_ajuan" id="status_ajuan">
    </form>
@endsection

@section('javascript')

    <script src="{{ asset('app-assets') }}/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendor/summernote/js/summernote.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.4/dist/sweetalert2.all.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.30.1/moment.min.js"></script>
    
    
    <script>    
    
        var aset_pilihan = [];
        var satuan_bahan = @json($satuan);
        var route_download_dokumen = "{{ route('penelitian_mhs_download_dokumen', ['id' => 'idfile_placeholder']) }}";
        var route_hapus_dokumen = "{{ route('penelitian_mhs_hapus_dokumen', ['id' => 'idfile_placeholder']) }}";
        // var alat_digunakan_terpilih = @json($alat_digunakan_kodebarang);
        var aset_lab_help = @json($alat_lab_pilihan);
        var timer_edit_topik;

        window.onload = function() {
            flatpickr("#mdl_range_tanggal_awal", {
                dateFormat: 'd/m/Y',
                allowInput: true
            });

            flatpickr("#mdl_range_tanggal_akhir", {
                dateFormat: 'd/m/Y',
                allowInput: true
            });

            flatpickr("#mdl_range_waktu_awal", {
                enableTime: true,
                noCalendar: true,
                dateFormat: 'H:i',
                time_24hr: true,
                allowInput: true
            });

            flatpickr("#mdl_range_waktu_akhir", {
                enableTime: true,
                noCalendar: true,
                dateFormat: 'H:i',
                time_24hr: true,
                allowInput: true
            });

            flatpickr("#mdl_satu_tanggal_awal", {
                dateFormat: 'd/m/Y',
                allowInput: true
            });

            flatpickr("#mdl_satu_waktu_awal", {
                enableTime: true,
                noCalendar: true,
                dateFormat: 'H:i',
                time_24hr: true,
                allowInput: true
            });

            flatpickr("#mdl_satu_waktu_akhir", {
                enableTime: true,
                noCalendar: true,
                dateFormat: 'H:i',
                time_24hr: true,
                allowInput: true
            });

           
            $('#tabel_waktu_terpilih tbody tr').each(function(i, el){
                var row_num = i + 1;
                flatpickr('#row' + row_num + ' .datepicker-tanggal', {
                    dateFormat: 'd/m/Y',
                    allowInput: true
                });
                flatpickr('#row' + row_num + ' .timepicker-waktu-mulai', {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: 'H:i',
                    time_24hr: true,
                    allowInput: true
                });
                flatpickr('#row' + row_num + ' .timepicker-waktu-akhir', {
                    enableTime: true,
                    noCalendar: true,
                    dateFormat: 'H:i',
                    time_24hr: true,
                    allowInput: true
                });
            });

            $.each(aset_lab_help, function(i, el){
                aset_pilihan.push(el);
            });

            // get_data_aset_onload();
            // console.log(alat_digunakan_terpilih);
            
        }

        jQuery(document).ready(function() {
            satuan_bahan = JSON.parse(satuan_bahan);
            $(".summernote").summernote({
                height: 300,
                minHeight: 200,
                maxHeight: 500,
                focus: true,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            }), $(".inline-editor").summernote({
                airMode: true
            })
        }), window.edit = function() {
            $(".click2edit").summernote()
        }, window.save = function() {
            $(".click2edit").summernote("destroy")
        };

        function submituploadDokumen(){
            // var form = $('#form_upload_dokumen');

            // if (!form[0].checkValidity()) {
            //     form[0].reportValidity(); // munculkan pesan required
            //     return; // STOP submit
            // }

            // $('#mdl_upload_dokumen_button').hide();
            // $('#mdl_upload_dokumen_button_loader').show();

            // form[0].requestSubmit();

            idfile_ajuan_penelitian = $('#upload_idfile_ajuan_penelitian').val();
            nama_file = $('#mdl_upload_dokumen_nama_file').val();
            file_dokumen = $('#mdl_upload_dokumen_file')[0].files[0];

            if(!nama_file || !file_dokumen){
                alert('Nama file dan file dokumen harus diisi');
                return;
            }

            const formData = new FormData();
            formData.append('file_dokumen', file_dokumen);
            formData.append('nama_file', nama_file);
            formData.append('idfile_ajuan_penelitian', idfile_ajuan_penelitian);
            formData.append('_token', '{{ csrf_token() }}');

            $('#mdl_upload_dokumen_button').hide();
            $('#mdl_upload_dokumen_button_loader').show();

            $.ajax({
                url: "{{ route('penelitian_mhs_upload_dokumen') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // console.log(response);
                    if(response.code == 200){
                        var url_file = route_download_dokumen.replace('idfile_placeholder', response.data.idfile_ajuan_penelitian);
                        $('#btn_status_syarat_' + idfile_ajuan_penelitian).html('<a href="' + url_file + '" class="btn btn-sm btn-success" target="_blank">Lihat Dokumen</a>\
                                                                                <button class="btn btn-sm btn-danger" onclick="hapus_dokumen(this, ' + response.data.plain_idfile_ajuan_penelitian + ', \'' + response.data.idfile_ajuan_penelitian + '\')">Hapus Dokumen</button>');
                        $('#mdl_upload_dokumen').modal('hide');
                    }else{
                        alert(response.message);
                    }
                    $('#mdl_upload_dokumen_button').show();
                    $('#mdl_upload_dokumen_button_loader').hide();
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengupload dokumen');
                    $('#mdl_upload_dokumen_button').show();
                    $('#mdl_upload_dokumen_button_loader').hide();
                }
            });
        }

        function hapus_dokumen(el, idfile_ajuan, enc_idfile_ajuan_penelitian){
            el.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
            var url_hapus = route_hapus_dokumen.replace('idfile_placeholder', enc_idfile_ajuan_penelitian);
            $.ajax({
                url: url_hapus,
                type: 'GET',
                success: function(response) {
                    // console.log(response);
                    if(response.code == 200){
                        $('#btn_status_syarat_' + idfile_ajuan).html('<button class="btn btn-sm btn-primary" onclick="upload_dokumen(' + idfile_ajuan + ')">Upload Dokumen</button>');
                    }else{
                        alert(response.message);
                        el.innerHTML = 'Hapus Dokumen';
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menghapus dokumen');
                    el.innerHTML = 'Hapus Dokumen';
                }
            });
        }

        function bukamdl_pilih_dosen(){
            $('#mdl_pilih_dosen_nip').val('');
            $('#mdl_pilih_dosen_nama').val('');
            $('#mdl_pilih_dosen_fakultas').val('');
            $('#mdl_pilih_dosen_id').val('');
            $('#mdl_pilih_dosen_gelar_depan').val('');
            $('#mdl_pilih_dosen_gelar_belakang').val('');
            $('#mdl_pilih_dosen_id_unit_kerja').val('');
            $('#mdl_pilih_dosen_idprogram_studi').val('');
            $('#mdl_pilih_dosen_nipnik').val('');
            $('#mdl_pilih_dosen').modal('show');
        }

        function cari_dosen(){
            var nip = $('#mdl_pilih_dosen_nip').val();
            if(nip == ''){
                $('#mdl_loader_cari').html('NIP tidak boleh kosong');
                return;
            }

            $('#mdl_loader_cari').html('<i class="fa fa-spinner fa-spin"></i> Mencari dosen...');

            $.ajax({
                url: "{{ route('penelitian_mhs_cari_dosen') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    nip: nip
                },
                success: function(response) {
                    // console.log(response);
                    $('#mdl_loader_cari').html('');
                    if(response.code == 200){
                        $('#mdl_pilih_dosen_nama').val(response.data.nama);
                        $('#mdl_pilih_dosen_gelar_depan').val(response.data.gelar_depan);
                        $('#mdl_pilih_dosen_gelar_belakang').val(response.data.gelar_belakang);
                        $('#mdl_pilih_dosen_fakultas').val(response.data.nama_unit_kerja);
                        $('#mdl_pilih_dosen_id').val(response.data.iduser);
                        $('#mdl_pilih_dosen_nipnik').val(response.data.nipnik);
                        $('#mdl_pilih_dosen_id_unit_kerja').val(response.data.idunit_kerja);
                        $('#mdl_pilih_dosen_idprogram_studi').val(response.data.idprogram_studi);
                    }else{
                        $('#mdl_loader_cari').html('Dosen tidak ditemukan');
                        $('#mdl_pilih_dosen_nama').val('');
                        $('#mdl_pilih_dosen_fakultas').val('');
                        $('#mdl_pilih_dosen_id').val('');
                        $('#mdl_pilih_dosen_gelar_depan').val('');
                        $('#mdl_pilih_dosen_gelar_belakang').val('');
                        $('#mdl_pilih_dosen_id_unit_kerja').val('');
                        $('#mdl_pilih_dosen_idprogram_studi').val('');
                        $('#mdl_pilih_dosen_nipnik').val('');

                    }
                },
                error: function() {
                    $('#mdl_loader_cari').html('Terjadi kesalahan saat mencari dosen');
                    $('#mdl_pilih_dosen_nama').val('');
                        $('#mdl_pilih_dosen_fakultas').val('');
                        $('#mdl_pilih_dosen_id').val('');
                        $('#mdl_pilih_dosen_gelar_depan').val('');
                        $('#mdl_pilih_dosen_gelar_belakang').val('');
                        $('#mdl_pilih_dosen_id_unit_kerja').val('');
                        $('#mdl_pilih_dosen_idprogram_studi').val('');
                        $('#mdl_pilih_dosen_nipnik').val('');
                }
            });
        }

        function pick_dosen(){
            $('#mdl_pilih_dosen_button').hide();
            $('#mdl_pilih_dosen_button_loader').show();

            var iduser = $('#mdl_pilih_dosen_id').val();
            var nama_dosen = $('#mdl_pilih_dosen_nama').val();
            var gelar_depan = $('#mdl_pilih_dosen_gelar_depan').val();
            var gelar_belakang = $('#mdl_pilih_dosen_gelar_belakang').val();
            var id_unit_kerja = $('#mdl_pilih_dosen_id_unit_kerja').val();
            var idprogram_studi = $('#mdl_pilih_dosen_idprogram_studi').val();
            var nipnik = $('#mdl_pilih_dosen_nipnik').val();

            if(iduser == ''){
                alert('Silahkan cari dan pilih dosen terlebih dahulu');
                $('#mdl_pilih_dosen_button').show();
                $('#mdl_pilih_dosen_button_loader').hide();
                return;
            }

            $.ajax({
                url: "{{ route('penelitian_mhs_cek_simpan_dosen') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    iduser: iduser,
                    nama_dosen: nama_dosen,
                    idprogram_studi: idprogram_studi,
                    gelar_depan: gelar_depan,
                    gelar_belakang: gelar_belakang,
                    id_unit_kerja: id_unit_kerja,
                    nipnik: nipnik,
                    idpenelitian: '{{ $penelitian[0]->idpenelitian }}'
                },
                success: function(response) {
                    // console.log(response);
                    if(response.code == 200){
                        $('#mdl_pilih_dosen_button').show();
                        $('#mdl_pilih_dosen_button_loader').hide();
                        $('#main_dosen_pembimbing').val(gelar_depan + ' ' + nama_dosen + ' ' + gelar_belakang);
                        $('#main_nip_dosen_pembimbing').val(nipnik);

                        $('#mdl_pilih_dosen').modal('hide');

                    }else{
                        alert(response.message);
                        $('#mdl_pilih_dosen_button').show();
                        $('#mdl_pilih_dosen_button_loader').hide();
                    }
                    
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengambil template dosen pembimbing');
                    $('#mdl_pilih_dosen_button').show();
                    $('#mdl_pilih_dosen_button_loader').hide();
                }
            });

            
        }

        function bukamdl_pilih_ruangan(){
            $('#mdl_pilih_ruangan').modal('show');
            get_data_aset();
        }

        function get_data_instrumen_append(idruang){
            $.ajax({
                url: "{{ route('penelitian_mhs_get_data_aset_ruangan') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idruang: idruang
                },
                success: function(response) {
                    var index = aset_pilihan.length;
                    $.each(response.data, function(i, aset) {
                        if(aset.keterangan == null){
                            aset.keterangan = '';
                        }
                        
                        aset_pilihan[index] = aset;
                        index++;
                    });

                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan saat mencari aset di ruangan tersebut');
                }
            });
        }

        function get_data_aset(){
            var idruang = $('#mdl_pilih_ruangan_id').val();
            $('#mdl_loader_data_aset').show();

            $.ajax({
                url: "{{ route('penelitian_mhs_get_data_aset_ruangan') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idruang: idruang
                },
                success: function(response) {
                    // console.log(response);
                    var tbody = $('#tbl_aset_ruangan tbody');
                    tbody.empty();
                    // aset_pilihan = response.data;
                    $.each(response.data, function(i, aset) {
                        if(aset.keterangan == null){
                            aset.keterangan = '';
                        }
                        
                        // aset_pilihan[i] = aset;
                        // aset_pilihan[i]['status_pilih'] = 0;
                        var row = '<tr>' +
                            '<td>' + aset.kode_barang_aset + '</td>' +
                            '<td>' + aset.nama_barang + '<br>' + aset.merk_barang + '<br>' + aset.keterangan + '</td>' +
                            '<td>' + aset.tahun_aset + '</td>' +
                            '<td>' + aset.kondisi_barang + '</td>' +
                            '</tr>';
                        tbody.append(row);
                    });
                    $('#mdl_loader_data_aset').hide();
                    // console.log(aset_pilihan);
                },
                error: function() {
                    alert('Terjadi kesalahan saat mencari aset di ruangan tersebut');
                    $('#mdl_loader_data_aset').hide();
                }
            });
        }

        function pick_ruangan(){
            var idruang = $('#mdl_pilih_ruangan_id').val();
            var nama_ruangan = $('#mdl_pilih_ruangan_id option:selected').text();

            $('#mdl_pilih_ruangan_button').hide();
            $('#mdl_pilih_ruangan_button_loader').show();

            $.ajax({
                url: "{{ route('penelitian_mhs_cek_simpan_ruangan') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idruang: idruang,
                    idpenelitian: '{{ $penelitian[0]->idpenelitian }}'
                },
                success: function(response) {
                    // console.log(response);
                    if(response.code == 200){
                        $('#mdl_pilih_ruangan_button').show();
                        $('#mdl_pilih_ruangan_button_loader').hide();

                        $('#mdl_pilih_ruangan').modal('hide');
                        $('#tbl_aset_terpilih tbody').empty();

                        // $('#nama_ruangan').val(nama_ruangan);
                        // $('#id_ruangan').val(idruang);

                        var tabel = $('#tabel_ruangan_lab tbody');

                        var row = '<tr>' +
                                    '<td>' + nama_ruangan + '<input type="hidden" form="form_update_ajuan" name="idruang[]" value="' + idruang + '"></td>' +
                                    '<td><button class="btn btn-sm btn-danger" onclick="hapusRuangan(this, {{ $penelitian[0]->idpenelitian }}, ' + idruang + ')">Hapus</button></td>'+
                                    '</tr>';
                        tabel.append(row);
                        get_data_instrumen_append(idruang);

                    }else{
                        alert(response.message);
                        $('#mdl_pilih_ruangan_button').show();
                        $('#mdl_pilih_ruangan_button_loader').hide();
                    }
                    
                },
                error: function() {
                    alert('Terjadi kesalahan saat menyimpan ruangan');
                    $('#mdl_pilih_ruangan_button').show();
                    $('#mdl_pilih_ruangan_button_loader').hide();
                }
            });
            
        }

        function isi_tabel_aset_pilihan(){
            var tbody = $('#tbl_aset_lab tbody');
            tbody.empty();
            // console.log(aset_pilihan);
            $('#checkAll').prop('checked', false);
            $.each(aset_pilihan, function(i, aset) {
                if(aset.ketarangan == null){
                    aset.keterangan = '';
                }

                // var row = '<tr onclick="pilihAset(' + i + ')" style="cursor: pointer;">' +
                //         '<td>' + aset.kode_barang_aset + '</td>' +
                //         '<td>' + aset.nama_barang + '<br>' + aset.merk_barang + '<br>' + aset.keterangan + '</td>' +
                //         '<td>' + aset.nama_ruang + '<br>' + aset.nama_gedung + '</td>' +
                //         '<td>' + aset.tahun_aset + '</td>' +
                //         '<td>' + aset.kondisi_barang + '</td>' +
                //         '</tr>';
                var row = '<tr>' +
                        '<td> <input type="checkbox" class="checkItem" value="'+ i +'"> </td>' +
                        '<td>' + aset.kode_barang_aset + '</td>' +
                        '<td>' + aset.nama_barang + '<br>' + aset.merk_barang + '<br>' + aset.keterangan + '</td>' +
                        '<td>' + aset.nama_ruang + '<br>' + aset.nama_gedung + '</td>' +
                        '<td>' + aset.tahun_aset + '</td>' +
                        '<td>' + aset.kondisi_barang + '</td>' +
                        '</tr>';

                tbody.append(row);
            });
        }

        function tambahAset(){
            isi_tabel_aset_pilihan();
            $('#mdl_pilih_aset').modal('show');
            
        }

        function pilihAset(index, idwaktu){
            var kode_barang_aset = aset_pilihan[index].kode_barang_aset;

            $.ajax({
                url: "{{ route('penelitian_mhs_cek_simpan_aset') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    kode_barang_aset: kode_barang_aset,
                    idpenelitian: '{{ $penelitian[0]->idpenelitian }}',
                    idwaktu: idwaktu,
                    jenis_pemakaian: '3'
                },
                success: function(response) {
                    // console.log(response);
                    if(response.code == 200){
                        aset_pilihan[index].status_pilih = 1;

                        var tabel = $('#tabel_instrumen_terpilih_' + idwaktu + ' tbody');
                        if(aset_pilihan[index].keterangan == null){
                            aset_pilihan[index].keterangan = '';
                        }
                        var row = '<tr>' +
                                    '<td>' + aset_pilihan[index].kode_barang_aset + '<input type="hidden" form="form_update_ajuan" name="aset[]" value="' + aset_pilihan[index].kode_barang_aset + '">\
                                        <input type="hidden" form="form_update_ajuan" name="idalat_digunakan[]" value="' + response.data.idalat_digunakan + '"></td>' +
                                    '<td>' + aset_pilihan[index].nama_barang + '<br>' + aset_pilihan[index].merk_barang + '<br>' + aset_pilihan[index].keterangan + '<br> tahun ' + aset_pilihan[index].tahun_aset + '</td>' +
                                    '<td><span id="tujuan_aset_' + response.data.idalat_digunakan + '"></span><br> <code onclick="editTujuanInstrumen(' + response.data.idalat_digunakan + ')" style="cursor:pointer">Edit</code> </td>' +
                                    '<td><button type="button" class="btn btn-danger" onclick="hapusAset(this, ' + response.data.idalat_digunakan + ', ' + idwaktu + ')">Hapus</button></td>'+
                                    '</tr>';
                        tabel.append(row);

                        var jumlah_row = tabel.children('tr').length;
                        $('#jumlah_instrumen_' + idwaktu).html(jumlah_row);
                    }
                    else{
                        alert(response.message);
                    }
                    
                },
                error: function(xhr, status, error) {
                    var errorMsg = 'Terjadi kesalahan saat menyimpan aset';
                    if(xhr.responseJSON && xhr.responseJSON.message){
                        errorMsg += ': ' + xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                }
            });

            $('#mdl_pilih_aset').modal('hide');
            
        }

        function hapusAset(button, idalat_digunakan, idwaktu){
            button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

            $.ajax({
                url: "{{ route('penelitian_mhs_hapus_aset') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idalat_digunakan: idalat_digunakan
                },
                success: function(response) {
                    // console.log(response);
                    if(response.code == 200){
                        $(button).closest('tr').remove();

                        var tabel = $('#tabel_instrumen_terpilih_' + idwaktu + ' tbody');
                        var jumlah_row = tabel.children('tr').length;
                        $('#jumlah_instrumen_' + idwaktu).html(jumlah_row);

                    }
                    else{
                        alert(response.message);
                        button.innerHTML = 'Hapus';
                    }
                },
                error: function(response) {
                    var msg = response.responseJSON?.message ?? 'Terjadi kesalahan saat menghapus aset';
                    alert(msg);
                    button.innerHTML = 'Hapus';
                }
            }); 
        }

        function tambahBahan(){
            var tabel = $('#tabel_bahan_terpilih tbody');
            var jumlah_row = tabel.children('tr').length + 1;
            var row = '<tr>' +
                        '<td>' + jumlah_row + '</td>' +
                        '<td><input type="text" name="nama_bahan[]" class="form-control" form="form_update_ajuan" required></td>' +
                        '<td><input type="text" name="spesifikasi_bahan[]" class="form-control" form="form_update_ajuan" required></td>' +
                        '<td><input type="number" name="jumlah_bahan[]" class="form-control" form="form_update_ajuan" required>\
                                <input type="hidden" name="id_bahan[]" form="form_update_ajuan" value="0">' +
                        '</td>' +
                        '<td>\
                            <select name="satuan_bahan[]" class="form-control" form="form_update_ajuan" required>' +
                                '<option value="">Pilih Satuan</option>';

            $.each(satuan_bahan, function(i, satuan){
                row += '<option value="' + satuan.id + '">' + satuan.nm_satuan + '</option>';
            });


                row +=  '   </select>\
                        </td>\
                        <td><button type="button" class="btn btn-sm btn-danger" onclick="hapusBahan(this, 0)">Hapus</button></td>\
                    </tr>';
            tabel.append(row);
        }

        function hapusBahan(button, idbahan){

            if(idbahan == 0){
                $(button).closest('tr').remove();

                var tabel = $('#tabel_bahan_terpilih tbody');
                tabel.children('tr').each(function(i, el){
                    $(el).children('td').first().html(i + 1);
                });
                return;
            }

            $(button).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ route('penelitian_mhs_hapus_bahan') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idbahan: idbahan
                },
                success: function(response) {
                    // console.log(response);
                    if(response.code == 200){
                        $(button).closest('tr').remove();

                        var tabel = $('#tabel_bahan_terpilih tbody');
                        tabel.children('tr').each(function(i, el){
                            $(el).children('td').first().html(i + 1);
                        });
                    }
                    else{
                        alert(response.message);
                        $(button).html('Hapus');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menghapus bahan');
                    $(button).html('Hapus');
                }
            });

            
        }

        

        function tambahWaktu(){
            $('#mdl_satu_tanggal_awal').val('');
            $('#mdl_satu_tanggal_akhir').val('');
            $('#mdl_satu_waktu_awal').val('');

            $('#mdl_satu_tanggal').modal('show');
        }

        function range_tanggal(){
            $('#mdl_range_tanggal').modal('show');
        }

        async function pick_waktu(){
            $('#mdl_pilih_waktu_button').hide();
            $('#mdl_pilih_waktu_button_loader').show();

            try{
                var tanggal_awal = $('#mdl_range_tanggal_awal').val();
                var tanggal_akhir = $('#mdl_range_tanggal_akhir').val();
                var waktu_awal = $('#mdl_range_waktu_awal').val();
                var waktu_akhir = $('#mdl_range_waktu_akhir').val();

                if(tanggal_awal == '' || tanggal_akhir == '' || waktu_awal == '' || waktu_akhir == ''){
                    alert('Semua field harus diisi');
                    return;
                }

                var start = moment(tanggal_awal + ' ' + waktu_awal, 'DD/MM/YYYY HH:mm');
                var end = moment(tanggal_akhir + ' ' + waktu_akhir, 'DD/MM/YYYY HH:mm');

                if(end.isBefore(start)){
                    alert('Tanggal dan waktu akhir harus setelah tanggal dan waktu awal');
                    return;
                }

                var current = start.clone().startOf('day');
                var lastDate = end.clone().startOf('day');

                var gagal = [];
                var berhasil = 0;


                while(current.isSameOrBefore(end)){
                    var tanggal = current.format('DD/MM/YYYY');

                    try{
                        const response = await simpan_waktu(tanggal, waktu_awal, waktu_akhir);
                        if(response.code == 200){
                            var isi = renderAccordionWaktu(response.data.idwaktu, tanggal, waktu_awal, waktu_akhir);
                            
                            $('#accordionWaktuInstrumen').append(isi);
                            berhasil++;
                        }
                        else{
                            gagal.push(`${tanggal} (${response.message})`);
                        }
                    }
                    catch (err) {
                        let pesan = 'Gagal menyimpan';
                        if(err.responseJSON && err.responseJSON.message){
                            pesan = err.responseJSON.message;
                        } else if(err.message){
                            pesan = err.message;
                        }

                        gagal.push(`${tanggal} (${pesan})`);
                    }

                    current.add(1, 'days');  
                }
                

                $('#mdl_range_tanggal').modal('hide');
            }
            finally{
                $('#mdl_pilih_waktu_button_loader').hide();
                $('#mdl_pilih_waktu_button').show();
            }
            
        }

        function upload_dokumen(idfile){
            $('#upload_idfile_ajuan_penelitian').val(idfile);
            $('#mdl_upload_dokumen').modal('show');
            $('#mdl_upload_dokumen_nama_file').val('');
            $('#mdl_upload_dokumen_file').val('');
        }

        function pilih_waktu(){
            $('#mdl_satu_waktu_button').hide();
            $('#mdl_satu_waktu_button_loader').show();
            var tanggal = $('#mdl_satu_tanggal_awal').val();
            var waktu_awal = $('#mdl_satu_waktu_awal').val();
            var waktu_akhir = $('#mdl_satu_waktu_akhir').val();

            simpan_waktu(tanggal, waktu_awal, waktu_akhir)
                .done(function(response){
                    // console.log(response);
                    if(response.code == 200){
                        var div = $('#accordionWaktuInstrumen');

                        var isi = renderAccordionWaktu(response.data.idwaktu, tanggal, waktu_awal, waktu_akhir);

                        div.append(isi);
                        $('#mdl_satu_tanggal').modal('hide');
                    }
                    else{
                        alert(response.message);
                    }

                })
                .fail(function(xhr, status, error){
                    let message = 'Terjadi kesalahan saat menyimpan waktu pelaksanaan';

                    if(xhr.responseJSON && xhr.responseJSON.message){
                        message += ': ' + xhr.responseJSON.message;
                    } else if(error){
                        message += ': ' + error;
                    }

                    alert(message);
                })
                .always(function(){
                    $('#mdl_satu_waktu_button_loader').hide();
                    $('#mdl_satu_waktu_button').show();
                });

        }

        function simpan_waktu(tanggal, waktu_awal, waktu_akhir){
            if(tanggal == '' || waktu_awal == '' || waktu_akhir == ''){
                alert('Semua field harus diisi');
                return;
            }

            return $.ajax({
                        url: "{{ route('penelitian_mhs_cek_simpan_waktu') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            tanggal: tanggal,
                            jam_mulai: waktu_awal,
                            jam_akhir: waktu_akhir,
                            idpenelitian: '{{ $penelitian[0]->idpenelitian }}'
                        }
                    });
        }

        function renderAccordionWaktu(idwaktu, tanggal, waktu_awal, waktu_akhir){
            return `<div class="accordion__item">
                        <div class="accordion__header collapsed" data-toggle="collapse" data-target="#left-indicator_${idwaktu}">
                            <span class="accordion__header--text">Jadwal ${tanggal} ${waktu_awal} - ${waktu_akhir}</span>
                            <span class="accordion__header--indicator"></span>
                        </div>
                        <div id="left-indicator_${idwaktu}" class="collapse accordion__body" data-parent="#accordionWaktuInstrumen">
                            <div class="accordion__body--text">
                                <div class="row mb-3">
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <button type="button" class="btn btn-sm btn-info" onclick="edit_jadwal(this, ${idwaktu})">Edit Jadwal</button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="hapus_jadwal(this, ${idwaktu})">Hapus Jadwal</button>
                                    </div>
                                    <div class="col-lg-3 col-md-4 col-sm-6">
                                        <button type="button" class="btn btn-sm btn-success" onclick="tambah_instrumen(this, ${idwaktu})">Tambah Instrumen</button>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="copy_instrumen(this, ${idwaktu})">Copy Instrumen</button>
                                    </div>
                                </div>
                                <b>Instrumen yang digunakan:</b>
                                <table id="tabel_instrumen_terpilih_${idwaktu}" class="table table-bordered verticle-middle table-responsive-sm" width="100%">
                                    <thead>
                                        <tr>
                                            <th scope="col">Kode Barang</th>
                                            <th scope="col">Nama Barang</th>
                                            <th scope="col">Tujuan</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>`;
        }

        function tambah_instrumen(idwaktu){
            // console.log(aset_pilihan, idwaktu);
            isi_tabel_aset_pilihan();
            $('#mdl_pilih_aset_idwaktu').val(idwaktu);
            $('#mdl_pilih_aset').modal('show');
        }

        function pilih_instrumen(){
            var idwaktu = $('#mdl_pilih_aset_idwaktu').val();
            var checkboxes = $('.checkItem:checked');
            if(checkboxes.length == 0){
                alert('Silahkan pilih minimal 1 instrumen');
                return;
            }

            checkboxes.each(function(){
                var index = $(this).val();
                pilihAset(index, idwaktu);
            });
        }

        function copy_instrumen(idwaktu){
            var list_el_idwaktu = [];
            $('#tbl_copy_instrumen tbody').empty();

            $('.accordion__header--text').each(function(el, index){
                var text = $(this).text();
                var idwaktu = $(this).siblings('input[type=hidden]').val();

                list_el_idwaktu.push({
                    text: text,
                    idwaktu: idwaktu
                });
            });

            // console.log(list_el_idwaktu);
            
            list_el_idwaktu.forEach(function(el){
                var text = el.text;
                var idwaktu_el = el.idwaktu;

                // console.log(el, text, idwaktu);

                var row = `<tr style="cursor: pointer;" onclick="copy_instrumen_darijadwal( ${idwaktu_el}, ${idwaktu})">` +
                            `<td>` + text + `</td>` +
                            `</tr>`;

                $('#tbl_copy_instrumen tbody').append(row);
            });

            $('#mdl_copy_instrumen').modal('show');

        }

        function copy_instrumen_darijadwal(idwaktuel, idwaktu){
            $('#mdl_copy_instrumen').modal('hide');
            var checkboxes = $('#tabel_instrumen_terpilih_' + idwaktuel + ' tbody tr td input[name="aset[]"]');
            if(checkboxes.length == 0){
                alert('Instrumen pada jadwal yang dipilih kosong');
                return;
            }

            console.log(checkboxes);
            
            var arr_help = [];

            aset_pilihan.forEach(function(aset, index){
                arr_help[index] = aset.kode_barang_aset;
            });

            // console.log(aset_pilihan, arr_help);

            checkboxes.each(function(){
                var kode_barang_aset = $(this).val();
                var index = arr_help.indexOf(kode_barang_aset);
                // console.log(index, idwaktu);
                pilihAset(index, idwaktu);
            });
        }

        function editTujuanInstrumen(idalat_digunakan){
            var tujuan = $('#tujuan_aset_' + idalat_digunakan).html();
            $('#mdl_edit_tujuan_instrumen_isi_tujuan').val(tujuan);

            $('#mdl_edit_tujuan_instrumen').modal('show');
            $('#mdl_edit_tujuan_instrumen_idalat_digunakan').val(idalat_digunakan);
        }

        function submitEditTujuanInstrumen(){
            $('#mdl_edit_tujuan_instrumen_button').hide();
            $('#mdl_edit_tujuan_instrumen_button_loader').show();

            var idalat_digunakan = $('#mdl_edit_tujuan_instrumen_idalat_digunakan').val();
            var tujuan = $('#mdl_edit_tujuan_instrumen_isi_tujuan').val();

            ajaxEditTujuanInstrumen(idalat_digunakan, tujuan)
                .done(function(response){
                    // console.log(response);
                    if(response.code == 200){
                        $('#tujuan_aset_' + idalat_digunakan).html(tujuan);
                        $('#mdl_edit_tujuan_instrumen').modal('hide');
                    }
                    else{
                        alert(response.message);
                    }
                })
                .fail(function(xhr, status, error){
                    let message = 'Terjadi kesalahan saat menyimpan tujuan instrumen';

                    if(xhr.responseJSON && xhr.responseJSON.message){
                        message += ': ' + xhr.responseJSON.message;
                    } else if(error){
                        message += ': ' + error;
                    }

                    alert(message);
                })
                .always(function(){
                    $('#mdl_edit_tujuan_instrumen_button_loader').hide();
                    $('#mdl_edit_tujuan_instrumen_button').show();
                });
        }

        function ajaxEditTujuanInstrumen(idalat_digunakan, tujuan){
            if(idalat_digunakan == '' || tujuan == ''){
                alert('idalat digunakan dan tujuan tidak boleh kosong');
                return;
            }

            return $.ajax({
                        url: "{{ route('penelitian_mhs_edit_tujuan_instrumen') }}",
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            idalat_digunakan: idalat_digunakan,
                            tujuan: tujuan
                        }
                    });
        }

        function hapusRuangan(el, idpenelitian, idruang){
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Ruangan akan dihapus dari daftar',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }
                
                var button = $(el);
                button.html('<i class="fa fa-spinner fa-spin"></i>');

                $.ajax({
                    url: "{{ route('penelitian_mhs_hapus_ruangan') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        idpenelitian: idpenelitian,
                        idruang: idruang
                    },
                    success: function(response) {
                        console.log(response);
                        if(response.code == 200){
                            location.reload();
                        }
                        else{
                            alert(response.message);
                            button.html('Hapus');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat menghapus ruangan');
                        button.html('Hapus');
                    }
                });
            });
        }

        function updateTopik(el){
            clearTimeout(timer_edit_topik);
            timer_edit_topik = setTimeout(function(){
                execupdateTopik($(el).val());
            }, 1500);
        }

        function execupdateTopik(topik){
            $('#loader_topik').html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ route('penelitian_mhs_update_topik') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idpenelitian: '{{ $penelitian[0]->idpenelitian }}',
                    topik: topik
                },
                success: function(response) {
                    // console.log(response);
                    if(response.code == 200){
                        $('#loader_topik').html('<span style="color:green;"><i class="fa fa-check"></i></span>');
                    }
                    else{
                        alert(response.message);
                        $('#loader_topik').html('<span style="color:red;"><i class="fa fa-close"></i></span>');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menyimpan topik penelitian');
                    $('#loader_topik').html('<span style="color:red;"><i class="fa fa-close"></i></span>');
                }
            });
        }

        function edit_jadwal(idwaktu){
            var idaccordion = "accordionWaktuInstrumen_" + idwaktu;
            var text_jadwal = $('#' + idaccordion).find('.accordion__header--text').text();
            var arr = text_jadwal.split(' ');
            var tanggal = arr[1];
            
            var waktu_awal = arr[2];
            var waktu_akhir = arr[4];

            // console.log(arr);

            $('#mdl_satu_tanggal_awal').val(tanggal);
            $('#mdl_satu_waktu_awal').val(waktu_awal);
            $('#mdl_satu_waktu_akhir').val(waktu_akhir);
            $('#mdl_satu_idwaktu').val(idwaktu);
            $('#mdl_satu_tanggal').modal('show');
        }

        function ex_edit_jadwal(){
            $('#mdl_satu_waktu_button').hide();
            $('#mdl_satu_waktu_button_loader').show();

            var idwaktu = $('#mdl_satu_idwaktu').val();
            var tanggal = $('#mdl_satu_tanggal_awal').val();
            var waktu_awal = $('#mdl_satu_waktu_awal').val();
            var waktu_akhir = $('#mdl_satu_waktu_akhir').val();

            $.ajax({
                url: "{{ route('penelitian_mhs_edit_jadwal') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idwaktu: idwaktu,
                    tanggal: tanggal,
                    jam_mulai: waktu_awal,
                    jam_akhir: waktu_akhir,
                    idpenelitian: '{{ $penelitian[0]->idpenelitian }}'
                },
                success: function(response) {
                    // console.log(response);
                    if(response.code == 200){
                        var idaccordion = "accordionWaktuInstrumen_" + idwaktu;
                        var text_jadwal = $('#' + idaccordion).find('.accordion__header--text').text();
                        var arr = text_jadwal.split(' ');

                        $('#' + idaccordion).find('.accordion__header--text').html(`Jadwal ${tanggal} ${waktu_awal} - ${waktu_akhir} # <span id="jumlah_instrumen_${idwaktu}">${arr[6]}</span> instrumen`);
                        $('#mdl_satu_tanggal').modal('hide');
                    }
                    else{
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menyimpan jadwal');
                },
                complete: function(){
                    $('#mdl_satu_waktu_button_loader').hide();
                    $('#mdl_satu_waktu_button').show();
                }
            });
        }
        
        function hapus_jadwal(el, idwaktu){
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: 'Jadwal akan dihapus dari daftar',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }
                
                var button = $(el);
                button.html('<i class="fa fa-spinner fa-spin"></i>');

                $.ajax({
                    url: "{{ route('penelitian_mhs_hapus_jadwal') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        idwaktu: idwaktu
                    },
                    success: function(response) {
                        console.log(response);
                        if(response.code == 200){
                            $('#accordionWaktuInstrumen_' + idwaktu).remove();
                        }
                        else{
                            alert(response.message);
                            button.html('Hapus Jadwal');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat menghapus jadwal');
                        button.html('Hapus Jadwal');
                    }
                });
            });
        }

        function submitForm(el, status){
            var form = $('#form_update_ajuan');
            $('#status_ajuan').val(status);

            if(status == 6){
                swal.fire({
                    title: 'Apakah anda yakin?',
                    text: 'Ajuan akan Dibatalkan dan tidak bisa dikembalikan lagi',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, simpan!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(el).html('<i class="fa fa-spinner fa-spin"></i>');

                        form[0].submit();                        
                    }
                });
            }
            else{
                if(!form[0].checkValidity()){
                    form[0].reportValidity();
                    return;
                }

                $(el).html('<i class="fa fa-spinner fa-spin"></i>');

                form[0].submit();
            }

            
        }

        
    </script>
        
@endsection