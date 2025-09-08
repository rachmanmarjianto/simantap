@extends('layout_home')

@section('title', 'Master Pemetaan Operator ke Layanan')

@section('page-css')
    <link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Master Pemetaan Operator ke Layanan</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Master Pemetaan Operator ke Layanan</a></li>
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
                                <h4 class="card-title">Data Layanan yang telah memiliki Operator</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-responsive-sm">
                                        <thead>
											<tr>
												<th>Unit Kerja</th>
												<th>Jumlah Layanan</th>
												<th>Jumlah Layanan Terpetakan</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($unitkerja as $row)
											<tr>
												<td>{{ $row->nm_unit_kerja }}</td>
												<td>{{ $layanan[$row->id_unit_kerja] ?? 0 }}</td>
												<td>{{ $row->jumlah_layanan }}</td>
												<td id="aksi-{{ $row->id_unit_kerja }}">
													<a type="button" class="btn btn-rounded btn-outline-success" href="{{ route('layanan_operator_maping_operator', ['iduk'=>encrypt($row->id_unit_kerja)]) }}">Detail</a>													
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