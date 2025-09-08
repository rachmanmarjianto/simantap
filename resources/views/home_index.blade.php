@extends('layout_home')

@section('content')
		<div class="content-body">
			<div class="container-fluid">
				<div class="row page-titles mx-0">
					<div class="col-sm-6 p-md-0">
						<div class="welcome-text">
							<h4>Home</h4>
						</div>
					</div>
					<div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
						<ol class="breadcrumb">
							<li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
						</ol>
					</div>
				</div>
				<!-- row -->


				<div class="row">
					<div class="col-12">
						<div class="card">
							<div class="card-header">
								<h4 class="card-title">
									{{ $today_quote }} 
								</h4>
							</div>
							<div class="card-body">
								<blockquote class="blockquote">
									<h3>{{ $quote }}</h3>
									<footer class="blockquote-footer">{{ $author }}</footer>
								</blockquote>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
@endsection