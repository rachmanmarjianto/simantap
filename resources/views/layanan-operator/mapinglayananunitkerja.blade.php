@extends('layout_home')

@section('title', 'Pemataan Operator Lab ke Layanan')

@section('page-css')
    <link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Pemataan Operator Lab ke Layanan</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('layanan_operator_index') }}">Master Pemetaan Operator ke Layanan</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Pemataan Operator Lab ke Layanan</a></li>
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
                                    <h4 class="card-title">Pemetaan Operator ke Layanan di {{ $unitkerja->nm_unit_kerja }}</h4>
                                </div>
                                <div>
                                    <a href="{{ route('layanan_operator_index') }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive mt-3" style="overflow-x: auto;">									
                                <table id="example3" class="display" style="min-width: 845px; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Nama Layanan</th>
                                            <th>Jumlah Operator Terpetakan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($operator as $row)
                                            <tr>
                                                <td>{{ $row->nama_layanan }}</td>
                                                <td>{{ $row->jumlah_operator }}</td>
                                                <td id="aksi-{{ $row->idlayanan }}">
                                                    <a type="button" class="btn btn-rounded btn-outline-success" href="{{ route('layanan_operator_maping_operator_detail', ['iduk' => encrypt($idunitkerja), 'idlayanan' => encrypt($row->idlayanan)]) }}" onclick="petakan('{{ $row->idlayanan }}')">Petakan</a>													
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
@endsection

@section('javascript')
    <script src="{{ asset('app-assets') }}/vendor/datatables/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#example3').DataTable({
                "order": [[ 0, "asc" ]]
            });
        });

        function petakan(idlayanan) {
            var aksi = document.getElementById('aksi-' + idlayanan);
            aksi.innerHTML = '<span class="spinner-border text-success" role="status" aria-hidden="true"></span>';
        }
    </script>
@endsection