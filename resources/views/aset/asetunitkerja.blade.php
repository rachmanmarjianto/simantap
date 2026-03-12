@extends('layout_home')

@section('title', 'Alat Lab Unit Kerja')

@section('page-css')
    <link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        /* Biar rapi di modal */
        #qr-reader {
            border: 1px dashed #cfcfcf;
            border-radius: 12px;
            padding: 8px;
            min-height: 260px;
        }
    </style>
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
                            <div class="d-flex align-items-center">
                                <a type="button" class="btn btn-success mr-2" href="{{ route('aset_unitkerja_tambah', ['idunitkerja' => encrypt($idunitkerja)]) }}">Tambah Alat</a>
                                <button type="button" class="btn btn-secondary mr-auto" id="btnOpenScan">Cari aset dengan Scan QR</button>
                                <div class="form-check" style="margin-left: 12px;">
                                    <input type="checkbox" class="form-check-input" id="check1" onclick="nowrapTable()">
                                    <label class="form-check-label" for="check1">no Wrap</label>
                                </div>
                            </div>
                            <div class="table-responsive mt-3" style="overflow-x: auto;">									
                                <table id="example3" class="display" style="min-width: 900px; width:100%;">
                                    <thead>
                                        <tr>
                                            <th>Kode Aset</th>
                                            <th>Nama</th>
                                            <th>Merk</th>
                                            <th>Tahun Aset</th>
                                            <th>Kondisi</th>
                                            <th>Ruang</th>
                                            <th>Kapasitas Max (Min/Hari)</th>
                                            <th>ditawarkan<br>publik</th>
                                            <th>status</th>
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
                                            <td>
                                                {{ $a->merk_barang }}<i class="fa fa-pencil" aria-hidden="true" style="cursor:pointer; color:red; float:right; vertical-align:super;" onclick="ubahketerangan('{{ $a->kode_barang_aset }}', '{{ $a->keterangan }}')"></i><br>
                                                {{ $a->keterangan }}
                                            </td>
                                            <td>{{ $a->tahun_aset }}</td>
                                            <td>{{ $kondisi }}</td>
                                            <td>{{ $a->nama_ruang }}#<br>{{ $a->nama_gedung }}#<br>{{ $a->nama_kampus }}</td>
                                            <td style="cursor:pointer" onclick="setkapasitasmax('{{ $a->kode_barang_aset }}')" title="set kapasitas max"><input type="text" class="form-control input-default " value="{{ $a->kapasitas_max }}"  readonly/></td>
                                            
                                                {{-- {!! $a->public == 1 ? '<button class="btn btn-success" onclick="public(0, \'' . $a->kode_barang_aset . '\')">Ya</button>' : '<button class="btn btn-danger" onclick="public(1, \'' . $a->kode_barang_aset . '\')">Tidak</button>' !!} --}}
                                                {{-- {!! $a->public == 1 ? '<span style="cursor:pointer; color:green;" onclick="public(0, \'' . $a->kode_barang_aset . '\')">Ya</span>' : '<span style="cursor:pointer; color:red;" onclick="public(1, \'' . $a->kode_barang_aset . '\')">Tidak</span>' !!} --}}
                                            @if($a->public == 1)
                                                <td id="btn-public-{{ $a->kode_barang_aset }}" style="cursor:pointer; color:green;" onclick="public(0, '{{ $a->kode_barang_aset }}')">Ya</td>
                                            @else
                                                <td id="btn-public-{{ $a->kode_barang_aset }}" style="cursor:pointer; color:red;" onclick="public(1, '{{ $a->kode_barang_aset }}')">Tidak</td>
                                            @endif
                                            <td id="btn-status-{{ $a->kode_barang_aset }}">
                                                {!! $a->status == 1 ? '<button class="btn btn-success" onclick="ubahstatus(0, \'' . $a->kode_barang_aset . '\')">Aktif</button>' : '<button class="btn btn-danger" onclick="ubahstatus(1, \'' . $a->kode_barang_aset . '\')">Tidak Aktif</button>' !!}
                                            </td>
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

    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="mdl_keterangan_aset">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Keterangan Aset</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('aset_keterangan_simpan') }}" method="POST" id="form_keterangan">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-12">                                    
                                        <input type="hidden" name="kodeaset" id="mdl_kodeaset_keterangan">
                                        <textarea class="form-control" name="keterangan" id="ta_keterangan" placeholder="Keterangan" rows="4" maxlength="500"></textarea>
                                    </form>
                                </div>                                
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" style="float:right" form="form_keterangan">Simpan</button>
                    </form>
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

    <div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scanModalLabel">Scan QR Code</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>

            <div class="modal-body">
                <div class="row g-3">
                <div class="col-12 col-md-4">
                    <label class="form-label">Pilih Kamera</label>
                    <select id="cameraSelect" class="form-select">
                        <option value="">Memuat kamera...</option>
                    </select>

                    <div class="d-grid gap-2 mt-3">
                    <button id="btnStartScan" class="btn btn-success" disabled>Mulai Scan</button>
                    <button id="btnStopScan" class="btn btn-outline-danger" disabled>Stop</button>
                    </div>

                    <div class="mt-3">
                    <label class="form-label">Status</label>
                    <div id="scanStatus" class="small text-muted">Belum mulai.</div>
                    </div>
                </div>

                <div class="col-12 col-md-8">
                    <!-- Area kamera -->
                    <div id="qr-reader" style="width: 100%;"></div>
                    <div class="small text-muted mt-2">
                    Jika di iPhone, pastikan akses kamera diizinkan (Safari/Chrome).
                    </div>
                </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <audio id="beepSound" style="display:none;">
        <source src="{{ asset('app-assets/sound/beep.mp3') }}" type="audio/mpeg">
    </audio>
