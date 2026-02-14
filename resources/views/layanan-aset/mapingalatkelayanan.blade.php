@extends('layout_home')

@section('title', 'Proses Pemetaan Alat ke Layanan')

@section('page-css')
    <link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Proses Pemetaan Alat ke Layanan</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('layanan_aset_index') }}">Alat Lab</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('layanan_aset_maping_layanan_unitkerja', ['iduk' => encrypt($idunitkerja)]) }}">Pemataan Alat Lab ke Layanan</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Proses Pemetaan</a></li>
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
                                    <h4 class="card-title">Layanan <b style="color:blue">{{ $layanan->nama_layanan }}</b></h4>
                                </div>
                                <div>
                                    <a href="{{ route('layanan_aset_maping_layanan_unitkerja', ['iduk' => encrypt($idunitkerja)]) }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-success" onclick="tambahalat()">Tambah alat</button>
                            <div class="table-responsive">
                                <table class="table table-striped table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th>No Urut</th>
                                            <th>Kode Aset</th>
                                            <th>Nama</th>
                                            <th>Merk</th>
                                            <th>Tahun Aset</th>
                                            <th>Ruang</th>
                                            <th>Waktu Ideal<br>(Menit)</th>
                                            <th>is_deleted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <form id="formmaping" method="POST" action="{{ route('layanan_aset_maping_layanan_unitkerja_simpan') }}">
                                            @csrf
                                            <input type="hidden" name="idlayanan" value="{{ $layanan->idlayanan }}">
                                            @php
                                                $i=0;
                                            @endphp
                                            @foreach($alatlabmapped as $a)
                                                <tr>
                                                    <td>
                                                        <input type="number" class="form-control input-default " name="nourut[{{$i}}]" value="{{$a->no_urut}}">
                                                        <input type="hidden" name="idlayanan_aset[{{$i}}]" value="{{$a->idlayanan_aset}}">
                                                    </td>
                                                    <td>{{ $a->kode_barang_aset }}</td>
                                                    <td>{{ $a->nama_barang }}</td>
                                                    <td>{{ $a->merk_barang }}<br>{{ $a->keterangan }}</td>
                                                    <td>{{ $a->tahun_aset }}</td>
                                                    <td>{{ $a->nama_ruang }}#{{ $a->nama_gedung }}#{{ $a->nama_kampus }}</td>
                                                    <td><input type="number" class="form-control input-default " name="waktu_ideal[{{$i}}]" value="{{$a->waktu_penggunaan_ideal_min}}"></td>
                                                    <td id="btnhapus-{{ $a->idlayanan_aset }}">
                                                        @if($a->is_deleted == 0)
                                                            <span class="badge badge-success" style="cursor:pointer" onclick="hapus({{$a->idlayanan_aset}}, 1)">Tidak</span>
                                                        @else
                                                            <span class="badge badge-danger" style="cursor:pointer" onclick="hapus({{$a->idlayanan_aset}}, 0)">Terhapus</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                        </form>
                                    </tbody>
                                </table>
                                <div id="btnsimpan" style="float:right" >
                                    <button type="button" class="btn btn-secondary" onclick="simpan()">Simpan Perubahan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" id="modalTambahAlat" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alat yang Tersedia</h5>
                    
                    </button>
                </div>
                <div class="modal-body">
                    <table id="example3" class="display" style="min-width: 845px; width:100%;">
                        <thead>
                            <tr>
                                <th>Kode Aset</th>
                                <th>Nama</th>
                                <th>Merk</th>
                                <th>Tahun Aset</th>
                                <th>Ruang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alatlab as $a)
                            <tr>
                                <td>{{ $a->kode_barang_aset }}</td>
                                <td>{{ $a->nama_barang }}</td>
                                <td>{{ $a->merk_barang }}<br>{{ $a->keterangan }}</td>
                                <td>{{ $a->tahun_aset }}</td>
                                <td>{{ $a->nama_ruang }}#{{ $a->nama_gedung }}#{{ $a->nama_kampus }}</td>
                                <td id="btntambah-{{ $a->kode_barang_aset }}">
                                    <button href="" class="btn btn-rounded btn-primary" onclick="tambahkan({{ $a->kode_barang_aset }})"><i class="fa fa-plus"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer" id="mdl_close_btn">
                    <button type="button" class="btn btn-secondary" onclick="tutupmodal()">Tutup dan Refresh</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('app-assets') }}/vendor/datatables/js/jquery.dataTables.min.js"></script>

    <script>

        $(document).ready(function() {
            $('#example3').DataTable({
                "order": [[ 0, "asc" ]]
            });
        });

        function hapus(idlayanan_aset, is_deleted){
            var id = 'btnhapus-' + idlayanan_aset;
            $('#' + id).html('<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            $.ajax({
                url: "{{ route('layanan_aset_maping_layanan_unitkerja_hapus_alat') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idlayanan_aset: idlayanan_aset,
                    is_deleted: is_deleted
                },
                success: function(response) {
                    console.log(response);
                    if( response.code === 200) {
                        if( is_deleted == 1) {
                            $('#' + id).html('<span class="badge badge-danger" style="cursor:pointer" onclick="hapus('+idlayanan_aset+', 0)">Terhapus</span>');
                        } else {
                            $('#' + id).html('<span class="badge badge-success" style="cursor:pointer" onclick="hapus('+idlayanan_aset+', 1)">Tidak</span>');
                        }
                    } else {
                        if( is_deleted == 0) {
                            $('#' + id).html('<span class="badge badge-danger" style="cursor:pointer" onclick="hapus('+idlayanan_aset+', 0)">Terhapus</span>');
                        } else {
                            $('#' + id).html('<span class="badge badge-success" style="cursor:pointer" onclick="hapus('+idlayanan_aset+', 1)">Tidak</span>');
                        }
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    if( is_deleted == 0) {
                        $('#' + id).html('<span class="badge badge-danger" style="cursor:pointer" onclick="hapus('+idlayanan_aset+', 0)">Terhapus</span>');
                    } else {
                        $('#' + id).html('<span class="badge badge-success" style="cursor:pointer" onclick="hapus('+idlayanan_aset+', 1)">Tidak</span>');
                    }
                    alert('error: ' + error);
                }
            });
        }

        function simpan(){
            $('#btnsimpan').html('<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            $('#formmaping').submit();
        }

        function tutupmodal() {
            $('#mdl_close_btn').html('<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            window.location.reload();
        }

        function tambahalat() {
            // Open the modal
            $('#modalTambahAlat').modal('show');
        }

        function tambahkan(kode_barang) {
            var id = 'btntambah-' + kode_barang;
            $('#' + id).html('<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            
            $.ajax({
                url: "{{ route('layanan_aset_maping_layanan_unitkerja_tambah_alat') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    kode_barang: kode_barang,
                    idlayanan: '{{ $layanan->idlayanan }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#' + id).html('<span style="color:green"><i class="fa fa-check"></i></span>'); // Show success icon
                        
                    } else {
                        $('#' + id).html('<span style="color:red"><i class="fa fa-close"></i></span>'); // Show error icon
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    $('#' + id).html('<span style="color:red"><i class="fa fa-close"></i></span>'); // Show error icon
                    alert('error: ' + error);
                }
            });
        }

        //<i class="fa fa-plus"></i>
        //<i class="fa fa-close"></i>
    </script>
@endsection