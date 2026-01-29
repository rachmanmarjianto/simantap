@extends('layout_home')
@section('title', 'Form Maintenance')
@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Form Maintenance</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Form Maintenance</a></li>
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
												<th  style="text-align: center;">Unit Kerja</th>
												<th  style="text-align: center;">Form Kalibrasi</th>
												<th  style="text-align: center;">Form Maintenance</th>
												<th  style="text-align: center;">Action</th>
											</tr>
											
										</thead>
										<tbody>
											@foreach($unitkerja as $uk)
											<tr>
												<td>{{ $uk->nm_unit_kerja }}</td>
												<td style="text-align: center">{{ $uk->jumlah_form_kalibrasi }}</td>
												<td style="text-align: center">{{ $uk->jumlah_form_maintenance }}</td>		
												<td style="text-align: center">
													<a href="{{ route('form_maintenance_unit_kerja', ['id' => encrypt($uk->idunit_kerja)]) }}" class="btn btn-rounded btn-primary">Detail</a>
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