@extends('layout_home')

@section('title', 'Tambah User')

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
							<h4>Tambah User</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item"><a href="javascript:void(0)">User</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Tambah</a></li>
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
								<div class="d-flex justify-content-between align-items-center w-100">
									<div class="flex-grow-1">
										<h4 class="card-title">Cari User</h4>
									</div>
									<div>
										<a href="{{ route('user_index') }}" class="btn btn-rounded btn-warning float-right">Kembali</a>
									</div>
								</div>
                                
                            </div>
                            <div class="card-body">
                                <div class="row">
									<div class="col-sm-9 col-md-6">
										<div class="form-group row">
											<label class="col-sm-2 col-form-label">NIP/NIK</label>
											<div class="col-sm-10">
												<input type="text" class="form-control" id="cari_nipnik">
												<span style="color:red" id="cari_pesan"></span>
											</div>
										</div>
									</div>
									<div class="col-sm-3 col-md-6" id="btn_cari">
										<button type="button" class="btn btn-rounded btn-outline-primary" onclick = "cariuser()">Cari</button>
									</div>
								</div>

                            </div>
                        </div>
					</div>
					<div class="col-12">
						<div class="card">
                            <div class="card-header">
                                <h4 class="card-title"></h4>
                            </div>
                            <div class="card-body">
								<form action = "{{ route('user_simpan') }}" method="POST" id="myform">
									@csrf
									<input type="hidden" name="id_cyber" id="id_cyber">
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">NIP / NIK</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="nipnik" id="found_nipnik" style="background-color:#F8F9FA" readonly>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Nama</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="nama" id="found_nama" style="background-color:#F8F9FA" readonly>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Gelar Depan</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="gelar_depan" id="found_gelar_depan" style="background-color:#F8F9FA" readonly>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Gelar Belakang</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" name="gelar_belakang" id="found_gelar_belakang" style="background-color:#F8F9FA" readonly>
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Jenis User</label>
										<div class="col-sm-10">
											<input type="text" class="form-control" id="found_jenis_user" style="background-color:#F8F9FA" readonly>
											<input type="hidden" name="join_table" id="found_join_table">
										</div>
									</div>
									<div class="form-group row">
										<label class="col-sm-2 col-form-label">Role</label>
										<div class="col-sm-10">
											<select class="form-control" id="sel1"  name = "role">
												@foreach($role as $r)
													@if(session('userdata')['idrole'] != '1' && $r->idrole == '1')
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
								</form>
								<button type="button" class="btn btn-success" style="float:right" onclick="submitform()" id="btnsubmit" disabled>Tambahkan</button>
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

		function cariuser(){
			var nipnik = $('#cari_nipnik').val();
			$('#btn_cari').html('<div class="spinner-border text-primary" role="status">\
										<span class="sr-only">Loading...</span>\
									</div>');
			$.ajax({
				url: "{{ route('user_get_user') }}",
				type: "POST",
				data: {
					_token: "{{ csrf_token() }}",
					nipnik: nipnik
				},
				success: function(response) {
					// console.log(response);
					if(response.code == 200) {
						$('#id_cyber').val(response.data.id_pengguna);
						$('#found_nipnik').val(response.data.nipnik);
						$('#found_nama').val(response.data.nm_pengguna);
						$('#found_gelar_depan').val(response.data.gelar_depan);
						$('#found_gelar_belakang').val(response.data.gelar_belakang);
						$('#found_jenis_user').val(response.data.join_table == '1' ? 'Tendik' : 'Dosen');
						$('#found_join_table').val(response.data.join_table);

						$('#cari_pesan').text('');
						
					} else {
						$('#id_cyber').val('');
						$('#found_nipnik').val('');
						$('#found_nama').val('');
						$('#found_gelar_depan').val('');
						$('#found_gelar_belakang').val('');
						$('#found_jenis_user').val('');
						$('#found_join_table').val('');
					
						$('#cari_pesan').text(response.message);
					}

					checksubmitbtn();

					// if(response.length > 0) {
					// 	$('#id_cyber').val(response[0].id_pengguna);
					// 	$('#found_nipnik').val(response[0].nipnik);
					// 	$('#found_nama').val(response[0].nm_pengguna	);
					// 	$('#found_gelar_depan').val(response[0].gelar_depan);
					// 	$('#found_gelar_belakang').val(response[0].gelar_belakang);
					// 	$('#found_jenis_user').val(response[0].join_table == '1' ? 'Tendik' : 'Dosen');

					// 	$('#cari_pesan').text('');
						
					// } else {
					// 	$('#cari_pesan').text('NIP/NIK tidak dikenali');
					// }

					$('#btn_cari').html('<button type="button" class="btn btn-rounded btn-outline-primary" onclick = "cariuser()">Cari</button>');
				},
				error: function(xhr, status, error) {
					console.error('Error:', error);
					alert('Terjadi kesalahan saat mencari user:' + error);

					$('#btn_cari').html('<button type="button" class="btn btn-rounded btn-outline-primary" onclick = "cariuser()">Cari</button>');
					checksubmitbtn();
				}
			});
		}

		function checksubmitbtn(){
			var id_cyber = $('#id_cyber').val();
			if(id_cyber != '') {
				$('#btnsubmit').prop('disabled', false);
			} else {
				$('#btnsubmit').prop('disabled', true);
			}
		}

		function submitform(){
			Swal.fire({
				title: "Yakin tambahkan?",
				text: "Pastikan data yang dimasukkan sudah benar",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Ya, Tambahkan!"
				}).then((result) => {
				if (result.isConfirmed) {
					$('#myform').submit();
				}
			});
		}
	</script>
@endsection