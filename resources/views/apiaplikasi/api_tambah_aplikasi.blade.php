@extends('layout_home')
@section('title', 'Registrasi Aplikasi Baru')

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
							<h4>Registrasi Aplikasi Baru</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item"><a href="{{ route('api_aplikasi_index') }}">API Aplikasi</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Registrasi Aplikasi Baru</a></li>
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
											Registrasi Aplikasi Baru milik <span style="color:blue"> {{ $unitkerja->nm_unit_kerja }} </span>
										</h4>
									</div>
									<div class="ms-auto">
										<a href="{{ route('api_aplikasi_list', ['id'=>encrypt($idunitkerja)]) }}" class="btn btn-warning">Kembali</a>
									</div>
								</div>
							</div>
							<div class="card-body">
								<form method="post" action="{{ route('api_aplikasi_store') }}" id="formtambahaplikasi">
									@csrf
									<input type="hidden" name="idunitkerja" value="{{ $idunitkerja }}">
									<div class="form-group row">
										<label class="col-sm-3 col-form-label">Nama Aplikasi</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="nama_aplikasi" placeholder="Nama Aplikasi" required>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-3 col-form-label">IP Address</label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="ip_address" placeholder="IP Address" required>
										</div>
									</div>
									<div id="btn_submit" style="float:right;">
										<input type="submit" class="btn btn-success" value="Simpan">
									</div>


								</form>

								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection

@section('javascript')

	<script>
		function submit(){
			$('#btn_submit').html('<span class="spinner-border text-success" role="status" aria-hidden="true"></span>');
		}
	</script>

@endsection