@extends('layout_home')

@section('title', 'Maintenance Alat Lab Unit Kerja')

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
                        <li class="breadcrumb-item"><a href="{{ route('maintenance_alat_index') }}">Maintenance Alat Lab</a></li>
                        <li class="breadcrumb-item active"><a href="javascript:void(0)">List Alat Lab</a></li>
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
                                    <h4 class="card-title">Alat Unit Kerja {{ $unitkerja->nm_unit_kerja }} untuk di Maintenance</h4>
                                </div>
                                <div>
                                    <a href="{{ route('maintenance_alat_index') }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <a type="button" class="btn btn-success" href="{{ route('maintenance_unitkerja_tambah', ['idunitkerja' => Crypt::encrypt($idunit_kerja)]) }}">Tambah Alat</a>
                            <div class="table-responsive mt-3" style="overflow-x: auto;">									
                                <table id="example3" class="display" style="min-width: 845px; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Aset</th>
                                            <th>Ruang</th>
                                            <th>Jarak Kalibrasi</th>                                            
                                            <th>Jarak Maintenance</th>
                                            {{-- <th>Aksi</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($aset as $a)
                                        
                                        <tr>
                                            <td rowspan="2" title="aktifasi kalibrasi dan maintenance" style="cursor:pointer" onclick="aktifasikalibrasimaintenance('{{ $a->kode_barang_aset }}')">
                                                ({{ $a->kode_barang_aset }})<br>
                                                {{ $a->nama_barang }} - {{ $a->merk_barang }} {{ $a->keterangan }}<br>
                                                Tahun Aset: {{ $a->tahun_aset }}
                                            </td>
                                            <td>{{ $a->nama_ruang }} - {{ $a->nama_gedung }} - {{ $a->nama_kampus }}</td>
                                            @if($a->terjadwal_kalibrasi && $a->jarak_kalibrasi != 0)
                                            <td style="cursor:pointer" onclick="setjarakhari('{{ $a->kode_barang_aset }}', 1)" title="set jarak kalibrasi">
                                                <input type="text" class="form-control input-default " value="{{ $a->jarak_kalibrasi ?? 0 }}"  readonly/>
                                                {{ $a->satuan_kalibrasi ?? '' }}
                                            </td>
                                            @elseif($a->terjadwal_kalibrasi && $a->jarak_kalibrasi == 0)
                                            <td style="cursor:pointer; color:orange" onclick="setjarakhari('{{ $a->kode_barang_aset }}', 1)" title="set jarak kalibrasi">By Request</td>
                                            @else
                                            <td></td>
                                            @endif
                                            @if($a->terjadwal_maintenance && $a->jarak_maintenance != 0)
                                            <td style="cursor:pointer" onclick="setjarakhari('{{ $a->kode_barang_aset }}', 2)" title="set jarak Maintenance">
                                                <input type="text" class="form-control input-default " value="{{ $a->jarak_maintenance ?? 0 }}"  readonly/>
                                                {{ $a->satuan_maintenance ?? '' }}
                                            </td>
                                            @elseif($a->terjadwal_maintenance && $a->jarak_maintenance == 0)
                                            <td style="cursor:pointer; color:orange" onclick="setjarakhari('{{ $a->kode_barang_aset }}', 2)" title="set jarak Maintenance">By Request</td>
                                            @else
                                            <td></td>
                                            @endif
                                            
                                        </tr>
                                        <tr>
                                            <td style="text-align:right">Jumlah PJ</td>
                                            @if($a->terjadwal_kalibrasi)
                                            <td style="cursor:pointer" onclick="setpj('{{ $a->kode_barang_aset }}', 1)" title="Jumlah PJ Kalibrasi"><input type="text" class="form-control input-default " value="{{ $a->jumlah_pj_kalibrasi ?? 0 }}"  readonly/></td>
                                            @else
                                            <td></td>
                                            @endif

                                            @if($a->terjadwal_maintenance)
                                            <td style="cursor:pointer" onclick="setpj('{{ $a->kode_barang_aset }}', 2)" title="Jumlah PJ Maintenance"><input type="text" class="form-control input-default " value="{{ $a->jumlah_pj_maintenance ?? 0 }}"  readonly/></td>
                                            @else
                                            <td></td>
                                            @endif

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

    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" id="mdl_pj_maintenance" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Penanggung Jawab <b id="mdl_pj_maintenance_jenis_judul"></b></h5>
                    <button type="button" class="close" data-dismiss="modal" onclick="tutup_mdl_pj()"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="mdl_pj_loader">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div id="mdl_pj_body" style="display: none;">
                        <div class="row" style="margin-bottom: 20px">
                            <div class="col-md-4 col-sm-12">
                                <label>Kode Aset</label>
                                <input type="text" name="kodeaset" class="form-control" id="mdl_pj_kode_aset" form="form_tambah_pj" readonly>
                            </div>
                            <div class="col-md-8 col-sm-12">
                                <label>Nama Aset</label>
                                <input type="text" class="form-control" id="mdl_pj_nama_aset" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Daftar Personil Unit Kerja</label>
                            <div class="row">
                                
                                <div class="col-md-6 col-sm-10">
                                    <form action="{{ route('maintenance_pj_maintenance_simpan') }}" method="POST" id="form_tambah_pj">
                                    @csrf
                                        <input type="hidden" name="jenis" id="mdl_pj_form_jenis">
                                        <select name="iduser" class="form-control default-select">
                                            @foreach($personil as $p)
                                                <option value="{{ $p->iduser }}">{{ $p->nama }} ({{ $p->nipnik }})</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                                <div class="col-md-6 col-sm-2" id="btn_tambah_pj">
                                    <button type="button" class="btn btn-primary mb-2" onclick="tambah_pj()">Tambahkan</button>
                                </div>
                                
                            </div>                            
                        </div>

                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table" style="min-width: 500px;">
                                <thead class="thead-primary">
                                    <tr>
                                        <th scope="col">Penanggung Jawab <b id="mdl_pj_maintenance_jenis_kolom_table"></b></th>
                                        <th scope="col">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="mdl_pj_tbl_body">
                                    
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="tutup_mdl_pj()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" id="mdl_kapasitas_max" >
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Jarak <span id="mdl_header_jenis_maintenance"></span></h5>
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
                            <div class="row" style="margin-bottom: 20px">
                                <div class="col-md-4 col-sm-12">
                                    <label>Kode Aset</label>
                                    <input type="text" class="form-control" id="mdl_kode_aset" readonly>
                                </div>
                                <div class="col-md-8 col-sm-12">
                                    <label>Nama Aset</label>
                                    <input type="text" class="form-control" id="mdl_nama_aset" readonly>
                                </div>
                            </div>
                            <label>Jarak <span id="mdl_jenis_maintenance_label"></span> Yang baru </label>
                            <small style="color: red">* Masukan 0 untuk by request</small>
                            <div class="row">
                                
                                <div class="col-4">   
                                    <form action="{{ route('maintenance_jarak_hari_simpan') }}" method="POST" id="form_jarak_maintenance">
                                    @csrf                                
                                    <input type="hidden" name="kodeaset" id="mdl_kodeaset">
                                    <input type="number" class="form-control" name="jarak_maintenance" id="jarak_maintenance" placeholder="Jarak Maintenance" required>
                                    <input type="hidden" name="jenis" id="mdl_jenis_maintenance_input">  
                                    </form>                                  
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <select class="form-control" name="satuan_jarak_maintenance" id="satuan_jarak_maintenance" form="form_jarak_maintenance" required>
                                            <option value="1">Jam</option>
                                            <option value="2">Jam Setelah Pemakaian</option>
                                            <option value="3">Hari</option>
                                            <option value="4">Hari Setelah Pemakaian</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-4" id="btn_simpan_jarak_maintenance">
                                    <button type="submit" class="btn btn-primary" onclick="simpanJarakMaintenance()">Simpan</button>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table" style="min-width: 500px;">
                                <thead class="thead-primary">
                                    <tr>
                                        <th scope="col">Jarak <span id="mdl_jenis_maintenance_kolom"></span></th>
                                        <th scope="col">Satuan</th>
                                        <th scope="col">Timestamp</th>
                                        <th scope="col">Diubah oleh</th>
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

    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" id="mdl_kalibrasimaintenance" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Setting Aset</h5>
                    <button type="button" class="close" data-dismiss="modal" onclick="tutup_mdl_kalibrasimaintenance()"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="loader_kalibrasimaintenance" style="display:block; text-align:center;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <div id="body_kalibrasimaintenance" style="display:none;">
                        <div class="table-responsive">
                            <table class="table table-bordered table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th>Nama aset</th>
                                        <th style="text-align:center">Kalibrasi terjadwal</th>
                                        <th style="text-align:center">Maintenance terjadwal</th>
                                    </tr>
                                </thead>
                                <tbody id="tbl_kalibrasimaintenance_body">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="tutup_mdl_kalibrasimaintenance()">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('javascript')
    <script src="{{ asset('app-assets') }}/vendor/datatables/js/jquery.dataTables.min.js"></script>

    <script>
        var aksiubahstatus = false;

        $(document).ready(function() {
            $('#example3').DataTable({
                "order": [[ 0, "asc" ]],
                "pageLength": 50
            });
        });

        function tambah_pj(){
            $('#btn_tambah_pj').html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            $('#form_tambah_pj').submit();
        }

        function tutup_mdl_pj(){
            if(aksiubahstatus){
                location.reload();
            } 
        }

        function aktifasikalibrasimaintenance(kodeaset){
            aksiubahstatus = false;

            $('#body_kalibrasimaintenance').hide();
            $('#loader_kalibrasimaintenance').show();
            
            $('#mdl_kalibrasimaintenance').modal('show');

            $.ajax({
                url: "{{ route('maintenance_aktifasi_kalibrasi_maintenance_get') }}",
                type: "POST",
                data: {
                    kodeaset: kodeaset,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    // console.log(response.data.length);

                    table_body = $('#tbl_kalibrasimaintenance_body');
                    table_body.empty();

                    if(response.code != 200){
                        alert('Gagal mendapatkan data aset');
                        $('#mdl_kalibrasimaintenance').modal('hide');
                        return;
                    }

                    if (response.data.length > 0) {
                        response.data.forEach(function(item) {

                            if(item.terjadwal_kalibrasi){
                                status_kalibrasi = '<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus_maintenance_kalibrasi(\''+item.kode_barang_aset+'\', 0, \'kalibrasi\')">Aktif</button>';
                            } else {
                                status_kalibrasi = '<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus_maintenance_kalibrasi(\''+item.kode_barang_aset+'\', 1, \'kalibrasi\')">Tidak Aktif</button>';
                            }

                            if(item.terjadwal_maintenance){
                                status_maintenance = '<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus_maintenance_kalibrasi(\''+item.kode_barang_aset+'\', 0, \'maintenance\')">Aktif</button>';
                            } else {
                                status_maintenance = '<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus_maintenance_kalibrasi(\''+item.kode_barang_aset+'\', 1, \'maintenance\')">Tidak Aktif</button>';
                            }

                            table_body.append(`
                                <tr>
                                    <td>${item.nama_barang} - ${item.merk_barang}</td>
                                    <td style="text-align:center" id="mdl_status_kalibrasi_btn">${status_kalibrasi}</td>
                                    <td style="text-align:center" id="mdl_status_maintenance_btn">${status_maintenance}</td>
                                </tr>
                            `);
                        });
                    } else {
                        table_body.append(`
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data aset</td>
                            </tr>
                        `);
                    }

                    $('#loader_kalibrasimaintenance').hide();
                    $('#body_kalibrasimaintenance').show();
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }

        function ubahstatus_maintenance_kalibrasi(kodebarang, status, jenis){
            var idbtn = "#mdl_status_"+jenis+"_btn";

            $(idbtn).html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');

            // console.log(idbtn);

            $.ajax({
                url: "{{ route('ubah_status_maintenance_kalibrasi') }}",
                type: "POST",
                data: {
                    kodeaset: kodebarang,
                    status: status,
                    jenis: jenis,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    // console.log(response);

                    if(response.code == 200){
                        aksiubahstatus = true;
                        if(jenis == 'kalibrasi'){
                            if(status == 1){
                                status = '<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus_maintenance_kalibrasi(\''+kodebarang+'\', 0, \'kalibrasi\')">Aktif</button>';
                            } else {
                                status = '<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus_maintenance_kalibrasi(\''+kodebarang+'\', 1, \'kalibrasi\')">Tidak Aktif</button>';
                            }
                        } else {
                            if(status == 1){
                                status = '<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus_maintenance_kalibrasi(\''+kodebarang+'\', 0, \'maintenance\')">Aktif</button>';
                            } else {
                                status = '<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus_maintenance_kalibrasi(\''+kodebarang+'\', 1, \'maintenance\')">Tidak Aktif</button>';
                            }
                        }
                    } else {
                        alert('Gagal mengubah status aset');
                        if(jenis == 'kalibrasi'){
                            if(status == 0){
                                status = '<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus_maintenance_kalibrasi(\''+kodebarang+'\', 0, \'kalibrasi\')">Aktif</button>';
                            } else {
                                status = '<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus_maintenance_kalibrasi(\''+kodebarang+'\', 1, \'kalibrasi\')">Tidak Aktif</button>';
                            }
                        } else {
                            if(status == 0){
                                status = '<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus_maintenance_kalibrasi(\''+kodebarang+'\', 0, \'maintenance\')">Aktif</button>';
                            } else {
                                status = '<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus_maintenance_kalibrasi(\''+kodebarang+'\', 1, \'maintenance\')">Tidak Aktif</button>';
                            }
                        }
                    }
                    $(idbtn).html(status);
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });

        }

        function tutup_mdl_kalibrasimaintenance(){
            if(aksiubahstatus){
                location.reload();
            } 
        }

        function ubahstatus(idpj_maintenance, status){
            // console.log(idpj_maintenance, status);
            aksiubahstatus = false;

            var id = '#status_'+idpj_maintenance;
            $(id).html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');

            $.ajax({
                url: "{{ route('maintenance_pj_maintenance_ubah_status') }}",
                type: "POST",
                data: {
                    idpj_maintenance: idpj_maintenance,
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    // console.log(response);

                    if(response.code == 200){
                        aksiubahstatus = true;
                        if(status == 1){
                            status = '<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus(\''+idpj_maintenance+'\', 0)">Aktif</button>';
                        } else {
                            status = '<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus(\''+idpj_maintenance+'\', 1)">Tidak Aktif</button>';
                        }
                    } else {
                        alert('Gagal mengubah status penanggung jawab');
                        if(status == 0){
                            status = '<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus(\''+idpj_maintenance+'\', 0)">Aktif</button>';
                        } else {
                            status = '<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus(\''+idpj_maintenance+'\', 1)">Tidak Aktif</button>';
                        }
                    }

                    $(id).html(status);
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }

        function setpj(kodeaset, jenis){
            if(jenis == 1){
                $('#mdl_pj_maintenance_jenis_judul').text('Kalibrasi');
                $('#mdl_pj_maintenance_jenis_kolom_table').text('Kalibrasi');
            } else {
                $('#mdl_pj_maintenance_jenis_judul').text('Maintenance');
                $('#mdl_pj_maintenance_jenis_kolom_table').text('Maintenance');
            }

            $('#mdl_pj_form_jenis').val(jenis);

            $('#mdl_pj_loader').show();
            $('#mdl_pj_body').hide();
            $('#mdl_pj_maintenance').modal('show');

            $.ajax({
                url: "{{ route('maintenance_pj_maintenance_get') }}",
                type: "POST",
                data: {
                    kodeaset: kodeaset,
                    jenis: jenis,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    // console.log(response);

                    table_body = $('#mdl_pj_tbl_body');
                    table_body.empty();

                    $('#mdl_pj_kode_aset').val(response.data.aset.kode_barang_aset);
                    $('#mdl_pj_nama_aset').val(response.data.aset.nama_barang+' - '+response.data.aset.merk_barang);

                    if (response.data.pj.length > 0) {
                        response.data.pj.forEach(function(item) {

                            if(item.status){
                                status = '<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus(\''+item.idpj_maintenance+'\', 0)">Aktif</button>';
                            } else {
                                status = '<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus(\''+item.idpj_maintenance+'\', 2)">Tidak Aktif</button>';
                            }

                            if(item.gelar_belakang == null){
                                item.gelar_belakang = '';
                            }

                            if(item.gelar_depan == null){
                                item.gelar_depan = '';
                            }

                            table_body.append(`
                                <tr>
                                    <td>${item.gelar_depan} ${item.nama} ${item.gelar_belakang} (${item.nipnik})</td>
                                    <td id="status_${item.idpj_maintenance}">${status}</td>
                                </tr>
                            `);
                        });
                    } else {
                        table_body.append(`
                            <tr>
                                <td colspan="2" class="text-center">Tidak ada data penanggung jawab</td>
                            </tr>
                        `);
                    }

                    $('#mdl_pj_loader').hide();
                    $('#mdl_pj_body').show();
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }

        function simpanJarakMaintenance(){
            $('#btn_simpan_jarak_maintenance').html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            $('#form_jarak_maintenance').submit();
        }

        function setjarakhari(kodeaset, jenis) {
            // console.log(kodeaset);
            if(jenis == 1){
                $('#mdl_header_jenis_maintenance').text('Kalibrasi');
                $('#mdl_jenis_maintenance_label').text('Kalibrasi');
                $('#mdl_jenis_maintenance_kolom').text('Kalibrasi');
            } else {
                $('#mdl_header_jenis_maintenance').text('Maintenance');
                $('#mdl_jenis_maintenance_label').text('Maintenance');
                $('#mdl_jenis_maintenance_kolom').text('Maintenance');
            }

            $('#mdl_jenis_maintenance_input').val(jenis);

            $('#mdl_loader').show();
            $('#mdl_body').hide();
            $('#mdl_kapasitas_max').modal('show');

            $('#mdl_kodeaset').val(kodeaset);

            $.ajax({
                url: "{{ route('maintenance_jarak_hari_get') }}",
                type: "POST",
                data: {
                    kodeaset: kodeaset,
                    jenis: jenis,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    // console.log(response);

                    table_body = $('#mdl_tbl_body');
                    table_body.empty();

                    $('#mdl_kode_aset').val(response.data.aset.kode_barang_aset);
                    $('#mdl_nama_aset').val(response.data.aset.nama_barang+' - '+response.data.aset.merk_barang);

                    if (response.data.history.length > 0) {
                        response.data.history.forEach(function(item) {
                            if(item.jarak_baru == 0){
                                item.jarak_baru = 'By Request';
                                item.satuan = '';
                            }
                            table_body.append(`
                                <tr>
                                    <td>${item.jarak_baru}</td>
                                    <td>${item.satuan}</td>
                                    <td>${item.timestamp}</td>
                                    <td>${item.gelar_depan} ${item.nama} ${item.gelar_belakang}</td>
                                </tr>
                            `);
                        });
                    } else {
                        table_body.append(`
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada data jarak maintenance</td>
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