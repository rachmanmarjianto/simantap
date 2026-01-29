@extends('layout_home')
@section('title', 'Maintenance Alat Lab')
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
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Maintenance Alat Lab</a></li>
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
								<h4 class="card-title">
									
								</h4>
							</div>
							<div class="card-body">
								<div class="table table-striped table-responsive-sm">
									<table id="example3" class="display" style="width:100%">
										<thead>
											<tr>
												<th rowspan="2" style="vertical-align: middle; text-align: center;">Unit Kerja</th>
												<th rowspan="2" style="vertical-align: middle; text-align: center;">Jumlah Alat</th>
												<th colspan="2" style="text-align: center;">Jumlah Alat Perlu Maintenance</th>
												<th colspan="2" style="text-align: center;">Jumlah Alat Perlu Kalibrasi</th>
												<th rowspan="2" style="vertical-align: middle; text-align: center;">Action</th>
											</tr>
											<tr>
												<th style="text-align: center;">Jumlah Alat</th>
												<th style="text-align: center;">Dengan PJ</th>
												<th style="text-align: center;">Jumlah Alat</th>
												<th style="text-align: center;">Dengan PJ</th>
											</tr>
										</thead>
										<tbody>
											@foreach($unitkerja as $uk)
											<tr>
												<td>{{ $uk->nm_unit_kerja }}</td>
												<td style="text-align: center">{{ $uk->jumlah_aset }}</td>
												<td style="text-align: center">{{ $jumlah_aset_maintenance[$uk->idunit_kerja] ?? 0 }}</td>												
												<td style="text-align: center">{{ $pj_maintenance[$uk->idunit_kerja] ?? 0 }}</td>
												<td style="text-align: center">{{ $jumlah_aset_kalibrasi[$uk->idunit_kerja] ?? 0 }}</td>
												<td style="text-align: center">{{ $pj_kalibrasi[$uk->idunit_kerja] ?? 0 }}</td>
												<td>
													<a href="{{ route('maintenance_unit_kerja', ['id' => encrypt($uk->idunit_kerja)]) }}" class="btn btn-rounded btn-primary">Lihat Alat</a>
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