<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>ARP Pemakaian Aset </title>
	<!-- Favicon icon -->
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('app-assets') }}/images/unair/favicon.ico">
	<link href="{{ asset('app-assets') }}/css/style.css" rel="stylesheet">

</head>

<body class="h-100">
	<div class="authincation h-100">
		<div class="container h-100">
			<div class="row justify-content-center h-100 align-items-center">
				<div class="col-md-6">
					<div class="authincation-content">
						<div class="row no-gutters">
							<div class="col-xl-12">
								<div class="auth-form">
									{{-- <div align="center"><img src="{{ asset('app-assets') }}/images/logo_unair.jpeg" alt="logo" class="mb-4" width="150"></div> --}}
									<div align="center"><img src="{{ asset('app-assets') }}/images/unair/logo_simantab_panjang.jpg" alt="logo" class="mb-4" width="100%"></div>
									{{-- <h2 class="text-center mb-4">SIM Alat</h2> --}}
									<form action = "{{ route('login_masuk') }}" method="POST">
										@csrf
										<div class="form-group">
											<label><strong>Username</strong></label>
											<input type="username" class="form-control" name = "username">
										</div>
										<div class="form-group">
											<label><strong>Password</strong></label>
											<input type="password" class="form-control" name = "password">
										</div>
										<div class="text-center">
											<button type="submit" class="btn btn-primary btn-block">Sign in</button>
										</div>
									</form>
								</div>
								@if(session('message'))
									<div class="alert alert-{{ session('status') }} solid alert-dismissible fade show">
										<button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="mdi mdi-close"></i></span></button>
										{{ session('message') }}
									</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<!--**********************************
		Scripts
	***********************************-->
	<!-- Required vendors -->
	<script src="{{ asset('app-assets') }}/vendor/global/global.min.js"></script>
	<script src="{{ asset('app-assets') }}/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="{{ asset('app-assets') }}/js/custom.min.js"></script>
	<script src="{{ asset('app-assets') }}/js/deznav-init.js"></script>

</body>

</html>