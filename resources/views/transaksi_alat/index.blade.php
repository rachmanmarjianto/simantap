@extends('layout_home')
@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Transaksi Alat</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Transaksi Alat</a></li>
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
									<button type="button" class="btn btn-rounded btn-outline-primary" onclick = "location.href='{{ route('transaksi_alat_tambah') }}';">Tambah Data</button>
								</h4>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example3" class="display" style="min-width: 845px">
										<thead>
											<tr>
												<th>Alat</th>
												<th>Layanan</th>
												<th>Unit Pemakai</th>
												<th>Waktu Mulai Pakai</th>
												<th>Waktu Selesai Pakai</th>
												<th>Biaya Pakai Alat</th>
												<th>Operator Alat</th>
												<th>Action</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($list_transaksi_alat as $alat)
											<tr>
												<td>{{ $alat->alat->nama }}</td>
												<td>{{ $alat->layanan->nama }}</td>
												<td>{{ $alat->unit->nama_unit }}</td>
												<td>{{ $alat->waktu_pakai_alat_mulai }}</td>
												<td>{{ $alat->waktu_pakai_alat_selesai }}</td>
												<td>{{ $alat->biaya_pakai_alat }}</td>
												<td>{{ $alat->operator->nama }}</td>
												<td>
													<button type="button" class="btn btn-rounded btn-outline-primary" onclick = "location.href='{{ route('transaksi_alat_edit', ['id' => $alat->id] ) }}';">Edit</button>
													<button type="button" class="btn btn-rounded btn-outline-danger" onclick="if(confirm('Yakin hapus ?')){ location.href='{{ route('transaksi_alat_hapus', ['id' => $alat->id]) }}'; }">
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