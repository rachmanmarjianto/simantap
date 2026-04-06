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
                        <div class="card-header">
                            
                            
                        </div>
                        <div class="card-body">
                            <h2 style="text-align: center">FORM PELAKSANAAN PENELITIAN {{ $penelitian[0]->internal == 1 ? 'INTERNAL' : 'EKSTERNAL' }}</h2>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Topik Penelitian</label>
                                <div class="col-lg-10">
                                    <input type="text" class="form-control" value="{{ $penelitian[0]->topik }}" form="form_update_ajuan" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">NIP Dosen Pembimbing</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="main_nip_dosen_pembimbing" name="nip_dosen_pembimbing" value="" form="form_update_ajuan" readonly required>
                                </div>
                                <div class="col-lg-2">
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Nama Dosen Pembimbing</label>
                                <div class="col-lg-8">
                                    <input type="text" class="form-control" id="main_dosen_pembimbing" name="dosen_pembimbing" value="" form="form_update_ajuan" readonly required>
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
                                                        <td><button class="btn btn-sm btn-danger" onclick="hapusRuangan(this, {{ $lab->idlab_penelitian }})">Hapus</button></td>
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
                                                        <input type="text" name="default[{{ $child['idisi_template'] }}]" class="form-control" value="{{ $child['nilai_default'] }}" >
                                                    </div>
                                                </div>
                                            
                                            @elseif($child['jenis_isi'] == 3)
                                                <div class="form-group row">
                                                    <div class="col-lg-12">
                                                        <textarea  class="summernote" name="default[{{ $child['idisi_template'] }}]"> {{ $child['nilai_default'] }} </textarea>
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
                                                <input type="text" name="default[{{ $el['idisi_penelitian'] }}]" class="form-control" value="{{ $el['nilai_default'] }}" >
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
                                    <div class="accordion accordion-left-indicator accordion-bordered" id="accordionWaktuInstrumen">
                                        
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
                                                    <ul>
                                                        <li>Alat A</li>
                                                        <li>Alat B</li>
                                                        <li>Alat C</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    
                                </div>
                            </div>
                            

                            <div class="form-group col-md-12" style="padding-left:0px; padding-right:0px">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <div class="flex-grow-1">
                                        <b>Instrumen yang akan digunakan</b>
                                    </div>
                                    <div>
                                        <span style="float:right; color:green; cursor:pointer; font-size:20px" onclick="tambahAset()"><i class="fa fa-plus-circle"></i></span>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    
                                    <div class="table-responsive">
                                        <table id="tabel_instrumen_terpilih" class="table table-bordered verticle-middle table-responsive-sm" width="100%">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No</th>
                                                    <th scope="col">Kode Barang</th>
                                                    <th scope="col">Nama Barang</th>
                                                    <th scope="col">Tujuan</th>
                                                    <th scope="col">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $index = 1;
                                                @endphp
                                                @foreach($alat_digunakan as $alat)
                                                    <tr>
                                                        <td>{{ $index }}</td>
                                                        <td>{{ $alat->kode_barang_aset }}<input type="hidden" form="form_update_ajuan" name="aset[]" value="{{ $alat->kode_barang_aset }}"><input type="hidden" form="form_update_ajuan" name="idalat_digunakan[]" value="{{ $alat->idalat_digunakan }}"></td>
                                                        <td>{{ $alat->nama_barang }}<br>{{ $alat->merk_barang }}<br>{{ $alat->keterangan }}</td>
                                                        <td><textarea name="tujuan_aset[{{ $alat->kode_barang_aset }}]" class="form-control" form="form_update_ajuan" required>{{ $alat->tujuan }}</textarea></td>
                                                        <td><button type="button" class="btn btn-danger" onclick="hapusAset(this, null, {{ $alat->idalat_digunakan }}, '{{ $alat->kode_barang_aset }}')">Hapus</button></td>
                                                    </tr>
                                                    @php
                                                        $index++;
                                                    @endphp
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>


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
                    <h5 class="modal-title">Pilih Tanggal dan Waktu</h5>
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
                </div>
                <div class="modal-footer" id="mdl_satu_waktu_button">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" onclick="pilih_waktu()">Proses</button>
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

    <form id="form_update_ajuan" method="POST" action="">
        @csrf
        <input type="hidden" name="idpenelitian" value="{{ $penelitian[0]->idpenelitian }}">
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
        var alat_digunakan_terpilih = @json($alat_digunakan_kodebarang);

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

            get_data_aset_onload();
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
                    console.log(response);
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
                    console.log(response);
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
                    console.log(response);
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
                    nipnik: nipnik
                },
                success: function(response) {
                    console.log(response);
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

        function get_data_aset_onload(){
            $idruang = $('#id_ruangan').val();

            if($idruang != null){
                $.ajax({
                    url: "{{ route('penelitian_mhs_get_data_aset_ruangan') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        idruang: $idruang
                    },
                    success: function(response) {
                        
                        aset_pilihan = response.data;
                        $.each(response.data, function(i, aset) {
                            aset_pilihan[i] = aset;
                            if(alat_digunakan_terpilih.includes(aset.kode_barang_aset)){
                                aset_pilihan[i]['status_pilih'] = 1;
                                
                            }else{
                                aset_pilihan[i]['status_pilih'] = 0;
                            }
                            
                        });
                        
                        // console.log(aset_pilihan, alat_digunakan_terpilih);
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat mencari aset di ruangan tersebut');
                    }
                });
            }
        }

        function get_data_aset(){
            $idruang = $('#mdl_pilih_ruangan_id').val();
            $('#mdl_loader_data_aset').show();

            $.ajax({
                url: "{{ route('penelitian_mhs_get_data_aset_ruangan') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idruang: $idruang
                },
                success: function(response) {
                    // console.log(response);
                    var tbody = $('#tbl_aset_ruangan tbody');
                    tbody.empty();
                    aset_pilihan = response.data;
                    $.each(response.data, function(i, aset) {
                        if(aset.keterangan == null){
                            aset.keterangan = '';
                        }
                        
                        aset_pilihan[i] = aset;
                        aset_pilihan[i]['status_pilih'] = 0;
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
                    console.log(response);
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
                                    '<td><button class="btn btn-sm btn-danger" onclick="hapusRuangan(this, ' + response.data.idlab_penelitian + ')">Hapus</button></td>'+
                                    '</tr>';
                        tabel.append(row);

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
            console.log(aset_pilihan);
            $.each(aset_pilihan, function(i, aset) {
                if(aset.ketarangan == null){
                    aset.keterangan = '';
                }

                if(aset.status_pilih == 0){
                    var row = '<tr onclick="pilihAset(' + i + ')" style="cursor: pointer;">' +
                        '<td>' + aset.kode_barang_aset + '</td>' +
                        '<td>' + aset.nama_barang + '<br>' + aset.merk_barang + '<br>' + aset.keterangan + '</td>' +
                        '<td>' + aset.tahun_aset + '</td>' +
                        '<td>' + aset.kondisi_barang + '</td>' +
                        '</tr>';
                    tbody.append(row);
                }
            });
        }

        function tambahAset(){
            isi_tabel_aset_pilihan();
            $('#mdl_pilih_aset').modal('show');
            
        }

        function pilihAset(index){
            var kode_barang_aset = aset_pilihan[index].kode_barang_aset;

            $.ajax({
                url: "{{ route('penelitian_mhs_cek_simpan_aset') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    kode_barang_aset: kode_barang_aset,
                    idpenelitian: '{{ $penelitian[0]->idpenelitian }}'
                },
                success: function(response) {
                    console.log(response);
                    if(response.code == 200){
                        aset_pilihan[index].status_pilih = 1;

                        var tabel = $('#tabel_instrumen_terpilih tbody');
                        var jumlah_row = tabel.children('tr').length + 1;
                        if(aset_pilihan[index].keterangan == null){
                            aset_pilihan[index].keterangan = '';
                        }
                        var row = '<tr>' +
                                    '<td>' + jumlah_row + '</td>' +
                                    '<td>' + aset_pilihan[index].kode_barang_aset + '<input type="hidden" form="form_update_ajuan" name="aset[]" value="' + aset_pilihan[index].kode_barang_aset + '">\
                                        <input type="hidden" form="form_update_ajuan" name="idalat_digunakan[]" value="' + response.data.idalat_digunakan + '"></td>' +
                                    '<td>' + aset_pilihan[index].nama_barang + '<br>' + aset_pilihan[index].merk_barang + '<br>' + aset_pilihan[index].keterangan + '</td>' +
                                    '<td><textarea name="tujuan_aset[' + aset_pilihan[index].kode_barang_aset + ']" class="form-control" form="form_update_ajuan" required></textarea></td>' +
                                    '<td><button type="button" class="btn btn-danger" onclick="hapusAset(this, ' + index + ', ' + response.data.idalat_digunakan + ', \'' + aset_pilihan[index].kode_barang_aset + '\')">Hapus</button></td>'+
                                    '</tr>';
                        tabel.append(row);

                        
                    }
                    else{
                        alert(response.message);
                    }
                    
                },
                error: function() {
                    alert('Terjadi kesalahan saat menyimpan aset');
                }
            });

            $('#mdl_pilih_aset').modal('hide');
            
        }

        function hapusAset(button, index, idalat_digunakan, kode_barang_aset){
            button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

            $.ajax({
                url: "{{ route('penelitian_mhs_hapus_aset') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idalat_digunakan: idalat_digunakan
                },
                success: function(response) {
                    console.log(response);
                    if(response.code == 200){
                        $(button).closest('tr').remove();

                        if(index == null){
                            var index_pilihan = aset_pilihan.findIndex(a => a.kode_barang_aset === kode_barang_aset);
                            if(index_pilihan !== -1){
                                index = index_pilihan;
                            }
                        }
                        aset_pilihan[index].status_pilih = 0;

                        var tabel = $('#tabel_instrumen_terpilih tbody');
                        tabel.children('tr').each(function(i, el){
                            $(el).children('td').first().html(i + 1);
                        });

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
                        '<td><input type="number" name="jumlah_bahan[]" class="form-control" form="form_update_ajuan" required></td>' +
                        '<td>\
                            <select name="satuan_bahan[]" class="form-control" form="form_update_ajuan" required>' +
                                '<option value="">Pilih Satuan</option>';

            $.each(satuan_bahan, function(i, satuan){
                row += '<option value="' + satuan.id + '">' + satuan.nm_satuan + '</option>';
            });


                row +=  '   </select>\
                        </td>\
                        <td><button type="button" class="btn btn-danger" onclick="hapusBahan(this)">Hapus</button></td>\
                    </tr>';
            tabel.append(row);
        }

        function hapusBahan(button){
            $(button).closest('tr').remove();

            var tabel = $('#tabel_bahan_terpilih tbody');
            tabel.children('tr').each(function(i, el){
                $(el).children('td').first().html(i + 1);
            });
        }

        

        function tambahWaktu(){
            $('#mdl_satu_tanggal_awal').val('');
            $('#mdl_satu_tanggal_akhir').val('');
            $('#mdl_satu_waktu_awal').val('');

            $('#mdl_satu_tanggal').modal('show');
        }

        function hapusWaktu(button){
            // $(button).closest('tr').remove();

            var row = $(button).closest('tr');
            var id = row.find('input[name="id_tgl_pelaksanaan[]"]').val();
            var loader = row.find('td').last();
            loader.html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ route('penelitian_mhs_hapus_waktu') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idwaktu: id
                },
                success: function(response) {
                    if(response.code == 200){
                        row.remove();
                        var tabel = $('#tabel_waktu_terpilih tbody');
                        tabel.children('tr').each(function(i, el){
                            var num = i + 1;
                            $(el).attr('id', 'row' + num);
                            $(el).children('td').first().html(num);
                            $(el).children('td').last().attr('id', 'loader' + num);
                            $(el).find('input[name="tanggal_pelaksanaan[]"]').attr('onchange', 'ubah(' + num + ')');
                            $(el).find('input[name="jam_mulai[]"]').attr('onchange', 'ubah(' + num + ')');
                            $(el).find('input[name="jam_akhir[]"]').attr('onchange', 'ubah(' + num + ')');
                        });
                    }else{
                        alert('Gagal menghapus waktu pelaksanaan: ' + response.message);
                        loader.html('<span style="color:red;"><i class="fa fa-close"></i></span>');
                    }
                },
                error: function(response) {
                    alert('Gagal menghapus waktu pelaksanaan: ' + response.message);
                        loader.html('<span style="color:red;"><i class="fa fa-close"></i></span>');
                }
            });

            
        }

        function range_tanggal(){
            $('#mdl_range_tanggal').modal('show');
        }

        function pick_waktu(){
            $('#mdl_pilih_waktu_button').hide();
            $('#mdl_pilih_waktu_button_loader').show();
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

            var current = start.clone();
            var index = 1;
            while(current.isSameOrBefore(end)){
                var tanggal = current.format('DD/MM/YYYY');
                simpan_waktu(tanggal, waktu_awal, waktu_akhir);

                current.add(1, 'days');  
            }

            $('#mdl_pilih_waktu_button_loader').hide();
            $('#mdl_pilih_waktu_button').show();
            

            $('#mdl_range_tanggal').modal('hide');
        }

        function upload_dokumen(idfile){
            $('#upload_idfile_ajuan_penelitian').val(idfile);
            $('#mdl_upload_dokumen').modal('show');
            $('#mdl_upload_dokumen_nama_file').val('');
            $('#mdl_upload_dokumen_file').val('');
        }

        function simpan_waktu(tanggal, waktu_awal, waktu_akhir){
            if(tanggal == '' || waktu_awal == '' || waktu_akhir == ''){
                alert('Semua field harus diisi');
                return;
            }

            $.ajax({
                url: "{{ route('penelitian_mhs_cek_simpan_waktu') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    tanggal: tanggal,
                    jam_mulai: waktu_awal,
                    jam_akhir: waktu_akhir,
                    idpenelitian: '{{ $penelitian[0]->idpenelitian }}'
                },
                success: function(response) {
                    console.log(response);
                    if(response.code == 200){
                        var div = $('#accordionWaktuInstrumen');

                        var isi = '<div class="accordion__item">\
                                        <div class="accordion__header collapsed" data-toggle="collapse" data-target="#left-indicator_'+response.data.idwaktu+'">\
                                            <span class="accordion__header--text">Jadwal '+tanggal+' '+waktu_awal+' - '+waktu_akhir+'</span>\
                                            <span class="accordion__header--indicator"></span>\
                                        </div>\
                                        <div id="left-indicator_'+response.data.idwaktu+'" class="collapse accordion__body" data-parent="#accordionWaktuInstrumen">\
                                            <div class="accordion__body--text">\
                                                <div class="row mb-3">\
                                                    <div class="col-lg-3 col-md-4 col-sm-6">\
                                                        <button type="button" class="btn btn-sm btn-info" onclick="editJadwal(this, '+response.data.idwaktu+')">Edit Jadwal</button>\
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="hapusJadwal(this, '+response.data.idwaktu+')">Hapus Jadwal</button>\
                                                    </div>\
                                                    <div class="col-lg-3 col-md-4 col-sm-6">\
                                                        <button type="button" class="btn btn-sm btn-success" onclick="tambahInstrumen(this, '+response.data.idwaktu+')">Tambah Instrumen</button>\
                                                    </div>\
                                                </div>\
                                                <b>Instrumen yang digunakan:</b>\
                                                <ul id="instrumen_'+response.data.idwaktu+'">\
                                                </ul>\
                                            </div>\
                                        </div>\
                                    </div>';

                        div.append(isi);
                        $('#mdl_satu_tanggal').modal('hide');

                    }
                    else{
                        alert(response.message);
                    }

                    return [response.code, response.message];
                },
                error: function(response) {
                    alert('Terjadi kesalahan saat menyimpan waktu pelaksanaan:'+response.message);
                    return [500, response.message];
                }
            });
        }

        function pilih_waktu(){
            $('#mdl_satu_waktu_button').hide();
            $('#mdl_satu_waktu_button_loader').show();
            var tanggal = $('#mdl_satu_tanggal_awal').val();
            var waktu_awal = $('#mdl_satu_waktu_awal').val();
            var waktu_akhir = $('#mdl_satu_waktu_akhir').val();

            var respons = simpan_waktu(tanggal, waktu_awal, waktu_akhir);

            if(respons[0] == 200){
                $('#mdl_satu_tanggal').modal('hide');
                $('#mdl_satu_waktu_button').show();
                $('#mdl_satu_waktu_button_loader').hide();
            }
            else{
                $('#mdl_satu_waktu_button').show();
                $('#mdl_satu_waktu_button_loader').hide();
            }

        }
    </script>
        
@endsection