@extends('layout_home')

@section('title', 'List Alat Lab')

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
							<h4>Alat</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Alat Lab</a></li>
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
									
								</h4>
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="tabel_alat" class="display" style="min-width: 845px">
										<thead>
											<tr>
												<th>Kode alat</th>
												<th>Nama</th>
												<th>Kondisi</th>
                                                <th>Lokasi</th>
												<th>Pengelola alat</th>
											</tr>										
											<tr>
												<th><input type="text" placeholder="Cari Kode alat" style="width:100%"></th>
												<th><input type="text" placeholder="Cari Nama" style="width:100%"></th>
												<th><input type="text" placeholder="Cari Kondisi" style="width:100%"></th>
												<th><input type="text" placeholder="Cari Lokasi" style="width:100%"></th>
												<th><input type="text" placeholder="Cari Pengelola alat" style="width:100%"></th>
											</tr>
										</thead>
										<tbody>
											@foreach ($alat as $alat)
                                            @php
                                                if($alat->kondisi_barang == 1){
                                                    $alat->kondisi_barang = 'Baik';
                                                }elseif($alat->kondisi_barang == 2){
                                                    $alat->kondisi_barang = 'Rusak Ringan';
                                                }elseif($alat->kondisi_barang == 3){
                                                    $alat->kondisi_barang = 'Rusak Berat';
                                                }else{
                                                    $alat->kondisi_barang = 'Tidak Diketahui';
                                                }

                                            @endphp

											<tr>
												<td>{{ $alat->kode_barang_aset }}</td>
												<td>{{ $alat->nama_barang }} {{ $alat->merk_barang }}<br>{{ $alat->keterangan }}</td>
												<td>{{ $alat->kondisi_barang }}</td>
                                                <td>{{ $alat->nama_ruang }} # <br>{{ $alat->nama_gedung }} # <br> {{ $alat->nama_kampus }}</td>
												<td>{{ $alat->nm_unit_kerja }}</td>
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
	<script>
		$(document).ready(function() {
			var table = $('#tabel_alat').DataTable();

			$('#tabel_alat thead tr:eq(1) th').each(function(i) {
				var input = $('input', this);
				input.on('keyup change', function() {
					table.column(i).search(this.value).draw();
				});
			});
		});
	</script>
@endsection