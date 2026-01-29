@extends('layout_home')

@section('title', 'Alat Lab Unit Kerja')

@section('page-css')
    <link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Alat Lab</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('aset_index') }}">Alat Lab</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Detail Alat Lab</a></li>
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
                                    <h4 class="card-title">Alat Unit Kerja {{ $unitkerja->nm_unit_kerja }}</h4>
                                </div>
                                <div>
                                    <a href="{{ route('aset_index') }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <a type="button" class="btn btn-success" href="{{ route('aset_unitkerja_tambah', ['idunitkerja' => encrypt($idunitkerja)]) }}">Tambah Alat</a>
                            <div class="table-responsive mt-3" style="overflow-x: auto;">									
                                <table id="example3" class="display" style="min-width: 845px; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Kode Aset</th>
                                            <th>Nama</th>
                                            <th>Merk</th>
                                            <th>Tahun Aset</th>
                                            <th>Kondisi</th>
                                            <th>Ruang</th>
                                            <th>Kapasitas Max (Min/Hari)</th>
                                            {{-- <th>Aksi</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($aset as $a)
                                        @php
                                            $kondisi = '';
                                            if($a->kondisi_barang == 1){
                                                $kondisi = 'Baik';
                                            } else if($a->kondisi_barang == 2){
                                                $kondisi = 'Rusak Ringan';
                                            } else {
                                                $kondisi = 'Rusak Berat';
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $a->kode_barang_aset }}</td>
                                            <td>{{ $a->nama_barang }}</td>
                                            <td>{{ $a->merk_barang }}</td>
                                            <td>{{ $a->tahun_aset }}</td>
                                            <td>{{ $kondisi }}</td>
                                            <td>{{ $a->nama_ruang }}#{{ $a->nama_gedung }}#{{ $a->nama_kampus }}</td>
                                            <td style="cursor:pointer" onclick="setkapasitasmax('{{ $a->kode_barang_aset }}')" title="set kapasitas max"><input type="text" class="form-control input-default " value="{{ $a->kapasitas_max }}"  readonly/></td>
                                            {{-- <td>
                                                <a href="" class="btn btn-rounded btn-primary">Detail</a>
                                            </td> --}}
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

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="mdl_kapasitas_max">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Kapasitas Max <span id="mdl_header_nm_alat"></span></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="mdl_loader">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div id="mdl_body" style="display: none;">
                        
                        <div class="form-group">
                            <label>Kapasitas Max (Min / Hari)</label>
                            <div class="row">
                                <div class="col-8">
                                    <form action="{{ route('aset_kapasitas_max_simpan') }}" method="POST" id="form_kapasitas_max">
                                        @csrf
                                        <input type="hidden" name="kodeaset" id="mdl_kodeaset">
                                        <input type="number" class="form-control" name="kapasitas_max" id="kapasitas_max" placeholder="Kapasitas Max (Min / Hari)" required>
                                    </form>
                                </div>
                                <div class="col-4" id="btn_simpan_kapasitas_max">
                                    <button type="submit" class="btn btn-primary" onclick="simpankapasitasmax()">Simpan</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table" style="min-width: 500px;">
                                <thead class="thead-primary">
                                    <tr>
                                        <th scope="col">Kapasitas Max</th>
                                        <th scope="col">Created_at</th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="mdl_tbl_body">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                "order": [[ 0, "asc" ]],
                "pageLength": 50
            });
        });

        function simpankapasitasmax(){
            $('#btn_simpan_kapasitas_max').html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            $('#form_kapasitas_max').submit();
        }

        function setkapasitasmax(kodeaset) {
            $('#mdl_loader').show();
            $('#mdl_body').hide();
            $('#mdl_kapasitas_max').modal('show');

            $('#mdl_kodeaset').val(kodeaset);

            $.ajax({
                url: "{{ route('aset_kapasitas_max_get') }}",
                type: "POST",
                data: {
                    kodeaset: kodeaset,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);

                    table_body = $('#mdl_tbl_body');
                    table_body.empty();
                    
                    if (response.kapasitas_max.length > 0) {
                        response.kapasitas_max.forEach(function(item) {
                            if(item.status == 1){
                                item.status = '<span style="color:green">Aktif</span>';
                            } else {
                                item.status = '<span style="color:red">Tidak Aktif</span>';
                            }
                            table_body.append(`
                                <tr>
                                    <td>${item.kapasitas_max}</td>
                                    <td>${item.created_at}</td>
                                    <td>${item.status}</td>
                                </tr>
                            `);
                        });
                    } else {
                        table_body.append(`
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data kapasitas max</td>
                            </tr>
                        `);
                    }



                    $('#mdl_loader').hide();
                    $('#mdl_body').show();
                    
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }
    </script>
        
@endsection