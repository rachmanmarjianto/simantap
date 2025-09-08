@extends('layout_home')
@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Edit Transksi Alat</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item"><a href="javascript:void(0)">Alat</a></li>
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
									<form action = "{{ route('transaksi_alat_edit_simpan', ['id' => $id ] ) }}" method="POST">
										@csrf
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Unit</label>
											<div class="col-sm-10">
												<select class="form-control" id = "unit_id" name = "unit_id">
													<option value = "0">---</option>
													@foreach($unit as $row)
														<option value = "{{ $row->id }}" @if($transaksi_alat->unit_id == $row->id) selected @endif>{{ $row->nama_unit }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Alat</label>
											<div class="col-sm-10">
												<select class="form-control" id = "alat_id" name = "alat_id">
													<option value = "0">---</option>
													@foreach($alat as $row)
														<option value = "{{ $row->id }}" @if($transaksi_alat->alat_id == $row->id) selected @endif>{{ $row->nama }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Layanan</label>
											<div class="col-sm-10">
												<select class="form-control" id = "layanan_id" name = "layanan_id">
													<option value = "0">---</option>
													@foreach($layanan as $row)
														<option value = "{{ $row->id }}" @if($transaksi_alat->layanan_id == $row->id) selected @endif>{{ $row->nama }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Pakai Mulai</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name = "waktu_pakai_alat_mulai" id="waktu_pakai_alat_mulai" value="{{ $transaksi_alat->waktu_pakai_alat_mulai }}">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Pakai Selesai</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name = "waktu_pakai_alat_selesai" id="waktu_pakai_alat_selesai" value="{{ $transaksi_alat->waktu_pakai_alat_selesai }}">
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Biaya Pakai Alat</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" name = "biaya_pakai_alat" id="biaya_pakai_alat" value="{{ $transaksi_alat->biaya_pakai_alat }}">
											</div>
										</div>
										<div class="form-group row">
											<div class="col-sm-10">
												<button type="button" class="btn btn-warning" onclick = "location.href='{{ route('transaksi_alat_index') }}';">Kembali</button>
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
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection