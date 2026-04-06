@extends('layout_home')
@section('title', 'Penelitian')

@section('page-css')
    
@endsection

@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Penelitian</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Penelitian</a></li>
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
										
									</div>
									<div>
										<button class="btn btn-rounded btn-success float-right" onclick="penelitianbaru()">Buat Ajuan Penelitian Baru</button>
									</div>
								</div>
								
							</div>
							<div class="card-body">
								<div class="table table-striped table-responsive-sm">
									<table id="example3" class="display" style="width:100%">
										<thead>
											<tr>
												<th>ID Penelitian</th>
												<th>Topik</th>
												<th>Dosen Pembimbing</th>
												<th>unit / Fakultas<br>Pengelola</th>
												<th>Lab</th>
												<th>Status Ajuan</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody>
											@foreach($list_ajuan_penelitian as $ajuan)
												@php
													if($ajuan->status_ajuan == 1){
														$ajuan->status_ajuan = 'draft';
														$warna = 'orange';
													}
													else if($ajuan->status_ajuan == 2){
														$ajuan->status_ajuan = 'Menunggu Verif DosPem';
														$warna = 'blue';
													}
													else if($ajuan->status_ajuan == 3){
														$ajuan->status_ajuan = 'Menunggu Verif PJ Ruang';
														$warna = 'blue';
													}
													else if($ajuan->status_ajuan == 4){
														$ajuan->status_ajuan = 'Diizinkan';
														$warna = 'green';
													}
													else if($ajuan->status_ajuan == 5){
														$ajuan->status_ajuan = 'Ditolak';
														$warna = 'red';
													}
													else if($ajuan->status_ajuan == 6){
														$ajuan->status_ajuan = 'Dibatalkan';
														$warna = 'grey';
													}
													else{
														$ajuan->status_ajuan = 'Tidak Diketahui';
														$warna = 'black';
													}
												@endphp

												<tr>
													<td>{{ $ajuan->idpenelitian }}</td>
													<td>{{ $ajuan->topik }}</td>
													<td>{{ $ajuan->dosen_pembimbing ? $ajuan->gelar_depan . ' ' . $ajuan->dosen_pembimbing . ', ' . $ajuan->gelar_belakang : '-' }}</td>
													<td>{{ $ajuan->unit_kerja }}</td>
													<td>
														@if(isset($lab_digunakan[$ajuan->idpenelitian]))
															{{ implode(', ', $lab_digunakan[$ajuan->idpenelitian]) }}
														@else
															-
														@endif
													</td>
													<td><span style="color:{{ $warna }}">{{ $ajuan->status_ajuan }}</span></td>
													<td>
														<a href="{{ route('penelitian_mhs_show', ['id' => Crypt::encrypt($ajuan->idpenelitian)]) }}" class="btn btn-sm btn-primary">Lihat Detail</a>
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

		<form method="get" action="{{ route('penelitian_mhs_create') }}" id="form_penelitian_baru" style="display: none;">
		</form>
@endsection

@section('javascript')
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<script>
		function penelitianbaru(){
			Swal.fire({
				title: 'Apakah Anda yakin?',
				text: "Anda akan membuat ajuan penelitian baru.",
				icon: 'question',
				showCancelButton: true,
				confirmButtonText: 'Ya, lanjutkan',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.isConfirmed) {
					document.getElementById('form_penelitian_baru').submit();
				}
			});
		}
	</script>
@endsection