@endsection

@section('javascript')
    <script src="{{ asset('app-assets') }}/vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        $(document).ready(function() {
            $('#example3').DataTable({
                "order": [[ 0, "asc" ]],
                "pageLength": 50
            });
        });

        function nowrapTable() {
            var table = $('#example3').DataTable();
            if ($('#check1').is(':checked')) {
                $('#example3').removeClass('nowrap').addClass('nowrap');
            } else {
                $('#example3').removeClass('nowrap');
            }
            table.draw();
        }

        function ubahstatus(status, kodeaset) {
            var id = '#btn-status-' + kodeaset;
            $(id).html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');

            $.ajax({
                url: "{{ route('aset_ubah_status') }}",
                type: "POST",
                data: {
                    kodeaset: kodeaset,
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);
                    if (response.code == 200) {
                        // Update tampilan tombol berdasarkan status baru
                        var btnStatus = $('#btn-status-' + kodeaset);
                        if (status == 1) {
                            btnStatus.html('<button class="btn btn-success" onclick="ubahstatus(0, \'' + kodeaset + '\')">Aktif</button>');
                        } else {
                            btnStatus.html('<button class="btn btn-danger" onclick="ubahstatus(1, \'' + kodeaset + '\')">Tidak Aktif</button>');
                        }
                    } else {
                        if (status == 1) {
                            btnStatus.html('<button class="btn btn-danger" onclick="ubahstatus(1, \'' + kodeaset + '\')">Tidak Aktif</button>');                            
                        } else {
                            btnStatus.html('<button class="btn btn-success" onclick="ubahstatus(0, \'' + kodeaset + '\')">Aktif</button>');
                        }
                        alert('Gagal mengubah status.');
                    }
                },
                error: function(xhr, status, error) {
                    if (status == 1) {
                        btnStatus.html('<button class="btn btn-danger" onclick="ubahstatus(1, \'' + kodeaset + '\')">Tidak Aktif</button>');                            
                    } else {
                        btnStatus.html('<button class="btn btn-success" onclick="ubahstatus(0, \'' + kodeaset + '\')">Aktif</button>');
                    }
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }

        function public(status, kodeaset) {
            var id = '#btn-public-' + kodeaset;
            $(id).html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');

            $.ajax({
                url: "{{ route('aset_publish_aset') }}",
                type: "POST",
                data: {
                    kodeaset: kodeaset,
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);
                    if (response.code == 200) {
                        // Update tampilan tombol berdasarkan status baru
                        var btnPublic = $('#btn-public-' + kodeaset);
                        if (status == 1) {
                            // btnPublic.html('<span style="cursor:pointer; color:green;" onclick="public(0, \'' + kodeaset + '\')">Ya</span>');
                            btnPublic.css('color', 'green');
                            btnPublic.attr('onclick', 'public(0, \'' + kodeaset + '\')');
                            btnPublic.text('Ya');
                        } else {
                            btnPublic.css('color', 'red');
                            btnPublic.attr('onclick', 'public(1, \'' + kodeaset + '\')');
                            btnPublic.text('Tidak');
                        }
                    } else {
                        if (status == 1) {
                            btnPublic.css('color', 'red');
                            btnPublic.attr('onclick', 'public(1, \'' + kodeaset + '\')');
                            btnPublic.text('Tidak');                          
                        } else {
                            btnPublic.css('color', 'green');
                            btnPublic.attr('onclick', 'public(0, \'' + kodeaset + '\')');
                            btnPublic.text('Ya');
                        }
                        alert('Gagal mengubah status public.');
                    }
                },
                error: function(xhr, status, error) {
                    if (status == 1) {
                        btnPublic.css('color', 'red');
                        btnPublic.attr('onclick', 'public(1, \'' + kodeaset + '\')');
                        btnPublic.text('Tidak');                            
                    } else {
                        btnPublic.css('color', 'green');
                        btnPublic.attr('onclick', 'public(0, \'' + kodeaset + '\')');
                        btnPublic.text('Ya');
                    }
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        }

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

        function ubahketerangan(kodeaset, keterangan){
            $('#mdl_kodeaset_keterangan').val(kodeaset);
            $('#ta_keterangan').val(keterangan);
            $('#mdl_keterangan_aset').modal('show');
            console.log(kodeaset);
        }
    </script>

    <script>
        const modalEl = document.getElementById('scanModal');
        const scanModal = new bootstrap.Modal(modalEl);

        let html5QrCode = null;
        let isScanning = false;

        const $cameraSelect = $('#cameraSelect');
        const $btnStart = $('#btnStartScan');
        const $btnStop  = $('#btnStopScan');
        const $status   = $('#scanStatus');
        const $result   = $('#qrResult');

        function setStatus(msg) {
            $status.text(msg);
        }

        async function loadCameras() {
            console.log('Loading cameras...');
            try {
            setStatus('Meminta daftar kamera...');
            const devices = await Html5Qrcode.getCameras();

            console.log(devices);

            $cameraSelect.empty();

            if (!devices || devices.length === 0) {
                $cameraSelect.append(`<option value="">Kamera tidak ditemukan</option>`);
                setStatus('Tidak ada kamera yang terdeteksi.');
                $btnStart.prop('disabled', true);
                return;
            }

            // isi dropdown
            devices.forEach((d, idx) => {
                $cameraSelect.append(`<option value="${d.id}">${d.label || ('Camera ' + (idx+1))}</option>`);
            });

            $cameraSelect.selectpicker('refresh');

            // prefer kamera belakang (biasanya ada kata "back/rear/environment")
            const preferred = devices.find(d => /back|rear|environment/i.test(d.label || ''));
            if (preferred) $cameraSelect.val(preferred.id);

            setStatus(`Kamera terdeteksi: ${devices.length}. Pilih kamera lalu klik "Mulai Scan".`);
            $btnStart.prop('disabled', false);

            } catch (err) {
            console.error(err);
            setStatus('Gagal memuat kamera. Pastikan izin kamera diizinkan & pakai HTTPS/localhost.');
            $btnStart.prop('disabled', true);
            }
        }

        async function startScan() {
            console.log('mulai scan');
            const cameraId = $cameraSelect.val();
            if (!cameraId) {
                setStatus('Silakan pilih kamera dulu.');
                return;
            }

            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("qr-reader");
            }

            try {
                setStatus('Memulai kamera...');
                $btnStart.prop('disabled', true);
                $btnStop.prop('disabled', false);

                isScanning = true;

                await html5QrCode.start(
                    { deviceId: { exact: cameraId } },
                    {
                        fps: 10,
                        qrbox: { width: 260, height: 260 },
                        // aspectRatio: 1.777, // opsional
                    },
                    (decodedText, decodedResult) => {
                        // sukses scan
                        console.log('QR Code Terbaca:', decodedText);
                        stopScan(true);
                        const beepSound = document.getElementById('beepSound');
                        if (beepSound) beepSound.play();
                        
                        $('#example3').DataTable().search(decodedText).draw();

                        scanModal.hide();
                        
                        // $result.val(decodedText);
                        // setStatus('QR terbaca. Menghentikan scan...');
                        // stopScan(true);
                        // scanModal.hide();
                    },
                    (errorMessage) => {
                        // ignore error scanning frame-by-frame (normal)
                    }
                );

                setStatus('Arahkan kamera ke QR code...');
            } catch (err) {
                console.error(err);
                setStatus('Gagal memulai kamera. Coba ganti kamera / refresh.');
                $btnStart.prop('disabled', false);
                $btnStop.prop('disabled', true);
                isScanning = false;
            }
        }

        async function stopScan(fromSuccess = false) {
            if (!html5QrCode || !isScanning) {
            $btnStop.prop('disabled', true);
            $btnStart.prop('disabled', false);
            if (!fromSuccess) setStatus('Belum mulai.');
            return;
            }

            try {
            await html5QrCode.stop();
            await html5QrCode.clear();
            } catch (e) {
            // kadang stop/clear bisa error kecil di beberapa device, aman diabaikan
            console.warn(e);
            } finally {
            isScanning = false;
            $btnStop.prop('disabled', true);
            $btnStart.prop('disabled', false);
            if (!fromSuccess) setStatus('Scan dihentikan.');
            }
        }

        // Button utama
        $('#btnOpenScan').on('click', async function () {
            scanModal.show();
            await loadCameras();
        });

        // Saat modal tampil -> load kamera
        // modalEl.addEventListener('shown.bs.modal', async () => {
        //     await loadCameras();
        // });

        // Saat modal ditutup -> stop kamera (biar gak nyangkut)
        modalEl.addEventListener('hidden.bs.modal', async () => {
            await stopScan(false);
        });

        // Buttons di modal
        $btnStart.on('click', startScan);
        $btnStop.on('click', () => stopScan(false));

        // Jika user ganti kamera saat scanning, restart scanning
        $cameraSelect.on('change', async () => {
            if (isScanning) {
            setStatus('Ganti kamera... restart scan.');
            await stopScan(false);
            await startScan();
            }
        });
    </script>
        
@endsection