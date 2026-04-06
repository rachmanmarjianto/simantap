@extends('layout_home')

@section('title', 'Form - Form Baru')

@section('page-css')
    <link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Tambah Form Baru</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('form_maintenance_index') }}">Form</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('form_maintenance_unit_kerja', ['id' => encrypt($idunit_kerja)]) }}">Detail Unit Kerja</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Form Baru</a></li>
                        
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
                                    <h4 class="card-title">Template Form Baru untuk {{ $unitkerja->nm_unit_kerja }}</h4>
                                </div>
                                <div>
                                    <a href="{{ route('form_maintenance_unit_kerja', ['id' => encrypt($idunit_kerja)]) }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <form action="{{ route('form_maintenance_simpan_form_baru') }}" method="POST" id="form-tambah-form-baru">
                                @csrf
                                <input type="hidden" name="idunit_kerja" value="{{ $idunit_kerja }}">
                                <div class="form-group">
                                    <label for="jenis_maintenance">Jenis Maintenance</label>
                                    <select class="form-control" id="jenis_maintenance" name="jenis_maintenance" required>
                                        <option value="1">Kalibrasi</option>
                                        <option value="2">Maintenance</option>
                                        <option value="3">Penelitian</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="pelaksana_pengaju">Pelaksana / Pengaju</label>
                                    <select class="form-control" id="pelaksana_pengaju" name="pelaksana_pengaju" required>
                                        <option value="true">Internal</option>
                                        <option value="false">Eksternal</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="nama_template">Nama Template</label>
                                    <input type="text" class="form-control" id="nama_template" name="nama_template" required>
                                </div>
                                <div id="btn-submit">
                                    <button type="button" class="btn btn-primary" onclick="submitForm()">Simpan Nama Template dan Lanjut Edit</button>
                                </div>
                            </form>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection

@section('javascript')

    <script>
        function submitForm() {
            var form = document.getElementById('form-tambah-form-baru');
            if (!form.checkValidity()) {
                form.reportValidity(); // munculkan pesan required
                return; // STOP submit
            }

            $('#btn-submit').html('<button type="button" class="btn btn-primary" disabled>Memproses...</button>'); // ubah tombol dengan versi disabled
            form.submit();
        }

        
    </script>
        
@endsection