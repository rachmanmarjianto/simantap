@extends('layout_home')

@section('title', 'Form Maintenance - Edit Form')

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
                        <h4>Form {{ $jenis_maintenance == 1 ? 'KALIBRASI' : 'MAINTENANCE' }}</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('proses_maintenance_index') }}">Proses Maintenance</a></li>
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
                                    <h4 class="card-title"></h4>
                                    
                                </div>
                                <div class="flex-grow-1">
                                    <a href="{{ route('proses_maintenance_index') }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <h2 style="text-align: center">DATA {{ $jenis_maintenance == 1 ? 'KALIBRASI' : 'MAINTENANCE' }}</h2>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">ID Form {{ $jenis_maintenance == 1 ? 'KALIBRASI' : 'MAINTENANCE' }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $maintenance_aset->idmaintenance_aset }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Kode Barang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $maintenance_aset->kode_barang_aset }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Nama Barang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $maintenance_aset->nama_barang }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Merk Barang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $maintenance_aset->merk_barang }} {{ $maintenance_aset->keterangan }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tanggal {{ $jenis_maintenance == 1 ? 'Kalibrasi' : 'Maintenance' }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{ Terisi otomatis }" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tempat {{ $jenis_maintenance == 1 ? 'Kalibrasi' : 'Maintenance' }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $maintenance_aset->nama_ruang }} # {{ $maintenance_aset->nama_gedung }} # {{ $maintenance_aset->nama_kampus }}" readonly>
                                </div>
                            </div>
                            

                            @foreach($layout as $el)
                                @if($el['jenis_isi'] == 1)
                                    {{-- <div class="card-body"> --}}
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <h5 style="font-weight: bold;">{{ $el['nilai_tampil'] }}</h5>
                                        </div>
                                    </div>
                                    

                                    @if(count($el['children']) > 0)
                                        @foreach($el['children'] as $child)
                                            @php
                                                if(empty($child['nilai']) || is_null($child['nilai'])) {
                                                    $isi_nilai = $child['nilai_default'];
                                                }
                                                else{
                                                    $isi_nilai = $child['nilai'];
                                                }
                                            @endphp 

                                            @if($child['jenis_isi'] == 2)
                                                

                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">{{ $child['nilai_tampil'] }}</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="default[{{ $child['idisi_maintenanceaset'] }}]" class="form-control" value="{{ $isi_nilai }}" form="form_submit_maintenance_proses" >
                                                    </div>
                                                </div>
                                            
                                            @elseif($child['jenis_isi'] == 3)
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <textarea  class="summernote" name="default[{{ $child['idisi_maintenanceaset'] }}]" form="form_submit_maintenance_proses"> {{ $isi_nilai }} </textarea>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    {{-- </div> --}}
                                
                                @elseif($el['jenis_isi'] == 2)
                                    {{-- <div class="card-body"> --}}
                                        @php
                                            if(empty($el['nilai']) || is_null($el['nilai'])) {
                                                $isi_nilai = $el['nilai_default'];
                                            }
                                            else{
                                                $isi_nilai = $el['nilai'];
                                            }
                                        @endphp 
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">{{ $el['nilai_tampil'] }}</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="default[{{ $el['idisi_maintenanceaset'] }}]" class="form-control" value="{{ $isi_nilai }}" form="form_submit_maintenance_proses">
                                            </div>
                                        </div>
                                    {{-- </div> --}}
                                @endif
                            @endforeach
                                
                            @php
                                if(empty(session('userdata')['gelar_depan']) || is_null(session('userdata')['gelar_depan']) || session('userdata')['gelar_depan'] == '') {
                                    $gelar_depan = '';
                                } else {
                                    $gelar_depan = session('userdata')['gelar_depan'] . ' ';
                                }

                                if(empty(session('userdata')['gelar_belakang']) || is_null(session('userdata')['gelar_belakang']) || session('userdata')['gelar_belakang'] == '') {
                                    $gelar_belakang = '';
                                } else {
                                    $gelar_belakang = ', ' . session('userdata')['gelar_belakang'];
                                }
                                $nama_personil = $gelar_depan . session('userdata')['nama'] . $gelar_belakang;
                            @endphp
                            
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Personil Pelaksana</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $nama_personil }}" readonly>
                                </div>
                            </div>
                            @php
                                $gelar_depan = empty($maintenance_aset->gelar_depan_penanggungjawab) ? '' : $maintenance_aset->gelar_depan_penanggungjawab . ' ';
                                $gelar_belakang = empty($maintenance_aset->gelar_belakang_penanggungjawab) ? '' : ', ' . $maintenance_aset->gelar_belakang_penanggungjawab;
                                $nama_penanggungjawab = $gelar_depan . $maintenance_aset->nama_penanggungjawab . $gelar_belakang;
                            @endphp
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Penanggung Jawab Ruang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $nama_penanggungjawab }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Rekomendasi Status Aset</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="sel1" name="rekom_kondisi_aset" form="form_submit_maintenance_proses" onchange="change_rekom_kondisi_aset()">
                                        <option value="1" @if($maintenance_aset->rekom_kondisi_aset == 1) selected @endif>Baik</option>
                                        <option value="2" @if($maintenance_aset->rekom_kondisi_aset == 2) selected @endif>Rusak Ringan</option>
                                        <option value="3" @if($maintenance_aset->rekom_kondisi_aset == 3) selected @endif>Rusak Berat</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row" id="div_ajukan_maintenance" style="display: none;">
                                <label class="col-sm-2 col-form-label">Ajukan permintaan Maintenance?</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="idajukanmaintenance" name="ajukan_maintenance" form="form_submit_maintenance_proses">
                                        <option value="1" @if($maintenance_aset->permintaan_maintenance == 1) selected @endif>Ya</option>
                                        <option value="0" @if($maintenance_aset->permintaan_maintenance == 0) selected @endif>Tidak</option>
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <h5 style="font-weight: bold;">File</h5>
                                </div>
                            </div>

                            <form id="form_upload_file" method="POST" action="{{ route('proses_maintenance.form_upload_file_maintenance') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="idmaintenance_aset" value="{{ $maintenance_aset->idmaintenance_aset }}">
                                <div class="form-group row">
                                    <div class="col-lg-7 col-sm-12">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">Nama File:</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="nama_file" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-5 col-sm-12">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                {{-- <input type="file" class="custom-file-input" name="file" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" required>
                                                <label class="custom-file-label">Choose file</label> --}}
                                                <input type="file" class="form-control" name="document" required>
                                            </div>
                                            <div class="input-group-append" id="btn_upload_file">
                                                <button class="btn btn-primary" onclick="exec_upload_file()">Tambahkan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <span style="color:red">*File yang diupload akan tampil setelah proses upload selesai dan halaman direfresh</span>
                                    <div class="table-responsive">
                                        <table class="table table-bordered verticle-middle table-responsive-sm" id="table_file">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Nama File</th>
                                                    <th scope="col">File</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($files as $file)
                                                    <tr id="tr_file_{{ $file->idfile_maintenance }}">
                                                        <td>{{ $file->nama_file }}</td>
                                                        <td><a href="{{ route('filestorage_get', ['id' => encrypt($file->idfile_maintenance)]) }}" target="_blank">Lihat File</a></td>
                                                        <td id="td_btn_file_{{ $file->idfile_maintenance }}"><button class="btn btn-danger btn-sm" onclick="hapus_file({{ $file->idfile_maintenance }})">Hapus</button></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div class="col-sm-12 mt-3" style="text-align: right;" id="div_button_submit">
                                <button type="button" class="btn btn-danger" onclick="submit(4)">Batalkan Draft</button>
                                <button type="button" class="btn btn-warning" onclick="submit(1)">Simpan Sebagai Draft</button>
                                <button type="button" class="btn btn-success" onclick="submit(2)">Ajukan Verifikasi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <form id="form_submit_maintenance_proses" method="POST" action="{{ route('form_submit_maintenance_proses') }}">
        @csrf
        {{-- <input type="hidden" name="idtemplate_maintenance" value="{{ $template[0]->idtemplate_maintenance }}"> --}}
        <input type="hidden" name="idmaintenance_aset" value="{{ $maintenance_aset->idmaintenance_aset }}">
        <input type="hidden" name="status" id="idstatus">
    </form>
    <form id="form_batalkan_ajuan" method="POST" action="{{ route('form_batal_ajuan') }}">
        @csrf
        {{-- <input type="hidden" name="idtemplate_maintenance" value="{{ $template[0]->idtemplate_maintenance }}"> --}}
        <input type="hidden" name="idmaintenance_aset" value="{{ $maintenance_aset->idmaintenance_aset }}">
        <input type="hidden" name="status" id="idstatus_batal">
    </form>
