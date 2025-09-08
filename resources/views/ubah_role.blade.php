@extends('layout_home')

@section('title', 'Ubah Role')

@section('page-css')
@endsection

@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Ubah Role</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Ubah Role</a></li>
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
								<h4 class="card-title"></h4>
							</div>
							<div class="card-body">
								<div class="table-responsive">
                                    <table class="table table-striped table-responsive-sm">
                                        <thead>
                                            <tr>
                                                <th>Role</th>
                                                <th>Unit Kerja</th>
                                                <th>Status</th>
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
														<button type="button" class="btn btn-danger btn-sm" onclick="ubahstatus({{ $ru->idrole_user }}, 1)">Non-Aktif</button>  
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

		<form action="{{ route('ubah_role_simpan') }}" method="POST" id="myform">
			@csrf
			<input type="hidden" name="idroleuser" id="idroleuser">
			<input type="hidden" name="status" id="status">
			<input type="hidden" name="iduser" id="iduser" value="{{ session()->get('userdata')['iduser'] }}">
		</form>
@endsection

@section('javascript')
		<script>
			function ubahstatus(idroleuser, status){
				var elemen = document.getElementById("status-" + idroleuser);
				elemen.innerHTML = '<div class="spinner-border text-primary" role="status">\
										<span class="sr-only">Loading...</span>\
									</div>';

				document.getElementById("idroleuser").value = idroleuser;
				document.getElementById("status").value = status;
				document.getElementById("myform").submit();				
			}
		</script>
@endsection