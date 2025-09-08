<!doctype html>

<html
	lang="en"
	class="light-style layout-navbar-fixed layout-menu-fixed layout-compact"
	dir="ltr"
	data-theme="theme-default"
	data-assets-path="{{ asset('app-assets/home') }}/"
	data-template="vertical-menu-template-no-customizer"
	data-style="light">
	<head>
		<meta charset="utf-8" />
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

		<title>Vertical Layouts - Forms | Vuexy - Bootstrap Admin Template</title>

		<meta name="description" content="" />

		<!-- Favicon -->
		<link rel="icon" type="image/x-icon" href="{{ asset('app-assets/home') }}/img/favicon/favicon.ico" />

		<!-- Fonts -->
		<link rel="preconnect" href="https://fonts.googleapis.com" />
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
		<link
			href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&ampdisplay=swap"
			rel="stylesheet" />

		<!-- Icons -->
		<link rel="stylesheet" href="{{ asset('app-assets/home') }}/vendor/fonts/fontawesome.css" />
		<link rel="stylesheet" href="{{ asset('app-assets/home') }}/vendor/fonts/tabler-icons.css" />
		<link rel="stylesheet" href="{{ asset('app-assets/home') }}/vendor/fonts/flag-icons.css" />

		<!-- Core CSS -->

		<link rel="stylesheet" href="{{ asset('app-assets/home') }}/vendor/css/rtl/core.css" />
		<link rel="stylesheet" href="{{ asset('app-assets/home') }}/vendor/css/rtl/theme-default.css" />

		<link rel="stylesheet" href="{{ asset('app-assets/home') }}/css/demo.css" />

		<!-- Vendors CSS -->
		<link rel="stylesheet" href="{{ asset('app-assets/home') }}/vendor/libs/node-waves/node-waves.css" />

		<link rel="stylesheet" href="{{ asset('app-assets/home') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
		<link rel="stylesheet" href="{{ asset('app-assets/home') }}/vendor/libs/typeahead-js/typeahead.css" />
		<link rel="stylesheet" href="{{ asset('app-assets/home') }}/vendor/libs/flatpickr/flatpickr.css" />
		<link rel="stylesheet" href="{{ asset('app-assets/home') }}/vendor/libs/select2/select2.css" />

		<!-- Page CSS -->

		<!-- Helpers -->
		<script src="{{ asset('app-assets/home') }}/vendor/js/helpers.js"></script>
		<!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

		<!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
		<script src="{{ asset('app-assets/home') }}/js/config.js"></script>
	</head>

	<body>
		<!-- Layout wrapper -->
		<div class="layout-wrapper layout-content-navbar">
			<div class="layout-container">
				<!-- Menu -->

				<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
					<div class="app-brand demo">
						<a href="index.html" class="app-brand-link">
							<span class="app-brand-logo demo">
								<svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path
										fill-rule="evenodd"
										clip-rule="evenodd"
										d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
										fill="#7367F0" />
									<path
										opacity="0.06"
										fill-rule="evenodd"
										clip-rule="evenodd"
										d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
										fill="#161616" />
									<path
										opacity="0.06"
										fill-rule="evenodd"
										clip-rule="evenodd"
										d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
										fill="#161616" />
									<path
										fill-rule="evenodd"
										clip-rule="evenodd"
										d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
										fill="#7367F0" />
								</svg>
							</span>
							<span class="app-brand-text demo menu-text fw-bold">Vuexy</span>
						</a>

						<a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
							<i class="ti menu-toggle-icon d-none d-xl-block align-middle"></i>
							<i class="ti ti-x d-block d-xl-none ti-md align-middle"></i>
						</a>
					</div>

					<div class="menu-inner-shadow"></div>

					<ul class="menu-inner py-1">
						<!-- Apps & Pages -->
						<li class="menu-header small">
							<span class="menu-header-text" data-i18n="Apps & Pages">Apps &amp; Pages</span>
						</li>
						<li class="menu-item">
							<a href="app-email.html" class="menu-link">
								<i class="menu-icon tf-icons ti ti-mail"></i>
								<div data-i18n="Pemilihan Penerimaan">Pemilihan Penerimaan</div>
							</a>
						</li>
						<li class="menu-item">
							<a href="app-chat.html" class="menu-link">
								<i class="menu-icon tf-icons ti ti-messages"></i>
								<div data-i18n="Pengisian Form">Pengisian Form</div>
							</a>
						</li>
						<li class="menu-item">
							<a href="app-calendar.html" class="menu-link">
								<i class="menu-icon tf-icons ti ti-calendar"></i>
								<div data-i18n="Upload Berkas">Upload Berkas</div>
							</a>
						</li>
						<li class="menu-item">
							<a href="app-kanban.html" class="menu-link">
								<i class="menu-icon tf-icons ti ti-layout-kanban"></i>
								<div data-i18n="Kode Voucher">Kode Voucher</div>
							</a>
						</li>
						<li class="menu-item">
							<a href="app-kanban.html" class="menu-link">
								<i class="menu-icon tf-icons ti ti-layout-kanban"></i>
								<div data-i18n="Cetak Kartu">Cetak Kartu</div>
							</a>
						</li>
					</ul>
				</aside>
				<!-- / Menu -->

				<!-- Layout container -->
				<div class="layout-page">
					<!-- Navbar -->

					<nav
						class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
						id="layout-navbar">
						<div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
							<a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
								<i class="ti ti-menu-2 ti-md"></i>
							</a>
						</div>

						<div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
							<!-- Breadcumb -->
							<div class="navbar-nav align-items-center" style = "padding-top: 20px;">
								<nav aria-label="breadcrumb">
										<ol class="breadcrumb">
											<li class="breadcrumb-item">
												<a href="javascript:void(0);">Home</a>
											</li>
											<li class="breadcrumb-item">
												<a href="javascript:void(0);">Library</a>
											</li>
											<li class="breadcrumb-item active">Data</li>
										</ol>
									</nav>
							</div>
							<!-- /Breadcumb -->

							<ul class="navbar-nav flex-row align-items-center ms-auto">
								<!-- Language -->
								<li class="nav-item dropdown-language dropdown">
									<a
										class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
										href="javascript:void(0);"
										data-bs-toggle="dropdown">
										<i class="ti ti-language rounded-circle ti-md"></i>
									</a>
									<ul class="dropdown-menu dropdown-menu-end">
										<li>
											<a class="dropdown-item" href="javascript:void(0);" data-language="en" data-text-direction="ltr">
												<span>English</span>
											</a>
										</li>
										<li>
											<a class="dropdown-item" href="javascript:void(0);" data-language="fr" data-text-direction="ltr">
												<span>French</span>
											</a>
										</li>
										<li>
											<a class="dropdown-item" href="javascript:void(0);" data-language="ar" data-text-direction="rtl">
												<span>Arabic</span>
											</a>
										</li>
										<li>
											<a class="dropdown-item" href="javascript:void(0);" data-language="de" data-text-direction="ltr">
												<span>German</span>
											</a>
										</li>
									</ul>
								</li>
								<!--/ Language -->

								<!-- Quick links  -->
								<li class="nav-item dropdown-shortcuts navbar-dropdown dropdown">
									<a
										class="nav-link btn btn-text-secondary btn-icon rounded-pill btn-icon dropdown-toggle hide-arrow"
										href="javascript:void(0);"
										data-bs-toggle="dropdown"
										data-bs-auto-close="outside"
										aria-expanded="false">
										<i class="ti ti-layout-grid-add ti-md"></i>
									</a>
									<div class="dropdown-menu dropdown-menu-end p-0">
										<div class="dropdown-menu-header border-bottom">
											<div class="dropdown-header d-flex align-items-center py-3">
												<h6 class="mb-0 me-auto">Shortcuts</h6>
												<a
													href="javascript:void(0)"
													class="btn btn-text-secondary rounded-pill btn-icon dropdown-shortcuts-add"
													data-bs-toggle="tooltip"
													data-bs-placement="top"
													title="Add shortcuts"
													><i class="ti ti-plus text-heading"></i
												></a>
											</div>
										</div>
										<div class="dropdown-shortcuts-list scrollable-container">
											<div class="row row-bordered overflow-visible g-0">
												<div class="dropdown-shortcuts-item col">
													<span class="dropdown-shortcuts-icon rounded-circle mb-3">
														<i class="ti ti-calendar ti-26px text-heading"></i>
													</span>
													<a href="app-calendar.html" class="stretched-link">Calendar</a>
													<small>Appointments</small>
												</div>
												<div class="dropdown-shortcuts-item col">
													<span class="dropdown-shortcuts-icon rounded-circle mb-3">
														<i class="ti ti-file-dollar ti-26px text-heading"></i>
													</span>
													<a href="app-invoice-list.html" class="stretched-link">Invoice App</a>
													<small>Manage Accounts</small>
												</div>
											</div>
											<div class="row row-bordered overflow-visible g-0">
												<div class="dropdown-shortcuts-item col">
													<span class="dropdown-shortcuts-icon rounded-circle mb-3">
														<i class="ti ti-user ti-26px text-heading"></i>
													</span>
													<a href="app-user-list.html" class="stretched-link">User App</a>
													<small>Manage Users</small>
												</div>
												<div class="dropdown-shortcuts-item col">
													<span class="dropdown-shortcuts-icon rounded-circle mb-3">
														<i class="ti ti-users ti-26px text-heading"></i>
													</span>
													<a href="app-access-roles.html" class="stretched-link">Role Management</a>
													<small>Permission</small>
												</div>
											</div>
											<div class="row row-bordered overflow-visible g-0">
												<div class="dropdown-shortcuts-item col">
													<span class="dropdown-shortcuts-icon rounded-circle mb-3">
														<i class="ti ti-device-desktop-analytics ti-26px text-heading"></i>
													</span>
													<a href="index.html" class="stretched-link">Dashboard</a>
													<small>User Dashboard</small>
												</div>
												<div class="dropdown-shortcuts-item col">
													<span class="dropdown-shortcuts-icon rounded-circle mb-3">
														<i class="ti ti-settings ti-26px text-heading"></i>
													</span>
													<a href="pages-account-settings-account.html" class="stretched-link">Setting</a>
													<small>Account Settings</small>
												</div>
											</div>
											<div class="row row-bordered overflow-visible g-0">
												<div class="dropdown-shortcuts-item col">
													<span class="dropdown-shortcuts-icon rounded-circle mb-3">
														<i class="ti ti-help ti-26px text-heading"></i>
													</span>
													<a href="pages-faq.html" class="stretched-link">FAQs</a>
													<small>FAQs & Articles</small>
												</div>
												<div class="dropdown-shortcuts-item col">
													<span class="dropdown-shortcuts-icon rounded-circle mb-3">
														<i class="ti ti-square ti-26px text-heading"></i>
													</span>
													<a href="modal-examples.html" class="stretched-link">Modals</a>
													<small>Useful Popups</small>
												</div>
											</div>
										</div>
									</div>
								</li>
								<!-- Quick links -->

								<!-- Notification -->
								<li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
									<a
										class="nav-link btn btn-text-secondary btn-icon rounded-pill dropdown-toggle hide-arrow"
										href="javascript:void(0);"
										data-bs-toggle="dropdown"
										data-bs-auto-close="outside"
										aria-expanded="false">
										<span class="position-relative">
											<i class="ti ti-bell ti-md"></i>
											<span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
										</span>
									</a>
									<ul class="dropdown-menu dropdown-menu-end p-0">
										<li class="dropdown-menu-header border-bottom">
											<div class="dropdown-header d-flex align-items-center py-3">
												<h6 class="mb-0 me-auto">Notification</h6>
												<div class="d-flex align-items-center h6 mb-0">
													<span class="badge bg-label-primary me-2">8 New</span>
													<a
														href="javascript:void(0)"
														class="btn btn-text-secondary rounded-pill btn-icon dropdown-notifications-all"
														data-bs-toggle="tooltip"
														data-bs-placement="top"
														title="Mark all as read"
														><i class="ti ti-mail-opened text-heading"></i
													></a>
												</div>
											</div>
										</li>
										<li class="dropdown-notifications-list scrollable-container">
											<ul class="list-group list-group-flush">
												<li class="list-group-item list-group-item-action dropdown-notifications-item">
													<div class="d-flex">
														<div class="flex-shrink-0 me-3">
															<div class="avatar">
																<img src="{{ asset('app-assets/home') }}/img/avatars/1.png" alt class="rounded-circle" />
															</div>
														</div>
														<div class="flex-grow-1">
															<h6 class="small mb-1">Congratulation Lettie 🎉</h6>
															<small class="mb-1 d-block text-body">Won the monthly best seller gold badge</small>
															<small class="text-muted">1h ago</small>
														</div>
														<div class="flex-shrink-0 dropdown-notifications-actions">
															<a href="javascript:void(0)" class="dropdown-notifications-read"
																><span class="badge badge-dot"></span
															></a>
															<a href="javascript:void(0)" class="dropdown-notifications-archive"
																><span class="ti ti-x"></span
															></a>
														</div>
													</div>
												</li>
												<li class="list-group-item list-group-item-action dropdown-notifications-item">
													<div class="d-flex">
														<div class="flex-shrink-0 me-3">
															<div class="avatar">
																<span class="avatar-initial rounded-circle bg-label-danger">CF</span>
															</div>
														</div>
														<div class="flex-grow-1">
															<h6 class="mb-1 small">Charles Franklin</h6>
															<small class="mb-1 d-block text-body">Accepted your connection</small>
															<small class="text-muted">12hr ago</small>
														</div>
														<div class="flex-shrink-0 dropdown-notifications-actions">
															<a href="javascript:void(0)" class="dropdown-notifications-read"
																><span class="badge badge-dot"></span
															></a>
															<a href="javascript:void(0)" class="dropdown-notifications-archive"
																><span class="ti ti-x"></span
															></a>
														</div>
													</div>
												</li>
												<li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
													<div class="d-flex">
														<div class="flex-shrink-0 me-3">
															<div class="avatar">
																<img src="{{ asset('app-assets/home') }}/img/avatars/2.png" alt class="rounded-circle" />
															</div>
														</div>
														<div class="flex-grow-1">
															<h6 class="mb-1 small">New Message ✉️</h6>
															<small class="mb-1 d-block text-body">You have new message from Natalie</small>
															<small class="text-muted">1h ago</small>
														</div>
														<div class="flex-shrink-0 dropdown-notifications-actions">
															<a href="javascript:void(0)" class="dropdown-notifications-read"
																><span class="badge badge-dot"></span
															></a>
															<a href="javascript:void(0)" class="dropdown-notifications-archive"
																><span class="ti ti-x"></span
															></a>
														</div>
													</div>
												</li>
												<li class="list-group-item list-group-item-action dropdown-notifications-item">
													<div class="d-flex">
														<div class="flex-shrink-0 me-3">
															<div class="avatar">
																<span class="avatar-initial rounded-circle bg-label-success"
																	><i class="ti ti-shopping-cart"></i
																></span>
															</div>
														</div>
														<div class="flex-grow-1">
															<h6 class="mb-1 small">Whoo! You have new order 🛒</h6>
															<small class="mb-1 d-block text-body">ACME Inc. made new order $1,154</small>
															<small class="text-muted">1 day ago</small>
														</div>
														<div class="flex-shrink-0 dropdown-notifications-actions">
															<a href="javascript:void(0)" class="dropdown-notifications-read"
																><span class="badge badge-dot"></span
															></a>
															<a href="javascript:void(0)" class="dropdown-notifications-archive"
																><span class="ti ti-x"></span
															></a>
														</div>
													</div>
												</li>
												<li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
													<div class="d-flex">
														<div class="flex-shrink-0 me-3">
															<div class="avatar">
																<img src="{{ asset('app-assets/home') }}/img/avatars/9.png" alt class="rounded-circle" />
															</div>
														</div>
														<div class="flex-grow-1">
															<h6 class="mb-1 small">Application has been approved 🚀</h6>
															<small class="mb-1 d-block text-body"
																>Your ABC project application has been approved.</small
															>
															<small class="text-muted">2 days ago</small>
														</div>
														<div class="flex-shrink-0 dropdown-notifications-actions">
															<a href="javascript:void(0)" class="dropdown-notifications-read"
																><span class="badge badge-dot"></span
															></a>
															<a href="javascript:void(0)" class="dropdown-notifications-archive"
																><span class="ti ti-x"></span
															></a>
														</div>
													</div>
												</li>
												<li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
													<div class="d-flex">
														<div class="flex-shrink-0 me-3">
															<div class="avatar">
																<span class="avatar-initial rounded-circle bg-label-success"
																	><i class="ti ti-chart-pie"></i
																></span>
															</div>
														</div>
														<div class="flex-grow-1">
															<h6 class="mb-1 small">Monthly report is generated</h6>
															<small class="mb-1 d-block text-body">July monthly financial report is generated </small>
															<small class="text-muted">3 days ago</small>
														</div>
														<div class="flex-shrink-0 dropdown-notifications-actions">
															<a href="javascript:void(0)" class="dropdown-notifications-read"
																><span class="badge badge-dot"></span
															></a>
															<a href="javascript:void(0)" class="dropdown-notifications-archive"
																><span class="ti ti-x"></span
															></a>
														</div>
													</div>
												</li>
												<li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
													<div class="d-flex">
														<div class="flex-shrink-0 me-3">
															<div class="avatar">
																<img src="{{ asset('app-assets/home') }}/img/avatars/5.png" alt class="rounded-circle" />
															</div>
														</div>
														<div class="flex-grow-1">
															<h6 class="mb-1 small">Send connection request</h6>
															<small class="mb-1 d-block text-body">Peter sent you connection request</small>
															<small class="text-muted">4 days ago</small>
														</div>
														<div class="flex-shrink-0 dropdown-notifications-actions">
															<a href="javascript:void(0)" class="dropdown-notifications-read"
																><span class="badge badge-dot"></span
															></a>
															<a href="javascript:void(0)" class="dropdown-notifications-archive"
																><span class="ti ti-x"></span
															></a>
														</div>
													</div>
												</li>
												<li class="list-group-item list-group-item-action dropdown-notifications-item">
													<div class="d-flex">
														<div class="flex-shrink-0 me-3">
															<div class="avatar">
																<img src="{{ asset('app-assets/home') }}/img/avatars/6.png" alt class="rounded-circle" />
															</div>
														</div>
														<div class="flex-grow-1">
															<h6 class="mb-1 small">New message from Jane</h6>
															<small class="mb-1 d-block text-body">Your have new message from Jane</small>
															<small class="text-muted">5 days ago</small>
														</div>
														<div class="flex-shrink-0 dropdown-notifications-actions">
															<a href="javascript:void(0)" class="dropdown-notifications-read"
																><span class="badge badge-dot"></span
															></a>
															<a href="javascript:void(0)" class="dropdown-notifications-archive"
																><span class="ti ti-x"></span
															></a>
														</div>
													</div>
												</li>
												<li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
													<div class="d-flex">
														<div class="flex-shrink-0 me-3">
															<div class="avatar">
																<span class="avatar-initial rounded-circle bg-label-warning"
																	><i class="ti ti-alert-triangle"></i
																></span>
															</div>
														</div>
														<div class="flex-grow-1">
															<h6 class="mb-1 small">CPU is running high</h6>
															<small class="mb-1 d-block text-body"
																>CPU Utilization Percent is currently at 88.63%,</small
															>
															<small class="text-muted">5 days ago</small>
														</div>
														<div class="flex-shrink-0 dropdown-notifications-actions">
															<a href="javascript:void(0)" class="dropdown-notifications-read"
																><span class="badge badge-dot"></span
															></a>
															<a href="javascript:void(0)" class="dropdown-notifications-archive"
																><span class="ti ti-x"></span
															></a>
														</div>
													</div>
												</li>
											</ul>
										</li>
										<li class="border-top">
											<div class="d-grid p-4">
												<a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
													<small class="align-middle">View all notifications</small>
												</a>
											</div>
										</li>
									</ul>
								</li>
								<!--/ Notification -->

								<!-- User -->
								<li class="nav-item navbar-dropdown dropdown-user dropdown">
									<a
										class="nav-link dropdown-toggle hide-arrow p-0"
										href="javascript:void(0);"
										data-bs-toggle="dropdown">
										<div class="avatar avatar-online">
											<img src="{{ asset('app-assets/home') }}/img/avatars/1.png" alt class="rounded-circle" />
										</div>
									</a>
									<ul class="dropdown-menu dropdown-menu-end">
										<li>
											<a class="dropdown-item mt-0" href="pages-account-settings-account.html">
												<div class="d-flex align-items-center">
													<div class="flex-shrink-0 me-2">
														<div class="avatar avatar-online">
															<img src="{{ asset('app-assets/home') }}/img/avatars/1.png" alt class="rounded-circle" />
														</div>
													</div>
													<div class="flex-grow-1">
														<h6 class="mb-0">John Doe</h6>
														<small class="text-muted">Admin</small>
													</div>
												</div>
											</a>
										</li>
										<li>
											<div class="dropdown-divider my-1 mx-n2"></div>
										</li>
										<li>
											<a class="dropdown-item" href="pages-profile-user.html">
												<i class="ti ti-user me-3 ti-md"></i><span class="align-middle">My Profile</span>
											</a>
										</li>
										<li>
											<a class="dropdown-item" href="pages-account-settings-account.html">
												<i class="ti ti-settings me-3 ti-md"></i><span class="align-middle">Settings</span>
											</a>
										</li>
										<li>
											<a class="dropdown-item" href="pages-account-settings-billing.html">
												<span class="d-flex align-items-center align-middle">
													<i class="flex-shrink-0 ti ti-file-dollar me-3 ti-md"></i
													><span class="flex-grow-1 align-middle">Billing</span>
													<span class="flex-shrink-0 badge bg-danger d-flex align-items-center justify-content-center"
														>4</span
													>
												</span>
											</a>
										</li>
										<li>
											<div class="dropdown-divider my-1 mx-n2"></div>
										</li>
										<li>
											<a class="dropdown-item" href="pages-pricing.html">
												<i class="ti ti-currency-dollar me-3 ti-md"></i><span class="align-middle">Pricing</span>
											</a>
										</li>
										<li>
											<a class="dropdown-item" href="pages-faq.html">
												<i class="ti ti-question-mark me-3 ti-md"></i><span class="align-middle">FAQ</span>
											</a>
										</li>
										<li>
											<div class="d-grid px-2 pt-2 pb-1">
												<a class="btn btn-sm btn-danger d-flex" href="{{ route('index_login') }}" target="_self">
													<small class="align-middle">Logout</small>
													<i class="ti ti-logout ms-2 ti-14px"></i>
												</a>
											</div>
										</li>
									</ul>
								</li>
								<!--/ User -->
							</ul>
						</div>

						<!-- Search Small Screens -->
						<div class="navbar-search-wrapper search-input-wrapper d-none">
							<input
								type="text"
								class="form-control search-input container-xxl border-0"
								placeholder="Search..."
								aria-label="Search..." />
							<i class="ti ti-x search-toggler cursor-pointer"></i>
						</div>
					</nav>

					<!-- / Navbar -->

					<!-- Content wrapper -->
					<div class="content-wrapper">
						<!-- Content -->

						<div class="container-xxl flex-grow-1 container-p-y">
							<!-- Basic Layout -->
							<div class="row">
								<div class="col-xl mb-6">
									<div class="card">
										<div class="card-header d-flex justify-content-between align-items-center">
											<h5 class="mb-0">Basic Layout</h5>
											<small class="text-muted float-end">Default label</small>
										</div>
										<div class="card-body">
											<form>
												<div class="mb-6">
													<label class="form-label" for="basic-default-fullname">Full Name</label>
													<input type="text" class="form-control" id="basic-default-fullname" placeholder="John Doe" />
												</div>
												<div class="mb-6">
													<label class="form-label" for="basic-default-company">Company</label>
													<input type="text" class="form-control" id="basic-default-company" placeholder="ACME Inc." />
												</div>
												<div class="mb-6">
													<label class="form-label" for="basic-default-email">Email</label>
													<div class="input-group input-group-merge">
														<input
															type="text"
															id="basic-default-email"
															class="form-control"
															placeholder="john.doe"
															aria-label="john.doe"
															aria-describedby="basic-default-email2" />
														<span class="input-group-text" id="basic-default-email2">@example.com</span>
													</div>
													<div class="form-text">You can use letters, numbers & periods</div>
												</div>
												<div class="mb-6">
													<label class="form-label" for="basic-default-phone">Phone No</label>
													<input
														type="text"
														id="basic-default-phone"
														class="form-control phone-mask"
														placeholder="658 799 8941" />
												</div>
												<div class="mb-6">
													<label class="form-label" for="basic-default-message">Message</label>
													<textarea
														id="basic-default-message"
														class="form-control"
														placeholder="Hi, Do you have a moment to talk Joe?"></textarea>
												</div>
												<button type="submit" class="btn btn-primary">Send</button>
											</form>
										</div>
									</div>
								</div>
								<div class="col-xl mb-6">
									<div class="card">
										<div class="card-header d-flex justify-content-between align-items-center">
											<h5 class="mb-0">Basic with Icons</h5>
											<small class="text-muted float-end">Merged input group</small>
										</div>
										<div class="card-body">
											<form>
												<div class="mb-6">
													<label class="form-label" for="basic-icon-default-fullname">Full Name</label>
													<div class="input-group input-group-merge">
														<span id="basic-icon-default-fullname2" class="input-group-text"
															><i class="ti ti-user"></i
														></span>
														<input
															type="text"
															class="form-control"
															id="basic-icon-default-fullname"
															placeholder="John Doe"
															aria-label="John Doe"
															aria-describedby="basic-icon-default-fullname2" />
													</div>
												</div>
												<div class="mb-6">
													<label class="form-label" for="basic-icon-default-company">Company</label>
													<div class="input-group input-group-merge">
														<span id="basic-icon-default-company2" class="input-group-text"
															><i class="ti ti-building"></i
														></span>
														<input
															type="text"
															id="basic-icon-default-company"
															class="form-control"
															placeholder="ACME Inc."
															aria-label="ACME Inc."
															aria-describedby="basic-icon-default-company2" />
													</div>
												</div>
												<div class="mb-6">
													<label class="form-label" for="basic-icon-default-email">Email</label>
													<div class="input-group input-group-merge">
														<span class="input-group-text"><i class="ti ti-mail"></i></span>
														<input
															type="text"
															id="basic-icon-default-email"
															class="form-control"
															placeholder="john.doe"
															aria-label="john.doe"
															aria-describedby="basic-icon-default-email2" />
														<span id="basic-icon-default-email2" class="input-group-text">@example.com</span>
													</div>
													<div class="form-text">You can use letters, numbers & periods</div>
												</div>
												<div class="mb-6">
													<label class="form-label" for="basic-icon-default-phone">Phone No</label>
													<div class="input-group input-group-merge">
														<span id="basic-icon-default-phone2" class="input-group-text"
															><i class="ti ti-phone"></i
														></span>
														<input
															type="text"
															id="basic-icon-default-phone"
															class="form-control phone-mask"
															placeholder="658 799 8941"
															aria-label="658 799 8941"
															aria-describedby="basic-icon-default-phone2" />
													</div>
												</div>
												<div class="mb-6">
													<label class="form-label" for="basic-icon-default-message">Message</label>
													<div class="input-group input-group-merge">
														<span id="basic-icon-default-message2" class="input-group-text"
															><i class="ti ti-message-dots"></i
														></span>
														<textarea
															id="basic-icon-default-message"
															class="form-control"
															placeholder="Hi, Do you have a moment to talk Joe?"
															aria-label="Hi, Do you have a moment to talk Joe?"
															aria-describedby="basic-icon-default-message2"></textarea>
													</div>
												</div>
												<button type="submit" class="btn btn-primary">Send</button>
											</form>
										</div>
									</div>
								</div>
							</div>

							<!-- Multi Column with Form Separator -->
							<div class="card mb-6">
								<h5 class="card-header">Multi Column with Form Separator</h5>
								<form class="card-body">
									<h6>1. Account Details</h6>
									<div class="row g-6">
										<div class="col-md-6">
											<label class="form-label" for="multicol-username">Username</label>
											<input type="text" id="multicol-username" class="form-control" placeholder="john.doe" />
										</div>
										<div class="col-md-6">
											<label class="form-label" for="multicol-email">Email</label>
											<div class="input-group input-group-merge">
												<input
													type="text"
													id="multicol-email"
													class="form-control"
													placeholder="john.doe"
													aria-label="john.doe"
													aria-describedby="multicol-email2" />
												<span class="input-group-text" id="multicol-email2">@example.com</span>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-password-toggle">
												<label class="form-label" for="multicol-password">Password</label>
												<div class="input-group input-group-merge">
													<input
														type="password"
														id="multicol-password"
														class="form-control"
														placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
														aria-describedby="multicol-password2" />
													<span class="input-group-text cursor-pointer" id="multicol-password2"
														><i class="ti ti-eye-off"></i
													></span>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-password-toggle">
												<label class="form-label" for="multicol-confirm-password">Confirm Password</label>
												<div class="input-group input-group-merge">
													<input
														type="password"
														id="multicol-confirm-password"
														class="form-control"
														placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
														aria-describedby="multicol-confirm-password2" />
													<span class="input-group-text cursor-pointer" id="multicol-confirm-password2"
														><i class="ti ti-eye-off"></i
													></span>
												</div>
											</div>
										</div>
									</div>
									<hr class="my-6 mx-n4" />
									<h6>2. Personal Info</h6>
									<div class="row g-6">
										<div class="col-md-6">
											<label class="form-label" for="multicol-first-name">First Name</label>
											<input type="text" id="multicol-first-name" class="form-control" placeholder="John" />
										</div>
										<div class="col-md-6">
											<label class="form-label" for="multicol-last-name">Last Name</label>
											<input type="text" id="multicol-last-name" class="form-control" placeholder="Doe" />
										</div>
										<div class="col-md-6">
											<label class="form-label" for="multicol-country">Country</label>
											<select id="multicol-country" class="select2 form-select" data-allow-clear="true">
												<option value="">Select</option>
												<option value="Australia">Australia</option>
												<option value="Bangladesh">Bangladesh</option>
												<option value="Belarus">Belarus</option>
												<option value="Brazil">Brazil</option>
												<option value="Canada">Canada</option>
												<option value="China">China</option>
												<option value="France">France</option>
												<option value="Germany">Germany</option>
												<option value="India">India</option>
												<option value="Indonesia">Indonesia</option>
												<option value="Israel">Israel</option>
												<option value="Italy">Italy</option>
												<option value="Japan">Japan</option>
												<option value="Korea">Korea, Republic of</option>
												<option value="Mexico">Mexico</option>
												<option value="Philippines">Philippines</option>
												<option value="Russia">Russian Federation</option>
												<option value="South Africa">South Africa</option>
												<option value="Thailand">Thailand</option>
												<option value="Turkey">Turkey</option>
												<option value="Ukraine">Ukraine</option>
												<option value="United Arab Emirates">United Arab Emirates</option>
												<option value="United Kingdom">United Kingdom</option>
												<option value="United States">United States</option>
											</select>
										</div>
										<div class="col-md-6 select2-primary">
											<label class="form-label" for="multicol-language">Language</label>
											<select id="multicol-language" class="select2 form-select" multiple>
												<option value="en" selected>English</option>
												<option value="fr" selected>French</option>
												<option value="de">German</option>
												<option value="pt">Portuguese</option>
											</select>
										</div>
										<div class="col-md-6">
											<label class="form-label" for="multicol-birthdate">Birth Date</label>
											<input
												type="text"
												id="multicol-birthdate"
												class="form-control dob-picker"
												placeholder="YYYY-MM-DD" />
										</div>
										<div class="col-md-6">
											<label class="form-label" for="multicol-phone">Phone No</label>
											<input
												type="text"
												id="multicol-phone"
												class="form-control phone-mask"
												placeholder="658 799 8941"
												aria-label="658 799 8941" />
										</div>
									</div>
									<div class="pt-6">
										<button type="submit" class="btn btn-primary me-4">Submit</button>
										<button type="reset" class="btn btn-label-secondary">Cancel</button>
									</div>
								</form>
							</div>

							<!-- Collapsible Section -->
							<div class="row my-6">
								<div class="col">
									<h6>Collapsible Section</h6>
									<div class="accordion" id="collapsibleSection">
										<div class="card accordion-item active">
											<h2 class="accordion-header" id="headingDeliveryAddress">
												<button
													type="button"
													class="accordion-button"
													data-bs-toggle="collapse"
													data-bs-target="#collapseDeliveryAddress"
													aria-expanded="true"
													aria-controls="collapseDeliveryAddress">
													Delivery Address
												</button>
											</h2>
											<div
												id="collapseDeliveryAddress"
												class="accordion-collapse collapse show"
												data-bs-parent="#collapsibleSection">
												<div class="accordion-body">
													<div class="row g-6">
														<div class="col-md-6">
															<label class="form-label" for="collapsible-fullname">Full Name</label>
															<input
																type="text"
																id="collapsible-fullname"
																class="form-control"
																placeholder="John Doe" />
														</div>
														<div class="col-md-6">
															<label class="form-label" for="collapsible-phone">Phone No</label>
															<input
																type="text"
																id="collapsible-phone"
																class="form-control phone-mask"
																placeholder="658 799 8941"
																aria-label="658 799 8941" />
														</div>
														<div class="col-12">
															<label class="form-label" for="collapsible-address">Address</label>
															<textarea
																name="collapsible-address"
																class="form-control"
																id="collapsible-address"
																rows="2"
																placeholder="1456, Mall Road"></textarea>
														</div>
														<div class="col-md-6">
															<label class="form-label" for="collapsible-pincode">Pincode</label>
															<input type="text" id="collapsible-pincode" class="form-control" placeholder="658468" />
														</div>
														<div class="col-md-6">
															<label class="form-label" for="collapsible-landmark">Landmark</label>
															<input
																type="text"
																id="collapsible-landmark"
																class="form-control"
																placeholder="Nr. Wall Street" />
														</div>
														<div class="col-md-6">
															<label class="form-label" for="collapsible-city">City</label>
															<input type="text" id="collapsible-city" class="form-control" placeholder="Jackson" />
														</div>
														<div class="col-md-6">
															<label class="form-label" for="collapsible-state">State</label>
															<select id="collapsible-state" class="select2 form-select" data-allow-clear="true">
																<option value="">Select</option>
																<option value="AL">Alabama</option>
																<option value="AK">Alaska</option>
																<option value="AZ">Arizona</option>
																<option value="AR">Arkansas</option>
																<option value="CA">California</option>
																<option value="CO">Colorado</option>
																<option value="CT">Connecticut</option>
																<option value="DE">Delaware</option>
																<option value="DC">District Of Columbia</option>
																<option value="FL">Florida</option>
																<option value="GA">Georgia</option>
																<option value="HI">Hawaii</option>
																<option value="ID">Idaho</option>
																<option value="IL">Illinois</option>
																<option value="IN">Indiana</option>
																<option value="IA">Iowa</option>
																<option value="KS">Kansas</option>
																<option value="KY">Kentucky</option>
																<option value="LA">Louisiana</option>
																<option value="ME">Maine</option>
																<option value="MD">Maryland</option>
																<option value="MA">Massachusetts</option>
																<option value="MI">Michigan</option>
																<option value="MN">Minnesota</option>
																<option value="MS">Mississippi</option>
																<option value="MO">Missouri</option>
																<option value="MT">Montana</option>
																<option value="NE">Nebraska</option>
																<option value="NV">Nevada</option>
																<option value="NH">New Hampshire</option>
																<option value="NJ">New Jersey</option>
																<option value="NM">New Mexico</option>
																<option value="NY">New York</option>
																<option value="NC">North Carolina</option>
																<option value="ND">North Dakota</option>
																<option value="OH">Ohio</option>
																<option value="OK">Oklahoma</option>
																<option value="OR">Oregon</option>
																<option value="PA">Pennsylvania</option>
																<option value="RI">Rhode Island</option>
																<option value="SC">South Carolina</option>
																<option value="SD">South Dakota</option>
																<option value="TN">Tennessee</option>
																<option value="TX">Texas</option>
																<option value="UT">Utah</option>
																<option value="VT">Vermont</option>
																<option value="VA">Virginia</option>
																<option value="WA">Washington</option>
																<option value="WV">West Virginia</option>
																<option value="WI">Wisconsin</option>
																<option value="WY">Wyoming</option>
															</select>
														</div>

														<label class="form-check-label">Address Type</label>
														<div class="col mt-2">
															<div class="form-check form-check-inline">
																<input
																	name="collapsible-address-type"
																	class="form-check-input"
																	type="radio"
																	value=""
																	id="collapsible-address-type-home"
																	checked="" />
																<label class="form-check-label" for="collapsible-address-type-home"
																	>Home (All day delivery)</label
																>
															</div>
															<div class="form-check form-check-inline">
																<input
																	name="collapsible-address-type"
																	class="form-check-input"
																	type="radio"
																	value=""
																	id="collapsible-address-type-office" />
																<label class="form-check-label" for="collapsible-address-type-office">
																	Office (Delivery between 10 AM - 5 PM)
																</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card accordion-item">
											<h2 class="accordion-header" id="headingDeliveryOptions">
												<button
													type="button"
													class="accordion-button collapsed"
													data-bs-toggle="collapse"
													data-bs-target="#collapseDeliveryOptions"
													aria-expanded="false"
													aria-controls="collapseDeliveryOptions">
													Delivery Options
												</button>
											</h2>
											<div
												id="collapseDeliveryOptions"
												class="accordion-collapse collapse"
												aria-labelledby="headingDeliveryOptions"
												data-bs-parent="#collapsibleSection">
												<div class="accordion-body">
													<div class="row">
														<div class="col-md mb-md-0 mb-2">
															<div class="form-check custom-option custom-option-basic">
																<label class="form-check-label custom-option-content" for="radioStandard">
																	<input
																		name="CustomRadioDelivery"
																		class="form-check-input"
																		type="radio"
																		value=""
																		id="radioStandard"
																		checked />
																	<span class="custom-option-header">
																		<span class="h6 mb-0">Standard 3-5 Days</span>
																		<span class="text-muted">Free</span>
																	</span>
																	<span class="custom-option-body">
																		<small> Friday, 15 Nov - Monday, 18 Nov </small>
																	</span>
																</label>
															</div>
														</div>
														<div class="col-md mb-md-0 mb-2">
															<div class="form-check custom-option custom-option-basic">
																<label class="form-check-label custom-option-content" for="radioExpress">
																	<input
																		name="CustomRadioDelivery"
																		class="form-check-input"
																		type="radio"
																		value=""
																		id="radioExpress" />
																	<span class="custom-option-header">
																		<span class="h6 mb-0">Express</span>
																		<span class="text-muted">$5.00</span>
																	</span>
																	<span class="custom-option-body">
																		<small> Friday, 15 Nov - Sunday, 17 Nov </small>
																	</span>
																</label>
															</div>
														</div>
														<div class="col-md">
															<div class="form-check custom-option custom-option-basic">
																<label class="form-check-label custom-option-content" for="radioOvernight">
																	<input
																		name="CustomRadioDelivery"
																		class="form-check-input"
																		type="radio"
																		value=""
																		id="radioOvernight" />
																	<span class="custom-option-header">
																		<span class="h6 mb-0">Overnight</span>
																		<span class="text-muted">$10.00</span>
																	</span>
																	<span class="custom-option-body">
																		<small>Friday, 15 Nov - Saturday, 16 Nov</small>
																	</span>
																</label>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card accordion-item">
											<h2 class="accordion-header" id="headingPaymentMethod">
												<button
													type="button"
													class="accordion-button collapsed"
													data-bs-toggle="collapse"
													data-bs-target="#collapsePaymentMethod"
													aria-expanded="false"
													aria-controls="collapsePaymentMethod">
													Payment Method
												</button>
											</h2>
											<div
												id="collapsePaymentMethod"
												class="accordion-collapse collapse"
												aria-labelledby="headingPaymentMethod"
												data-bs-parent="#collapsibleSection">
												<form>
													<div class="accordion-body">
														<div class="mb-6">
															<div class="form-check form-check-inline">
																<input
																	name="collapsible-payment"
																	class="form-check-input form-check-input-payment"
																	type="radio"
																	value="credit-card"
																	id="collapsible-payment-cc"
																	checked="" />
																<label class="form-check-label" for="collapsible-payment-cc">
																	Credit/Debit/ATM Card <i class="ti ti-credit-card"></i>
																</label>
															</div>
															<div class="form-check form-check-inline">
																<input
																	name="collapsible-payment"
																	class="form-check-input form-check-input-payment"
																	type="radio"
																	value="cash"
																	id="collapsible-payment-cash" />
																<label class="form-check-label" for="collapsible-payment-cash">
																	Cash On Delivery
																	<i
																		class="ti ti-help"
																		data-bs-toggle="tooltip"
																		data-bs-placement="top"
																		title="You can pay once you receive the product."></i>
																</label>
															</div>
														</div>
														<div id="form-credit-card" class="row">
															<div class="col-12 col-md-8 col-xl-6">
																<div class="mb-6">
																	<label class="form-label w-100" for="creditCardMask">Card Number</label>
																	<div class="input-group input-group-merge">
																		<input
																			type="text"
																			id="creditCardMask"
																			name="creditCardMask"
																			class="form-control credit-card-mask"
																			placeholder="1356 3215 6548 7898"
																			aria-describedby="creditCardMask2" />
																		<span class="input-group-text cursor-pointer p-1" id="creditCardMask2"
																			><span class="card-type"></span
																		></span>
																	</div>
																</div>
																<div class="row">
																	<div class="col-12 col-md-6">
																		<div class="mb-6">
																			<label class="form-label" for="collapsible-payment-name">Name</label>
																			<input
																				type="text"
																				id="collapsible-payment-name"
																				class="form-control"
																				placeholder="John Doe" />
																		</div>
																	</div>
																	<div class="col-6 col-md-3">
																		<div class="mb-6">
																			<label class="form-label" for="collapsible-payment-expiry-date">Exp. Date</label>
																			<input
																				type="text"
																				id="collapsible-payment-expiry-date"
																				class="form-control expiry-date-mask"
																				placeholder="MM/YY" />
																		</div>
																	</div>
																	<div class="col-6 col-md-3">
																		<div class="mb-6">
																			<label class="form-label" for="collapsible-payment-cvv">CVV Code</label>
																			<div class="input-group input-group-merge">
																				<input
																					type="text"
																					id="collapsible-payment-cvv"
																					class="form-control cvv-code-mask"
																					maxlength="3"
																					placeholder="654" />
																				<span class="input-group-text cursor-pointer" id="collapsible-payment-cvv2"
																					><i
																						class="ti ti-help text-muted"
																						data-bs-toggle="tooltip"
																						data-bs-placement="top"
																						title="Card Verification Value"></i
																				></span>
																			</div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="mt-1">
															<button type="submit" class="btn btn-primary me-4">Submit</button>
															<button type="reset" class="btn btn-label-secondary">Cancel</button>
														</div>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Form with Tabs -->
							<div class="row">
								<div class="col">
									<h6 class="mt-6">Form with Tabs</h6>
									<div class="card mb-6">
										<div class="card-header px-0 pt-0">
											<div class="nav-align-top">
												<ul class="nav nav-tabs" role="tablist">
													<li class="nav-item">
														<button
															type="button"
															class="nav-link active"
															data-bs-toggle="tab"
															data-bs-target="#form-tabs-personal"
															aria-controls="form-tabs-personal"
															role="tab"
															aria-selected="true">
															<span class="ti ti-user ti-lg d-sm-none"></span
															><span class="d-none d-sm-block">Personal Info</span>
														</button>
													</li>
													<li class="nav-item">
														<button
															type="button"
															class="nav-link"
															data-bs-toggle="tab"
															data-bs-target="#form-tabs-account"
															aria-controls="form-tabs-account"
															role="tab"
															aria-selected="false">
															<span class="ti ti-user-cog ti-lg d-sm-none"></span
															><span class="d-none d-sm-block">Account Details</span>
														</button>
													</li>
													<li class="nav-item">
														<button
															type="button"
															class="nav-link"
															data-bs-toggle="tab"
															data-bs-target="#form-tabs-social"
															aria-controls="form-tabs-social"
															role="tab"
															aria-selected="false">
															<span class="ti ti-link ti-lg d-sm-none"></span
															><span class="d-none d-sm-block">Social Links</span>
														</button>
													</li>
												</ul>
											</div>
										</div>

										<div class="card-body">
											<div class="tab-content p-0">
												<div class="tab-pane fade active show" id="form-tabs-personal" role="tabpanel">
													<form>
														<div class="row g-6">
															<div class="col-md-6">
																<label class="form-label" for="formtabs-first-name">First Name</label>
																<input type="text" id="formtabs-first-name" class="form-control" placeholder="John" />
															</div>
															<div class="col-md-6">
																<label class="form-label" for="formtabs-last-name">Last Name</label>
																<input type="text" id="formtabs-last-name" class="form-control" placeholder="Doe" />
															</div>
															<div class="col-md-6">
																<label class="form-label" for="formtabs-country">Country</label>
																<select id="formtabs-country" class="select2 form-select" data-allow-clear="true">
																	<option value="">Select</option>
																	<option value="Australia">Australia</option>
																	<option value="Bangladesh">Bangladesh</option>
																	<option value="Belarus">Belarus</option>
																	<option value="Brazil">Brazil</option>
																	<option value="Canada">Canada</option>
																	<option value="China">China</option>
																	<option value="France">France</option>
																	<option value="Germany">Germany</option>
																	<option value="India">India</option>
																	<option value="Indonesia">Indonesia</option>
																	<option value="Israel">Israel</option>
																	<option value="Italy">Italy</option>
																	<option value="Japan">Japan</option>
																	<option value="Korea">Korea, Republic of</option>
																	<option value="Mexico">Mexico</option>
																	<option value="Philippines">Philippines</option>
																	<option value="Russia">Russian Federation</option>
																	<option value="South Africa">South Africa</option>
																	<option value="Thailand">Thailand</option>
																	<option value="Turkey">Turkey</option>
																	<option value="Ukraine">Ukraine</option>
																	<option value="United Arab Emirates">United Arab Emirates</option>
																	<option value="United Kingdom">United Kingdom</option>
																	<option value="United States">United States</option>
																</select>
															</div>
															<div class="col-md-6 select2-primary">
																<label class="form-label" for="formtabs-language">Language</label>
																<select id="formtabs-language" class="select2 form-select" multiple>
																	<option value="en" selected>English</option>
																	<option value="fr" selected>French</option>
																	<option value="de">German</option>
																	<option value="pt">Portuguese</option>
																</select>
															</div>
															<div class="col-md-6">
																<label class="form-label" for="formtabs-birthdate">Birth Date</label>
																<input
																	type="text"
																	id="formtabs-birthdate"
																	class="form-control dob-picker"
																	placeholder="YYYY-MM-DD" />
															</div>
															<div class="col-md-6">
																<label class="form-label" for="formtabs-phone">Phone No</label>
																<input
																	type="text"
																	id="formtabs-phone"
																	class="form-control phone-mask"
																	placeholder="658 799 8941"
																	aria-label="658 799 8941" />
															</div>
														</div>
														<div class="pt-6">
															<button type="submit" class="btn btn-primary me-4">Submit</button>
															<button type="reset" class="btn btn-label-secondary">Cancel</button>
														</div>
													</form>
												</div>
												<div class="tab-pane fade" id="form-tabs-account" role="tabpanel">
													<form>
														<div class="row g-6">
															<div class="col-md-6">
																<label class="form-label" for="formtabs-username">Username</label>
																<input type="text" id="formtabs-username" class="form-control" placeholder="john.doe" />
															</div>
															<div class="col-md-6">
																<label class="form-label" for="formtabs-email">Email</label>
																<div class="input-group input-group-merge">
																	<input
																		type="text"
																		id="formtabs-email"
																		class="form-control"
																		placeholder="john.doe"
																		aria-label="john.doe"
																		aria-describedby="formtabs-email2" />
																	<span class="input-group-text" id="formtabs-email2">@example.com</span>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-password-toggle">
																	<label class="form-label" for="formtabs-password">Password</label>
																	<div class="input-group input-group-merge">
																		<input
																			type="password"
																			id="formtabs-password"
																			class="form-control"
																			placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
																			aria-describedby="formtabs-password2" />
																		<span class="input-group-text cursor-pointer" id="formtabs-password2"
																			><i class="ti ti-eye-off"></i
																		></span>
																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="form-password-toggle">
																	<label class="form-label" for="formtabs-confirm-password">Confirm Password</label>
																	<div class="input-group input-group-merge">
																		<input
																			type="password"
																			id="formtabs-confirm-password"
																			class="form-control"
																			placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
																			aria-describedby="formtabs-confirm-password2" />
																		<span class="input-group-text cursor-pointer" id="formtabs-confirm-password2"
																			><i class="ti ti-eye-off"></i
																		></span>
																	</div>
																</div>
															</div>
														</div>
														<div class="pt-6">
															<button type="submit" class="btn btn-primary me-4">Submit</button>
															<button type="reset" class="btn btn-label-secondary">Cancel</button>
														</div>
													</form>
												</div>
												<div class="tab-pane fade" id="form-tabs-social" role="tabpanel">
													<form>
														<div class="row g-6">
															<div class="col-md-6">
																<label class="form-label" for="formtabs-twitter">Twitter</label>
																<input
																	type="text"
																	id="formtabs-twitter"
																	class="form-control"
																	placeholder="https://twitter.com/abc" />
															</div>
															<div class="col-md-6">
																<label class="form-label" for="formtabs-facebook">Facebook</label>
																<input
																	type="text"
																	id="formtabs-facebook"
																	class="form-control"
																	placeholder="https://facebook.com/abc" />
															</div>
															<div class="col-md-6">
																<label class="form-label" for="formtabs-google">Google+</label>
																<input
																	type="text"
																	id="formtabs-google"
																	class="form-control"
																	placeholder="https://plus.google.com/abc" />
															</div>
															<div class="col-md-6">
																<label class="form-label" for="formtabs-linkedin">Linkedin</label>
																<input
																	type="text"
																	id="formtabs-linkedin"
																	class="form-control"
																	placeholder="https://linkedin.com/abc" />
															</div>
															<div class="col-md-6">
																<label class="form-label" for="formtabs-instagram">Instagram</label>
																<input
																	type="text"
																	id="formtabs-instagram"
																	class="form-control"
																	placeholder="https://instagram.com/abc" />
															</div>
															<div class="col-md-6">
																<label class="form-label" for="formtabs-quora">Quora</label>
																<input
																	type="text"
																	id="formtabs-quora"
																	class="form-control"
																	placeholder="https://quora.com/abc" />
															</div>
														</div>
														<div class="pt-6">
															<button type="submit" class="btn btn-primary me-4">Submit</button>
															<button type="reset" class="btn btn-label-secondary">Cancel</button>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<!-- Form Alignment -->
							<div class="card">
								<h5 class="card-header">Form Alignment</h5>
								<div class="card-body">
									<div class="d-flex align-items-center justify-content-center h-px-500">
										<form class="w-px-400 border rounded p-3 p-md-5">
											<h3 class="mb-6">Sign In</h3>

											<div class="mb-6">
												<label class="form-label" for="form-alignment-username">Username</label>
												<input type="text" id="form-alignment-username" class="form-control" placeholder="john.doe" />
											</div>

											<div class="mb-6 form-password-toggle">
												<label class="form-label" for="form-alignment-password">Password</label>
												<div class="input-group input-group-merge">
													<input
														type="password"
														id="form-alignment-password"
														class="form-control"
														placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
														aria-describedby="form-alignment-password2" />
													<span class="input-group-text cursor-pointer" id="form-alignment-password2"
														><i class="ti ti-eye-off"></i
													></span>
												</div>
											</div>
											<div class="mb-6">
												<label class="form-check m-0">
													<input type="checkbox" class="form-check-input" />
													<span class="form-check-label">Remember me</span>
												</label>
											</div>
											<div class="d-grid gap-2">
												<button type="submit" class="btn btn-primary">Login</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- / Content -->

						<!-- Footer -->
						<footer class="content-footer footer bg-footer-theme">
							<div class="container-xxl">
								<div
									class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
									<div class="text-body">
										©
										<script>
											document.write(new Date().getFullYear());
										</script>
										, made with ❤️ by <a href="https://pixinvent.com" target="_blank" class="footer-link">Pixinvent</a>
									</div>
									<div class="d-none d-lg-inline-block">
										<a href="https://themeforest.net/licenses/standard" class="footer-link me-4" target="_blank"
											>License</a
										>
										<a href="https://1.envato.market/pixinvent_portfolio" target="_blank" class="footer-link me-4"
											>More Themes</a
										>

										<a
											href="https://demos.pixinvent.com/vuexy-html-admin-template/documentation/"
											target="_blank"
											class="footer-link me-4"
											>Documentation</a
										>

										<a href="https://pixinvent.ticksy.com/" target="_blank" class="footer-link d-none d-sm-inline-block"
											>Support</a
										>
									</div>
								</div>
							</div>
						</footer>
						<!-- / Footer -->

						<div class="content-backdrop fade"></div>
					</div>
					<!-- Content wrapper -->
				</div>
				<!-- / Layout page -->
			</div>

			<!-- Overlay -->
			<div class="layout-overlay layout-menu-toggle"></div>

			<!-- Drag Target Area To SlideIn Menu On Small Screens -->
			<div class="drag-target"></div>
		</div>
		<!-- / Layout wrapper -->

		<!-- Core JS -->
		<!-- build:js assets/vendor/js/core.js -->

		<script src="{{ asset('app-assets/home') }}/vendor/libs/jquery/jquery.js"></script>
		<script src="{{ asset('app-assets/home') }}/vendor/libs/popper/popper.js"></script>
		<script src="{{ asset('app-assets/home') }}/vendor/js/bootstrap.js"></script>
		<script src="{{ asset('app-assets/home') }}/vendor/libs/node-waves/node-waves.js"></script>
		<script src="{{ asset('app-assets/home') }}/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
		<script src="{{ asset('app-assets/home') }}/vendor/libs/hammer/hammer.js"></script>
		<script src="{{ asset('app-assets/home') }}/vendor/libs/i18n/i18n.js"></script>
		<script src="{{ asset('app-assets/home') }}/vendor/libs/typeahead-js/typeahead.js"></script>
		<script src="{{ asset('app-assets/home') }}/vendor/js/menu.js"></script>

		<!-- endbuild -->

		<!-- Vendors JS -->
		<script src="{{ asset('app-assets/home') }}/vendor/libs/cleavejs/cleave.js"></script>
		<script src="{{ asset('app-assets/home') }}/vendor/libs/cleavejs/cleave-phone.js"></script>
		<script src="{{ asset('app-assets/home') }}/vendor/libs/moment/moment.js"></script>
		<script src="{{ asset('app-assets/home') }}/vendor/libs/flatpickr/flatpickr.js"></script>
		<script src="{{ asset('app-assets/home') }}/vendor/libs/select2/select2.js"></script>

		<!-- Main JS -->
		<script src="{{ asset('app-assets/home') }}/js/main.js"></script>

		<!-- Page JS -->
		<script src="{{ asset('app-assets/home') }}/js/form-layouts.js"></script>
	</body>
</html>
