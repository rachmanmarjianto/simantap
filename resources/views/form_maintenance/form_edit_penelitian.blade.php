@extends('layout_home')

@section('title', 'Form - Edit Form')

@section('page-css')
    <link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('app-assets') }}/vendor/summernote/summernote.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.4/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Edit Form</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('form_maintenance_index') }}">Form</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('form_maintenance_unit_kerja', ['id' => encrypt($template[0]->idunit_kerja)]) }}">Detail Unit Kerja</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Form</a></li>
                        
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
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="flex-grow-1">
                                    <h4 class="card-title">Edit Form {{ $template[0]->nama_template }} <span style="cursor:pointer; color:blue" data-toggle="modal" data-target="#mdl_edit_nama_form"><i class="fa fa-wrench" ></i></span></h4>
                                </div>
                                <div>
                                    <a href="{{ route('form_maintenance_unit_kerja', ['id' => encrypt($template[0]->idunit_kerja)]) }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Pilih Peruntukan Form</label>
                                <select class="form-control" id="sel1" name="jenis_form" onchange="ubahjenisform(this.value)">
                                    <option value="">-- Pilih Jenis Form --</option>
                                    <option value="3" {{ $template[0]->jenis_maintenance == 3 ? 'selected' : '' }}>Penelitian</option>
                                </select>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">                       
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="flex-grow-1">
                                    <h4 class="card-title">Atur Form</h4>
                                    
                                </div>
                                <div class="flex-grow-1">
                                    <span style="float:right; color:green; cursor:pointer; font-size:20px" onclick="tambahElemen()"><i class="fa fa-plus-circle"></i></span>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <h2 style="text-align: center">FORM PELAKSANAAN {{ $template[0]->jenis_maintenance == 3 ? 'PENELITIAN' : 'PRAKTIKUM' }} {{ $template[0]->internal == 1 ? 'INTERNAL' : 'EKSTERNAL' }}</h2>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Topik Penelitian</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{ Wajib }" readonly>
                                </div>
                                <div class="col-sm-1">
                                    1
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Dosen Pembimbing</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{ Wajib }" readonly>
                                </div>
                                <div class="col-sm-1">
                                    2
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Ruangan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{ Wajib }" readonly>
                                </div>
                                <div class="col-sm-1">
                                    3
                                </div>
                            </div>

                            <form action="{{ route('form_maintenance.edit.simpan_nilai_default') }}" method="POST">
                                @csrf
                                <input type="hidden" name="idtemplate_maintenance" value="{{ $template[0]->idtemplate_maintenance }}">

                                @php
                                    $flag = 0;
                                @endphp

                                @foreach($layout as $el)
                                    @if($el['jenis_isi'] == 1)
                                        {{-- <div class="card-body"> --}}
                                        <div class="form-group row">
                                            <div class="col-sm-11">
                                                <h5 style="font-weight: bold;">{{ $el['nilai_tampil'] }}</h5>
                                            </div>
                                            <div class="col-sm-1">
                                                {{ $el['urutan'] }} <span style="cursor:pointer; color:blue" onclick="editElTemplate({{ $el['idisi_template'] }})"><i class="fa fa-wrench" ></i></span></h4>
                                            </div>
                                        </div>
                                        

                                        @if(count($el['children']) > 0)
                                            @php
                                                $flag = 1;
                                            @endphp
                                            @foreach($el['children'] as $child)
                                                @if($child['jenis_isi'] == 2)
                                                    <div class="form-group row">
                                                        <label class="col-sm-2 col-form-label">{{ $child['nilai_tampil'] }}</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="default[{{ $child['idisi_template'] }}]" class="form-control" value="{{ $child['nilai_default'] }}" >
                                                        </div>
                                                        <div class="col-sm-1">
                                                            {{ $child['urutan'] }} <span style="cursor:pointer; color:blue" onclick="editElTemplate({{ $child['idisi_template'] }})"><i class="fa fa-wrench" ></i></span></h4>
                                                        </div>
                                                    </div>
                                                
                                                @elseif($child['jenis_isi'] == 3)
                                                    <div class="form-group row">
                                                        <div class="col-sm-11">
                                                            <textarea  class="summernote" name="default[{{ $child['idisi_template'] }}]"> {{ $child['nilai_default'] }} </textarea>
                                                        </div>
                                                        <div class="col-sm-1">
                                                            {{ $child['urutan'] }} <span style="cursor:pointer; color:blue" onclick="editElTemplate({{ $child['idisi_template'] }})"><i class="fa fa-wrench" ></i></span></h4>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                        {{-- </div> --}}
                                    
                                    @elseif($el['jenis_isi'] == 2)
                                        {{-- <div class="card-body"> --}}
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">{{ $el['nilai_tampil'] }}</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="default[{{ $el['idisi_template'] }}]" class="form-control" value="{{ $el['nilai_default'] }}" >
                                                </div>
                                                <div class="col-sm-1">
                                                    {{ $el['urutan'] }} <span style="cursor:pointer; color:blue" onclick="editElTemplate({{ $el['idisi_template'] }})"><i class="fa fa-wrench" ></i></span></h4>
                                                </div>
                                            </div>
                                        {{-- </div> --}}
                                    @endif
                                @endforeach
                                @if($flag > 0)
                                <div class="form-group row" >
                                    <div class="col-sm-11" style="text-align:right">
                                        <button type="submit" class="btn btn-success" style="float:right">Simpan Nilai Default</button>
                                    </div>
                                    <div class="col-sm-1"></div>
                                </div>
                                @endif
                            </form>

                            <div class="form-group col-md-12" style="padding-left:0px; padding-right:0px">
                                <label>Instrumen yang akan digunakan (wajib)</label>
                                <div class="col-sm-11">
                                    
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
                                <div class="col-sm-1">
                                    
                                </div>
                            </div>

                            <div class="form-group col-md-12" style="padding-left:0px; padding-right:0px">
                                <label>Bahan padat / cair yang diajukan (wajib)</label>
                                <div class="col-sm-11">
                                    
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
                                <div class="col-sm-1">
                                    
                                </div>
                            </div>

                            <div class="form-group col-md-12" style="padding-left:0px; padding-right:0px">
                                <label>Tanggal dan Waktu Pelaksanaan (wajib)</label>
                                <div class="col-sm-11">
                                    
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
                                <div class="col-sm-1">
                                    
                                </div>
                            </div>

                            <div class="form-group col-md-12" style="padding-left:0px; padding-right:0px">
                                <div class="d-flex justify-content-between">
                                    <b>Syarat Ajuan Pelaksanaan {{ $template[0]->jenis_maintenance == 3 ? 'Penelitian' : 'Praktikum' }}</b>
                                    <span style="cursor:pointer; color:green; font-size:20px" onclick="$('#mdl_tambah_syarat').modal('show')"><i class="fa fa-plus-circle"></i></span>
                                </div>
                                <table class="table table-bordered verticle-middle table-responsive-sm" width="100%" id="tbl_syarat_penelitian">
                                    <thead>
                                        <tr>
                                            <th scope="col">Syarat</th>
                                            <th scope="col">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($syarat_penelitian as $syarat)
                                            <tr id="syarat_row_{{ $syarat->idsyarat_ajuan_penelitian }}">
                                                <td>{{ $syarat->nama_syarat }}</td>
                                                <td id="btn_status_syarat_{{ $syarat->idsyarat_ajuan_penelitian }}">
                                                    @if($syarat->status == 1)
                                                        <button class="btn btn-success btn-sm" onclick="gantistatussyarat({{ $syarat->idsyarat_ajuan_penelitian }}, 0)">Aktif</button>
                                                    @else
                                                        <button class="btn btn-danger btn-sm" onclick="gantistatussyarat({{ $syarat->idsyarat_ajuan_penelitian }}, 1)">Tidak Aktif</button>
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

    <div class="modal fade" id="mdl_tambah_syarat">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group col-md-12">
                        <label>Syarat</label>
                        <input type="text" class="form-control" name="nama_syarat" id="mdl_nm_syarat" value="">
                    </div>
                </div>
                <div class="modal-footer" id="mdl_tambah_syarat_button">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="tambahkan_syarat()">Simpan</button>
                </div>
                <div class="modal-footer" id="mdl_tambah_syarat_button_loader" style="display:none;">
                    <button type="button" class="btn btn-secondary" disabled>Close</button>
                    <button type="button" class="btn btn-primary" disabled>Menyimpan...</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mdl_edit_nama_form">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Nama Form</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('form_maintenance_edit_nama_template') }}" id="form_edit_nama_template">
                        @csrf
                        <div class="form-group col-md-12">
                            <label>ID Form</label>
                            <input type="text" class="form-control" name="idtemplate_maintenance" value="{{ $template[0]->idtemplate_maintenance }}" readonly>
                        </div>
                        <div class="form-group col-md-12">
                            <label>First Name</label>
                            <input type="text" class="form-control" name="nama_template" value="{{ $template[0]->nama_template }}">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('form_edit_nama_template').submit();">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    

    <div class="modal fade bd-example-modal-lg" id="mdl_tambah_elemen">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Element</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('form_maintenance_mdl_tambah_elemen') }}" id="mdl_form_tambah_elemen">
                        @csrf
                        <input type="hidden" name="idtemplate_maintenance" value="{{ $template[0]->idtemplate_maintenance }}">
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Urutan ke </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" name="urutan" id="mdl_urutan_element" readonly>
                            </div>
                        </div>

                        <div class="form-group row" >
                            <label class="col-sm-2 col-form-label">Level</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="mdl_sel_level_element" name="level_element" onchange="mdl_gantilevel(this.value)">
                                    @for($i = 1; $i <= $level_max; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jenis Element </label>
                            <div class="col-sm-10">
                                <select class="form-control" id="mdl_sel_jenis_element" name="jenis_element" onchange="mld_ubahjeniselemen(this.value)">
                                    <option value="1">Judul Topic</option>
                                    <option value="2">Input Field</option>
                                    <option value="3">Text Editor</option>
                                </select>
                            </div>                        
                        </div>

                        <div id="mdl_parent">
                            <div class="form-group row" >
                                <label class="col-sm-2 col-form-label">Parent</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="mdl_sel_parent_element" name="parent_element">
                                                                                
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="mdl_nilai_tampil">
                            <div class="form-group row" >
                                <label class="col-sm-2 col-form-label">Nilai Tampil</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="nilai_tampil" >
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="document.getElementById('mdl_form_tambah_elemen').submit();">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="mdl_edit_elemen">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Element</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="mdl_edit_elemen.loader" style="text-align:center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div id="mdl_edit_elemen.content" style="display:none;">
                        <form action="{{ route('form_maintenance.simpan_edit_elemen') }}" method="POST" id="mdl_edit_elemen.simpan_edit_elemen">
                            @csrf
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group col-md-12">
                                        <label>ID Elemen</label>
                                        <input type="text" class="form-control" name="idisi_template" value="" id="mdl_edit_elemen.idtemplate" readonly>
                                    </div>
                                </div>
                                <div class="col-sm-9">
                                    <div class="form-group col-md-12">
                                        <label>Urutan</label>
                                        <input type="number" class="form-control" name="urutan" value="" id="mdl_edit_elemen.urutan">
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="form-group col-md-12">
                                <label>Nilai tampil</label>
                                <input type="text" class="form-control" name="nilai_tampil" value="" id="mdl_edit_elemen.nilai_tampil">
                            </div>
                        </form>
                        
                        <button type="button" class="btn btn-danger" onclick="hapusElemen()">Hapus Element</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="simpanmdlForm(this)">Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <form id="form_ubah_jenis_form" method="POST" action="{{ route('form_maintenance_ubah_jenis_form') }}">
        @csrf
        <input type="hidden" name="idtemplate_maintenance" value="{{ $template[0]->idtemplate_maintenance }}">
        <input type="hidden" name="jenis_maintenance" id="input_jenis_maintenance" value="">
    </form>

    <form id="form_hapus_elemen_template" method="POST" action="{{ route('form_maintenance.hapus_elemen_template') }}">
        @csrf
        <input type="hidden" name="idisi_template" id="hapus_elemen_template_idisi_template" value="">
    </form>
@endsection

@section('javascript')
    <script src="{{ asset('app-assets') }}/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendor/summernote/js/summernote.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.4/dist/sweetalert2.all.min.js"></script>

    <script>

        var jumlah_elemen = {{ count($isitemplate) + 3 }};

        jQuery(document).ready(function() {
            $(".summernote").summernote({
                height: 190,
                minHeight: null,
                maxHeight: null,
                focus: !1
            }), $(".inline-editor").summernote({
                airMode: !0
            })
        }), window.edit = function() {
            $(".click2edit").summernote()
        }, window.save = function() {
            $(".click2edit").summernote("destroy")
        };

        function tambahkan_syarat(){
                $('#mdl_tambah_syarat_button').hide();
                $('#mdl_tambah_syarat_button_loader').show();

                var nama_syarat = document.getElementById('mdl_nm_syarat').value;
                if(nama_syarat == null || nama_syarat == ''){
                    alert('Nama syarat tidak boleh kosong');
                    return;
                }
    
                // Simpan syarat ke database
                $.ajax({
                    url: "{{ route('form_maintenance.tambah_syarat_penelitian') }}",
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        idtemplate_maintenance: '{{ $template[0]->idtemplate_maintenance }}',
                        nama_syarat: nama_syarat
                    },
                    success: function(response) {
                        console.log(response);
                        if(response.code == 200){
                            var tablesyarat = $('#tbl_syarat_penelitian tbody');

                            var newRow = '<tr id="syarat_row_'+response.data['idsyarat_ajuan_penelitian']+'">'+
                                            '<td>'+response.data.nama_syarat+'</td>'+
                                            '<td id="btn_status_syarat_'+response.data['idsyarat_ajuan_penelitian']+'"><button class="btn btn-success btn-sm" onclick="gantistatussyarat('+response.data['idsyarat_ajuan_penelitian']+', 0)">Aktif</button></td>'+
                                        '</tr>';

                            tablesyarat.append(newRow);

                            $('#mdl_tambah_syarat_button').show();
                            $('#mdl_tambah_syarat_button_loader').hide();
                            $('#mdl_nm_syarat').val('');
                            $('#mdl_tambah_syarat').modal('hide');
                        }
                        else{
                            alert('Gagal menyimpan syarat');
                            $('#mdl_tambah_syarat_button').show();
                            $('#mdl_tambah_syarat_button_loader').hide();
                        }
                    }
                });
        }

        function simpanmdlForm(e){
            e.preventDefault();
            e.target.disabled = true;
            e.target.innerText = 'Menyimpan...';
            document.getElementById('mdl_edit_elemen.simpan_edit_elemen').submit();
            
        }

        function hapusElemen(){
            var idisi_template = document.getElementById('mdl_edit_elemen.idtemplate').value;

            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data elemen akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('hapus_elemen_template_idisi_template').value = idisi_template;
                    document.getElementById('form_hapus_elemen_template').submit();
                }
            });
        }

        function ubahjenisform(val){
            document.getElementById('input_jenis_maintenance').value = val;
            if(val == null || val == ''){
                return;
            }
            document.getElementById('form_ubah_jenis_form').submit();
        }

        function tambahElemen(){
            document.getElementById('mdl_parent').style.display = 'none';
            jumlah_elemen_baru = jumlah_elemen + 1;
            document.getElementById('mdl_urutan_element').value = jumlah_elemen_baru;
            level = document.getElementById('mdl_sel_level_element').options[document.getElementById('mdl_sel_level_element').selectedIndex].value;
            mdl_gantilevel(level);
            $('#mdl_tambah_elemen').modal('show');
        }

        function mld_ubahjeniselemen(value){
            if(value == 3){
                document.getElementById('mdl_nilai_tampil').style.display = 'none';
            } else {
                document.getElementById('mdl_nilai_tampil').style.display = 'block';
            }
        }

        function mdl_gantilevel(value){
            console.log(value);

            if(value > 1){
                value--;
                var selectparent = document.getElementById('mdl_sel_jenis_element');
                selectparent.innerHTML = '';
                selectparent.options[0] = new Option('Input Field', '2');
                selectparent.options[1] = new Option('Text Editor', '3');
                // document.getElementById('mdl_sel_jenis_element').selectpicker('refresh');
                $('#mdl_sel_jenis_element').selectpicker('refresh');


                $.ajax({
                    url: "{{ route('get_parent_element') }}",
                    type: "POST",
                    data: {
                        _token: '{{ csrf_token() }}',
                        idtemplate_maintenance: '{{ $template[0]->idtemplate_maintenance }}',
                        level: value
                    },
                    success: function(response) {
                        if(response.code == 200){
                            if(response.data.length > 0){
                                var select = document.getElementById('mdl_sel_parent_element');
                                select.innerHTML = '';
                                response.data.forEach(function(item) {
                                    console.log(item.idisi_template);
                                    var option = document.createElement('option');

                                    var jenis_parent = '';
                                    if(item.jenis_isi == 1){
                                        jenis_parent = 'Judul Topic';
                                    } else if(item.jenis_isi == 2){
                                        jenis_parent = 'Input Field';
                                    } else if(item.jenis_isi == 3){
                                        jenis_parent = 'Text Editor';
                                    }

                                    option.value = item.idisi_template;
                                    option.text = jenis_parent+ ' - '+item.nilai_tampil;
                                    select.appendChild(option);
                                });
                                $('#mdl_sel_parent_element').selectpicker('refresh');
                                document.getElementById('mdl_parent').style.display = 'block';
                            } else {
                                alert('ERROR: Tidak ada parent untuk level tersebut');
                                document.getElementById('mdl_parent').style.display = 'none';
                            }
                        }
                        else{
                            alert('ERROR: Tidak ada parent untuk level tersebut');
                            document.getElementById('mdl_parent').style.display = 'none';
                        }
                    }
                });
            }
            else{
                var selectparent = document.getElementById('mdl_sel_jenis_element');
                selectparent.innerHTML = '';
                selectparent.options[0] = new Option('Judul Topic', '1');
                selectparent.options[1] = new Option('Input Field', '2');
                selectparent.options[2] = new Option('Text Editor', '3');
                // document.getElementById('mdl_sel_jenis_element').selectpicker('refresh');
                $('#mdl_sel_jenis_element').selectpicker('refresh');

                document.getElementById('mdl_sel_parent_element').innerHTML = '';
                document.getElementById('mdl_parent').style.display = 'none';
            }
        }

        function editElTemplate(idisi_template){
            document.getElementById('mdl_edit_elemen.loader').style.display = 'block';
            document.getElementById('mdl_edit_elemen.content').style.display = 'none';
            $('#mdl_edit_elemen').modal('show');

            $.ajax({
                url: "{{ route('form_maintenance.edit.get_isi_template') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    idisi_template: idisi_template
                },
                success: function(response) {
                    // console.log(response);

                    document.getElementById('mdl_edit_elemen.idtemplate').value = response.data.idisi_template;
                    document.getElementById('mdl_edit_elemen.urutan').value = response.data.urutan;
                    document.getElementById('mdl_edit_elemen.nilai_tampil').value = response.data.nilai_tampil;

                    document.getElementById('mdl_edit_elemen.loader').style.display = 'none';
                    document.getElementById('mdl_edit_elemen.content').style.display = 'block';
                },
                error: function(xhr) {
                    alert('ERROR: Terjadi kesalahan saat mengambil data elemen template.');
                }
            });
        }

        function gantistatussyarat(idsyarat_ajuan_penelitian, status_baru){
            var btn_status = document.getElementById('btn_status_syarat_'+idsyarat_ajuan_penelitian);

            btn_status.innerHTML = '<div class="spinner-border spinner-border-sm text-primary" role="status">\
                                        <span class="sr-only">Loading...</span>\
                                    </div>';
        
           

            $.ajax({
                url: "{{ route('form_maintenance.ganti_status_syarat_penelitian') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    idsyarat_ajuan_penelitian: idsyarat_ajuan_penelitian,
                    status_baru: status_baru
                },
                success: function(response) {
                    if(response.code == 200){
                        
                        if(status_baru == 1){
                            btn_status.innerHTML = '<button class="btn btn-success btn-sm" onclick="gantistatussyarat('+idsyarat_ajuan_penelitian+', 0)">Aktif</button>';
                        } else {
                            btn_status.innerHTML = '<button class="btn btn-danger btn-sm" onclick="gantistatussyarat('+idsyarat_ajuan_penelitian+', 1)">Tidak Aktif</button>';
                        }
                    }
                    else{
                        alert('Gagal mengganti status syarat');
                        if(status_baru == 1){
                            btn_status.innerHTML = '<button class="btn btn-danger btn-sm" onclick="gantistatussyarat('+idsyarat_ajuan_penelitian+', 1)">Tidak Aktif</button>';
                            
                        } else {
                            btn_status.innerHTML = '<button class="btn btn-success btn-sm" onclick="gantistatussyarat('+idsyarat_ajuan_penelitian+', 0)">Aktif</button>';
                        }
                    }
                },
                error: function(xhr) {
                    alert('ERROR: Terjadi kesalahan saat mengganti status syarat.');
                    
                    if(status_baru == 1){
                        btn_status.innerHTML = '<button class="btn btn-danger btn-sm" onclick="gantistatussyarat('+idsyarat_ajuan_penelitian+', 1)">Tidak Aktif</button>';
                        
                    } else {
                        btn_status.innerHTML = '<button class="btn btn-success btn-sm" onclick="gantistatussyarat('+idsyarat_ajuan_penelitian+', 0)">Aktif</button>';
                    }
                }
            });
        }
    </script>
        
@endsection