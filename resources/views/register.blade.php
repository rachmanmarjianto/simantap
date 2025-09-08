<!DOCTYPE html>
<html lang="zxx">
<head>
	<title>Pendaftaran Universitas Airlangga</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="UTF-8">
	<!-- External CSS libraries -->
	<link type="text/css" rel="stylesheet" href="{{ asset('app-assets/login') }}/css/bootstrap.min.css">
	<link type="text/css" rel="stylesheet" href="{{ asset('app-assets/login') }}/fonts/font-awesome/css/font-awesome.min.css">
	<link type="text/css" rel="stylesheet" href="{{ asset('app-assets/login') }}/fonts/flaticon/font/flaticon.css">

	<!-- Favicon icon -->
	<link rel="shortcut icon" href="{{ asset('app-assets/login') }}/img/favicon.ico" type="image/x-icon" >

	<!-- Google fonts -->
	<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800%7CPoppins:400,500,700,800,900%7CRoboto:100,300,400,400i,500,700">
	<link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

	<!-- Custom Stylesheet -->
	<link type="text/css" rel="stylesheet" href="{{ asset('app-assets/login') }}/css/style.css">
	<link rel="stylesheet" type="text/css" id="style_sheet" href="{{ asset('app-assets/login') }}/css/skins/default.css">

</head>
<body id="top">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TAGCODE" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<div class="page_loader"></div>

<!-- Login 34 start -->
<div class="login-34">
	<div class="container">
		<div class="row login-box">
			<div class="col-lg-6 bg-color-15 pad-0 none-992 bg-img" style="padding-top: 70px;">
				<div class="info clearfix">
					<img src="{{ asset('app-assets/login') }}/img/logo_unair.jpeg" alt="Logo" class="img-fluid"><br><br>
					<h2>Kontak Helpdesk Pendaftaran</h2>
					<ul style = "text-align: left;">
						<li><b>WA Only :</b> 0821-3861-1156, 0822-2954-9254</li>
						<li><b>Telp :</b> (031) 5956009, (031) 5956010, (031) 5956013, (031) 5956027</i>
						<li><b>Email :</b> info@ppmb.unair.ac.id</i>
						<li><b>Web :</b> http://ppmb.unair.ac.id</i>
						<li><b>Alamat :</b> Airlangga Convention Center (ACC), Kampus C Universitas Airlangga, Mulyorejo, Surabaya 60115</i>
						<li><b>Facebook Group :</b> facebook.com/groups/ppmb.unair/</i>
						<li><b>Twitter :</b> Follow @PPMBUnair</i>
					</ul>
				</div>
			</div>
			<div class="col-lg-6 pad-0 form-info">
				<div class="form-section align-self-center">
					<h1>Universitas Airlangga</h1>
					<h3>Pendaftaran Online Calon Mahasiswa Baru<br>[Registrasi]</h3>
					<div class="clearfix"></div>
					<div class="btn-section clearfix">
						<a href="{{ route('index_login') }}" class="link-btn active btn-1 active-bg">Login</a>
						<a href="{{ route('register') }}" class="link-btn btn-2 default-bg">Register</a>
					</div>
					<div class="clearfix"></div>
					<form action="#" method="GET">
						<div class="form-group form-box">
							<label for="first_field" class="form-label">Nama</label>
							<input name="nama" class="form-control" id="first_field" placeholder="Nama Pendaftar" aria-label="Nama Pendaftar">
						</div>
						<div class="form-group form-box">
							<label for="first_field" class="form-label">Hp</label>
							<input name="hp" class="form-control" id="first_field" placeholder="Hp Pendaftar" aria-label="Hp Pendaftar">
						</div>
						<div class="form-group form-box">
							<label for="first_field" class="form-label">Email</label>
							<input name="email" type="email" class="form-control" id="first_field" placeholder="Email Address" aria-label="Email Address">
						</div>
						<div class="form-group form-box">
							<label for="second_field" class="form-label">Password</label>
							<input name="password" type="password" class="form-control" autocomplete="off" id="second_field" placeholder="Password" aria-label="Password">
						</div>
						<div class="form-group clearfix">
							<button type="submit" class="btn-md btn-theme w-100">Daftar</button>
						</div>
					</form>
					<p>Help & Support</p>
					<div class="social-list">
						<a href="#">
							<i class="fa fa-facebook"></i>
						</a>
						<a href="#">
							<i class="fa fa-twitter"></i>
						</a>
						<a href="#">
							<i class="fa fa-google"></i>
						</a>
						<a href="#">
							<i class="fa fa-linkedin"></i>
						</a>
						<a href="#">
							<i class="fa fa-pinterest"></i>
						</a>
						<a href="#">
							<i class="fa fa-youtube"></i>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Login 34 end -->

<!-- External JS libraries -->
<script src="{{ asset('app-assets/login') }}/js/jquery.min.js"></script>
<script src="{{ asset('app-assets/login') }}/js/popper.min.js"></script>
<script src="{{ asset('app-assets/login') }}/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS Script -->
</body>
</html>