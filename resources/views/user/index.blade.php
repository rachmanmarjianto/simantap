@extends('layout_home')

@section('title', 'User')

@section('page-css')
	<!-- Datatable -->
	<link href="{{ asset('app-assets') }}/vendor/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
@endsection

@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>User</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">User</a></li>
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
									<button type="button" class="btn btn-rounded btn-outline-primary" onclick = "location.href='{{ route('user_tambah') }}';">Tambah Data</button>
								</h4>
							</div>
							<div class="card-body">
								<div class="table-responsive"style="overflow-x: auto;">									
									<table id="example3" class="display" style="min-width: 845px; width:100%;">
										<thead>
											<tr>
												<th>Nip Nik</th>
												<th>Nama</th>
												<th>Gelar Depan</th>
												<th>Gelar Belakang</th>
												<th>Join Table</th>
												<th>Status</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($list_user as $user)
											<tr>
												<td>{{ $user->nipnik }}</td>
												<td>{{ $user->nama }}</td>
												<td>{{ $user->gelar_depan }}</td>
												<td>{{ $user->gelar_belakang }}</td>
												<td>@if($user->join_table == '1') Tendik @elseif($user->join_table == '2') Dosen @endif</td>
												<td>@if($user->status == '1') Aktif @elseif($user->status == '0' or $user->status == '') Tidak Aktif @endif</td>
												<td>	
													<button type="button" class="btn btn-rounded btn-outline-primary" onclick = "location.href='{{ route('user_edit', ['id' => encrypt($user->iduser)] ) }}';">Edit</button>
													
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
	<!-- Datatable -->
	<script src="{{ asset('app-assets') }}/vendor/datatables/js/jquery.dataTables.min.js"></script>
	{{-- <script src="{{ asset('app-assets') }}/js/plugins-init/datatables.init.js"></script> --}}

	<script>
		$(document).ready(function() {
			var table = $('#example3').DataTable();

			$('#example tbody').on('click', 'tr', function () {
				var data = table.row( this ).data();
			});
		});
	</script>

@endsection