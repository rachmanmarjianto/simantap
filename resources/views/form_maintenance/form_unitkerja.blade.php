@extends('layout_home')

@section('title', 'Form Maintenance - Detail Unit Kerja')

@section('page-css')
    <link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Form Maintenance</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('form_maintenance_index') }}">Form Maintenance</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Detail Unit Kerja</a></li>
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
                                    <h4 class="card-title">Template Form {{ $unitkerja[0]->nm_unit_kerja }}</h4>
                                </div>
                                <div>
                                    <a href="{{ route('form_maintenance_index') }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div id="btntarikdata">
                                <a type="button" class="btn btn-success" href="{{ route('form_maintenance_create', ['idunit_kerja' => encrypt($idunit_kerja)]) }}">Buat Template Baru</a>
                            </div>
                            <span id="pesan" style="color:red"></span>
                            <div class="table-responsive mt-3" style="overflow-x: auto;">									
                                <table id="example3" class="display" style="min-width: 845px; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>id</th>
                                            <th>Nama Template</th>
                                            <th>Jenis Maintenance</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($unitkerja as $temp)
                                            @if(!is_null($temp->jenis_maintenance))
                                                @if($temp->jenis_maintenance == 1)
                                                    @php $jenis = "Kalibrasi"; @endphp
                                                @else
                                                    @php $jenis = "Maintenance"; @endphp
                                                @endif
                                                <tr>
                                                    <td>{{ $temp->idtemplate_maintenance }}</td>
                                                    <td>{{ $temp->nama_template }}</td>
                                                    <td>{{ $jenis }}</td>
                                                    <td id="status_{{ $temp->idtemplate_maintenance }}">
                                                        @if($temp->status == 1)
                                                            <span class="badge badge-success" style="cursor:pointer" onclick="gantistatus({{ $temp->idtemplate_maintenance }}, 0)">Aktif</span>
                                                        @else
                                                            <span class="badge badge-danger" style="cursor:pointer" onclick="gantistatus({{ $temp->idtemplate_maintenance }}, 1)">Non Aktif</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('form_maintenance_edit_form', ['idform' => encrypt($temp->idtemplate_maintenance)]) }}" class="btn btn-rounded btn-primary">Edit</a>
                                                    </td>
                                                </tr>
                                            @endif
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

        function gantistatus(idtemplate_maintenance, status){
            $idcol = '#status_' + idtemplate_maintenance;

            $($idcol).html('<div class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></div>');

            $.ajax({
                url: "{{ route('form_maintenance_ganti_status_template') }}",
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    idtemplate_maintenance: idtemplate_maintenance,
                    status: status
                },
                success: function(response) {
                    if(response.code == 200){
                        if(status == 1){
                            $($idcol).html('<span class="badge badge-success" style="cursor:pointer" onclick="gantistatus(' + idtemplate_maintenance + ', 0)">Aktif</span>');
                        } else {
                            $($idcol).html('<span class="badge badge-danger" style="cursor:pointer" onclick="gantistatus(' + idtemplate_maintenance + ', 1)">Non Aktif</span>');
                        }
                    } else {
                        if(status == 1){
                            $($idcol).html('<span class="badge badge-danger" style="cursor:pointer" onclick="gantistatus(' + idtemplate_maintenance + ', 1)">Non Aktif</span>');
                            
                        } else {
                            $($idcol).html('<span class="badge badge-success" style="cursor:pointer" onclick="gantistatus(' + idtemplate_maintenance + ', 0)">Aktif</span>');
                        }

                        alert(response.message);
                    }
                },
                error: function() {
                    if(status == 1){
                        $($idcol).html('<span class="badge badge-danger" style="cursor:pointer" onclick="gantistatus(' + idtemplate_maintenance + ', 1)">Non Aktif</span>');
                        
                    } else {
                        $($idcol).html('<span class="badge badge-success" style="cursor:pointer" onclick="gantistatus(' + idtemplate_maintenance + ', 0)">Aktif</span>');
                    }
                    alert(response.message);
                }
            });
        }

        
    </script>
        
@endsection