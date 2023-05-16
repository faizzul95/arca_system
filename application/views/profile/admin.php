@extends('templates.desktop_blade')

@section('content')

<div class="profile-foreground position-relative mx-n4 mt-n4">
	<div class="profile-wid-bg">
		<img src="{{ asset('common/images/auth-one-bg.jpg', null, false) }}" alt="" class="profile-wid-img" width="100%" />
	</div>
</div>

<div class="pt-4 mb-4 mb-lg-3 pb-lg-4">
	<div class="row g-4">
		<div class="col-auto">
			<div class="avatar-lg" style="position: relative; display:inline-block;">
				<img src="{{ currentUserAvatar() }}" id="user_avatar_view" alt="user image" class="img-thumbnail rounded-circle img-fluid">
				<?php if ($permission['settings-upload-profile']) : ?>
					<a href="javascript:void(0)" onclick="updateProfilePhoto()" class="btn btn-icon btn-sm btn-info rounded-circle" style="position: absolute; top: 35px; right: -10px;" title="Change profile">
						<i class="ri-camera-fill" aria-hidden="true"></i>
					</a>
				<?php endif; ?>
			</div>
		</div>
		<!--end col-->
		<div class="col">
			<div class="p-2">
				<h3 class="text-white text-uppercase mb-1" id="showFullNameMain">{{ currentUserFullName() }}</h3>
				<p class="text-white-75 text-uppercase" id="showProfileNameMain">{{ currentUserProfileName() }}</p>
				<div class="hstack text-white-50 gap-1">
					<div class="me-2">
						<i class="ri-map-pin-user-line me-1 text-white-75 fs-16 align-middle text-uppercase"></i>
						{{ currentUserBranchName() }}
					</div>
				</div>
			</div>
		</div>
		<!--end col-->
	</div>
	<!--end row-->
</div>

