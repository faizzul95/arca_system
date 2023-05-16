<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

	<meta charset="utf-8" />
	<title> {{ $title }} | ARCA System </title>
	<base href="{{ baseURL() }}">
	<meta name="csrf-token" content="{{ csrfValue() }}" />
	<meta name="base_url" content="{{ baseURL() }}" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- App favicon -->
	<link rel="shortcut icon" href="{{ asset('common/images/favicon.png', null, false) }}">

	<!-- Layout config Js -->
	<script src="{{ asset('js/layout.js') }}"></script>
	<!-- Bootstrap Css -->
	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
	<!-- Icons Css -->
	<link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
	<!-- App Css-->
	<link href="{{ asset('css/app.min.css') }}" rel="stylesheet" type="text/css" />
	<!-- custom Css-->
	<link href="{{ asset('css/custom.min.css') }}" rel="stylesheet" type="text/css" />

	<!-- sweetalert2 -->
	<!-- Sweet Alert css-->
	<link href="{{ asset('libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
	<script src="{{ asset('libs/sweetalert2/sweetalert2.min.js') }}"></script>

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- CUSTOM -->
	<!-- <script src="{{ asset('dist/js/custom.min.js', null, false) }}"></script> -->
	<script src="{{ mix('dist/js/custom.min.js', null, false) }}"></script>
	<link href="{{ mix('dist/css/custom.css', null, false) }}" rel="stylesheet" type="text/css" />

	<script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>

	<!--datatable css-->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />

	<!--datatable responsive css-->
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />

	<!--datatable js-->
	<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

	<!-- select2 -->
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
	<!-- Begin page -->
	<div id="layout-wrapper">

		@includeif('templates.desktop_header')
		@includeif('templates.desktop_menu')

		<!-- ============================================================== -->
		<!-- Start right Content here -->
		<!-- ============================================================== -->
		<div class="main-content">

			<div class="page-content">
				<div class="container-fluid">

					@if(segment(1) != 'profile' && segment(2) != 'settings')

					<!-- start page title -->
					<div class="row">
						<div class="col-12">
							<div class="page-title-box d-sm-flex align-items-center justify-content-between">
								<h4 class="mb-sm-0"> {{ $title }} <span id="subpage1" class="text-muted"></span> <span id="subpage2" class="text-muted"></span> </h4>

								<div class="page-title-right">
									<ol class="breadcrumb m-0">
										<li class="breadcrumb-item"><a href="javascript: void(0);"> {{ $currentSidebar }} </a></li>
										@if(!empty($currentSubSidebar))
										<li class='breadcrumb-item active'>{{ (!empty($currentSubSidebar)) ? $currentSubSidebar : '' }}</li>
										@endif
									</ol>
								</div>

							</div>
						</div>
					</div>
					<!-- end page title -->

					<div class="row">
						<div class="col">

							<div class="h-100">

								<div class="row mb-3 pb-1">
									<div class="col-12">
										<div class="d-flex align-items-lg-center flex-lg-row flex-column">
											<div class="flex-grow-1">
												<h4 class="fs-16 mb-1"> Welcome, {{ currentUserFullName() }} ! </h4>
											</div>
											<div class="mt-3 mt-lg-0">
												<div class="row g-3 mb-0 align-items-center">
													<div class="col-auto">
														<span id="currentTime"></span>
													</div>
												</div>
											</div>
										</div><!-- end card header -->
									</div>
									<!--end col-->
								</div>
								<!--end row-->

								@yield('content')

							</div> <!-- end .h-100-->

						</div> <!-- end col -->
					</div>

					@else
					<div class="row">
						<div class="container-fluid">
							@yield('content')
						</div>
					</div>
					@endif

				</div>
				<!-- container-fluid -->
			</div>
			<!-- End Page-content -->

			<footer class="footer">
				<div class="container-fluid">
					<div class="row">
						<div class="col-sm-6">
							<script>
								document.write(new Date().getFullYear())
							</script> © ARCA System.
						</div>
						<div class="col-sm-6">
							<div class="text-sm-end d-none d-sm-block">
								Develop by UiTM Perlis
							</div>
						</div>
					</div>
				</div>
			</footer>
		</div>
		<!-- end main content-->

	</div>
	<!-- END layout-wrapper -->

	@includeif('templates.desktop_theme_settings')

	<!-- JAVASCRIPT -->
	<script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
	<script src="{{ asset('libs/moment/moment.js') }}"></script>
	<script src="{{ asset('libs/simplebar/simplebar.min.js') }}"></script>
	<script src="{{ asset('libs/node-waves/waves.min.js') }}"></script>
	<script src="{{ asset('libs/feather-icons/feather.min.js') }}"></script>
	<script src="{{ asset('js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
	<script src="{{ asset('js/plugins.js') }}"></script>

	<!-- App js -->
	<script src="{{ asset('js/app.js') }}"></script>

	<script>
		$(document).ready(function() {
			clock();
		});

		function clock() {
			var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
			var d = new Date();
			var dayName = days[d.getDay()];

			let today = new Date().toLocaleDateString('en-GB', {
				month: '2-digit',
				day: '2-digit',
				year: 'numeric'
			});

			var display = new Date().toLocaleTimeString();
			$("#currentTime").html(dayName + ', ' + today + ' | ' + display);
			setTimeout(clock, 1000);
		}

		function changeProfile(profileID, userID, roleName) {
			Swal.fire({
				title: 'Are you sure?',
				html: "You want to switch profile to <b>" + roleName + "</b>?",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Switch now!',
				reverseButtons: true
			}).then(
				async (result) => {
					if (result.isConfirmed) {
						const res = await callApi('post', 'auth/switchProfile', {
							'profile_id': profileID,
							'user_id': userID,
						});

						if (isSuccess(res)) {
							noti(200, "Switch to profile " + roleName);
							if (isSuccess(res.data.resCode)) {
								setTimeout(function() {
									redirect('dashboard');
								}, 500);
							} else {
								noti(500, "Switch profile unsuccessfully");
							}
						} else {
							noti(500, "Switch profile unsuccessfully");
						}
					}
				})
		}
	</script>
</body>

@includeif('_generals.php.common')
@includeif('_generals._modalGeneral')
@includeif('_generals._modalLogout')

</html>
