<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="PWA - ARCA System">
	<meta content="UiTM Perlis" name="author" />
	<meta name="base_url" content="<?= baseURL() ?>" />
	<meta name="csrf-token" content="<?= csrfValue() ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="theme-color" content="#0134d4">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">

	<!-- Title -->
	<title> <?= $title ?> | PWA ARCA System </title>

	<!-- Favicon -->
	<link rel="shortcut icon" href="<?= asset('common/images/favicon.png', null, false) ?>">
	<link rel="apple-touch-icon" href="<?= asset('img/icons/icon-96x96.png', 'pwa') ?>">
	<link rel="apple-touch-icon" sizes="152x152" href="<?= asset('img/icons/icon-152x152.png', 'pwa') ?>">
	<link rel="apple-touch-icon" sizes="167x167" href="<?= asset('img/icons/icon-167x167.png', 'pwa') ?>">
	<link rel="apple-touch-icon" sizes="180x180" href="<?= asset('img/icons/icon-180x180.png', 'pwa') ?>">

	<!-- Style CSS -->
	<link rel="stylesheet" href="<?= asset('style.css', 'pwa') ?>">

	<!-- Web App Manifest -->
	<link rel="manifest" href="<?= asset('manifest.json', 'pwa') ?>">

	<link href="<?= asset('custom/css/toastr.min.css', null, false) ?>" rel="stylesheet" type="text/css" />

	<!-- CUSTOM -->
	<script src="<?= mix('dist/js/custom.min.js', null, false) ?>"></script>
	<link href="<?= mix('dist/css/custom.css', null, false) ?>" rel="stylesheet" type="text/css" />

	<script src="https://cdn.jsdelivr.net/npm/js-cookie@3.0.1/dist/js.cookie.min.js"></script>

	<!-- google -->
	<script src="https://apis.google.com/js/platform.js" async defer></script>
	<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback" async defer></script>

	<style>
		.grecaptcha-badge {
			display: none;
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

	<!-- Login Wrapper Area -->
	<div class="login-wrapper d-flex align-items-center justify-content-center">
		<div class="custom-container">
			<div class="text-center px-4">
				<!-- <img class="login-intro-img" src="<?= asset('img/bg-img/36.png', 'pwa') ?>" alt=""> -->
				<img src="<?= asset('images/logo/logo.png') ?>" alt="" class="mb-2">
			</div>

			<!-- Register Form -->
			<div class="register-form mt-4">
				<h4 class="mb-2 text-center">Welcome to ARCA Mobile!</h4>
				<h6 class="mb-4 text-center">Sign in to continue to ARCA.</h6>

				<form id="formAuthentication" method="POST">

					<div class="form-group">
						<input name="username" class="form-control" type="text" id="username" placeholder="Username" autocomplete="off">
					</div>

					<div class="form-group position-relative">
						<input class="form-control" id="psw-input" type="password" name="password" placeholder="Enter Password">
						<div class="position-absolute" id="password-visibility">
							<i class="bi bi-eye"></i>
							<i class="bi bi-eye-slash"></i>
						</div>
					</div>

					<!-- Google reCAPTCHA widget -->
					<?= recaptchaDiv() ?>
					<button id="loginBtn" class="btn btn-primary w-100" type="submit">Sign In</button>
				</form>

				<div class="mt-4 text-center">
					<div class="signin-other-title">
						<h6 class="fs-13 mb-4 title"> - OR - </h6>
					</div>
					<div>
						<button type="button" class="btn btn-danger btn-icon waves-effect waves-light w-100 google-signin" onclick="googleLogin()" disabled>
							<i class="ri-google-fill fs-16"></i> &nbsp; Sign In with Google
						</button>
					</div>
				</div>

			</div>

			<!-- Login Meta -->
			<!-- <div class="login-meta-data text-center">
                <a href="<?= url('auth/forgot') ?>" class="stretched-link forgot-password d-block mt-3 mb-1">Forgot
                    Password?</a>
            </div> -->
		</div>
	</div>

	<div class="modal fade" id="modal2FA" role="dialog" aria-modal="true">
		<div class="modal-dialog modal-lg">
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
								<p>Please enter the 6 digit code from Google Authenticator</p>
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

	<!-- All JavaScript Files -->
	<script src="<?= asset('js/bootstrap.bundle.min.js', 'pwa') ?>"></script>
	<script src="<?= asset('js/slideToggle.min.js', 'pwa') ?>"></script>
	<script src="<?= asset('js/internet-status.js', 'pwa') ?>"></script>
	<script src="<?= asset('js/tiny-slider.js', 'pwa') ?>"></script>
	<script src="<?= asset('js/baguetteBox.min.js', 'pwa') ?>"></script>
	<script src="<?= asset('js/countdown.js', 'pwa') ?>"></script>
	<script src="<?= asset('js/index.js', 'pwa') ?>"></script>
	<script src="<?= asset('js/imagesloaded.pkgd.min.js', 'pwa') ?>"></script>
	<script src="<?= asset('js/dark-rtl.js', 'pwa') ?>"></script>
	<script src="<?= asset('js/active.js', 'pwa') ?>"></script>
	<script src="<?= asset('js/pwa.js', 'pwa') ?>"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			setTimeout(function() {
				googleLogin();
			}, 50);
		});

		var onloadCallback = function() {
			grecaptcha.execute();
		};

		function setResponse(response) {
			document.getElementById('captcha-response').value = response;
		}

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

			const res = await callApi('post', 'auth/socialite', {
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
						}, 250);
					}
				} else {
					noti(500, 'Email not found or not registered!');
				}
			}
		}

		$("#formAuthentication").submit(async function(event) {
			event.preventDefault();

			if (validateData()) {
				var form = $(this);
				const res = await loginApi("auth/sign-in", form.serializeArray(), 'formAuthentication');
				if (isSuccess(res)) {
					const data = res.data;
					const resCode = parseInt(data.resCode);
					const verify = data.verify;

					if (verify == true) {
						$('#username_2fa').val($('#username').val());
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

			grecaptcha.reset();
			onloadCallback();
		});

		function validateData() {

			const rules = {
				'password': 'required',
				'username': 'required',
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

				const res = await callApi('post', "auth/verify-user", {
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