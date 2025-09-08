@extends('layout_home')
@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>{{ $sjudul }}</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">{{ $sjudul }}</a></li>
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
								<h4 class="card-title">
									<button type="button" class="btn btn-rounded btn-outline-primary" onclick = "location.href='{{ route($sroute_prefix.'tambah') }}';">Tambah Data</button>
								</h4>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example3" class="display" style="min-width: 845px">
										<thead>
											<tr>
												<th>User</th>
												<th>Role</th>
												<th>Status Aktif</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($data as $row)
											<tr>
												<td>{{ $row->user->nipnik }}<br>{{ $row->user->nama }}</td>
												<td>{{ $row->role->nama_role }}</td>
												<td>@if($row->status == '1') Aktif @elseif($row->status == '0') Tidak Aktif @endif</td>
												<td>
													<button type="button" class="btn btn-rounded btn-outline-primary" onclick = "location.href='{{ route($sroute_prefix.'edit', ['id' => $row->idrole_user] ) }}';">Edit</button>
													<button type="button" class="btn btn-rounded btn-outline-danger" onclick="if(confirm('Yakin hapus ?')){ location.href='{{ route($sroute_prefix.'hapus', ['id' => $row->idrole_user]) }}'; }">
														Hapus
													</button>
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