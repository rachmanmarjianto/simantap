@extends('layout_home')

@section('title', 'Setting Unit Kerja')

@section('page-css')
@endsection

@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Unit Kerja</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Unit Kerja</a></li>
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
								
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example3" class="table table-striped table-responsive-sm" style="min-width: 845px">
										<thead>
											<tr>
												<th>Nama Unit Kerja</th>
												<th>Layanan</th>
												<th>Penelitian</th>
												<th>Praktikum</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($unit_kerja as $unit)
											<tr>
												<td>{{ $unit->nm_unit_kerja }}</td>
												<td id="status_layanan_{{ $unit->idunit_kerja_simantap }}">
													@if($unit->layanan == 1)
														<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus({{ $unit->idunit_kerja_simantap }}, 'layanan', 0)">Aktif</button>
													@else
														<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus({{ $unit->idunit_kerja_simantap }}, 'layanan', 1)">Non Aktif</button>
													@endif
												</td>
												<td id="status_penelitian_{{ $unit->idunit_kerja_simantap }}">
													@if($unit->penelitian == 1)
														<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus({{ $unit->idunit_kerja_simantap }}, 'penelitian', 0)">Aktif</button>
													@else
														<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus({{ $unit->idunit_kerja_simantap }}, 'penelitian', 1)">Non Aktif</button>
													@endif
												</td>
												<td id="status_praktikum_{{ $unit->idunit_kerja_simantap }}">
													@if($unit->praktikum == 1)
														<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus({{ $unit->idunit_kerja_simantap }}, 'praktikum', 0)">Aktif</button>
													@else
														<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus({{ $unit->idunit_kerja_simantap }}, 'praktikum', 1)">Non Aktif</button>
													@endif
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
		function ubahstatus(idunit, jenis, statusbaru){
			var idkol = '#status_'+jenis+'_'+idunit;
			$(idkol).html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>');

			$.ajax({
				url: "{{ route('unitkerja_ubahstatus') }}",
				type: "POST",
				data: {
					_token: '{{ csrf_token() }}',
					idunit: idunit,
					jenis: jenis,
					statusbaru: statusbaru
				},
				success: function(response){
					if(response.code == 200){
						if(statusbaru == 1){
							$(idkol).html('<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus('+idunit+', \''+jenis+'\', 0)">Aktif</button>');
						} else {
							$(idkol).html('<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus('+idunit+', \''+jenis+'\', 1)">Non Aktif</button>');
						}
					} else {
						alert('Gagal mengubah status: ' + response.message);
						
						if(statusbaru == 1){
							$(idkol).html('<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus('+idunit+', \''+jenis+'\', 1)">Non Aktif</button>');
						} else {
							$(idkol).html('<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus('+idunit+', \''+jenis+'\', 0)">Aktif</button>');
						}
					}
				},
				error: function(xhr){
					alert('Terjadi kesalahan saat mengubah status.'+ xhr.responseText);
					if(statusbaru == 1){
						$(idkol).html('<button type="button" class="btn btn-rounded btn-danger" onclick="ubahstatus('+idunit+', \''+jenis+'\', 1)">Non Aktif</button>');
					} else {
						$(idkol).html('<button type="button" class="btn btn-rounded btn-success" onclick="ubahstatus('+idunit+', \''+jenis+'\', 0)">Aktif</button>');
					}
				}
				
			});
		}
		
	</script>


@endsection