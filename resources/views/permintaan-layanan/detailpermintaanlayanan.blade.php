@extends('layout_home')

@section('title', 'Rekam Penggunaan Alat Lab')

@section('page-css')
    <!-- Material color picker -->
    <link href="{{ asset('app-assets') }}/vendor/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
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
                                                <td rowspan="1">{{ $al->nama_barang }} {{ $al->merk_barang }} {{ $al->keterangan }}</td>
                                                <td rowspan="1">{{ $al->nama_ruang }}</td>
                                                <td rowspan="1" id="lamapeng-{{ $al->kode_barang_aset }}"></td>
                                            </tr>
                                            <tr @if($i%2 == 0) style="background-color: #f3f3f3;" @endif>
                                                <td colspan="4" style="padding: 0">
                                                    <table width="100%" style="border: none;">
                                                    <tr>
                                                        <td >
                                                            <div class="input-group" >
                                                                @if((!array_key_exists($al->kode_barang_aset, $timestamp_alat) || empty($timestamp_alat[$al->kode_barang_aset]['timestamp_mulai'])) && $permintaan_layanan->status != '3')
                                                                    <span class="input-group-btn" id="btnmulai-{{ $al->kode_barang_aset }}">
                                                                        <button type="button" id="check-minutes" class="btn waves-effect waves-light btn-ft btn-success" onclick="getts(1, {{ $al->kode_barang_aset }}, {{ $al->idpermintaan_layanan }})"><i class="fa fa-clock-o" aria-hidden="true"></i></button>
                                                                    </span>

                                                                @else
                                                                    <button type="button" class="btn btn-outline-success">Awal</button>
                                                                @endif
                                                                <input type="text" id="mulai-{{ $al->kode_barang_aset }}" name="ts_mulai[{{ $i }}]" class="form-control tspicker" value="{{ $timestamp_alat[$al->kode_barang_aset]['timestamp_mulai'] ?? '' }}" onchange="simpan(1, {{ $al->kode_barang_aset }}, {{ $al->idpermintaan_layanan }})">
                                                                <span class="input-group-btn" >
                                                                    <button type="button" id="btn-simpan-awal-{{ $al->kode_barang_aset }}" class="btn waves-effect waves-light btn-ft btn-primary" onclick="simpan(1, {{ $al->kode_barang_aset }}, {{ $al->idpermintaan_layanan }})">
                                                                        @if(array_key_exists($al->kode_barang_aset, $timestamp_alat))
                                                                            @if(!empty($timestamp_alat[$al->kode_barang_aset]['timestamp_mulai']))
                                                                                <i class="fa fa-check" aria-hidden="true"></i>
                                                                            @else
                                                                                <i class="fa fa-save"></i>
                                                                            @endif
                                                                        @else                                                                    
                                                                            <i class="fa fa-save"></i>
                                                                        @endif
                                                                    </button>
                                                                </span>
                                                            </div>
                                                        </td>
                                                        <td >
                                                            <div class="input-group" >
                                                                @if((!array_key_exists($al->kode_barang_aset, $timestamp_alat) || empty($timestamp_alat[$al->kode_barang_aset]['timestamp_akhir'])) && $permintaan_layanan->status != '3')                                                            
                                                                    <span class="input-group-btn" id="btnakhir-{{ $al->kode_barang_aset }}">
                                                                        <button type="button" id="check-minutes" class="btn waves-effect waves-light btn-ft btn-danger" onclick="getts(2, {{ $al->kode_barang_aset }}, {{ $al->idpermintaan_layanan }})"><i class="fa fa-clock-o" aria-hidden="true"></i></button>
                                                                    </span>
                                                                @else
                                                                    <button type="button" class="btn btn-outline-danger">Akhir</button>                                                        
                                                                @endif
                                                                <input type="text" name="ts_akhir[{{ $i }}]" class="form-control tspicker" value="{{ $timestamp_alat[$al->kode_barang_aset]['timestamp_akhir'] ?? '' }}" id="akhir-{{ $al->kode_barang_aset }}" onchange="simpan(2, {{ $al->kode_barang_aset }}, {{ $al->idpermintaan_layanan }})">
                                                                <span class="input-group-btn" >
                                                                    <button type="button" id="btn-simpan-akhir-{{ $al->kode_barang_aset }}" class="btn waves-effect waves-light btn-ft btn-primary" onclick="simpan(2, {{ $al->kode_barang_aset }}, {{ $al->idpermintaan_layanan }})">
                                                                        @if(array_key_exists($al->kode_barang_aset, $timestamp_alat))
                                                                            @if(!empty($timestamp_alat[$al->kode_barang_aset]['timestamp_akhir']))
                                                                                <i class="fa fa-check" aria-hidden="true"></i>
                                                                            @else
                                                                                <i class="fa fa-save"></i>
                                                                            @endif
                                                                        @else                                                                    
                                                                            <i class="fa fa-save"></i>
                                                                        @endif
                                                                    </button>
                                                                </span>
                                                            </div>
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
                            @if($permintaan_layanan->status != '3')
                                <div class="d-flex justify-content-between align-items-center w-100 mt-3">
                                    <div class="flex-grow-1">
                                    </div>
                                    <div id="btn-selesai">
                                        <button type="button" class="btn btn-warning" onclick="setselesai()">Set Selesai!!</button>
                                    </div>
                                    
                                </div>
                            @endif
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
    <script src="{{ asset('app-assets') }}/vendor/moment/moment.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendor/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.0/dist/sweetalert2.all.min.js"></script>

    <script>

        function setselesai() {
            Swal.fire({
                title: 'Yakin merubah status proses ke SELESAI?',
                text: "Aksi ini tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Selesaikan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-selesai').submit();
                }
            });
        }


        $('.tspicker').bootstrapMaterialDatePicker({
            format: 'YYYY-MM-DD HH:mm'+':00'
        });

        $(document).ready(function() {
            @foreach($alat_lab as $al)
                hitunglamapenggunaan('{{ $al->kode_barang_aset }}');
            @endforeach
        });

        function hitunglamapenggunaan(kode_barang_aset){
            var mulai = $('#mulai-' + kode_barang_aset).val();
            var akhir = $('#akhir-' + kode_barang_aset).val();

            if (mulai !== '' || akhir !== '') {
                if (mulai === '') {
                    return;
                }
                if (akhir === '') {
                    return;
                }

                var start = moment(mulai, 'YYYY-MM-DD HH:mm:ss');
                var end = moment(akhir, 'YYYY-MM-DD HH:mm:ss');

                if (end.isBefore(start)) {
                    alert('Waktu akhir tidak boleh sebelum waktu mulai');
                    return;
                }

                var duration = moment.duration(end.diff(start));
                var hours = Math.floor(duration.asHours());
                var minutes = Math.floor(duration.asMinutes()) % 60;

                $('#lamapeng-' + kode_barang_aset).text(hours + ' jam ' + minutes + ' menit');
            } else {
                $('#lamapeng-' + kode_barang_aset).text('');
            }
        }

        function getts(type, kode_barang_aset, idpermintaan_layanan) {
            let ts = moment().format('YYYY-MM-DD HH:mm:ss');

            if (type === 1) {
                $btnsave = $('#btn-simpan-awal-' + kode_barang_aset);
            } else {
                $btnsave = $('#btn-simpan-akhir-' + kode_barang_aset);
            }

            $btnsave.html('<i class="fa fa-spinner fa-spin"></i>');

            $.ajax({
                url: "{{ route('permintaanlayanan_save_ts_admin') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    kode_barang_aset: kode_barang_aset,
                    idpermintaan_layanan: idpermintaan_layanan,
                    type: type,
                    timestamp: ts
                },
                success: function(response) {
                    // console.log(response);
                    if (response.code === 200) {
                        if (type === 1) {
                            $('#mulai-' + kode_barang_aset).val(ts);
                            $('#btnmulai-' + kode_barang_aset).html('<button type="button" class="btn btn-outline-success">Awal</button>');
                        } else {
                            $('#akhir-' + kode_barang_aset).val(ts);
                            $('#btnakhir-' + kode_barang_aset).html('<button type="button" class="btn btn-outline-danger">Akhir</button>');
                        }
                        hitunglamapenggunaan(kode_barang_aset);

                        if( response.status_permintaan == 3){
                            setinganselesai();
                        }

                    } else {
                        alert('Error response:' + response.message);
                        console.log(response);
                    }
                    $btnsave.html('<i class="fa fa-check" aria-hidden="true"></i>');
                },
                error: function(xhr, status, error) {
                    alert('Error except: ' + error);
                    $btnsave.html('<i class="fa fa-close"></i>');
                }
            });
        }

        function simpan(type, kode_barang_aset, idpermintaan_layanan) {
            let ts = '';
            if (type === 1) {
                ts = $('#mulai-' + kode_barang_aset).val();
            } else {
                ts = $('#akhir-' + kode_barang_aset).val();
            }

            if (ts === '') {
                alert('Waktu tidak boleh kosong');
                return;
            }

            let $btnsave = type === 1 ? $('#btn-simpan-awal-' + kode_barang_aset) : $('#btn-simpan-akhir-' + kode_barang_aset);
            $btnsave.html('<i class="fa fa-spinner fa-spin"></i>');

            // console.log(ts);

            $.ajax({
                url: "{{ route('permintaanlayanan_save_ts_admin') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    kode_barang_aset: kode_barang_aset,
                    idpermintaan_layanan: idpermintaan_layanan,
                    type: type,
                    timestamp: ts
                },
                success: function(response) {
                    // console.log(response);
                    if (response.code === 200) {
                        if (type === 1) {
                            $('#mulai-' + kode_barang_aset).val(ts);
                            $('#btnmulai-' + kode_barang_aset).html('<button type="button" class="btn btn-outline-success">Awal</button>');
                        } else {
                            $('#akhir-' + kode_barang_aset).val(ts);
                            $('#btnakhir-' + kode_barang_aset).html('<button type="button" class="btn btn-outline-danger">Akhir</button>');
                        }
                        hitunglamapenggunaan(kode_barang_aset);

                        if( response.status_permintaan == 3){
                            setinganselesai();
                        }

                    } else {
                        alert(response.message);
                    }
                    $btnsave.html('<i class="fa fa-check" aria-hidden="true"></i>');
                },
                error: function(xhr, status, error) {
                    alert('Error: ' + error);
                    $btnsave.html('<i class="fa fa-close"></i>');
                }
            });
        }

        function setinganselesai(){
            $('#btn-selesai').html('');

            var el = document.getElementById('status_layanan');
            if (el) {
                el.value = 'Selesai';
                el.style.color = 'green';
            }
        }

    </script>
@endsection