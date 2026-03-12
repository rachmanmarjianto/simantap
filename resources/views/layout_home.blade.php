<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>@yield('title')</title>
	<!-- Favicon icon -->
	<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('app-assets') }}/images/unair/favicon.ico">
	@yield('page-css')
	<!-- Custom Stylesheet -->
	<link href="{{ asset('app-assets') }}/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
	<link href="{{ asset('app-assets') }}/css/style.css" rel="stylesheet">

	

</head>

<body>

	<!--*******************
		Preloader start
	********************-->
	<div id="preloader">
		<div class="sk-three-bounce">
			<div class="sk-child sk-bounce1"></div>
			<div class="sk-child sk-bounce2"></div>
			<div class="sk-child sk-bounce3"></div>
		</div>
	</div>
	<!--*******************
		Preloader end
	********************-->


	<!--**********************************
		Main wrapper start
	***********************************-->
	<div id="main-wrapper">

		<!--**********************************
			Nav header start
		***********************************-->
		<div class="nav-header">
			<div class="brand-logo">
				<img  src="{{ asset('app-assets') }}/images/unair/logo_simantab_panjang.png" alt="" width="100%">
				{{-- <img class="logo-abbr" src="{{ asset('app-assets') }}/images/unair/logo_simantab_panjang.jpg" alt=""> --}}
				{{-- <img class="logo-compact" src="{{ asset('app-assets') }}/images/unair/logo_simantab_panjang.jpg" alt=""> --}}
				{{-- <img class="brand-title" src="{{ asset('app-assets') }}/images/unair/logo_simantab_panjang.jpg" alt=""> --}}
				{{-- <img class="logo-compact" src="{{ asset('app-assets') }}/images/unair/favicon.ico" alt=""> --}}
				{{-- <span class="brand-title">SIM ALAT</span> --}}
			</div>

			<div class="nav-control">
				<div class="hamburger">
					<span class="line"></span><span class="line"></span><span class="line"></span>
				</div>
			</div>
		</div>
		<!--**********************************
			Nav header end
		***********************************-->

		<!--**********************************
			Header start
		***********************************-->
		<div class="header">
			<div class="header-content">
				<nav class="navbar navbar-expand">
					<div class="collapse navbar-collapse justify-content-between">
						<div class="header-left">
							
						</div>
						<ul class="navbar-nav header-right">
                            
                            <li class="nav-item dropdown header-profile">
                                <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                                    <img src="{{ asset('app-assets') }}/images/unair/simple_user_icon.png" width="20" alt="">
                                </a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{ route('ubah_password') }}" class="dropdown-item ai-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="18" height="18">
											<path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
										</svg>

                                        <span class="ml-2">Ubah Password </span>
                                    </a>
                                    <a href="{{ route('ubah_role') }}" class="dropdown-item ai-icon">
                                        <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" style="stroke-dasharray: 25, 45; stroke-dashoffset: 0;"></path><path d="M8,7A4,4 0,1,1 16,7A4,4 0,1,1 8,7" style="stroke-dasharray: 26, 46; stroke-dashoffset: 0;"></path></svg>
                                        <span class="ml-2">Ganti Role </span>
                                    </a>
                                    <a href="{{ route('logout') }}" class="dropdown-item ai-icon">
                                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" style="stroke-dasharray: 29, 49; stroke-dashoffset: 0;"></path><path d="M16,17L21,12L16,7" style="stroke-dasharray: 15, 35; stroke-dashoffset: 0;"></path><path d="M21,12L9,12" style="stroke-dasharray: 12, 32; stroke-dashoffset: 0;"></path></svg>
                                        <span class="ml-2">Logout </span>
                                    </a>
                                </div>
                            </li>
                        </ul>
					</div>
				</nav>
			</div>
		</div>
		<!--**********************************
			Header end ti-comment-alt
		***********************************-->

		<!--**********************************
			Sidebar start
		***********************************-->
		@if(in_array(session('userdata')['idrole'], [6]))
			@include('menu_mahasiswa')
		@else
    		@include('menu_kiri')
		@endif
		<!--**********************************
			Sidebar end
		***********************************-->

		<!--**********************************
			Content body start
		***********************************-->
		@yield('content')
		<!--**********************************
			Content body end
		***********************************-->


		<!--**********************************
			Footer start
		***********************************-->
		<div class="footer">
			<div class="copyright">
				<p>Coded with <span style="color:red">LOVE</span> by DSID 2025</p>
			</div>
		</div>
		<!--**********************************
			Footer end
		***********************************-->

		<!--**********************************
			 Support ticket button start
		***********************************-->

		<!--**********************************
			 Support ticket button end
		***********************************-->

		
	</div>
	<!--**********************************
		Main wrapper end
	***********************************-->

	<!--**********************************
		Scripts
	***********************************-->
	<!-- Required vendors -->
	<script src="{{ asset('app-assets') }}/vendor/global/global.min.js"></script>
	<script src="{{ asset('app-assets') }}/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="{{ asset('app-assets') }}/js/custom.min.js"></script>
	<script src="{{ asset('app-assets') }}/js/deznav-init.js"></script>

	

	<!-- Svganimation scripts -->
	<script src="{{ asset('app-assets') }}/vendor/svganimation/vivus.min.js"></script>
	<script src="{{ asset('app-assets') }}/vendor/svganimation/svg.animation.js"></script>
	@yield('javascript')
</body>

</html>