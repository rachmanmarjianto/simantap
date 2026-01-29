@extends('layout_home')

@section('title', 'Edit PJ Ruang')
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
							<li class="breadcrumb-item"><a href="javascript:void(0)">Edit</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">PJ ruang</a></li>
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
										<h4 class="card-title mb-0">Edit PJ Ruang</h4>
									</div>
									<div>
										<a href="{{ route('user_edit', ['id' => encrypt($iduser)]) }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="basic-form">
									
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
											<label class="col-sm-2 col-form-label">Role</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" value = "PJ Ruang" readonly>												
											</div>
										</div>
								</div> 
							</div>
						</div>
					</div>
					<div class="col-xl-12 col-xxl-12 col-lg-12">
						<div class="card">
							<div class="card-header">
								<div class="d-flex justify-content-between align-items-center w-100">
									<div class="flex-grow-1">
										<h4 class="card-title mb-0">Tambah Ruang</h4>
									</div>
									<div>	
									</div>										
								</div>
							</div>
							<div class="card-body">
								<form id="myForm" method="POST" action="{{ route('user_tambah_ruang_pj') }}">
									@csrf
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Ruang</label>
										<div class="col-sm-10">
											<select class="form-control select2" id="sel_ruang"  name = "idruang" width="100%">
												@foreach($ruang_list as $ruang)
													<option value="{{ $ruang->id }}">{{ $ruang->nama_ruang }} # {{ $ruang->nama_gedung }} # {{ $ruang->nama_kampus }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<input type="hidden" name="iduser" value="{{ $iduser }}">
								</form>

								<div id="btn-tambah-role-user" style="float:right">
									<button type="button" class="btn btn-primary"  onclick="tambahruang()">Tambahkan</button>
								</div>
							</div>
						</div>
					</div>
					<div class="col-xl-12 col-xxl-12 col-lg-12">
						<div class="card">
							<div class="card-header">
								<div class="d-flex justify-content-between align-items-center w-100">
									<div class="flex-grow-1">
										<h4 class="card-title mb-0">Tanggung Jawab Ruang</h4>
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
                                                <th>Ruang</th>
                                                <th>Unit Kerja</th>
												<th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($role_user as $ruang_pj)
                                                <tr>
                                                    <td>{{ $ruang_pj->nama_ruang }}</td>
                                                    <td>{{ $ruang_pj->nm_unit_kerja }}</td>
                                                    <td id="btn_status-{{ $ruang_pj->idpj_ruang }}">
                                                        @if($ruang_pj->status == 't')
															<button type="button" class="btn btn-success btn-sm" onclick="ubahstatus({{ $ruang_pj->idpj_ruang}}, 0)">Aktif</button>
														@else
															<button type="button" class="btn btn-danger btn-sm" onclick="ubahstatus({{ $ruang_pj->idpj_ruang}}, 1)">Non-Aktif</button>
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

			// function submitform() {
			// 	var btn = document.getElementById('btnsubmit');
			// 	btn.innerHTML = '<div class="spinner-border text-primary" role="status">\
			// 							<span class="sr-only">Loading...</span>\
			// 						</div>';
			// 	document.getElementById('myForm').submit();
			// }

			function tambahruang(){
				var btn = document.getElementById('btn-tambah-role-user');
				btn.innerHTML = '<div class="spinner-border text-primary" role="status">\
										<span class="sr-only">Loading...</span>\
									</div>';

				$('#myForm').submit();

			}

			

			function ubahstatus(idpj_ruang, status_baru) {
				var statusButton = document.getElementById('btn_status-' + idpj_ruang);

				statusButton.innerHTML = '<div class="spinner-border text-primary" role="status">\
												<span class="sr-only">Loading...</span>\
											</div>';

				$.ajax({
					url: "{{ route('user_ubah_status_ruang_pj') }}",
					type: 'POST',
					data: {
						_token: '{{ csrf_token() }}',
						idpj_ruang: idpj_ruang,
						status: status_baru
					},
					success: function(response) {
						console.log(response);

						if(response.code == 200) {
							if (status_baru == 1) {
								statusButton.innerHTML = '<button type="button" class="btn btn-success btn-sm" onclick="ubahstatus(' + idpj_ruang + ', 0)">Aktif</button>';
							} else {
								statusButton.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="ubahstatus(' + idpj_ruang + ', 1)">Non-aktif</button>';
							}
						} else {
							Swal.fire({
								title: 'Gagal',
								text: response.message,
								icon: 'error'
							});

							if (status_baru == 0) {
								statusButton.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="ubahstatus(' + idpj_ruang + ', 0)">Non-aktif</button>';
							} else {
								statusButton.innerHTML = '<button type="button" class="btn btn-success btn-sm" onclick="ubahstatus(' + idpj_ruang + ', 1)">Aktif</button>';
							}
						}
					},
					error: function(xhr, status, error) {
						Swal.fire({
							title: 'Error',
							text: 'error: ' + error,
							icon: 'error'
						});

						if (status_baru == 0) {
							statusButton.innerHTML = '<button type="button" class="btn btn-danger btn-sm" onclick="ubahstatus(' + idpj_ruang + ', 0)">Non-aktif</button>';
						} else {
							statusButton.innerHTML = '<button type="button" class="btn btn-success btn-sm" onclick="ubahstatus(' + idpj_ruang + ', 1)">Aktif</button>';
						}
					}
				});
			}
		</script>

@endsection