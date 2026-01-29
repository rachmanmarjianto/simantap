@extends('layout_home')
@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Tanggung Jawab Ruang</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
							<li class="breadcrumb-item active"><a href="javascript:void(0)">PJ Ruang</a></li>
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

				@foreach ($ruang as $r)
				<div class="row">
					<div class="col-12">
						<div class="card">
							
							<div class="card-header">
								
							</div>
							<div class="card-body">
								<div class="table-responsive">
									<table id="example3" class="table table-bordered table-responsive-sm" style="min-width: 845px">
										<thead>
											<tr>
												<th>Ruang</th>
												<th>Gedung</th>
												<th>Kampus</th>
											</tr>
										</thead>
										<tbody>
											
											<tr>
												<td>{{ $r->nama_ruang }}</td>
												<td>{{ $r->nama_gedung }}</td>
												<td>{{ $r->nama_kampus }}</td>
											</tr>
											
												
										</tbody>
									</table>
								</div>

								@foreach($layanan_ruang[$r->idruang] as $lr)
								<h4 style="margin-top:30px">Layanan: {{ $lr['nama_layanan'] }}</h4>
								<div class="table-responsive">
									<table id="example3" class="table table-bordered table-responsive-sm" >
										<tbody>
											<tr>
												<td rowspan="<?= count($lr['operator']) ?>">Laboran / Operator</td>
												<td>
													@if(count($lr['operator'])>0)
														{{ $lr['operator'][0]['nama'] }} {{$lr['operator'][0]['gelar_depan']}} {{$lr['operator'][0]['gelar_belakang']}}
													@endif
												</td>
											</tr>
											@foreach($lr['operator'] as $index => $op)
											@if($index==0)
												@continue
											@endif
											<tr>
												<td>{{ $op['nama'] }} {{$op['gelar_depan']}} {{$op['gelar_belakang']}}</td>
											</tr>
											@endforeach
											<tr>
												<td rowspan="<?= count($aset_layanan[$lr['idlayanan']]) ?>">Alat yang digunakan didalam ruangan</td>
												<td>
													@if(count($aset_layanan[$lr['idlayanan']])>0)
														{{ $aset_layanan[$lr['idlayanan']][0]['kode_barang_aset'] }} {{ $aset_layanan[$lr['idlayanan']][0]['nama_barang'] }} {{ $aset_layanan[$lr['idlayanan']][0]['merk_barang'] }} {{ $aset_layanan[$lr['idlayanan']][0]['tahun_aset'] }}
													@endif
												</td>
											</tr>
											@foreach($aset_layanan[$lr['idlayanan']] as $index => $aset)
											@if($index==0)
												@continue
											@endif
											<tr>
												<td>({{ $aset['kode_barang_aset'] }}) {{ $aset['nama_barang'] }} {{ $aset['merk_barang'] }} {{ $aset['tahun_aset'] }}</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
				@endforeach


			</div>
		</div>
@endsection