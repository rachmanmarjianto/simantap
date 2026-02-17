@extends('layout_home')

@section('title', 'Proses Pemetaan Alat ke Layanan')

@section('page-css')
    <link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
                        <h4>Proses Pemetaan Alat ke Layanan</h4>
                    </div>
                </div>
                <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('layanan_aset_index') }}">Alat Lab</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('layanan_aset_maping_layanan_unitkerja', ['iduk' => encrypt($idunitkerja)]) }}">Pemataan Alat Lab ke Layanan</a></li>
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
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="flex-grow-1">
                                    <h4 class="card-title">Layanan <b style="color:blue">{{ $layanan->nama_layanan }}</b></h4>
                                </div>
                                <div>
                                    <a href="{{ route('layanan_aset_maping_layanan_unitkerja', ['iduk' => encrypt($idunitkerja)]) }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
                                </div>
                            </div>
                            
                        </div>
                        <div class="card-body">
                            <button type="button" class="btn btn-success" onclick="tambahalat()">Tambah alat</button>
                            <button type="button" class="btn btn-secondary" id="btnOpenScan">Scan QR</button>
                            <div class="table-responsive">
                                <table class="table table-striped table-responsive-sm">
                                    <thead>
                                        <tr>
                                            <th>No Urut</th>
                                            <th>Kode Aset</th>
                                            <th>Nama</th>
                                            <th>Merk</th>
                                            <th>Tahun Aset</th>
                                            <th>Ruang</th>
                                            <th>Waktu Ideal<br>(Menit)</th>
                                            <th>is_deleted</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <form id="formmaping" method="POST" action="{{ route('layanan_aset_maping_layanan_unitkerja_simpan') }}">
                                            @csrf
                                            <input type="hidden" name="idlayanan" value="{{ $layanan->idlayanan }}">
                                            @php
                                                $i=0;
                                            @endphp
                                            @foreach($alatlabmapped as $a)
                                                <tr>
                                                    <td>
                                                        <input type="number" class="form-control input-default " name="nourut[{{$i}}]" value="{{$a->no_urut}}">
                                                        <input type="hidden" name="idlayanan_aset[{{$i}}]" value="{{$a->idlayanan_aset}}">
                                                    </td>
                                                    <td>{{ $a->kode_barang_aset }}</td>
                                                    <td>{{ $a->nama_barang }}</td>
                                                    <td>{{ $a->merk_barang }}<br>{{ $a->keterangan }}</td>
                                                    <td>{{ $a->tahun_aset }}</td>
                                                    <td>{{ $a->nama_ruang }}#{{ $a->nama_gedung }}#{{ $a->nama_kampus }}</td>
                                                    <td><input type="number" class="form-control input-default " name="waktu_ideal[{{$i}}]" value="{{$a->waktu_penggunaan_ideal_min}}"></td>
                                                    <td id="btnhapus-{{ $a->idlayanan_aset }}">
                                                        @if($a->is_deleted == 0)
                                                            <span class="badge badge-success" style="cursor:pointer" onclick="hapus({{$a->idlayanan_aset}}, 1)">Tidak</span>
                                                        @else
                                                            <span class="badge badge-danger" style="cursor:pointer" onclick="hapus({{$a->idlayanan_aset}}, 0)">Terhapus</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
                                            @endforeach
                                        </form>
                                    </tbody>
                                </table>
                                <div id="btnsimpan" style="float:right" >
                                    <button type="button" class="btn btn-secondary" onclick="simpan()">Simpan Perubahan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" id="modalTambahAlat" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alat yang Tersedia</h5>
                    
                    </button>
                </div>
                <div class="modal-body">
                    <table id="example3" class="display" style="min-width: 845px; width:100%;">
                        <thead>
                            <tr>
                                <th>Kode Aset</th>
                                <th>Nama</th>
                                <th>Merk</th>
                                <th>Tahun Aset</th>
                                <th>Ruang</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alatlab as $a)
                            <tr>
                                <td>{{ $a->kode_barang_aset }}</td>
                                <td>{{ $a->nama_barang }}</td>
                                <td>{{ $a->merk_barang }}<br>{{ $a->keterangan }}</td>
                                <td>{{ $a->tahun_aset }}</td>
                                <td>{{ $a->nama_ruang }}#{{ $a->nama_gedung }}#{{ $a->nama_kampus }}</td>
                                <td id="btntambah-{{ $a->kode_barang_aset }}">
                                    <button href="" class="btn btn-rounded btn-primary" onclick="tambahkan({{ $a->kode_barang_aset }})"><i class="fa fa-plus"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer" id="mdl_close_btn">
                    <button type="button" class="btn btn-secondary" onclick="tutupmodal()">Tutup dan Refresh</button>
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
                "order": [[ 0, "asc" ]]
            });
        });

        async function mulai_scanQR() {
            const camerea = await loadCameras();
            $('#scanModal').modal('show');
            
        }

        function hapus(idlayanan_aset, is_deleted){
            var id = 'btnhapus-' + idlayanan_aset;
            $('#' + id).html('<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            $.ajax({
                url: "{{ route('layanan_aset_maping_layanan_unitkerja_hapus_alat') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    idlayanan_aset: idlayanan_aset,
                    is_deleted: is_deleted
                },
                success: function(response) {
                    console.log(response);
                    if( response.code === 200) {
                        if( is_deleted == 1) {
                            $('#' + id).html('<span class="badge badge-danger" style="cursor:pointer" onclick="hapus('+idlayanan_aset+', 0)">Terhapus</span>');
                        } else {
                            $('#' + id).html('<span class="badge badge-success" style="cursor:pointer" onclick="hapus('+idlayanan_aset+', 1)">Tidak</span>');
                        }
                    } else {
                        if( is_deleted == 0) {
                            $('#' + id).html('<span class="badge badge-danger" style="cursor:pointer" onclick="hapus('+idlayanan_aset+', 0)">Terhapus</span>');
                        } else {
                            $('#' + id).html('<span class="badge badge-success" style="cursor:pointer" onclick="hapus('+idlayanan_aset+', 1)">Tidak</span>');
                        }
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    if( is_deleted == 0) {
                        $('#' + id).html('<span class="badge badge-danger" style="cursor:pointer" onclick="hapus('+idlayanan_aset+', 0)">Terhapus</span>');
                    } else {
                        $('#' + id).html('<span class="badge badge-success" style="cursor:pointer" onclick="hapus('+idlayanan_aset+', 1)">Tidak</span>');
                    }
                    alert('error: ' + error);
                }
            });
        }

        function simpan(){
            $('#btnsimpan').html('<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            $('#formmaping').submit();
        }

        function tutupmodal() {
            $('#mdl_close_btn').html('<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            window.location.reload();
        }

        function tambahalat() {
            // Open the modal
            $('#modalTambahAlat').modal('show');
        }

        function tambahkan(kode_barang) {
            var id = 'btntambah-' + kode_barang;
            $('#' + id).html('<div class="spinner-grow text-primary" role="status"><span class="sr-only">Loading...</span></div>');
            
            $.ajax({
                url: "{{ route('layanan_aset_maping_layanan_unitkerja_tambah_alat') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    kode_barang: kode_barang,
                    idlayanan: '{{ $layanan->idlayanan }}',
                    idunitkerja: '{{ $idunitkerja }}'
                },
                success: function(response) {
                    if (response.status === 'success') {
                        $('#' + id).html('<span style="color:green"><i class="fa fa-check"></i></span>'); // Show success icon
                        
                    } else {
                        $('#' + id).html('<span style="color:red"><i class="fa fa-close"></i></span>'); // Show error icon
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    $('#' + id).html('<span style="color:red"><i class="fa fa-close"></i></span>'); // Show error icon
                    alert('error: ' + error);
                }
            });
        }

        function tambahkanQR(kode_barang) {
            setStatus('QR terbaca. Memproses...');
            
            $.ajax({
                url: "{{ route('layanan_aset_maping_layanan_unitkerja_tambah_alat') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    kode_barang: kode_barang,
                    idlayanan: '{{ $layanan->idlayanan }}',
                    idunitkerja: '{{ $idunitkerja }}'
                },
                success: function(response) {
                    console.log(response);
                    if(response.code === 200) {
                        setStatus('Alat berhasil ditambahkan. Proses refresh halaman...');
                        window.location.reload();
                    } else {
                        setStatus('Gagal menambahkan alat: ' + response.message + '. Klik mulai scan lagi untuk coba ulang.');
                    }
                    // window.location.reload();
                },
                error: function(xhr, status, error) {
                    
                    setStatus('Error menambahkan alat. Menghentikan scan...');
                    alert('error: ' + error);
                }
            });
        }

        //<i class="fa fa-plus"></i>
        //<i class="fa fa-close"></i>

        // console.log('ready!');
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
                        tambahkanQR(decodedText);
                        
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