<div class="row">
	<div class="col-lg-12">
		<div>
			<div class="d-flex">
				<!-- Nav tabs -->
				<ul class="nav nav-pills animation-nav profile-nav gap-2 gap-lg-3 flex-grow-1" role="tablist">
					<li class="nav-item">
						<a class="nav-link fs-14 active" data-bs-toggle="tab" href="#overview-tab" role="tab">
							<i class="ri-airplay-fill d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Overview</span>
						</a>
					</li>
					<?php if ($permission['settings-security-tab']) : ?>
						<li class="nav-item">
							<a class="nav-link fs-14" data-bs-toggle="tab" href="#changepassword" role="tab">
								<i class="ri-list-unordered d-inline-block d-md-none"></i> <span class="d-none d-md-inline-block">Security</span>
							</a>
						</li>
					<?php endif; ?>
				</ul>
				<div class="flex-shrink-0">
					<?php if ($permission['settings-update']) : ?>
						<a href="javascript:void(0)" onclick="updateProfileDetails()" class="btn btn-success"><i class="ri-edit-box-line align-bottom"></i> Edit Profile</a>
					<?php endif; ?>
				</div>
			</div>
			<!-- Tab panes -->
			<div class="tab-content pt-4 text-muted">
				<div class="tab-pane active" id="overview-tab" role="tabpanel">
					<div class="row">
						<div class="col-xxl-6">
							<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
								<i class="fa fa-user label-icon"></i><strong> General Information </strong>
							</div>
							<div class="card">
								<div class="card-body">
									<?php if ($permission['settings-view-info']) { ?>
										<div class=" table-responsive">
											<table class="table table-borderless mb-0">
												<tbody>
													<tr>
														<th class="ps-0" scope="row">Full Name :</th>
														<td id="showFullName" class="text-muted text-uppercase">-</td>
													</tr>
													<tr>
														<th class="ps-0" scope="row">Matric ID</th>
														<td id="showMatricID" class="text-muted text-uppercase">-</td>
													</tr>
													<tr>
														<th class="ps-0" scope="row">NRIC</th>
														<td id="showNRIC" class="text-muted text-uppercase">-</td>
													</tr>
													<tr>
														<th class="ps-0" scope="row">Gender</th>
														<td id="showGender" class="text-muted text-uppercase">-</td>
													</tr>
													<tr>
														<th class="ps-0" scope="row">Mobile :</th>
														<td id="showMobileNo" class="text-muted text-uppercase">-</td>
													</tr>
													<tr>
														<th class="ps-0" scope="row">E-mail :</th>
														<td id="showEmail" class="text-muted">-</td>
													</tr>
													<tr>
														<th class="ps-0" scope="row">College :</th>
														<td id="showCollege" class="text-muted text-uppercase">-</td>
													</tr>
												</tbody>
											</table>
										</div>
									<?php } else {
										nodataAccess();
									} ?>
								</div><!-- end card body -->
							</div><!-- end card -->

						</div>
						<!--end col-->
						<div class="col-xxl-6">
							<div id="dataListProfileDiv" class="card">
								<div class="card-body">
									<h5 class="card-title mb-3">Profile</h5>

									<?php if ($permission['settings-view-profile']) { ?>
										<div id="listProfile"> </div>
									<?php } else {
										nodataAccess();
									} ?>

									<!--end row-->
								</div>
								<!--end card-body-->
							</div><!-- end card -->

						</div>
						<!--end col-->
					</div>
					<!--end row-->
				</div>
				<div class="tab-pane fade" id="changepassword" role="tabpanel">
					<div class="row">
						<div class="col-xxl-6">

							<!-- username -->
							<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
								<i class="fa fa-user-circle-o label-icon"></i><strong> Change Username </strong>
							</div>
							<div class="card">
								<div class="card-body">
									<form id="changeUsernameForm" action="user/reset-username" method="POST" class="mt-4">
										<div class="row">
											<div class="col-lg-12">
												<div>
													<label for="username" class="form-label"> Username <span class="text-danger">*</span></label>
													<input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
												</div>
											</div>
											<!--end col-->
											<div class="col-lg-12 mt-2">
												<span class="text-danger mb-2">* Indicates a required field</span>
												<div class="text-end">
													<button type="submit" id="submitBtn" class="btn btn-success"> <i class='fa fa-save'></i> Update </button>
												</div>
											</div>
											<!--end col-->
										</div>
										<!--end row-->
									</form>
								</div>
								<!--end card-body-->
							</div>

							<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
								<i class="fa fa-lock label-icon"></i><strong> Two-factor authentication (2FA) </strong>
							</div>
							<div class="card">
								<div class="card-header align-items-center d-flex">
									<h6 class="card-title mb-0 flex-grow-1">
										<div class="form-check form-switch form-switch-success">
											<input class="form-check-input" type="checkbox" role="switch" id="two_factor_status" onchange="changeStatus2FA()">
											<label class="form-check-label" for="two_factor_status"> Tick to Enable 2FA </label>
										</div>
									</h6>
									<div class="flex-shrink-0">
										<div>
											<button type="button" class="btn btn-soft-primary btn-sm" onclick="viewQr2FA()">
												See QR Code
											</button>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div>
										<div class="alert alert-warning mt-2" role="alert">
											<span class="form-text text-muted"><b> INSTRUCTION </b></span>
											<span class="form-text text-muted">
												<ul>
													<li> Please make sure to paired your device with Google Authenticator before logout </li>
													<li> For Android device please download Google Authenticator via Google Play Store </li>
													<li> For iOS device please download Google Authenticator via Apple AppStore </li>
												</ul>
											</span>
										</div>
									</div>
								</div>
								<!--end card-body-->
							</div>

						</div>
						<div class="col-xxl-6">
							<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
								<i class="fa fa-key label-icon"></i><strong> Change Password </strong>
							</div>
							<div class="card">
								<div class="card-body">
									<form id="changePasswordForm" action="user/reset-password" method="POST" class="mt-4">
										<div class="row g-2">
											<div class="col-lg-12">
												<div>
													<label for="oldpasswordInput" class="form-label">Current Password <span class="text-danger">*</span></label>
													<input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="Enter current password" required>
												</div>
											</div>
											<!--end col-->
											<div class="col-lg-12">
												<div>
													<label for="newpasswordInput" class="form-label">New Password <span class="text-danger">*</span></label>
													<input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="Enter new password" required>
												</div>
											</div>
											<!--end col-->
											<div class="col-lg-12">
												<div>
													<label for="confirmpasswordInput" class="form-label">Confirm Password <span class="text-danger">*</span></label>
													<input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm password" required>
												</div>
											</div>
											<!--end col-->
											<div class="col-lg-12">
												<span class="text-danger mb-2">* Indicates a required field</span>
												<div class="text-end">
													<!-- <button type="submit" class="btn btn-success">Change Password</button> -->
													<button type="submit" id="submitBtn" class="btn btn-success"> <i class='fa fa-save'></i> Change Password </button>
												</div>
											</div>
											<!--end col-->
										</div>
										<!--end row-->
									</form>
								</div>
								<!--end card-body-->
							</div>
							<!--end card-->
						</div>
					</div>
					<!--end row-->
				</div>
				<!--end tab-pane-->
			</div>
			<!--end tab-content-->
		</div>
	</div>
	<!--end col-->
