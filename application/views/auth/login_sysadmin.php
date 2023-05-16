<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg">

<head>

	<meta charset="utf-8" />
	<title> <?= $title ?> | ARCA System </title>
	<meta name="base_url" content="<?= baseURL() ?>" />
	<meta name="csrf-token" content="<?= csrfValue() ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta content="ARCA System" name="description" />
	<meta content="UiTM Perlis" name="author" />

	<!-- App favicon -->
	<link rel="shortcut icon" href="<?= asset('common/images/favicon.png', null, false) ?>">

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

	<link href="<?= asset('custom/css/toastr.min.css', null, false) ?>" rel="stylesheet" type="text/css" />

	<script src="<?= asset('custom/js/jquery.min.js', null, false) ?>"></script>
	<script src="<?= asset('custom/js/axios.min.js', null, false) ?>"></script>
	<script src="<?= asset('custom/js/common.js', null, false) ?>"></script>
	<script src="<?= asset('custom/js/validationJS.js', null, false) ?>"></script>
	<script src="<?= asset('custom/js/toastr.min.js', null, false) ?>"></script>

	<script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>

	<!-- google auth -->
	<script src="https://apis.google.com/js/platform.js" async defer></script>

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
						<div class="text-center mt-sm-5 mb-4 text-white-50">
							<div>
								<img src="<?= asset('images/logo/uitm.png') ?>" alt="" height="85">
							</div>
							<p class="mt-3 fs-15 fw-medium">ARCA System</p>
						</div>
					</div>
				</div>
				<!-- end row -->

				<div class="row justify-content-center">
					<div class="col-md-8 col-lg-6 col-xl-5">
						<div class="card mt-4">

							<div class="card-body p-4">

								<div class="text-center mt-2">

									<h5 class="text-primary mt-4">Welcome Back !</h5>
									<p class="text-muted">Sign in to continue to ARCA.</p>
								</div>

								<div class="p-2 mt-4">
									<form id="formAuthentication" method="POST">

										<div class="mb-3">
											<label for="username" class="form-label">Username</label>
											<input type="text" class="form-control" id="username" name="username" placeholder="Enter username / email / matric no.">
										</div>

										<div class="mb-3">
											<label class="form-label" for="password-input">Password</label>
											<div class="position-relative auth-pass-inputgroup mb-3">
												<input type="password" name="password" class="form-control pe-5" placeholder="Enter password" id="password-input">
												<button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted" type="button" id="password-addon"><i class="ri-eye-fill align-middle"></i></button>
											</div>
										</div>

										<!-- <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                            <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                        </div> -->

										<div class="mt-4">
											<button id="loginBtn" class="btn btn-info w-100" type="submit">Sign In</button>
										</div>

										<div class="mt-4 text-center">
											<div class="signin-other-title">
												<h5 class="fs-13 mb-4 title">OR</h5>
											</div>
											<div>
												<button type="button" class="btn btn-danger btn-icon waves-effect waves-light w-100 google-signin" onclick="googleLogin()" disabled>
													<i class="ri-google-fill fs-16"></i> &nbsp; Sign In with Google
												</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<!-- end card body -->
						</div>
						<!-- end card -->
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
								<script>
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
	<script src="<?= asset('js/pages/plugins/lord-icon-2.1.0.js') ?>"></script>
	<script src="<?= asset('js/plugins.js') ?>"></script>

	<!-- particles js -->
	<script src="<?= asset('libs/particles.js/particles.js') ?>"></script>
	<!-- particles app js -->
	<script src="<?= asset('js/pages/particles.app.js') ?>"></script>
	<!-- password-addon init -->
	<script src="<?= asset('js/pages/password-addon.init.js') ?>"></script>

	<div class="modal fade" id="modal2FA" role="dialog" aria-modal="true">
		<div class="modal-dialog modal-md">
			<div class="modal-content">
				<!-- <div class="modal-body"> -->
				<div class="card mt-4">

					<div class="card-body p-4">
						<div class="mb-4">
							<div class="avatar-lg mx-auto">
								<div class="avatar-title bg-light text-primary display-5 rounded-circle">
									<i class="ri-google-fill"></i>
								</div>
							</div>
						</div>

						<div class="p-2 mt-4">
							<div class="text-muted text-center mb-4 mx-lg-3">
								<h4>Verify Your Account</h4>
								<p>Please enter the 6 digit code by Google Authenticator</p>
							</div>

							<div class="row">
								<div class="col-2">
									<div class="mb-3">
										<label for="digit1-input" class="visually-hidden">Digit 1</label>
										<input type="text" class="form-control form-control-lg bg-light border-light text-center" onkeyup="moveToNext(1, event);" maxlength="1" id="digit1-input" name="digit1">
									</div>
								</div><!-- end col -->

								<div class="col-2">
									<div class="mb-3">
										<label for="digit2-input" class="visually-hidden">Digit 2</label>
										<input type="text" class="form-control form-control-lg bg-light border-light text-center" onkeyup="moveToNext(2, event)" maxlength="1" id="digit2-input" name="digit2">
									</div>
								</div><!-- end col -->

								<div class="col-2">
									<div class="mb-3">
										<label for="digit3-input" class="visually-hidden">Digit 3</label>
										<input type="text" class="form-control form-control-lg bg-light border-light text-center" onkeyup="moveToNext(3, event)" maxlength="1" id="digit3-input" name="digit3">
									</div>
								</div><!-- end col -->

								<div class="col-2">
									<div class="mb-3">
										<label for="digit4-input" class="visually-hidden">Digit 4</label>
										<input type="text" class="form-control form-control-lg bg-light border-light text-center" onkeyup="moveToNext(4, event)" maxlength="1" id="digit4-input" name="digit4">
									</div>
								</div><!-- end col -->

								<div class="col-2">
									<div class="mb-3">
										<label for="digit5-input" class="visually-hidden">Digit 5</label>
										<input type="text" class="form-control form-control-lg bg-light border-light text-center" onkeyup="moveToNext(5, event)" maxlength="1" id="digit5-input" name="digit5">
									</div>
								</div><!-- end col -->

								<div class="col-2">
									<div class="mb-3">
										<label for="digit6-input" class="visually-hidden">Digit 6</label>
										<input type="text" class="form-control form-control-lg bg-light border-light text-center" onkeyup="moveToNext(6, event)" maxlength="1" id="digit6-input" name="digit6">
									</div>
								</div><!-- end col -->
							</div>
							<input type="hidden" id="username_2fa" placeholder="username_2fa" readonly>
						</div>
					</div>
					<!-- end card body -->
				</div>
				<!-- </div> -->
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function() {
			setTimeout(function() {
				googleLogin();
			}, 50);
		});

		function googleLogin() {

			var auth2;

			gapi.load('auth2', function() {
				var gapiConfig = JSON.parse('<?= gapiConfig() ?>');

				// Retrieve the singleton for the GoogleAuth library and set up the client.
				auth2 = gapi.auth2.init(gapiConfig)
					.then(
						//oninit
						function(GoogleAuth) {
							attachSignin(GoogleAuth, document.getElementsByClassName('google-signin')[0]);
							$('.google-signin').attr('disabled', false);
						},
						//onerror
						function(error) {
							console.log('error initialize', error);
							noti(500, 'Google Auth cannot be initialize');
						}
					);
			});
		}

		function attachSignin(GoogleAuth, element) {
			GoogleAuth.attachClickHandler(element, {},
				function(googleUser) {
					var profile = googleUser.getBasicProfile();
					var google_id_token = googleUser.getAuthResponse().id_token;
					// console.log('google return', googleUser.getBasicProfile());
					loginGoogle(profile.getEmail());
				},
				function(res) {
					if (res.error != 'popup_closed_by_user') {
						noti(500, "Login using google was unsuccessful");
					} else {
						console.log('error', res);
					}
				});
		}

		async function loginGoogle(googleEmail) {

			const res = await callApi('post', 'sysadmin/socialite', {
				'email': googleEmail,
				'arcaSecurityCsrf': $('meta[name="csrf-token"]').attr('content') // csrf token
			});

			if (isSuccess(res.status)) {
				if (res.data != null) {
					const resCode = parseInt(res.data.resCode);
					noti(resCode, res.data.message);

					if (isSuccess(resCode)) {
						setTimeout(function() {
							window.location.href = res.data.redirectUrl;
						}, 650);
					}
				} else {
					noti(500, 'Email not found or not registered!');
				}
			}
		}

		$("#formAuthentication").submit(async function(event) {
			event.preventDefault();
			var username = $('#username').val();
			var password = $('#password').val();

			if (validateData()) {
				var form = $(this);
				const res = await loginApi("sysadmin/sign-in", form.serializeArray(), 'formAuthentication');
				if (isSuccess(res)) {
					const data = res.data;
					const resCode = parseInt(data.resCode);
					const verify = data.verify;

					if (verify == true) {
						$('#username_2fa').val(username);

						$("#modal2FA").on('shown.bs.modal', function() {
							document.getElementById("digit1-input").focus();
						});

						$('#modal2FA').modal('show');

					} else {
						noti(resCode, data.message);

						if (isSuccess(resCode)) {
							setTimeout(function() {
								window.location.href = data.redirectUrl;
							}, 500);
						} else {
							$("#loginBtn").html('Sign In');
							$("#loginBtn").attr('disabled', false);
						}
					}

				} else {
					$("#loginBtn").html('Sign In');
					$("#loginBtn").attr('disabled', false);
				}

			} else {
				validationJsError('toastr', 'multi'); // single or multi
			}
		});

		function validateData() {

			const rules = {
				'username': 'required',
				'password': 'required',
			};

			return validationJs(rules);
		}

		async function submit2FA() {
			var d1 = $('#digit1-input').val();
			var d2 = $('#digit2-input').val();
			var d3 = $('#digit3-input').val();
			var d4 = $('#digit4-input').val();
			var d5 = $('#digit5-input').val();
			var d6 = $('#digit6-input').val();

			if (validate2FAData()) {

				const res = await callApi('post', "sysadmin/verify-user", {
					'code_2fa': d1 + d2 + d3 + d4 + d5 + d6,
					'username_2fa': $('#username_2fa').val(),
				});

				if (isSuccess(res)) {
					const data = res.data;
					const resCode = parseInt(data.resCode);

					noti(resCode, data.message);

					if (isSuccess(resCode)) {
						$('#modal2FA').modal('hide');
						setTimeout(function() {
							window.location.href = data.redirectUrl;
						}, 300);
					} else {

						// reset
						$('#digit1-input').val('');
						$('#digit2-input').val('');
						$('#digit3-input').val('');
						$('#digit4-input').val('');
						$('#digit5-input').val('');
						$('#digit6-input').val('');
						document.getElementById("digit1-input").focus();
					}

				}

			} else {
				validationJsError('toastr', 'multi'); // single or multi
			}
		}

		function validate2FAData() {

			const rules = {
				'digit6': 'required|integer',
				'digit5': 'required|integer',
				'digit4': 'required|integer',
				'digit3': 'required|integer',
				'digit2': 'required|integer',
				'digit1': 'required|integer',
				'username_2fa': 'required',
			};

			const message = {
				'digit1': 'Field Digit 1',
				'digit2': 'Field Digit 2',
				'digit3': 'Field Digit 3',
				'digit4': 'Field Digit 4',
				'digit5': 'Field Digit 5',
				'digit6': 'Field Digit 6',
				'username_2fa': 'Username',
			};

			return validationJs(rules, message);
		}

		function moveToNext(e, t) {

			var d1 = $('#digit1-input').val();
			var d2 = $('#digit2-input').val();
			var d3 = $('#digit3-input').val();
			var d4 = $('#digit4-input').val();
			var d5 = $('#digit5-input').val();
			var d6 = $('#digit6-input').val();

			if (d1 != '' && d2 != '' && d3 != '' && d4 != '' && d5 != '' && d6 != '') {
				submit2FA();
			} else if (t.target.value != '' && e < 6) {
				document.getElementById("digit" + (e + 1) + "-input").focus();
			}

		}
	</script>

</body>

</html>