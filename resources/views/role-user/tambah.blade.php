@extends('layout_home')
@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Tambah {{ $sjudul }}</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item"><a href="javascript:void(0)">{{ $sjudul }}</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah</a></li>
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
									<form action = "{{ route($sroute_prefix.'simpan') }}" method="POST">
										@csrf
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">User</label>
											<div class="col-sm-10">
												<select class="form-control" id="sel1" name = "iduser">
													<option value = "0"> - </option>
													@foreach($user as $row)
														<option value = "{{ $row->iduser }}">{{ $row->nipnik.' - '.$row->nama }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Role</label>
											<div class="col-sm-10">
												<select class="form-control" id="sel2" name = "idrole">
													<option value = "0"> - </option>
													@foreach($role as $row)
														<option value = "{{ $row->idrole }}">{{ $row->nama_role }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Status</label>
											<div class="col-sm-10">
												<select class="form-control" id="sel1"  name = "status">
													<option value = "0">Tidak Aktif</option>
													<option value = "1">Aktif</option>
												</select>
											</div>
										</div>
										<div class="form-group row">
											<div class="col-sm-10">
												<button type="button" class="btn btn-warning" onclick = "location.href='{{ route($sroute_prefix.'index') }}';">Kembali</button>
												<button type="submit" class="btn btn-primary">Simpan</button>
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