@endsection

@section('javascript')
    <script src="{{ asset('app-assets') }}/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendor/summernote/js/summernote.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>

        var jenis_maintenance = {{ $jenis_maintenance }};
        

        jQuery(document).ready(function() {
            $(".summernote").summernote({
                height: 190,
                minHeight: null,
                maxHeight: null,
                focus: !1
            }), $(".inline-editor").summernote({
                airMode: !0
            });

            change_rekom_kondisi_aset();

        }), window.edit = function() {
            $(".click2edit").summernote()
        }, window.save = function() {
            $(".click2edit").summernote("destroy")
        };

        function exec_upload_file(){
            $('#btn_upload_file').html('<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...</button>');
            
            var form = $('#form_upload_file');
            if(form[0].checkValidity() === false){
                form[0].reportValidity();
                $('#btn_upload_file').html('<button class="btn btn-primary" onclick="exec_upload_file()">Tambahkan</button>');
                return;
            }

            $('#form_upload_file').submit();
        }

        function hapus_file(idfile){
            var idrow = '#tr_file_' + idfile;
            var id_btn = '#td_btn_file_' + idfile;
            $(id_btn).html('<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>');

            Swal.fire({
                title: "Hapus File?",
                text: "File yang sudah dihapus tidak dapat dikembalikan lagi",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus!"
                }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('filestorage_hapus') }}",
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            idfile_maintenance: idfile
                        },
                        success: function(response) {
                            $(id_btn).html('<button class="btn btn-danger btn-sm" onclick="hapus_file(' + idfile + ')">Hapus</button>');
                            if(response.code == 200){
                                Swal.fire("Deleted!", response.message, "success").then(() => {
                                    $(idrow).remove();
                                });
                            }
                            else{
                                Swal.fire("Error!", response.message, "error");
                            }
                        },
                        error: function(xhr) {
                            $(id_btn).html('<button class="btn btn-danger btn-sm" onclick="hapus_file(' + idfile + ')">Hapus</button>');
                            Swal.fire("Error!", "Terjadi kesalahan saat menghapus file.", "error");
                        }
                    });
                }
            });
        }

        function submit(status){

            if(status == 1){
                var warning = "Simpan sebagai draft ?";
            }
            else if(status == 4){
                var warning = "Batalkan Draft ?";
            }
            else{
                var warning = "Ajukan verifikasi ?";
            }

            Swal.fire({
                title: warning,
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya!"
                }).then((result) => {
                if (result.isConfirmed) {
                    $('#div_button_submit').html('<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...</button>');
                    if(status == 4){
                        $('#idstatus_batal').val(status);
                        $('#form_batalkan_ajuan').submit();
                        return;
                    }
                    else{
                        $('#idstatus').val(status);
                        $('#form_submit_maintenance_proses').submit();
                    }
                    
                }
            });
        }

        function change_rekom_kondisi_aset(){
            console.log('hit'+jenis_maintenance);

            var rekom_kondisi_aset = $('select[name="rekom_kondisi_aset"]').val();

            if(jenis_maintenance == 2){
                $('#div_ajukan_maintenance').hide();
                $('#idajukanmaintenance').val(0);
                //kalibrasi
                return;
            }
            else{
                if(rekom_kondisi_aset == 2 || rekom_kondisi_aset == 3){
                    $('#div_ajukan_maintenance').show();
                }
                else{
                    $('#div_ajukan_maintenance').hide();
                    $('#idajukanmaintenance').val(1);
                }
            }
            
        }

        
    </script>
        
@endsection