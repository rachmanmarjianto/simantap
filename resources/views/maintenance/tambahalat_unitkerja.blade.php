@extends('layout_home')

@section('title', 'Maintenance Alat Lab Unit Kerja')

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
                        <h4>Tarik Data Alat Lab</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('aset_index') }}">Maintenance Alat Lab</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('maintenance_unit_kerja', ['id' => encrypt($idunit_kerja)]) }}">List Alat Lab</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Pilih Maintenance Alat Lab</a></li>
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
                                    <h4 class="card-title">Data Aset {{ $unitkerja->nm_unit_kerja }} Yang Belum Terjadwal Maintenance</h4>
                                </div>
                                <div>
                                    <a href="{{ route('maintenance_unit_kerja', ['id' => encrypt($idunit_kerja)]) }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive mt-3" style="overflow-x: auto;">									
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
                                        @foreach($aset as $a)
                                            <tr>
                                                <td>{{ $a->kode_barang_aset }}</td>
                                                <td>{{ $a->nama_barang }}</td>
                                                <td>{{ $a->merk_barang }}<br>{{ $a->keterangan }}</td>
                                                <td>{{ $a->tahun_aset }}</td>
                                                <td>{{ $a->nama_ruang }} # {{ $a->nama_gedung }} # {{ $a->nama_kampus }}</td>
                                                <td id="btntari-{{ $a->kode_barang_aset }}">
                                                    <button class="btn btn-rounded btn-success" onclick="tarikdata('{{ $a->kode_barang_aset }}')">Maintenance</button>
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
@endsection

@section('javascript')
    <script src="{{ asset('app-assets') }}/vendor/datatables/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example3').DataTable({
                "order": [[ 0, "asc" ]]
            });
        });

        function tarikdata(kode_barang) {
            $.ajax({
                url: "{{ route('maintenance_unitkerja_aktifkan') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    kode_barang: kode_barang
                },
                beforeSend: function() {
                    $('#btntari-' + kode_barang).html('<div class="spinner-grow text-primary" role="status">\
                                                            <span class="sr-only">Loading...</span>\
                                                        </div>');
                },
                success: function(response) {
                    console.log(response);
                    if (response.code === 200) {
                        $('#btntari-' + kode_barang).html('<span style="color: green;"><i class="fa fa-check"></i></span>');
                    } else {
                        $('#btntari-' + kode_barang).html('<span style="color: red;">' + response.message + '</span>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#btntari-' + kode_barang).html('<span style="color: red;">Error: '+error+'</span>');
                }
            });
        }
    </script>
@endsection