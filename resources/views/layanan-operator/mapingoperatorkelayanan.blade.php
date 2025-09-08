@extends('layout_home')

@section('title', 'Proses Pemetaan Operator ke Layanan')

@section('page-css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Proses Pemetaan Operator ke Layanan</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('layanan_operator_index') }}">Master Pemetaan Alat ke Layanan</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('layanan_operator_maping_operator', ['iduk' => encrypt($idunitkerja)]) }}">Pemataan Operator Lab ke Layanan</a></li>
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
                        
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-9 col-md-6">
                                    <select class="form-control" id="sel_operator"  name = "role">
                                        <option value="">Pilih Operator</option>
                                        @foreach($operator as $op)
                                            <option value="{{ $op->iduser }}" >{{ $op->nipnik }} - {{ $op->gelar_depan }} {{ $op->nama }} {{ $op->gelar_belakang }}</option>
                                        @endforeach
                                    </select>
                                    <span id="msg_tambah" style="color:red"></span>
                                </div>
                                <div class="col-sm-3 col-md-6">
                                    <button type="button" class="btn btn-success" onclick="tambahoperator()" id="btn_tambah">Tambahkan Operator</button>
                                    
                                    <a href="{{ route('layanan_operator_maping_operator', ['iduk' => encrypt($idunitkerja)]) }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>

                            
                        
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">                       
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="flex-grow-1">
                                    <h4 class="card-title">Layanan <b style="color:blue">{{ $layanan->nama_layanan }}</b></h4>
                                </div>
                                <div>
                                    {{-- <a href="{{ route('layanan_aset_maping_layanan_unitkerja', ['iduk' => encrypt($idunitkerja)]) }}" class="btn btn-rounded btn-warning float-right">Kembali</a> --}}
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-responsive-sm" id="tableoperator">
                                    <thead>
                                        <tr>
                                            <th>Nama Operator</th>
                                            <th>NIP/NIK</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($operatorlayanan as $op)
                                            <tr id="row-{{ $op->iduser }}">
                                                <td>{{ $op->nama }}</td>
                                                <td>{{ $op->nipnik }}</td>
                                                <td id="status-{{ $op->iduser }}">
                                                    @if($op->status)
                                                        <span class="badge badge-success" style="cursor:pointer" onclick="ubahStatus({{ $op->iduser }}, false)">Aktif</span>
                                                    @else
                                                        <span class="badge badge-danger" style="cursor:pointer" onclick="ubahStatus({{ $op->iduser }}, true)">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <i class="fa fa-trash" aria-hidden="true" style="color:red; cursor:pointer" onclick="hapusOperator({{ $op->iduser }})"></i>
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

    <script>

        function ubahStatus(idoperator, status) {
            var aksi = document.getElementById('status-' + idoperator);
            aksi.innerHTML = '<span class="spinner-border text-success" role="status" aria-hidden="true"></span>';

            $.ajax({
                url: "{{ route('layanan_operator_ubah_status') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idoperator: idoperator,
                    status: status,
                    idlayanan: '{{ $layanan->idlayanan }}'
                },
                success: function(response) {
                    if(response.status == 'success'){
                        if(!status) {
                            aksi.innerHTML = '<span class="badge badge-danger" style="cursor:pointer" onclick="ubahStatus(' + idoperator + ', true)">Tidak Aktif</span>';
                        } else {
                            aksi.innerHTML = '<span class="badge badge-success" style="cursor:pointer" onclick="ubahStatus(' + idoperator + ', false)">Aktif</span>';
                        }
                    } else {
                        aksi.innerHTML = '<span class="badge badge-danger">Gagal mengubah status</span>';
                    }
                },
                error: function(xhr, status, error) {
                    aksi.innerHTML = '<span class="badge badge-danger">Error: ' + error + '</span>';
                }
            });
        }

        function hapusOperator(idoperator){
            
            $.ajax({
                url: "{{ route('layanan_operator_hapus_operator') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idoperator: idoperator,
                    idlayanan: '{{ $layanan->idlayanan }}'
                },
                success: function(response) {
                    // console.log(response);
                    if(response.status == 'success'){
                        $('#row-' + idoperator).remove();
                    } else {
                        $('#msg_tambah').text(response.message);
                    }
                    
                },
                error: function(xhr, status, error) {
                    $('#msg_tambah').text(error); // Show error icon
                }
            });
        }

        function tambahoperator() {
            var idoperator = $('#sel_operator').val();
            $('#msg_tambah').text('');


            if (idoperator === '') {
                $('#msg_tambah').text('Pilih operator terlebih dahulu');
                return;
            }
            else{
                $('#btn_tambah').prop('disabled', true);
                $('#btn_tambah').html('<svg class="spinner-border spinner-border-sm" role="status"><span class="sr-only">Loading...</span></svg>');
            }
            
            $.ajax({
                url: "{{ route('layanan_operator_tambah_operator') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idoperator: idoperator,
                    idlayanan: '{{ $layanan->idlayanan }}'
                },
                success: function(response) {
                    console.log(response);
                    $('#btn_tambah').prop('disabled', false);
                    $('#btn_tambah').html('Tambahkan Operator');

                    if(response.status == 'success'){
                        if(response.data[0].status == true) {
                            var statusBadge = '<span class="badge badge-success" style="cursor:pointer" onclick="ubahStatus(' + response.data[0].iduser + ', false)">Aktif</span>';
                        } else {
                            var statusBadge = '<span class="badge badge-danger" style="cursor:pointer" onclick="ubahStatus(' + response.data[0].iduser + ', true)">Tidak Aktif</span>';
                        }

                        if(response.data[0].gelar_depan == null){
                            response.data[0].gelar_depan = '';
                        }

                        if(response.data[0].gelar_belakang == null){
                            response.data[0].gelar_belakang = '';
                        }

                        var newRow = '<tr id="row-' + response.data[0].iduser + '">' +
                            '<td>' + response.data[0].gelar_depan + ' ' + response.data[0].nama + ' ' + response.data[0].gelar_belakang + '</td>' +
                            '<td>' + response.data[0].nipnik + '</td>' +
                            '<td id="status-' + response.data[0].iduser + '">' + statusBadge + '</td>' +
                            '<td><i class="fa fa-trash" aria-hidden="true" style="color:red; cursor:pointer" onclick="hapusOperator(' + response.data[0].iduser + ')"></i></td>' +
                            '</tr>';
                        $('#tableoperator tbody').append(newRow);
                    }

                },
                error: function(xhr, status, error) {
                    $('#msg_tambah').text(error); // Show error icon
                }
            });
        }

        //<i class="fa fa-plus"></i>
        //<i class="fa fa-close"></i>
    </script>
@endsection