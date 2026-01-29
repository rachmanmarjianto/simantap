@extends('layout_home')

@section('title', 'Rekam Penggunaan Alat Lab')

@section('page-css')
    <!-- Material color picker -->
    {{-- <link href="{{ asset('app-assets') }}/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.0/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Rekam Penggunaan Alat Lab</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Transaksi</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('permintaan_layanan_index_admin') }}">Permintaan Layanan</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Rekam Penggunaan Alat Lab</a></li>
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
                                    <h4 class="card-title">Detail Permintaan Layanan</h4>
                                </div>
                                <div>
                                    <a href="{{ route('permintaan_layanan_index_admin') }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ID Permintaan Layanan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $permintaan_layanan->idpermintaan_layanan }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">ID Permintaan Layanan Unit Kerja Asal</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $permintaan_layanan->idlayanan_aplikasi_asal }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Nama Layanan</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $permintaan_layanan->nama_layanan }} - {{ $permintaan_layanan->detail_layanan }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Waktu Permintaan Diterima</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $permintaan_layanan->ts_req_masuk_aplikasi_asal }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Status</label>
                                <div class="col-sm-9">
                                    @if($permintaan_layanan->status == '1')
                                        <input id="status_layanan" type="text" class="form-control" value="Menunggu" style="color:orange" readonly>
                                    @elseif($permintaan_layanan->status == '2')
                                        <input id="status_layanan" type="text" class="form-control" value="Diproses" style="color:blue" readonly>
                                    @elseif($permintaan_layanan->status == '3')
                                        <input id="status_layanan" type="text" class="form-control" value="Selesai" style="color:green" readonly>
                                    @elseif($permintaan_layanan->status == '4')
                                        <input id="status_layanan" type="text" class="form-control" value="Ditolak" style="color:red" readonly>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Laboran</label>
                                <div class="col-sm-9">
                                    <ul>
                                    @foreach($laboran as $lab)
                                        <li>- {{ $lab->nama }} {{ $lab->gelar_depan }} {{ $lab->gelar_belakang }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">                       
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="flex-grow-1">
                                    <h4 class="card-title">Penggunaan Alat Lab</h4>
                                </div>
                                <div>

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th>No Urut</th>
                                            <th>Kode Aset</th>
                                            <th>Nama</th>
                                            <th>Ruang</th>
                                            <th>Lama Penggunaan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i=0;
                                        @endphp
                                        @foreach($alat_lab as $al)
                                            <tr @if($i%2 == 0) style="background-color: #f3f3f3;" @endif>
                                                <td rowspan="2">
                                                    {{ $al->no_urut }}
                                                    <input type="hidden" name="idpermintaan_layanan[{{ $i }}]" value="{{ $al->idpermintaan_layanan }}">
                                                    <input type="hidden" name="kode_barang_aset[{{ $i }}]" value="{{ $al->kode_barang_aset }}">
                                                </td>
                                                <td rowspan="1">{{ $al->kode_barang_aset }}</td>
                                                <td rowspan="1">{{ $al->nama_barang }} {{ $al->merk_barang }}</td>
                                                <td rowspan="1">{{ $al->nama_ruang }}</td>
                                                <td rowspan="1" >{{ $timestamp_alat[$al->kode_barang_aset]['durasi'] ?? '' }}</td>
                                            </tr>
                                            <tr @if($i%2 == 0) style="background-color: #f3f3f3;" @endif>
                                                <td colspan="4" style="padding: 0">
                                                    <table width="100%" style="border: none;">
                                                    <tr>
                                                        <td >
                                                            <div class="input-group" >
                                                                @if((!array_key_exists($al->kode_barang_aset, $timestamp_alat) || empty($timestamp_alat[$al->kode_barang_aset]['timestamp_mulai'])) && $permintaan_layanan->status != '3')
                                                                    <span class="input-group-btn" id="btnmulai-{{ $al->kode_barang_aset }}">
                                                                        <button type="button" id="check-minutes" class="btn waves-effect waves-light btn-ft btn-success" ><i class="fa fa-clock-o" aria-hidden="true"></i></button>
                                                                    </span>

                                                                @else
                                                                    <button type="button" class="btn btn-outline-success">Awal</button>
                                                                @endif
                                                                <input type="text" id="mulai-{{ $al->kode_barang_aset }}"  class="form-control tspicker" value="{{ $timestamp_alat[$al->kode_barang_aset]['timestamp_mulai'] ?? '' }}"  readonly>
                                                                
                                                            </div>
                                                            @php
                                                                if(!empty($timestamp_alat[$al->kode_barang_aset]['dimulai_oleh'])){
                                                                    $id_laboran_dimulai = $timestamp_alat[$al->kode_barang_aset]['dimulai_oleh'];

                                                                    echo $laboran[$id_laboran_dimulai]->nama . ' ' . $laboran[$id_laboran_dimulai]->gelar_depan . ' ' . $laboran[$id_laboran_dimulai]->gelar_belakang;
                                                                }
                                                            @endphp
                                                            
                                                            
                                                        </td>
                                                        <td >
                                                            <div class="input-group" >
                                                                @if((!array_key_exists($al->kode_barang_aset, $timestamp_alat) || empty($timestamp_alat[$al->kode_barang_aset]['timestamp_akhir'])) && $permintaan_layanan->status != '3')                                                            
                                                                    <span class="input-group-btn" id="btnakhir-{{ $al->kode_barang_aset }}">
                                                                        <button type="button" id="check-minutes" class="btn waves-effect waves-light btn-ft btn-danger" ><i class="fa fa-clock-o" aria-hidden="true"></i></button>
                                                                    </span>
                                                                @else
                                                                    <button type="button" class="btn btn-outline-danger">Akhir</button>                                                        
                                                                @endif
                                                                <input type="text"  class="form-control tspicker" value="{{ $timestamp_alat[$al->kode_barang_aset]['timestamp_akhir'] ?? '' }}" id="akhir-{{ $al->kode_barang_aset }}"  readonly>
                                                                
                                                            </div>
                                                             @php
                                                                // $idlaboran = $timestamp_alat[$al->kode_barang_aset]['dimulai_oleh'] ?? null;
                                                                // echo $timestamp_alat[$al->kode_barang_aset]['dimulai_oleh'];
                                                                if(!empty($timestamp_alat[$al->kode_barang_aset]['diakhiri_oleh'])){
                                                                    $id_laboran_diakhiri = $timestamp_alat[$al->kode_barang_aset]['diakhiri_oleh'];

                                                                    echo $laboran[$id_laboran_diakhiri]->nama . ' ' . $laboran[$id_laboran_diakhiri]->gelar_depan . ' ' . $laboran[$id_laboran_diakhiri]->gelar_belakang;
                                                                }
                                                            @endphp
                                                        </td>
                                                    </tr>
                                                    </table>
                                                </td>
                                                
                                            </tr>

                                            @php
                                                $i++;
                                            @endphp
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

    <form id="form-selesai" action="{{ route('permintaan_layanan_set_status_admin') }}" method="POST">
        @csrf
        <input type="hidden" name="idpermintaan_layanan" value="{{ $permintaan_layanan->idpermintaan_layanan }}">
        <input type="hidden" name="status" value="3">
    </form>
@endsection

@section('javascript')
    {{-- <script src="{{ asset('app-assets') }}/vendor/moment/moment.min.js"></script> --}}
    {{-- <script src="{{ asset('app-assets') }}/vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.0/dist/sweetalert2.all.min.js"></script>

    
@endsection