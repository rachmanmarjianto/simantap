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
            
            <div class="row" >
                <div class="col-12" style="margin-bottom: 20px;">
                    <a href="{{ route('permintaan_layanan_index_admin') }}" class="btn btn-warning float-right">Kembali</a>
                </div>
                <div class="col-12">                    
                    <div class="card">
                        <div class="card-body">
                            <h4>{{ $permintaan_layanan->nama_layanan }} - {{ $permintaan_layanan->detail_layanan }}</h4>
                            <ul class="list-group mb-3 list-group-flush">
                                <li class="list-group-item px-0 border-top-0 d-flex justify-content-between"><span class="mb-0">ID Permintaan Layanan</span>
                                    <a href="javascript:void(0);"></i><strong>{{ $permintaan_layanan->idpermintaan_layanan }}</strong></a></li>
                                <li class="list-group-item px-0 border-top-0 d-flex justify-content-between"><span class="mb-0">ID Permintaan Layanan Unit Kerja Asal</span>
                                    <a href="javascript:void(0);"></i><strong>{{ $permintaan_layanan->idlayanan_aplikasi_asal }}</strong></a></li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="mb-0">Tgl Masuk </span><strong>{{ $permintaan_layanan->ts_req_masuk_aplikasi_asal }}</strong></li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="mb-0">Status </span>
                                    @if($permintaan_layanan->status == '1') 
                                        <strong style="color:orange">Menunggu</strong> 
                                    @elseif($permintaan_layanan->status == '2')
                                        <strong style="color:Blue">Proses</strong>   
                                    @elseif($permintaan_layanan->status == '3')
                                        <strong style="color:green">Selesai</strong>   
                                    @elseif($permintaan_layanan->status == '4')
                                        <strong style="color:red">Dibatalkan</strong>
                                    @endif
                                </li>
                                
                            </ul>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="col-12">
                    <H3>Penggunaan Alat Lab</H3>
                </div>

                @php
                    $i=0;
                @endphp
                @foreach($alat_lab as $al)
                    <div class="col-sm-12 col-md-6">                    
                        <div class="card border-warning">
                            <div class="card-body">
                                <input type="hidden" name="idpermintaan_layanan[{{ $i }}]" value="{{ $al->idpermintaan_layanan }}">
                                <input type="hidden" name="kode_barang_aset[{{ $i }}]" value="{{ $al->kode_barang_aset }}">
                                <h4>{{ $al->nama_barang }} - {{ $al->merk_barang }} {{ $al->keterangan }}</h4>
                                <ul class="list-group mb-3 list-group-flush">
                                    <li class="list-group-item px-0 border-top-0 d-flex justify-content-between"><span class="mb-0">Kode Aset</span>
                                        <strong>{{ $al->kode_barang_aset }}</strong>
                                    </li>
                                    <li class="list-group-item px-0 border-top-0 d-flex justify-content-between"><span class="mb-0">Ruang</span>
                                        <strong>{{ $al->nama_ruang }}</strong>
                                    </li>
                                    <li class="list-group-item px-0 d-flex justify-content-between">
                                        <span class="mb-0">Lama Penggunaan </span>
                                        <strong id="lamapeng-{{ $al->kode_barang_aset }}"></strong>
                                    </li>
                                    <li class="list-group-item px-0">
                                        <label for="mulai-{{ $al->kode_barang_aset }}" class="form-label">Waktu Mulai</label>
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
                                        
                                    </li>
                                    <li class="list-group-item px-0">
                                        <label for="akhir-{{ $al->kode_barang_aset }}" class="form-label">Waktu Akhir</label>
                                        <div class="input-group" >
                                            @if((!array_key_exists($al->kode_barang_aset, $timestamp_alat) || empty($timestamp_alat[$al->kode_barang_aset]['timestamp_akhir'])) && $permintaan_layanan->status != '3')                                                            
                                                <span class="input-group-btn" id="btnakhir-{{ $al->kode_barang_aset }}">
                                                    <button type="button" id="check-minutes" class="btn waves-effect waves-light btn-ft btn-danger" onclick="getts(2, {{ $al->kode_barang_aset }}, {{ $al->idpermintaan_layanan }})"><i class="fa fa-clock-o" aria-hidden="true"></i></button>
                                                </span>
                                            @else
                                                <button type="button" class="btn btn-outline-danger">Akhir</button>                                                        
                                            @endif
                                            <input type="text" id="akhir-{{ $al->kode_barang_aset }}" name="ts_akhir[{{ $i }}]" class="form-control tspicker" value="{{ $timestamp_alat[$al->kode_barang_aset]['timestamp_akhir'] ?? '' }}"  onchange="simpan(2, {{ $al->kode_barang_aset }}, {{ $al->idpermintaan_layanan }})">
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
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                    @php
                        $i++;
                    @endphp
                @endforeach


                @if($permintaan_layanan->status != '3')
                    <div class="col-sm-12 col-md-6">                    
                        <div class="card border-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center w-100 mt-3">
                                    <div class="flex-grow-1">
                                    </div>
                                    <div id="btn-selesai">
                                        <button type="button" class="btn btn-warning" onclick="setselesai()">Set Selesai!!</button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                
                
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

            console.log(ts);

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