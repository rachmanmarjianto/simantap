@extends('layout_home')

@section('title', 'Form Maintenance - Edit Form')

@section('page-css')
    <link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('app-assets') }}/vendor/summernote/summernote.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.26.4/dist/sweetalert2.min.css">
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row page-titles mx-0">
                <div class="col-sm-6 p-md-0">
                    <div class="welcome-text">
                        <h4>Form {{ $jenis_maintenance == 1 ? 'KALIBRASI' : 'MAINTENANCE' }}</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('proses_maintenance_index') }}">Proses Maintenance</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">Edit Form</a></li>
                        
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
                                    <h4 class="card-title"></h4>
                                    
                                </div>
                                <div class="flex-grow-1">
                                    <a href="{{ route('proses_maintenance_index') }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <h2 style="text-align: center">DATA {{ $jenis_maintenance == 1 ? 'KALIBRASI' : 'MAINTENANCE' }}</h2>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">ID Form {{ $jenis_maintenance == 1 ? 'KALIBRASI' : 'MAINTENANCE' }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $maintenance_aset->idmaintenance_aset }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Kode Barang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $maintenance_aset->kode_barang_aset }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Nama Barang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $maintenance_aset->nama_barang }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Merk Barang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $maintenance_aset->merk_barang }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tanggal {{ $jenis_maintenance == 1 ? 'Kalibrasi' : 'Maintenance' }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{ Terisi otomatis }" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Tempat {{ $jenis_maintenance == 1 ? 'Kalibrasi' : 'Maintenance' }}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $maintenance_aset->nama_ruang }} # {{ $maintenance_aset->nama_gedung }} # {{ $maintenance_aset->nama_kampus }}" readonly>
                                </div>
                            </div>
                            

                            @foreach($layout as $el)
                                @if($el['jenis_isi'] == 1)
                                    {{-- <div class="card-body"> --}}
                                    <div class="form-group row">
                                        <div class="col-sm-12">
                                            <h5 style="font-weight: bold;">{{ $el['nilai_tampil'] }}</h5>
                                        </div>
                                    </div>
                                    

                                    @if(count($el['children']) > 0)
                                        @foreach($el['children'] as $child)
                                            @php
                                                if(empty($child['nilai']) || is_null($child['nilai'])) {
                                                    $isi_nilai = $child['nilai_default'];
                                                }
                                                else{
                                                    $isi_nilai = $child['nilai'];
                                                }
                                            @endphp 

                                            @if($child['jenis_isi'] == 2)
                                                

                                                <div class="form-group row">
                                                    <label class="col-sm-2 col-form-label">{{ $child['nilai_tampil'] }}</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" name="default[{{ $child['idisi_maintenanceaset'] }}]" class="form-control" value="{{ $isi_nilai }}" form="form_submit_maintenance_proses" >
                                                    </div>
                                                </div>
                                            
                                            @elseif($child['jenis_isi'] == 3)
                                                <div class="form-group row">
                                                    <div class="col-sm-12">
                                                        <textarea  class="summernote" name="default[{{ $child['idisi_maintenanceaset'] }}]" form="form_submit_maintenance_proses"> {{ $isi_nilai }} </textarea>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                    {{-- </div> --}}
                                
                                @elseif($el['jenis_isi'] == 2)
                                    {{-- <div class="card-body"> --}}
                                        @php
                                            if(empty($el['nilai']) || is_null($el['nilai'])) {
                                                $isi_nilai = $el['nilai_default'];
                                            }
                                            else{
                                                $isi_nilai = $el['nilai'];
                                            }
                                        @endphp 
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">{{ $el['nilai_tampil'] }}</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="default[{{ $el['idisi_maintenanceaset'] }}]" class="form-control" value="{{ $isi_nilai }}" form="form_submit_maintenance_proses">
                                            </div>
                                        </div>
                                    {{-- </div> --}}
                                @endif
                            @endforeach
                                
                            @php
                                if(empty(session('userdata')['gelar_depan']) || is_null(session('userdata')['gelar_depan']) || session('userdata')['gelar_depan'] == '') {
                                    $gelar_depan = '';
                                } else {
                                    $gelar_depan = session('userdata')['gelar_depan'] . ' ';
                                }

                                if(empty(session('userdata')['gelar_belakang']) || is_null(session('userdata')['gelar_belakang']) || session('userdata')['gelar_belakang'] == '') {
                                    $gelar_belakang = '';
                                } else {
                                    $gelar_belakang = ', ' . session('userdata')['gelar_belakang'];
                                }
                                $nama_personil = $gelar_depan . session('userdata')['nama'] . $gelar_belakang;
                            @endphp
                            
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Personil Pelaksana</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $nama_personil }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Penanggung Jawab Ruang</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{ Terisi otomatis }" readonly>
                                </div>
                            </div>

                            <div class="col-sm-12 mt-3" style="text-align: right;" id="div_button_submit">
                                <button type="button" class="btn btn-danger" onclick="submit(4)">Batalkan Draft</button>
                                <button type="button" class="btn btn-warning" onclick="submit(1)">Simpan Sebagai Draft</button>
                                <button type="button" class="btn btn-success" onclick="submit(2)">Ajukan Verifikasi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    <form id="form_submit_maintenance_proses" method="POST" action="{{ route('form_submit_maintenance_proses') }}">
        @csrf
        {{-- <input type="hidden" name="idtemplate_maintenance" value="{{ $template[0]->idtemplate_maintenance }}"> --}}
        <input type="hidden" name="idmaintenance_aset" value="{{ $maintenance_aset->idmaintenance_aset }}">
        <input type="hidden" name="status" id="idstatus">
    </form>
    <form id="form_batalkan_ajuan" method="POST" action="{{ route('form_batal_ajuan') }}">
        @csrf
        {{-- <input type="hidden" name="idtemplate_maintenance" value="{{ $template[0]->idtemplate_maintenance }}"> --}}
        <input type="hidden" name="idmaintenance_aset" value="{{ $maintenance_aset->idmaintenance_aset }}">
        <input type="hidden" name="status" id="idstatus_batal">
    </form>
@endsection

@section('javascript')
    <script src="{{ asset('app-assets') }}/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendor/summernote/js/summernote.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>


        jQuery(document).ready(function() {
            $(".summernote").summernote({
                height: 190,
                minHeight: null,
                maxHeight: null,
                focus: !1
            }), $(".inline-editor").summernote({
                airMode: !0
            })
        }), window.edit = function() {
            $(".click2edit").summernote()
        }, window.save = function() {
            $(".click2edit").summernote("destroy")
        };

        function submit(status){

            if(status == 1){
                var warning = "Simpan sebagai draft ?";
            }
            else if(status == 4){
                var warning = "Batalkan Draft ?";
            }
            else{
                var warning = "Ajukan verifikasi ?";
            }

            Swal.fire({
                title: warning,
                text: "",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya!"
                }).then((result) => {
                if (result.isConfirmed) {
                    $('#div_button_submit').html('<button class="btn btn-primary" type="button" disabled><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...</button>');
                    if(status == 4){
                        $('#idstatus_batal').val(status);
                        $('#form_batalkan_ajuan').submit();
                        return;
                    }
                    else{
                        $('#idstatus').val(status);
                        $('#form_submit_maintenance_proses').submit();
                    }
                    
                }
            });
        }

        
    </script>
        
@endsection