@extends('layout_home')
@section('title', 'Penelitian')

@section('page-css')
    
@endsection

@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Penelitian</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item active"><a href="javascript:void(0)">Penelitian</a></li>
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
							<div class="card-header" style="text-align: right">
								<div class="row">
									<div class="col-12">
										<button type="button" class="btn btn-success float-right">Success</button>
									</div>
								</div>
								
							</div>
							<div class="card-body">
								<div class="table table-striped table-responsive-sm">
									<table id="example3" class="display" style="width:100%">
										<thead>
											<tr>
												<th>ID Penelitian</th>

											</tr>
										</thead>
										<tbody>
											
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

@endsection