@extends('layout_home')

@section('title', 'Rekam Maintenance Aset')

@section('page-css')
    <!-- Material color picker -->
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
                    <form action="{{ route('penelitian_mhs_exec_tambah_penelitian') }}" method="POST" id="form-mulai-proses">
                        @csrf
                        <div class="card">                       
                            <div class="card-header">
                                <small style="color:red">* Jika masih belum yakin tujuan unit / fakultas, silahkan cek detail alat <a href="{{ route('alat_lab_index') }}">disini</a></small>
                            </div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Jenis Penelitian</label>
                                    <div class="col-sm-8">
                                        <select name="jenis_penelitian" id="jenis_penelitian" class="form-control" onchange="loadform(1)" required>
                                            <option value="true">Penelitian Internal</option>
                                            {{-- <option value="false">Penelitian Eksternal</option> --}}
                                        </select>
                                    </div>
                                    <div class="col-sm-1" id="loader_form_penelitian_1">
                                        
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Unit / Fakultas yang dituju</label>
                                    <div class="col-sm-8">
                                        <select name="unitfak" id="id_unitfak" class="form-control" onchange="loadform(2)" required>
                                            @foreach($unitfak as $uk)
                                                <option value="{{ $uk->id_unit_kerja }}">{{ $uk->type_unit_kerja??'' }} {{ $uk->nm_unit_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-1" id="loader_form_penelitian_2">
                                        
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
                                <div id="btn_mulai">
                                    <button type="button" class="btn btn-success" style="float:right" onclick="mulai()">Mulai Proses</button>
                                </div>
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

        

        window.onload = function() {
            loadform(2);
        };

        function mulai(){
            var form = document.getElementById('form-mulai-proses');
            if(!form.checkValidity()){
                form.reportValidity();
                return;
            }

            $('#btn_mulai').html('<button type="button" class="btn btn-success" style="float:right" disabled><i class="fa fa-spinner fa-spin"></i> Memulai Proses...</button>');

            $('#form-mulai-proses').submit();
        }

        function loadtemplate(){
            var tampilform = $('#tampil_form_template');
            tampilform.empty();
            tampilform.html('<div class="col-12" style="text-align: center;"><i class="fa fa-spinner fa-spin"></i></div>');

            $.ajax({
                url: "{{ route('penelitian_mhs_get_form_template') }}",
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

        function loadform(loc){
            internal = $('#jenis_penelitian').val();
            unitfak = $('#id_unitfak').val();
            $('#idform').empty();
            $('#loader_form_penelitian_'+loc).html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ route('penelitian_mhs_get_form_penelitian') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    internal: internal,
                    unitfak: unitfak
                },
                success: function(response) {
                    
                    $('#idform').append(new Option('-- Pilih Form --', ''));

                    $.each(response.data, function(index, data) {
                        console.log(data);
                        // $('#idform').append(option.text);
                        $('#idform').append(new Option(data.nama, data.id));
                    });

                    $('#idform').selectpicker('refresh');
                    $('#loader_form_penelitian_'+loc).empty();
                    
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