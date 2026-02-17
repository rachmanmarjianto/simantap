@extends('layout_home')

@section('content')
<div class="container py-4">
    <h4 class="mb-3">Scan QR Code</h4>

    <div class="mb-3">
        <button id="btnOpenScan" class="btn btn-primary">
            <i class="bi bi-qr-code-scan"></i> Scan QR
        </button>
    </div>

    <div class="mb-3">
        <label class="form-label">Hasil QR</label>
        <input type="text" id="qrResult" class="form-control" placeholder="Hasil scan akan muncul di sini" readonly>
        <small class="text-muted">Tips: jika ingin auto-submit, bisa trigger setelah hasil terisi.</small>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="scanModal" tabindex="-1" aria-labelledby="scanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="scanModalLabel">Scan QR Code</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-css')
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

@section('javascript')
<!-- jQuery (kalau layout kamu belum include) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- html5-qrcode -->
<script src="https://unpkg.com/html5-qrcode@2.3.10/html5-qrcode.min.js"></script>

<script>
$(function () {
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
    try {
      setStatus('Meminta daftar kamera...');
      const devices = await Html5Qrcode.getCameras();

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
          $result.val(decodedText);
          setStatus('QR terbaca. Menghentikan scan...');
          stopScan(true);
          scanModal.hide();
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
    console.log('Open Scan Modal');
    scanModal.show();
  });

  // Saat modal tampil -> load kamera
  modalEl.addEventListener('shown.bs.modal', async () => {
    await loadCameras();
  });

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
});
</script>
@endsection
