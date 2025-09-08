@extends('layout_home_mobile')

@section('title', 'Permintaan Layanan')

@section('page-css')
    <!-- Daterange picker -->
    <link href="{{ asset('app-assets') }}/vendor/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Permintaan Layanan</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Transaksi</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Permintaan Layanan</a></li>
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
                                    
                                </div>
                                <div>
                                    
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Range Tanggal</label>
                                        <div class="col-sm-9">
                                            <input class="form-control input-daterange-datepicker" type="text" id="idrangetgl" value="{{ $tgl_awal }} - {{ $tgl_akhir }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12" id="btn_caritarik">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label"></label>
                                        <div class="col-sm-9">
                                            <button type="button" class="btn btn-success" onclick="getdata()">Get Data</button>
                                            <button type="button" class="btn btn-warning" style="margin-left:20px" onclick="tarikdata()">Tarik Data Dari Unit Kerja</button>
                                        </div>
                                    </div>
                                                                        
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row">
                        @foreach($permintaan_layanan as $pl)
                            <div class="col-md-6 col-sm-12">
                                <div class="card">
                                    <div class="card-body                                         
                                        @if($pl->status == '2')
                                            text-white bg-info  
                                        @elseif($pl->status == '3')
                                            text-white bg-success
                                        @elseif($pl->status == '4')
                                            text-white bg-danger
                                        @endif
                                        ">
                                        <h4>{{ $pl->nama_layanan }} - {{ $pl->detail_layanan }}</h4>
                                        <ul class="list-group mb-3 list-group-flush">
                                            <li class="list-group-item px-0 border-top-0 d-flex justify-content-between"><span class="mb-0">ID :</span>
                                                <strong>{{ $pl->idpermintaan_layanan }} - ({{ $pl->idlayanan_aplikasi_asal }})</strong></li>
                                            <li class="list-group-item px-0 d-flex justify-content-between">
                                                <span class="mb-0">Tgl Masuk :</span><strong>{{ $pl->ts_req_masuk_aplikasi_asal }}</strong></li>
                                            <li class="list-group-item px-0 d-flex justify-content-between">
                                                <span class="mb-0">Status :</span>
                                                @if($pl->status == '1') 
                                                    <strong style="color:orange">Menunggu</strong> 
                                                @elseif($pl->status == '2')
                                                    <strong>Proses</strong>   
                                                @elseif($pl->status == '3')
                                                    <strong>Selesai</strong>   
                                                @elseif($pl->status == '4')
                                                    <strong>Dibatalkan</strong>
                                                @endif
                                            </li>
                                            
                                        </ul>
                                        @if($pl->status < 3)
                                            <a onclick="detail({{ $pl->idpermintaan_layanan }})" href="{{ route('permintaanlayanan_detail_admin', ['id' => encrypt($pl->idpermintaan_layanan)]) }}" class="btn btn-primary">Proses</a>
                                        @else
                                            <a onclick="detail({{ $pl->idpermintaan_layanan }})" href="{{ route('permintaanlayanan_detail_admin', ['id' => encrypt($pl->idpermintaan_layanan)]) }}" class="btn btn-secondary">Lihat</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>               
        </div>
    </div>

    <form action="{{ route('permintaanlayanan_get_permintaan_admin') }}" method="POST" id="form_cari">
        @csrf
        <input type="hidden" name="idunitkerja" value="{{ session('userdata')['idunit_kerja'] }}">
        <input type="hidden" name="rangetanggal" id="sb_get_rangetanggal" >
    </form>

    <form action="{{ route('permintaanlayanan_tarik_permintaan_admin') }}" method="POST" id="form_tarik">
        @csrf
        <input type="hidden" name="idunitkerja" value="{{ session('userdata')['idunit_kerja'] }}">
        <input type="hidden" name="rangetanggal" id="sb_tarik_rangetanggal" >
    </form>
@endsection

@section('javascript')
    <script src="{{ asset('app-assets') }}/vendor/moment/moment.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="{{ asset('app-assets') }}/vendor/datatables/js/jquery.dataTables.min.js"></script>

    <!-- pickdate -->
    {{-- <script src="{{ asset('app-assets') }}/vendor/pickadate/picker.js"></script>
    <script src="{{ asset('app-assets') }}/vendor/pickadate/picker.time.js"></script>
    <script src="{{ asset('app-assets') }}/vendor/pickadate/picker.date.js"></script> --}}

    <script>
        $(document).ready(function() {
            $('.input-daterange-datepicker').daterangepicker({
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse',
                locale: {
                    format: 'YYYY-MM-DD' // Ini yang menentukan format tampilan
                }
            });

        });

        function detail(id) {
            $('#row-' + id).html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
        }

        function getdata() {
            $('#btn_caritarik').html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            var rangetgl = document.getElementById("idrangetgl").value;
            document.getElementById("sb_get_rangetanggal").value = rangetgl;
            document.getElementById("form_cari").submit();
        }

        function tarikdata() {
            $('#btn_caritarik').html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            var rangetgl = document.getElementById("idrangetgl").value;
            document.getElementById("sb_tarik_rangetanggal").value = rangetgl;
            document.getElementById("form_tarik").submit();
        }
    </script>
@endsection