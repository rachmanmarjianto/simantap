@extends('layout_home')

@section('title', 'Master Layanan Unit Kerja')

@section('page-css')
    <link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>User</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('layanan_index') }}">Layanan</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Detail Layanan</a></li>
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
                                    <h4 class="card-title">Master Layanan Unit Kerja {{ $unitkerja->nm_unit_kerja }}</h4>
                                </div>
                                <div>
                                    <a href="{{ route('layanan_index') }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div id="btntarikdata">
                                <button type="button" class="btn btn-success" onclick="tarikmasterlayanan()">Tarik Data Layanan Unit Kerja</button>
                            </div>
                            <span id="pesan" style="color:red"></span>
                            <div class="table-responsive mt-3" style="overflow-x: auto;">									
                                <table id="example3" class="display" style="min-width: 845px; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Nama Layanan</th>
                                            <th>idlayanan asal</th>
                                            <th>Aplikasi</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($layanan as $l)
                                        <tr>
                                            <td>{{ $l->nama_layanan }}</td>
                                            <td>{{ $l->idlayanan_unit_kerja }}</td>
                                            <td>{{ $l->nama_aplikasi }}</td>
                                            <td>
                                                @if($l->status == '1') 
                                                    <button type="button" class="btn btn-success btn-sm" onclick="ubahstatus({{ $l->idlayanan_unit_kerja }}, 0)">Aktif</button> 
                                                @else 
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="ubahstatus({{ $l->idlayanan_unit_kerja }}, 1)">Non-Aktif</button>  
                                                @endif
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

        function tarikmasterlayanan(){
            $('#btntarikdata').html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');

            $.ajax({
                url: "{{ route('layanan_tarik_master_layanan') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idunitkerja: '{{ $idunitkerja }}',
                    idaplikasi: '{{ $appuk[0]->idaplikasi_uk??null }}'
                },
                success: function(response) {
                    console.log(response);
                    $('#btntarikdata').html('<button type="button" class="btn btn-success" onclick="tarikmasterlayanan()">Tarik Data Layanan Unit Kerja</button>');
                    $('#pesan').html(response.message);
                    if(response.code == 200){                        
                        location.reload();
                    }
                    
                    
                },
                error: function(xhr, status, error) {
                    $('#btntarikdata').html('<button type="button" class="btn btn-success" onclick="tarikmasterlayanan()">Tarik Data Layanan Unit Kerja</button>');
                    $('#pesan').html('error: ' + error);
                    console.error('Error:', status, error);
                }
            });
        }
    </script>
        
@endsection