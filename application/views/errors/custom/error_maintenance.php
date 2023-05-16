<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>

	<meta charset="utf-8" />
	<title>Maintenance | ARCA System</title>
	<meta name="csrf-token" content="" />
	<meta name="base_url" content="<?= base_url ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="ARCA System" name="description" />
	<meta content="UiTM Perlis" name="author" />

	<!-- App favicon -->
	<link rel="shortcut icon" href="<?= asset('images/favicon.ico') ?>">

	<!-- Layout config Js -->
	<script src="<?= asset('js/layout.js') ?>"></script>
	<!-- Bootstrap Css -->
	<link href="<?= asset('css/bootstrap.min.css') ?>" rel="stylesheet" type="text/css" />
	<!-- Icons Css -->
	<link href="<?= asset('css/icons.min.css') ?>" rel="stylesheet" type="text/css" />
	<!-- App Css-->
	<link href="<?= asset('css/app.min.css') ?>" rel="stylesheet" type="text/css" />
	<!-- custom Css-->
	<link href="<?= asset('css/custom.min.css') ?>" rel="stylesheet" type="text/css" />

</head>

<body>

	<div class="auth-page-wrapper pt-5">
		<!-- auth page bg -->
		<div class="auth-one-bg-position auth-one-bg" id="auth-particles">
			<div class="bg-overlay"></div>

			<div class="shape">
				<svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
					<path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
				</svg>
			</div>
		</div>

		<!-- auth page content -->
		<div class="auth-page-content">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="text-center mt-sm-5 pt-4">
							<div class="mb-5 text-white-50">
								<h1 class="display-5 coming-soon-text">Site is Under Maintenance</h1>
								<p class="fs-14">Please check back in sometime</p>
								<div class="mt-4 pt-2">
									<a href="<?= url('dashboard') ?>" class="btn btn-success"><i class="mdi mdi-home me-1"></i> Back to Dashboard </a>
								</div>
							</div>
							<div class="row justify-content-center mb-5">
								<div class="col-xl-4 col-lg-8">
									<div>
										<img src="<?= asset('images/maintenance.png') ?>" alt="" width="250%" class="img-fluid">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- end row -->

			</div>
			<!-- end container -->
		</div>
		<!-- end auth page content -->

		<!-- footer -->
		<footer class="footer">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<div class="text-center">
							<p class="mb-0 text-muted">&copy;
								2022 - <script>
									document.write(new Date().getFullYear())
								</script> ARCA System. Develop by UiTM Perlis
							</p>
						</div>
					</div>
				</div>
			</div>
		</footer>
		<!-- end Footer -->

	</div>
	<!-- end auth-page-wrapper -->

	<!-- JAVASCRIPT -->
	<script src="<?= asset('libs/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
	<script src="<?= asset('libs/simplebar/simplebar.min.js') ?>"></script>
	<script src="<?= asset('libs/node-waves/waves.min.js') ?>"></script>
	<script src="<?= asset('libs/feather-icons/feather.min.js') ?>"></script>

	<!-- particles js -->
	<script src="<?= asset('libs/particles.js/particles.js') ?>"></script>
	<!-- particles app js -->
	<script src="<?= asset('js/pages/particles.app.js') ?>"></script>

</body>

</html>
