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
									<!-- Cybercampus SSO Header - UNAIR as Primary Owner -->
									<div align="center" style="margin-bottom: 40px; display: flex; align-items: center; justify-content: center; gap: 16px; flex-wrap: wrap;">
										<img src="{{ asset('app-assets') }}/images/unair/unair.png" alt="UNAIR Logo" style="height: 80px; width: auto;">
										<div style="text-align: left;">
											<div style="font-size: 12px; font-weight: 600; color: #666; letter-spacing: 0.8px; text-transform: uppercase; margin-bottom: 2px;">Universitas Airlangga</div>
											<div style="font-size: 18px; font-weight: 800; color: #004685; letter-spacing: 0.3px;">Cybercampus SSO</div>
										</div>
									</div>

									{{-- <div align="center"><img src="{{ asset('app-assets') }}/images/logo_unair.jpeg" alt="logo" class="mb-4" width="150"></div> --}}
									<div align="center"><img src="{{ asset('app-assets') }}/images/unair/logo_simantab_panjang.jpg" alt="logo" class="mb-4" style="width: 85%; max-width: 350px;"></div>
									{{-- <h2 class="text-center mb-4">SIM Alat</h2> --}}
									<form action = "{{ route('login_masuk') }}" method="POST" id="form-login">
										@csrf
										<div class="form-group">
											<label><strong>Username</strong></label>
											<input type="username" class="form-control" name = "username">
										</div>
										<div class="form-group">
											<label><strong>Password</strong></label>
											<input type="password" class="form-control" name = "password">
										</div>
										<div class="form-group mb-3">
											<label for="captcha">CAPTCHA</label>
											<img src="{{ captcha_src(6) }}" alt="CAPTCHA" style="border: 1px solid #ccc;" id="captcha-image">
											<span style="font-size: 12px; color:red; padding-left:10px; cursor:pointer" onclick="reloadCaptcha()">klik saya jika Captcha terlalu sulit</span>
											<div class="input-wrapper" style="margin-top: 10px">
												<input class="form-control" type="text" name="captcha" id="captcha" required placeholder="Masukkan CAPTCHA">
											</div>
											
										</div>
										<div class="text-center" id="button-login">
											<button type="button" class="btn btn-primary btn-block" onclick="submitform()">Sign in</button>
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

	<script>
		function reloadCaptcha(){
            console.log('reload');
            $.ajax({
                type: 'GET',
                url: '/reload-captcha',
                success: function(data) {
                    $('#captcha-image').attr('src', data.url);
        // console.log(data);
                }
            });
        }

		function submitform(){
			var form = document.getElementById('form-login');
            if (!form.checkValidity()) {
                form.reportValidity(); // munculkan pesan required
                return; // STOP submit
            }

			$('#button-login').html('<button type="button" class="btn btn-primary btn-block" disabled>Memproses...</button>'); // ubah tombol dengan versi disabled

            
            form.submit();
		}
	</script>

</body>

</html>