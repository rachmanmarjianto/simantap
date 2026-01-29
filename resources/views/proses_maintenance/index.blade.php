@extends('layout_home')

@section('title', 'Proses Maintenance')

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
                        <h4>Proses Maintenance</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Proses Maintenance</a></li>
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
                
                <div class="col-12" >
                    <div class="card">                       
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="flex-grow-1">
                                    <h4>Aset yang menjadi Tanggung Jawab Maintenance Anda</h4>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
                
                @foreach($aset as $item)
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            {{-- <div class="row"> --}}
                            <div class="col-md-6 col-sm-12">
                                <h4 class="card-title">({{ $item->kode_barang_aset }}) {{ $item->nama_barang }} - {{ $item->merk_barang }}</h4>
                            </div>
                            <div class="col-md-6 col-sm-12" style="text-align: right;">
                                @if($warning[$item->kode_barang_aset]['warna'] != 'none')
                                    <p class="text-{{ $warning[$item->kode_barang_aset]['warna'] }}">
                                        {{ $warning[$item->kode_barang_aset]['pesan'] }}
                                    </p>
                                @endif
                            </div>
                            {{-- </div> --}}
                            
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group col-md-12">
                                        <label>Tahun Anggaran</label>
                                        <input type="text" class="form-control" value="{{ $item->tahun_aset }}" readonly>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Gedung</label>
                                        <input type="text" class="form-control" value="{{ $item->nama_gedung }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="form-group col-md-12">
                                        <label>Ruangan</label>
                                        <input type="text" class="form-control" value="{{ $item->nama_ruang }}" readonly>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label>Kampus</label>
                                        <input type="text" class="form-control" value="{{ $item->nama_kampus }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-6 col-sm-12" style="">
                                    @if($warning[$item->kode_barang_aset]['maintenance_pernah'] == true)
                                        <button type="button" class="btn btn-success" onclick="lihat_riwayat('{{ $item->kode_barang_aset }}')">
                                            <i class="fa fa-file-text-o" aria-hidden="true"></i> Lihat Riwayat Maintenance
                                        </button>
                                    @endif
                                </div>

                                <div class="col-md-6 col-sm-12" >
                                    <a style="float:right" type="button" class="btn btn-warning" href="{{ route('prosesmaintenance_tambah_maintenance', ['kodeaset' => encrypt($item->kode_barang_aset)]) }}">
                                        <i class="fa fa-plus" aria-hidden="true"></i> Tambah Maintenance
                                    </a>
                                </div>
                                
                            </div>
                            

                            {{-- <div class="table-responsive">
                                <table class="table table-bordered table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th>Waktu Maintenance</th>
                                            <th>Catatan</th>
                                            <th>File</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div> --}}
                        </div>
                    </div>
                </div>
                @endforeach
                
            </div>               
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" id="mdl_riwayatmaintenance">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Riwayat Maintenance <span id="mdl_title_riwayatmaintenance"></span></h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="mdl_loader_riwayatmaintenance">
                        <div class="d-flex justify-content-center" style="display: block;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                    <div id="mdl_content_riwayatmaintenance" style="display: none;">

                        <table class="table table-bordered table-responsive-sm" id="tbl_riwayatmaintenance">
                            <thead>
                                <tr>
                                    <th>ID Form</th>
                                    <th>oleh</th>
                                    <th>Jenis</th>
                                    <th>Waktu Maintenance</th>
                                    <th>Ketepatan Jadwal (Hari)</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('prosesmaintenance_tarik_aset_uk') }}" method="POST" id="form_cari">
        @csrf
        <input type="hidden" name="iduser" value="{{ session('userdata')['iduser'] }}">
        <input type="hidden" name="rangetanggal" id="sb_get_rangetanggal" >
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
        function lihat_riwayat(id) {
            $('#mdl_title_riwayatmaintenance').html('');
            $('#mdl_loader_riwayatmaintenance').show();
            $('#mdl_content_riwayatmaintenance').hide();
            $('#tbl_riwayatmaintenance tbody').html('');
            $('#mdl_riwayatmaintenance').modal('show');

            var editurl = "{{ url('/proses-maintenance/edit_maintenance_aset') }}/";
            var detailurl = "{{ url('/proses-maintenance/view_maintenance_aset') }}/";

            $.ajax({
                url: "{{ route('prosesmaintenance_lihat_riwayat_maintenance') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_aset: id
                },
                success: function(response){
                    console.log(response);

                    $('#mdl_title_riwayatmaintenance').html('(' + response.data[0].nama_barang + '-'+ response.data[0].merk_barang +')');
                    var tbody = '';
                    if(response.data.length > 0){
                        response.data.forEach(function(item){
                            var status_label = '';
                            if(item.status == '1'){
                                status_label = '<span class="badge badge-warning">Draft</span>';
                            } else if(item.status == '2'){
                                status_label = '<span class="badge badge-info">Diajukan Verifikasi</span>';
                            } else if(item.status == '3'){
                                status_label = '<span class="badge badge-success">Terverifikasi</span>';
                            } else if(item.status == '4'){
                                status_label = '<span class="badge badge-danger">Dibatalkan</span>';
                            } else {
                                status_label = '<span class="badge badge-secondary">Unknown</span>';
                            }

                            var aksi_button = '';
                            if(item.status == '1'){
                                aksi_button += '<a href="' + editurl + item.idmaintenance_encrypted + '" class="btn btn-sm btn-primary">Edit</a>';
                            }
                            else if(item.status == '2'){
                                aksi_button += '<a href="' + editurl + item.idmaintenance_encrypted + '" class="btn btn-sm btn-info">Detail</a> ';
                            } 
                            else {
                                aksi_button += '<a href="' + editurl + item.idmaintenance_encrypted + '" class="btn btn-sm btn-primary">Detail</a>';
                            }

                            if(item.gelar_depan == null || item.gelar_depan == '') {
                                item.gelar_depan = '';
                            }
                            else {
                                item.gelar_depan = item.gelar_depan+'. ';
                            }

                            tbody += '<tr>';
                            tbody += '<td>' + item.idmaintenance_aset + '</td>';
                            tbody += '<td>' + item.nipnik + '<br>' + item.gelar_depan + item.nama + '.,' + item.gelar_belakang + '</td>';
                            tbody += '<td>' + item.jenis_maintenance + '</td>';
                            tbody += '<td>' + item.waktu_maintenance + '</td>';                            
                            tbody += '<td>' + item.ketepatan_jadwal_hari + '</td>';
                            tbody += '<td>' + status_label + '</td>';
                            tbody += '<td>' + aksi_button + '</td>';

                            // if(item.file_maintenance != null && item.file_maintenance != ''){
                            //     tbody += '<td><a href="{{ asset("storage/maintenance_files") }}/' + item.file_maintenance + '" target="_blank">Lihat File</a></td>';
                            // } else {
                            //     tbody += '<td>Tidak ada file</td>';
                            // }
                            tbody += '</tr>';
                        });
                    } else {
                        tbody += '<tr><td colspan="3" style="text-align:center;">Tidak ada riwayat maintenance</td></tr>';
                    }
                    $('#tbl_riwayatmaintenance tbody').html(tbody);

                    // $('#mdl_content_riwayatmaintenance tbody').html(tbody);
                    $('#mdl_loader_riwayatmaintenance').hide();
                    $('#mdl_content_riwayatmaintenance').show();
                },
                error: function(xhr){
                    alert('Terjadi kesalahan saat memuat riwayat maintenance.');
                    $('#mdl_riwayatmaintenance').modal('hide');
                }
            });

            // $('#row-' + id).html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
        }

        
        
    </script>
@endsection