</div>
<!--end row-->


<script type="text/javascript">
	$(document).ready(function() {
		getProfile();
		getDataListProfile();
	});

	function getDataList() {
		getProfile();
	}

	async function getDataListProfile() {
		loading('#dataListProfileDiv', true);
		const res = await callApi('get', 'profile/list-profile-li-userid');

		if (isSuccess(res)) {
			$('#listProfile').html(res.data);
		}

		loading('#dataListProfileDiv', false);
	}

	async function getProfile() {
		const res = await callApi('get', 'user/show');

		if (isSuccess(res)) {
			const data = res.data;
			const roles = res.data.currentProfile.roles;
			$('#showFullNameMain').text(data.user_full_name);
			$('#showProfileNameMain').text(roles.role_name);
			$('#showFullName').text(data.user_full_name);
			$('#showMatricID').text(data.user_matric_code);
			$('#showNRIC').text(data.user_nric);
			$('#showGender').text(data.user_gender == 1 ? 'Male' : 'Female');
			$('#showMobileNo').text(data.user_contact_no);
			$('#showEmail').text(data.user_email);
			$('#username').val(data.user_username);
			$('#showCollege').text('(No Information)');

			$('#two_factor_status').prop('checked', parseInt(data.two_factor_status));

		}
	}

	async function updateProfilePhoto() {
		const res = await callApi('get', 'user/show');

		if (isSuccess(res)) {
			loadFileContent('profile/_uploadProfileModal.php', 'generalContent', 'xl', 'PROFILE UPLOAD', res.data, 'offcanvas');
		}
	}

	async function updateProfileDetails() {

		const res = await callApi('get', 'user/show');

		if (isSuccess(res)) {
			loadFormContent('user/_userForm.php', 'generalContent', '500px', 'user/save', 'UPDATE USER', res.data, 'offcanvas');
		}

	}

	function setDefaultProfile(userID, profileID, branchID, roleName) {

		Swal.fire({
			title: 'Are you sure?',
			html: "Set this <b>" + roleName + "</b> to default ?",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Confirm!',
			reverseButtons: true
		}).then(
			async (result) => {
				if (result.isConfirmed) {
					loading('#dataListProfileDiv', true);

					const res = await callApi('post', 'profile/set-default-profile', {
						user_id: userID,
						profile_id: profileID,
						branch_id: branchID
					});

					if (isSuccess(res)) {
						noti(res.status, 'Main profile set');
						await getDataListProfile();
						setTimeout(async function() {
							await getProfile();
						}, 50);
					}

					loading('#dataListProfileDiv', false);
				}
			})
	}

	$("#changePasswordForm").submit(function(event) {

		event.preventDefault();

		if (validateResetPassData()) {

			const form = $(this);
			const url = form.attr('action');

			var errorMessage = "";
			if (errorMessage = validatePassword()) {
				return noti(500, errorMessage);
			}

			Swal.fire({
				title: 'Are you sure?',
				html: "Password will be reset!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Confirm!',
				reverseButtons: true
			}).then(
				async (result) => {
					if (result.isConfirmed) {
						const res = await submitApi(url, form.serializeArray(), 'changePasswordForm');
						if (isSuccess(res)) {

							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Reset password');
								$('#changePasswordForm')[0].reset(); // reset previous form
							} else {
								noti(400, res.data.message)
							}

						}
					}
				});
		} else {
			validationJsError('toastr', 'single'); // single or multi
		}
	});

	function validateResetPassData() {

		const rules = {
			'oldpassword': 'required|min:4|max:20',
			'newpassword': 'required|min:8|max:20',
			'confirmpassword': 'required|min:8|max:20',
		};

		const message = {
			'oldpassword': 'Current Password',
			'newpassword': 'New Password',
			'confirmpassword': 'Confirm Password',
		};

		return validationJs(rules, message);
	}

	function validatePassword() {

		var newPass = $('#newpassword').val();
		var confirmPass = $('#confirmpassword').val();

		if (newPass != confirmPass)
			return 'Your confirm passwords does not match.';
		if (newPass.length < 8)
			return 'Your password must be at least 8 characters.';
		if (newPass.search(/[a-z]/) < 0)
			return "Your password must contain at least one lowercase letter.";
		if (newPass.search(/[0-9]/) < 0 && newPass.search(/[!@#$%^&* ]/) < 0)
			return "Your password must contain at least one digit or any symbol without whitespace.";
	}

	$("#changeUsernameForm").submit(function(event) {

		event.preventDefault();

		if (validateResetUsernameData()) {

			const form = $(this);
			const url = form.attr('action');

			Swal.fire({
				title: 'Are you sure?',
				html: "Username will be reset!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Confirm!',
				reverseButtons: true
			}).then(
				async (result) => {
					if (result.isConfirmed) {
						const res = await submitApi(url, form.serializeArray(), 'changeUsernameForm');
						if (isSuccess(res)) {

							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Reset username');
							} else {
								noti(400, res.data.message)
							}

						}
					}
				});
		} else {
			validationJsError('toastr', 'single'); // single or multi
		}
	});

	function validateResetUsernameData() {

		const rules = {
			'username': 'required|min:5|max:15',
		};

		const message = {
			'username': 'Username',
		};

		return validationJs(rules, message);
	}

	async function changeStatus2FA() {

		if ($('#two_factor_status').is(":checked")) {
			Swal.fire({
				title: 'Are you sure?',
				html: "Two-factor authentication (2FA) will be enable",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Confirm!',
				reverseButtons: true
			}).then(
				async (result) => {
					if (result.isConfirmed) {
						const res = await callApi('post', 'user/verify-2fa-status', {
							status: 1,
						});

						if (isSuccess(res.data.resCode)) {
							noti(res.status, 'Enable 2FA ');
							viewQr2FA();
						} else {
							noti(400, res.data.message)
						}
					} else {
						$('#two_factor_status').prop('checked', false);
					}
				})

		} else {

			// const {
			// 	value: password
			// } = await Swal.fire({
			// 	icon: 'warning',
			// 	title: 'Are you sure?',
			// 	html: 'Enter your password',
			// 	input: 'password',
			// 	inputPlaceholder: 'Enter your password here',
			// 	inputAttributes: {
			// 		maxlength: 50,
			// 		autocapitalize: 'off',
			// 		autocorrect: 'off'
			// 	},
			// 	showCancelButton: true,
			// 	inputValidator: (value) => {
			// 		// return new Promise((resolve) => {
			// 		// 	if (value === 'oranges') {
			// 		// 		resolve()
			// 		// 	} else {
			// 		// 		resolve('You need to select oranges :)')
			// 		// 	}
			// 		// })
			// 	}
			// })

			// if (password) {
			// 	Swal.fire(`You selected: ${password}`)
			// }

			const res = await callApi('post', 'user/verify-2fa-status', {
				status: 0,
			});

			if (isSuccess(res.data.resCode)) {
				noti(res.status, 'Disable 2FA ');
			}
		}


	}

	async function viewQr2FA() {
		if ($('#two_factor_status').is(":checked")) {
			const res = await callApi('get', 'user/view-qr-2fa');

			if (isSuccess(res)) {
				loadFileContent('profile/_qr2FA.php', 'generalContent', 'md', 'Two-factor authentication (QR CODE)', res.data);
			}
		} else {
			noti(400, 'Please enable 2FA first!')
		}
	}
</script>

@endsection