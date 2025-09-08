@extends('layout_home')
@section('title', 'Report Penggunaan Alat detail')

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
							<h4>Report Penggunaan Alat</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item"><a href="{{ route('report_penggunaan_alat') }}">Report Penggunaan Alat</a></li>
							<li class="breadcrumb-item"><a href="{{ route('report_penggunaan_alat_summary', ['id'=>encrypt($idunitkerja)]) }}">Summary Penggunaan Alat</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Detail</a></li>
						</ol>
					</div>
				</div>
				<!-- row -->

				<div class="row">
					<div class="col-12">
						<div class="card">
							@if(session('status'))
								<div class="alert alert-{{ session('status')['status'] }} solid alert-dismissible fade show">
									<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
									{{ session('status')['message'] }}
								</div>
							@endif
							<div class="card-header">
								<div class="d-flex justify-content-between align-items-center w-100">
									<div class="flex-grow-1">
										<h4 class="card-title" >
											Detail Info Alat
										</h4>
									</div>
									<div class="ms-auto">
										<a href="{{ route('report_penggunaan_alat_summary', ['id'=>encrypt($idunitkerja)]) }}" class="btn btn-warning">Kembali</a>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Kode Barang</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" value="{{ $barang->kode_barang_aset }}" readonly>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Nama Barang</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" value="{{ $barang->nama_barang }}" readonly>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Merk Barang</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" value="{{ $barang->merk_barang }}" readonly>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Tahun Aset</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" value="{{ $barang->tahun_aset }}" readonly>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Ruangan</label>
									<div class="col-sm-9">
										<textarea class="form-control" rows="3" id="comment" style="text-align:left; vertical-align:top; padding-top:0.25rem;" readonly>{{ $barang->nama_ruang }} # {{ $barang->nama_gedung }} # {{ $barang->nama_kampus }}</textarea>

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
										<h4 class="card-title" >
											Detail Penggunaan Alat
										</h4>
									</div>
									<div class="ms-auto">
										
									</div>
								</div>
							</div>
							<div class="card-body">
								<form method="post" action="{{ route('report_set_tanggal') }}" id="formsettanggal">
									@csrf
									<input type="hidden" name="routename" value="report_penggunaan_alat_detail">
									<input type="hidden" name="idunitkerja" value="{{ $idunitkerja }}">
									<input type="hidden" name="kode_barang" value="{{ $kode_barang_aset }}">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group row">
												<label class="col-sm-3 col-form-label">Range Tanggal</label>
												<div class="col-sm-9">
													<input class="form-control input-daterange-datepicker" name="rangetanggal" type="text" id="idrangetgl" value="{{ $tgl_awal }} - {{ $tgl_akhir }}">
												</div>
											</div>
										</div>
										<div class="col-md-6" id="btn_settgl">
											<button type="button" class="btn btn-success" onclick="settanggal()">Get Data</button>
										</div>
									</div>
								</form>
								<div class="table table-striped table-responsive-sm">
									<table id="example3" class="display" style="width:100%">
										<thead>
											<tr>
												<th>ID Permintaan</th>
												<th>Nama Layanan</th>
												<th>Waktu Permintaan</th>
												<th>Waktu Mulai</th>
												<th>Waktu Akhir</th>
												<th>Durasi</th>
											</tr>
										</thead>
										<tbody>
											@foreach($det_pemakaian as $sum)
											<tr>
												@php
													$durasi_detik = $sum->durasi_detik;
													$durasi_jam = floor($durasi_detik / 3600);
													$durasi_detik -= $durasi_jam * 3600;
													$durasi_menit = floor($durasi_detik / 60);
													$durasi_detik -= $durasi_menit * 60;

													$durasi_pemakaian = sprintf("%02d:%02d:%02d", $durasi_jam, $durasi_menit, $durasi_detik);


												@endphp
												<td>{{ $sum->idpermintaan_layanan }}<br>({{ $sum->idlayanan_aplikasi_asal }})</td>
												<td>{{ $sum->nama_layanan }}</td>
												<td>{{ $sum->ts_req_masuk_aplikasi_asal }}</td>
												<td>{{ $sum->timestamp_mulai }}</td>
												<td>{{ $sum->timestamp_akhir }}</td>
												<td>
													{{ $durasi_pemakaian }}
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
	<script src="{{ asset('app-assets') }}/vendor/moment/moment.min.js"></script>
    <script src="{{ asset('app-assets') }}/vendor/bootstrap-daterangepicker/daterangepicker.js"></script>
    <script src="{{ asset('app-assets') }}/vendor/datatables/js/jquery.dataTables.min.js"></script>

	<script>
		$(document).ready(function() {
            $('.input-daterange-datepicker').daterangepicker({
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse',
                locale: {
                    format: 'YYYY-MM-DD' // Ini yang menentukan format tampilan
                }
            });

            $('#example3').DataTable({
                "order": [[ 0, "desc" ]]
            });
        });

		function settanggal() {
			$('#btn_settgl').html('<div class="spinner-border text-success" role="status">\
										<span class="sr-only">Loading...</span>\
									</div>');

			$('#formsettanggal').submit();
		}
	</script>

@endsection