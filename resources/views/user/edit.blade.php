@extends('layout_home')

@section('title', 'Edit User')
@section('page-css')
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.0/dist/sweetalert2.min.css" rel="stylesheet">
@endsection

@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Edit User</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item"><a href="javascript:void(0)">User</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Edit</a></li>
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
					<div class="col-xl-12 col-xxl-12 col-lg-12">
						<div class="card">
							<div class="card-header">
								<div class="d-flex justify-content-between align-items-center w-100">
									<div class="flex-grow-1">
										<h4 class="card-title mb-0">Edit User</h4>
									</div>
									<div>
										<a href="{{ route('user_index') }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="basic-form">
									<form action = "{{ route('user_edit_simpan', ['id' => encrypt($iduser) ] ) }}" method="POST" id="myForm">
										@csrf
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">NIP / NIK</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" value = "{{ $user->nipnik }}" readonly>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Nama</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" value = "{{ $user->nama }}" readonly>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Gelar Depan</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" value = "{{ $user->gelar_depan }}" readonly>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Gelar Belakang</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" value = "{{ $user->gelar_belakang }}" readonly>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Jenis User</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" value = "@if($user->join_table == 1) Tendik @else Dosen @endif" readonly>
											</div>
										</div>
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">Status</label>
											<div class="col-sm-10">
												<select class="form-control" id="sel1"  name = "status">
													<option value = "0" @if($user->status == '0') selected @endif>Tidak Aktif</option>
													<option value = "1" @if($user->status == '1') selected @endif>Aktif</option>
												</select>
											</div>
										</div>
										


										
										<div id="btnsubmit" style="float:right">
											
											<button type="button" class="btn btn-primary" onclick="submitform()">Simpan</button>
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
					<div class="col-xl-12 col-xxl-12 col-lg-12">
						<div class="card">
							<div class="card-header">
								<div class="d-flex justify-content-between align-items-center w-100">
									<div class="flex-grow-1">
										<h4 class="card-title mb-0">Tambah Role</h4>
									</div>
									<div>	
									</div>										
								</div>
							</div>
							<div class="card-body">
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Role</label>
									<div class="col-sm-10">
										<select class="form-control" id="sel_role"  name = "role">
											@foreach($role as $r)
												@if(session('userdata')['idrole'] !=1 && $r->idrole == 1)
													@continue
												@endif
												<option value="{{ $r->idrole }}">{{ $r->nama_role }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-2 col-form-label">Unit Kerja</label>
									<div class="col-sm-10">
										<select class="form-control select2" id="sel_uk"  name = "unit_kerja" width="100%">
											@foreach($unit_kerja as $uk)
												@if($uk->type_unit_kerja != 'PRODI')
												<option value="{{ $uk->idunit_kerja }}">{{ $uk->nama_unit_kerja }}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div>

								<div id="btn-tambah-role-user" style="float:right">
									<button type="button" class="btn btn-primary"  onclick="tambahroleuser()">Tambahkan</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-12 col-xxl-12 col-lg-12">
						<div class="card">
							<div class="card-header">
								<div class="d-flex justify-content-between align-items-center w-100">
									<div class="flex-grow-1">
										<h4 class="card-title mb-0">Detail Role</h4>
									</div>
									<div>
										
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive">
                                    <table class="table table-striped table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th>Role</th>
                                                <th>Unit Kerja</th>
                                                <th>Status</th>
                                                <th>is_delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($role_user as $ru)
											<tr>
												<td>{{ $ru->nama_role }}</td>
												<td>{{ $ru->nama_unit_kerja }}</td>
												<td id="status-{{ $ru->idrole_user }}">@if($ru->status == '1') 
														<button type="button" class="btn btn-success btn-sm">Aktif</button> 
													@else 
														<button type="button" class="btn btn-danger btn-sm" onclick="ubahstatus({{ $iduser }}, {{ $ru->idrole_user }}, 1)">Non-Aktif</button>  
													@endif
												</td>
												<td id="delete-{{ $ru->idrole_user }}">
													@if($ru->is_delete == '1') 
														<button type="button" class="btn btn-danger btn-sm" onclick="hapus({{ $ru->idrole_user }}, 0)">Terhapus</button>  
													@else 
														<button type="button" class="btn btn-success btn-sm" onclick="hapus({{ $ru->idrole_user }}, 1)">Tidak</button> 
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
		<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.22.0/dist/sweetalert2.all.min.js"></script>

		<script>
			$('.select2').select2();

			function submitform() {
				var btn = document.getElementById('btnsubmit');
				btn.innerHTML = '<div class="spinner-border text-primary" role="status">\
										<span class="sr-only">Loading...</span>\
									</div>';
				document.getElementById('myForm').submit();
			}

			function tambahroleuser(){
				var btn = document.getElementById('btn-tambah-role-user');
				btn.innerHTML = '<div class="spinner-border text-primary" role="status">\
										<span class="sr-only">Loading...</span>\
									</div>';

				$.ajax({
					url: "{{ route('user_tambah_role_user') }}",
					type: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						iduser: '{{ $iduser }}',
						idrole: $('#sel_role').val(),
						idunitkerja: $('#sel_uk').val()
					},
					success: function(response) {
						// console.log(response);
						if(response.code == 200) {
							$('#myForm').submit();
						} else {
							btn.innerHTML = '<button type="button" class="btn btn-primary" style="float:right" onclick="tambahroleuser()">Tambahkan</button>';
							Swal.fire({
								title: 'Gagal',
								text: response.message,
								icon: 'error'
							});
						}
					},
					error: function(xhr, status, error) {
						btn.innerHTML = '<button type="button" class="btn btn-primary" style="float:right" onclick="tambahroleuser()">Tambahkan</button>';
						Swal.fire({
							title: 'Error',
							text: 'error: ' + error,
							icon: 'error'
						});
					}
				});

			}

			function ubahstatus(iduser, idroleuser, status_ru) {
				var statusButton = document.getElementById('status-' + idroleuser);

				statusButton.innerHTML = '<div class="spinner-border text-primary" role="status">\
												<span class="sr-only">Loading...</span>\
											</div>';

				// console.log(status);
				$.ajax({
					url: "{{ route('user_ubah_status_role_user') }}",
					type: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						idroleuser: idroleuser,
						status: status_ru,
						iduser: iduser
					},
					success: function(response) {
						// console.log(response);
						if(response.code == 200) {
							// Update the status button text							
							if (status_ru == 1) {
								statusButton.innerHTML = '<button type="button" class="btn btn-success btn-sm">Aktif</button>';
							} else {
								statusButton.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="ubahstatus('+ iduser +','+ idroleuser + ', 1)">Non-Aktif</button>';
							}
							document.getElementById('myForm').submit();
						} else {
							Swal.fire({
								title: 'Gagal',
								text: response.message,
								icon: 'error'
							});

							if (status_ru == 0) {
								statusButton.innerHTML = '<button type="button" class="btn btn-success btn-sm">Aktif</button>';
							} else {
								statusButton.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="ubahstatus('+ iduser +','+ idroleuser + ', 1)">Non-Aktif</button>';
							}
						}
					},
					error: function(xhr, status, error) {
						Swal.fire({
							title: 'Error',
							text: 'error: ' + error,
							icon: 'error'
						});

						if (status_ru == 0) {
							statusButton.innerHTML = '<button type="button" class="btn btn-success btn-sm">Aktif</button>';
						} else {
							statusButton.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="ubahstatus('+ iduser +','+ idroleuser + ', 1)">Non-Aktif</button>';
						}
					}
				});
			}

			function hapus(idroleuser, is_delete) {
				var deleteButton = document.getElementById('delete-' + idroleuser);

				deleteButton.innerHTML = '<div class="spinner-border text-primary" role="status">\
												<span class="sr-only">Loading...</span>\
											</div>';

				$.ajax({
					url: "{{ route('user_hapus_role_user') }}",
					type: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						idroleuser: idroleuser,
						is_delete: is_delete
					},
					success: function(response) {
						if(response.code == 200) {
							if (is_delete == 1) {
								deleteButton.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="hapus(' + idroleuser + ', 0)">Terhapus</button>';
							} else {
								deleteButton.innerHTML = '<button type="button" class="btn btn-success btn-sm" onclick="hapus(' + idroleuser + ', 1)">Tidak</button>';
							}
						} else {
							Swal.fire({
								title: 'Gagal',
								text: response.message,
								icon: 'error'
							});

							if (is_delete == 0) {
								deleteButton.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="hapus(' + idroleuser + ', 0)">Terhapus</button>';
							} else {
								deleteButton.innerHTML = '<button type="button" class="btn btn-success btn-sm" onclick="hapus(' + idroleuser + ', 1)">Tidak</button>';
							}
						}
					},
					error: function(xhr, status, error) {
						Swal.fire({
							title: 'Error',
							text: 'error: ' + error,
							icon: 'error'
						});

						if (is_delete == 0) {
							deleteButton.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="hapus(' + idroleuser + ', 0)">Terhapus</button>';
						} else {
							deleteButton.innerHTML = '<button type="button" class="btn btn-success btn-sm" onclick="hapus(' + idroleuser + ', 1)">Tidak</button>';
						}
					}
				});
			}
		</script>

@endsection