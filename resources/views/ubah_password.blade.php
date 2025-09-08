@extends('layout_home')

@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Ubah Password</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Ubah Password</a></li>
						</ol>
					</div>
				</div>
				<!-- row -->
				<div class="row">
					<div class="col-xl-12 col-xxl-12 col-lg-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title"></h4>
							</div>
							<div class="card-body">
								<div class="basic-form">
									<form action = "{{ route('ubah_password_simpan') }}" method="POST">
										@csrf
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Password Lama</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name = "pass_lama">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Password Baru</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name = "pass_baru_1">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Password Baru (Konfirmasi)</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name = "pass_baru_2">
											</div>
										</div>
										<div class="form-group row">
											<div class="col-sm-10">
												<button type="submit" class="btn btn-primary">Proses</button>
											</div>
										</div>
										@if ($errors->any())
											<div class="alert alert-danger solid alert-dismissible fade show">
												<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
												<ul class="mb-0">
													@foreach ($errors->all() as $error)
														<li>{{ $error }}</li>
													@endforeach
												</ul>
											</div>
										@endif
										@if(session('status'))
											<div class="alert alert-{{ session('status')['status'] }} solid alert-dismissible fade show">
												<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
												{{ session('status')['message'] }}
											</div>
										@endif
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection