<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<meta name="description" content="PWA - ARCA Mobile">
	<meta name="csrf-token" content="{{ csrfValue() }}" />
	<meta name="base_url" content="{{ baseURL() }}" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- The above 4 meta tags *must* come first in the head; any other head content must come *after* these tags -->
	<base href="{{ baseURL() }}">

	<meta name="theme-color" content="#0134d4">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<!-- Title -->
	<title> {{ $title }} | PWA ARCA System </title>

	<!-- Favicon -->
	<link rel="shortcut icon" href="{{ asset('common/images/favicon.png', null, false) }}">
	<!-- <link rel="shortcut icon" href="{{ asset('img/core-img/icon.png', 'pwa') }}"> -->
	<link rel="apple-touch-icon" href="{{ asset('img/icons/icon-96x96.png', 'pwa') }}">
	<link rel="apple-touch-icon" sizes="152x152" href="{{ asset('img/icons/icon-152x152.png', 'pwa') }}">
	<link rel="apple-touch-icon" sizes="167x167" href="{{ asset('img/icons/icon-167x167.png', 'pwa') }}">
	<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/icons/icon-180x180.png', 'pwa') }}">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

	<!-- CUSTOM -->
	<script src="{{ mix('dist/js/custom.min.js', null, false) }}"></script>
	<link href="{{ mix('dist/css/custom.css', null, false) }}" rel="stylesheet" type="text/css" />

	<script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>

	<!-- sweetalert2 -->
	<!-- Sweet Alert css-->
	<link href="{{ asset('libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
	<script src="{{ asset('libs/sweetalert2/sweetalert2.min.js') }}"></script>

	<!-- Style CSS -->
	<link rel="stylesheet" href="{{ asset('style.css', 'pwa') }}">

	<!-- Web App Manifest -->
	<link rel="manifest" href="{{ asset('manifest.json', 'pwa') }}">

	<style>
		#toast-container>.toast-error {
			background-color: #BD362F;
		}
	</style>

</head>

<body>
	<!-- Preloader -->
	<div id="preloader">
		<div class="spinner-grow text-primary" role="status">
			<span class="visually-hidden">Loading...</span>
		</div>
	</div>

	<!-- Internet Connection Status -->
	<div class="internet-connection-status" id="internetStatus"></div>

	<!-- Dark mode switching -->
	<!-- <div class="dark-mode-switching">
        <div class="d-flex w-100 h-100 align-items-center justify-content-center">
            <div class="dark-mode-text text-center">
                <i class="bi bi-moon"></i>
                <p class="mb-0">Switching to night mode</p>
            </div>
            <div class="light-mode-text text-center">
                <i class="bi bi-brightness-high"></i>
                <p class="mb-0">Switching to light mode</p>
            </div>
        </div>
    </div> -->

	<!-- Setting Popup Overlay -->
	<div id="setting-popup-overlay"></div>

	<!-- Header Area -->
	<div class="header-area" id="headerArea">
		<div class="container">
			<!-- Header Content -->

			<div class="header-content header-style-four position-relative d-flex align-items-center justify-content-between">
				<!-- Navbar Toggler -->
				<!-- <div class="navbar--toggler" id="affanNavbarToggler5" data-bs-toggle="offcanvas" data-bs-target="#menuLeftOffCanvas" aria-controls="menuLeftOffCanvas">
                    <span class="d-block"></span>
                    <span class="d-block"></span>
                    <span class="d-block"></span>
                </div> -->

				<!-- Logo Wrapper -->
				<div class="logo-wrapper">
					<a href="javascript:void(0);">
						<img src="{{ asset('common/images/favicon.png', null, false) }}" alt="">
						<span style="color: #8480ae;">ARCA MOBILE</span>
					</a>
				</div>

				<div class="navbar-content-wrapper d-flex align-items-center">
					<!-- Search -->
					<!-- <div class="search-wrapper me-2">
                        <a class="search-trigger-btn" href="#" id="settingTriggerBtn">
                            <i class="bi bi-moon"></i>
                        </a>
                    </div> -->

					<!-- User Profile -->
					<div class="user-profile-wrapper">
						<a class="user-profile-trigger-btn" href="#" data-bs-toggle="offcanvas" data-bs-target="#menuRightOffCanvas">
							<img id="header_user_avatar" src="{{ currentUserAvatar() }}" alt="Student avatar">
						</a>
					</div>
				</div>

			</div>

		</div>
	</div>

	@if(in_array(segment(1), ['event', 'dashboard']))
	<div class="page-content-wrapper">
		@yield('content')
	</div>
	@elseif(segment(1) == 'profile')
	<div class="page-content-wrapper">
		<div class="row">
			<div class="container-fluid">
				@yield('content')
			</div>
		</div>
	</div>
	@else
	<div class="page-content-wrapper py-3">
		@yield('content')
	</div>
	@endif

	<!-- Footer Nav -->
	<div class="footer-nav-area" id="footerNav">
		<div class="container px-0">
			<!-- Footer Content -->
			<div class="footer-nav position-relative shadow-sm footer-style-two">
				<ul class="h-100 d-flex align-items-center justify-content-between ps-0">

					<li class="<?= ($currentSidebar == 'Dashboard') ? 'active' : '' ?>">
						<a href="{{ url('dashboard') }}">
							<i class="bi bi-house"></i>
						</a>
					</li>

					<li class="<?= ($currentSidebar == 'Scanner') ? 'active' : '' ?>">
						<a href="{{ url('event/qr-student-scanner') }}">
							<i class="bi bi-qr-code-scan"></i>
						</a>
					</li>

					<li class="<?= ($currentSidebar == 'Profile') ? 'active' : '' ?>">
						<a href="{{ url('profile') }}">
							<i class="bi bi-gear"></i>
						</a>
					</li>

				</ul>
			</div>

		</div>
	</div>

	<!-- # Sidenav Right -->
	<div class="offcanvas offcanvas-end" id="menuRightOffCanvas" data-bs-scroll="true" tabindex="-1" aria-labelledby="affanOffcanvsLabel">

		<button class="btn-close btn-close-white text-reset" type="button" data-bs-dismiss="offcanvas" aria-label="Close"></button>

		<div class="offcanvas-body p-0">
			<div class="sidenav-wrapper">
				<!-- Sidenav Profile -->
				<div class="sidenav-profile bg-gradient">
					<div class="sidenav-style1"></div>

					<!-- User Thumbnail -->
					<div class="user-profile">
						<img src="{{ currentUserAvatar() }}" alt="Menu student avatar">
					</div>

					<!-- User Info -->
					<div class="user-info">
						<h6 class="user-name mb-1">{{ currentUserFullName() }}</h6>
						<span>{{ currentUserProfileName() }}</span>
						<span> | </span>
						<span>{{ currentMatricID() }}</span>
					</div>
				</div>

				<!-- Sidenav Nav -->
				<ul class="sidenav-nav ps-0">
					<!-- <li>
                        <a href="pages.html">
                            <i class="bi bi-collection"></i> Pages
                            <span class="badge bg-success rounded-pill ms-2">100+</span>
                        </a>
                    </li> -->

					<li>
						<a href="#" class="nav-url">
							<i class="bi bi-people"></i> Profile
						</a>
						<ul>
							<?php
							$profile = getAllUserProfile();
							foreach ($profile as $up) {
								// $current = ($up['profile_id'] == currentUserProfileID()) ? '<span class="badge bg-soft-success text-success mt-1 ml-2 float-end"> Current </span>' : '';
								$current = ($up['profile_id'] == currentUserProfileID()) ? 'style="color:green!important"' : '';
								$roleName = ($up['profile_id'] == currentUserProfileID()) ? '<span style="color:green!important">' . $up['role_name'] . '</span>' : $up['role_name'];

								$changeProfile = ($up['profile_id'] != currentUserProfileID()) ? 'onclick="changeProfile(' . $up['profile_id'] . ', ' . $up['user_id'] . ', \'' . $up['role_name'] . '\')"' : '';
								echo '<li> <a href="javascript:void(0);" ' . $changeProfile . ' ' . $current . '>' . $roleName . '</a> </li>';
							}
							?>
						</ul>
					</li>

					<li>
						<a href="{{ url('profile') }}">
							<i class="bi bi-gear"></i> Settings
						</a>
					</li>
					<li>
						<div class="night-mode-nav">
							<i class="bi bi-moon"></i> Night Mode
							<div class="form-check form-switch">
								<input class="form-check-input form-check-success" id="darkSwitch" type="checkbox">
							</div>
						</div>
					</li>
					<li>
						<a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#logoutModal">
							<i class="bi bi-box-arrow-right"></i> Logout
						</a>
					</li>
				</ul>

				<!-- Copyright Info -->
				<div class="copyright-info">
					<p><span id="copyrightYear"></span> &copy; Develop by UiTM Perlis </p>
				</div>
			</div>
		</div>
	</div>

	<!-- All JavaScript Files -->
	<script src="{{ asset('js/bootstrap.bundle.min.js', 'pwa') }}"></script>
	<script src="{{ asset('libs/moment/moment.js') }}"></script>
	<script src="{{ asset('js/slideToggle.min.js', 'pwa') }}"></script>
	<script src="{{ asset('js/internet-status.js', 'pwa') }}"></script>
	<script src="{{ asset('js/tiny-slider.js', 'pwa') }}"></script>
	<script src="{{ asset('js/baguetteBox.min.js', 'pwa') }}"></script>
	<script src="{{ asset('js/vanilla-dataTables.min.js', 'pwa') }}"></script>
	<script src="{{ asset('js/countdown.js', 'pwa') }}"></script>
	<script src="{{ asset('js/index.js', 'pwa') }}"></script>
	<script src="{{ asset('js/imagesloaded.pkgd.min.js', 'pwa') }}"></script>
	<script src="{{ asset('js/dark-rtl.js', 'pwa') }}"></script>
	<script src="{{ asset('js/active.js', 'pwa') }}"></script>
	<script src="{{ asset('js/pwa.js', 'pwa') }}"></script>

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
			$("#currentTime").html(dayName + ', ' + today + ' ' + display);
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
							setTimeout(function() {
								// refreshPage();
								window.location.href = $('meta[name="base_url"]').attr('content') + 'dashboard';
							}, 500);
						} else {
							noti(500, "Switch profile unsuccessfully")
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