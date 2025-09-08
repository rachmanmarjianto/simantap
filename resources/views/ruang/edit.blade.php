@extends('layout_home')
@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Edit {{ $sjudul }}</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item"><a href="javascript:void(0)">{{ $sjudul }}</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Edit</a></li>
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
									<form action = "{{ route($sroute_prefix.'edit_simpan', ['id' => $id ] ) }}" method="POST">
										@csrf
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Nama Ruang</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name = "nama_ruang" value="{{ $data->nama_ruang }}">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Tipe Ruang</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name = "tipe_ruang" value="{{ $data->tipe_ruang }}">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Nama Gedung</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name = "nama_gedung" value="{{ $data->nama_gedung }}">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Nama Kampus</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name = "nama_kampus" value="{{ $data->nama_kampus }}">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Unit Kerja</label>
											<div class="col-sm-10">
												<select class="form-control" id="sel1" name = "idunit_kerja">
													<option value = "0"> - </option>
													@foreach($unit_kerja as $row)
														<option value = "{{ $row->idunit_kerja }}" @if($data->idunit_kerja == $row->idunit_kerja) selected @endif>{{ $row->nama_unit_kerja }}</option>
													@endforeach
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