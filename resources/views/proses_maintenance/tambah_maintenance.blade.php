@extends('layout_home')

@section('title', 'Rekam Maintenance Aset')

@section('page-css')
    <!-- Material color picker -->
    {{-- <link href="{{ asset('app-assets') }}/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.0/dist/sweetalert2.min.css"> --}}
    <link href="{{ asset('app-assets') }}/vendor/summernote/summernote.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Rekam Maintenance Aset</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('proses_maintenance_index') }}">Proses Maintenance</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Rekam Maintenance Aset</a></li>
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
                                    <h4 class="card-title">Data Aset</h4>
                                </div>
                                <div>
                                    <a href="{{ route('proses_maintenance_index') }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Kode Aset</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="kode_barang_aset" value="{{ $aset->kode_barang_aset }}" form="form-mulai-proses" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Nama Aset</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $aset->nama_barang }} - {{ $aset->merk_barang }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Tahun</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $aset->tahun_aset }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Ruang</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $aset->nama_ruang }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Gedung</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $aset->nama_gedung }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Kampus</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $aset->nama_kampus }}" readonly>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <form action="{{ route('prosesmaintenance_simpan_mulai_maintenance_aset') }}" method="POST" id="form-mulai-proses">
                        @csrf
                        <div class="card">                       
                            <div class="card-header">
                                
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Jenis Maintenance</label>
                                    <div class="col-sm-8">
                                        <select name="jenis_maintenance" id="idjenis_maintenance" class="form-control" onchange="loadform()" required>
                                            <option value="1">Kalibrasi Internal</option>
                                            <option value="2">Kalibrasi Eksternal</option>
                                            <option value="3">Maintenance Internal</option>
                                            <option value="4">Maintenance Eksternal</option>
                                        </select>
                                    </div>
                                    <div class="col-sm-1" id="loader_jenis_maintenance">
                                        
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Form</label>
                                    <div class="col-sm-8">
                                        <select name="form" id="idform" class="form-control" onchange="loadtemplate()" required>
                                            
                                        </select>
                                    </div>
                                    <div class="col-sm-1" id="loader_form">

                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success" style="float:right">Mulai Proses</button>
                            </div>                        
                        </div>
                    </form>
                </div>

                <div class="col-12" >
                    <div class="card">                       
                        <div class="card-header">
                            <h3><b>PREVIEW</b></h3>
                        </div>
                        <div class="card-body" id="tampil_form_template">

                        </div>
                    </div>
                </div>

                {{-- <div class="col-12">
                    <div class="card">                       
                        <div class="card-header">
                            
                        </div>
                        <div class="card-body">
                            
                            <div  id="catatan_dll">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Catatan</label>
                                    <div class="col-sm-9">
                                        <textarea class="form-control" rows="4" id="comment"></textarea>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">File Pendukung</label>
                                    <div class="col-sm-9">
                                        <input type="file" class="form-control" >
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama File</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Waktu Maintenance</label>
                                <div class="col-sm-9">
                                    
                                    <div class="input-group" >
                                        <span class="input-group-btn" id="btn_ts_maintenance-{{ $aset->kode_barang_aset }}">
                                            <button type="button" id="check-minutes" class="btn waves-effect waves-light btn-ft btn-success" onclick="getts({{ $aset->kode_barang_aset }})"><i class="fa fa-clock-o" aria-hidden="true"></i></button>
                                        </span>
                                        <input type="text" id="ts-maintenance-{{ $aset->kode_barang_aset }}" name="ts_maintenance" class="form-control tspicker" value="{{ $timestamp_alat[$aset->kode_barang_aset]['timestamp_mulai'] ?? '' }}" onchange="simpan({{ $aset->kode_barang_aset }})">
                                        <span class="input-group-btn" >
                                            <button type="button" id="btn-simpan-awal-{{ $aset->kode_barang_aset }}" class="btn waves-effect waves-light btn-ft btn-primary" onclick="simpan({{ $aset->kode_barang_aset }})">
                                                <i class="fa fa-save"></i>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    <form id="form-selesai" action="{{ route('permintaan_layanan_set_status_admin') }}" method="POST">
        @csrf
        <input type="hidden" name="status" value="3">
    </form>
@endsection

@section('javascript')
    {{-- <script src="{{ asset('app-assets') }}/vendor/moment/moment.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.0/dist/sweetalert2.all.min.js"></script> --}}
    <script src="{{ asset('app-assets') }}/vendor/summernote/js/summernote.min.js"></script>

    <script>

        // function setselesai() {
        //     Swal.fire({
        //         title: 'Yakin merubah status proses ke SELESAI?',
        //         text: "Aksi ini tidak dapat dibatalkan!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'Ya, Selesaikan!'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             $('#form-selesai').submit();
        //         }
        //     });
        // }


        // $('.tspicker').bootstrapMaterialDatePicker({
        //     format: 'YYYY-MM-DD HH:mm'+':00'
        // });

       
        // function getts(kode_barang_aset) {
        //     let ts = moment().format('YYYY-MM-DD HH:mm:ss');

        //     $btnsave = $('#btn_ts_maintenance-' + kode_barang_aset);

        //     $btnsave.html('<i class="fa fa-spinner fa-spin"></i>');

        //     $.ajax({
        //         url: "{{ route('prosesmaintenance_simpan_ts_maintenance') }}",
        //         type: 'POST',
        //         data: {
        //             _token: '{{ csrf_token() }}',
        //             kode_barang_aset: kode_barang_aset,
        //             timestamp: ts
        //         },
        //         success: function(response) {
        //             console.log(response);
        //             // if (response.code === 200) {
        //             //     if (type === 1) {
        //             //         $('#mulai-' + kode_barang_aset).val(ts);
        //             //         $('#btnmulai-' + kode_barang_aset).html('<button type="button" class="btn btn-outline-success">Awal</button>');
        //             //     } else {
        //             //         $('#akhir-' + kode_barang_aset).val(ts);
        //             //         $('#btnakhir-' + kode_barang_aset).html('<button type="button" class="btn btn-outline-danger">Akhir</button>');
        //             //     }
                        

        //             // } else {
        //             //     alert('Error response:' + response.message);
        //             //     console.log(response);
        //             // }
        //             // $btnsave.html('<i class="fa fa-check" aria-hidden="true"></i>');
        //         },
        //         error: function(xhr, status, error) {
        //             alert('Error except: ' + error);
        //             $btnsave.html('<i class="fa fa-close"></i>');
        //         }
        //     });
        // }

        window.onload = function() {
            loadform();
        };

        function loadtemplate(){
            var tampilform = $('#tampil_form_template');
            tampilform.empty();
            tampilform.html('<div class="col-12" style="text-align: center;"><i class="fa fa-spinner fa-spin"></i></div>');

            $.ajax({
                url: "{{ route('prosesmaintenance_get_form_template') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idform: $('#idform').val()
                },
                success: function(response) {

                    console.log(response);
                    
                    tampilform.empty();
                    tampilform.html(response.data);
                    $(".summernote").summernote({
                        height: 190,
                        minHeight: null,
                        maxHeight: null,
                        focus: !1
                    }), $(".inline-editor").summernote({
                        airMode: !0
                    })
                    
                },
                error: function(xhr, status, error) {
                    alert('Error except: ' + error);
                    tampilform.empty();
                    // $btnsave.html('<i class="fa fa-close"></i>');
                }
            });

        }

        function loadform(){
            idjenis_maintenance = $('#idjenis_maintenance').val();
            $('#idform').empty();
            $('#loader_form').html('<i class="fa fa-spinner fa-spin"></i>');

            if(idjenis_maintenance < 3){
                jenis_maintenance = 1;
            }
            else{
                jenis_maintenance = 2;
            }

            $.ajax({
                url: "{{ route('prosesmaintenance_get_form') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    jenis_maintenance: jenis_maintenance
                },
                success: function(response) {
                    
                    $('#idform').append(new Option('-- Pilih Form --', ''));

                    $.each(response.data, function(index, data) {
                        console.log(data);
                        // $('#idform').append(option.text);
                        $('#idform').append(new Option(data.nama, data.id));
                    });

                    $('#idform').selectpicker('refresh');
                    $('#loader_form').empty();
                    
                },
                error: function(xhr, status, error) {
                    alert('Error except: ' + error);
                    $('#loader_form').empty();
                    // $btnsave.html('<i class="fa fa-close"></i>');
                }
            });

        }

        

    </script>
@endsection