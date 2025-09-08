@extends('layout_home')
@section('title', 'List Aplikasi')

@section('page-css')
    <!-- Daterange picker -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
@endsection

@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>List Aplikasi</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item"><a href="{{ route('api_aplikasi_index') }}">API Aplikasi</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">List Aplikasi</a></li>
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
											List Aplikasi milik <span style="color:blue"> {{ $unitkerja->nm_unit_kerja }} </span>
										</h4>
									</div>
									<div class="ms-auto">
										<a href="{{ route('api_aplikasi_index') }}" class="btn btn-warning">Kembali</a>
									</div>
								</div>
							</div>
							<div class="card-body">
								<a type="button" class="btn btn-success" href="{{ route('api_aplikasi_tambah_aplikasi', ['id'=>encrypt($idunitkerja)]) }}"><i class="fa fa-plus" aria-hidden="true"></i> Aplikasi Baru</a>
								<div class="table table-striped table-responsive-sm">
									<table id="example3" class="display" style="width:100%">
										<thead>
											<tr>
												<th>Nama Aplikasi</th>
												<th>IP Address</th>
												<th>Status</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody>
											@foreach($aplikasi as $ap)
											<tr>
												<td>{{ $ap->nama_aplikasi }}</td>
												<td>{{ $ap->ipaddress }}</td>
												<td id="status-{{ $ap->idaplikasi_uk }}">
													@if($ap->status == 1)
														<span class="badge badge-success" onclick="setstatus({{$ap->idaplikasi_uk}}, 0)" style="cursor:pointer">Aktif</span>
													@else
														<span class="badge badge-danger" onclick="setstatus({{$ap->idaplikasi_uk}}, 1)" style="cursor:pointer">Non Aktif</span>
													@endif
												</td>
												<td>
													<a href="{{ route('api_aplikasi_set', ['idaplikasi'=>encrypt($ap->idaplikasi_uk)]) }}" class="btn btn-primary btn-sm">Detail</a>
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

		<form id="formstatus" method="post" action="{{ route('api_aplikasi_set_status') }}">
			@csrf
			<input type="hidden" name="idaplikasi_uk" id="idaplikasi_uk">
			<input type="hidden" name="status" id="status_value">
			<input type="hidden" name="idunitkerja" value="{{ $idunitkerja }}">
		</form>
@endsection

@section('javascript')

	<script>
		function setstatus(idaplikasi_uk, status) {
			// console.log(idaplikasi_uk, status);
			var id = 'status-' + idaplikasi_uk;
			$('#' + id).html('<span class="spinner-border text-success" role="status" aria-hidden="true"></span>');

			document.getElementById('idaplikasi_uk').value = idaplikasi_uk;
			document.getElementById('status_value').value = status;
			document.getElementById('formstatus').submit();
		}
	</script>

@endsection