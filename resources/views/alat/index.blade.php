@extends('layout_home')
@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Alat</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Layanan</a></li>
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
									<button type="button" class="btn btn-rounded btn-outline-primary" onclick = "location.href='{{ route('alat_tambah') }}';">Tambah Data</button>
								</h4>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example3" class="display" style="min-width: 845px">
										<thead>
											<tr>
												<th>Kode</th>
												<th>Nama</th>
												<th>Unit</th>
												<th>Lokasi Ruangan</th>
												<th>Keterangan</th>
												<th>Aktif</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($list_alat as $alat)
											<tr>
												<td>{{ $alat->kode }}</td>
												<td>{{ $alat->nama }}</td>
												<td>{{ $alat->unit->nama_unit }}</td>
												<td>{{ $alat->lokasi_ruangan }}</td>
												<td>{{ $alat->keterangan }}</td>
												<td>{{ $alat->is_aktif }}</td>
												<td>
													<button type="button" class="btn btn-rounded btn-outline-primary" onclick = "location.href='{{ route('alat_edit', ['id' => $alat->id] ) }}';">Edit</button>
													<button type="button" class="btn btn-rounded btn-outline-danger" onclick="if(confirm('Yakin hapus ?')){ location.href='{{ route('alat_hapus', ['id' => $alat->id]) }}'; }">
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