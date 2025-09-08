@extends('layout_home')
@section('title', 'API Aplikasi')

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
							<h4>API</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item"><a href="{{ route('api_aplikasi_index') }}">API Aplikasi</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">API</a></li>
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
											Aplikasi
										</h4>
									</div>
									<div class="ms-auto">
										<a href="{{ route('api_aplikasi_list', ['id'=>encrypt($idunitkerja)]) }}" class="btn btn-warning">Kembali</a>
									</div>
								</div>
							</div>
							<div class="card-body">
								
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">Nama Aplikasi</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" readonly value="{{ $aplikasi->nama_aplikasi }}">
									</div>
								</div>
								<div class="form-group row">
									<label class="col-sm-3 col-form-label">IP Address</label>
									<div class="col-sm-9">
										<input type="text" class="form-control" readonly value="{{ $aplikasi->ipaddress }}">
									</div>
								</div>								
							</div>
						</div>
					</div>

					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<div class="d-flex justify-content-between align-items-center w-100">
									<div class="flex-grow-1">
										<h4 class="card-title" >
											API
										</h4>
									</div>
									<div class="ms-auto">
										<button type="button" class="btn btn-success" style="float:right" onclick="tambahep()"><i class="fa fa-plus" aria-hidden="true"></i> Endpoint</button>
									</div>
								</div>
							</div>
							<div class="card-body">
								<div class="table-responsive" >
									
                                    <table class="table table-striped table-responsive-sm" >
                                        <thead>
                                            <tr>
                                                <th>Nama Endpoint</th>
                                                <th>Method</th>
                                                <th>URL</th>
                                                <th>Jenis Endpoint</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($api_aplikasi as $api)
												<tr>
													<td>{{ $api->nama_endpoint }}</td>
													<td>{{ $api->method }}</td>
													<td>{{ $api->link }}</td>
													<td>{{ $api->nama_jenis_endpoint }}</td>
													<td id="status-{{ $api->idendpoint }}">
														@if($api->status_endpoint == 1)
															<span class="badge badge-success" style="cursor:pointer" onclick="ubahstatus({{ $api->idendpoint }}, {{ $api->idaplikasi_uk }}, {{ $api->idjenis_endpoint }}, 0)">Aktif</span>
														@else
															<span class="badge badge-danger" style="cursor:pointer" onclick="ubahstatus({{ $api->idendpoint }}, {{ $api->idaplikasi_uk }}, {{ $api->idjenis_endpoint }}, 1)">Non Aktif</span>
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

		<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true" id="mdl_tambah_endpoint">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Tambah Endpoint</h5>
						<button type="button" class="close" data-dismiss="modal"><span>&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form method="POST" action="{{ route('api_aplikasi_tambah_endpoint') }}" id="formtambahep">
							@csrf
							<input type="hidden" name="idaplikasi" value="{{ $aplikasi->idaplikasi_uk }}">
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Nama Endpoint</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="nama_endpoint" placeholder="Nama Endpoint" required>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Method</label>
								<div class="col-sm-9">
									<select class="form-control" name="method" required>
										<option value="GET">GET</option>
										<option value="POST">POST</option>
									</select>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">URL</label>
								<div class="col-sm-9">
									<input type="text" class="form-control" name="link" placeholder="URL Endpoint" required>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 col-form-label">Jenis Endpoint</label>
								<div class="col-sm-9">
									<select class="form-control" name="jenis_endpoint" required>
										@foreach($jenis_endpoint as $je)
											<option value="{{ $je->idjenis_endpoint }}">{{ $je->nama_jenis_endpoint }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" onclick="$('#formtambahep').submit()">Simpan</button>
					</div>
				</div>
			</div>
		</div>
@endsection

@section('javascript')

	<script>
		function tambahep(){
			$('#mdl_tambah_endpoint').modal('show');
		}

		function ubahstatus(idendpoint, idaplikasi_uk, idjenis_endpoint, status) {
			var id = 'status-' + idendpoint;
			$('#' + id).html('<span class="spinner-border text-success" role="status" aria-hidden="true"></span>');

			$.ajax({
				url: "{{ route('api_aplikasi_set_status_endpoint') }}",
				type: "POST",
				data: {
					_token: '{{ csrf_token() }}',
					idendpoint: idendpoint,
					status: status,
					idaplikasi_uk: idaplikasi_uk,
					idjenis_endpoint: idjenis_endpoint
				},
				success: function(response) {
					if (response.code == 200) {
						$('#' + id).html('<span class="badge badge-' + (status == 1 ? 'success' : 'danger') + '" style="cursor:pointer" onclick="ubahstatus(' + idendpoint + ', '+ idaplikasi_uk + ', '+ idjenis_endpoint + ', ' + (status == 1 ? 0 : 1) + ')">' + (status == 1 ? 'Aktif' : 'Non Aktif') + '</span>');
						window.location.reload();
					} else {
						$('#' + id).html('<span class="badge badge-' + (status == 0 ? 'success' : 'danger') + '" style="cursor:pointer" onclick="ubahstatus(' + idendpoint + ', '+ idaplikasi_uk + ', '+ idjenis_endpoint + ', ' + status + ')">' + (status == 0 ? 'Aktif' : 'Non Aktif') + '</span>');
						alert(response.message);
					}
				},
				error: function(xhr) {
					$('#' + id).html('<span class="badge badge-' + (status == 0 ? 'success' : 'danger') + '" style="cursor:pointer" onclick="ubahstatus(' + idendpoint + ', '+ idaplikasi_uk + ', '+ idjenis_endpoint + ', ' + status + ')">' + (status == 0 ? 'Aktif' : 'Non Aktif') + '</span>');
					alert('Error: ' + xhr.responseText);
				}
			});
		}
	</script>

@endsection