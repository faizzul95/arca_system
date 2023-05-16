<div id="updateFormAssignCollege" class="row">

	<form id="formStudentAssignUpdate" action="student/save" method="POST">

		<div class="row">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="fa fa-user label-icon"></i><strong> Student Information </strong>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12 col-sm-12 mb-2">
				<label class="form-label"> Student Name <span class="text-danger">*</span></label>
				<input type="text" id="user_full_name_update" name="user_full_name" maxlength="255" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-12 col-sm-12 mb-2">
				<label class="form-label"> Email <span class="text-danger">*</span></label>
				<input type="email" id="user_email_update" name="user_email" maxlength="250" class="form-control" autocomplete="off" required>
			</div>
		</div>

		<div class="row">
			<div class="col-lg-4 col-sm-12 mb-2">
				<label class="form-label"> Matric ID <span class="text-danger">*</span> <span id="errMessageStudMatric" class="text-danger float-end"></span> </label>
				<input type="text" id="user_matric_code_update" name="user_matric_code" maxlength="15" class="form-control" onkeypress="isNumeric();checkMatricExist(this.value)" autocomplete="off" required>
			</div>

			<div class="col-lg-4 col-sm-12 mb-2">
				<label class="form-label"> NRIC <span class="text-danger">*</span></label>
				<input type="text" id="user_nric_update" name="user_nric" maxlength="15" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
			</div>

			<div class="col-lg-4 col-sm-12 mb-2">
				<label class="form-label"> Contact No <span class="text-danger">*</span></label>
				<input type="text" id="user_contact_no_update" name="user_contact_no" maxlength="13" class="form-control" onkeypress="isNumeric()" autocomplete="off" required>
			</div>
		</div>

		<div class="row">

			<div class="col-12 mt-4">
				<div class="row">
					<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
						<i class="fa fa-info-circle label-icon"></i><strong> Additional Information </strong>
					</div>
				</div>

				<div class="row">
					<div class="col-3">
						<label class="form-label"> Disability <span class="text-danger">*</span></label>
						<select id="is_special_update" name="is_special" class="form-control">
							<option value="0" selected> No </option>
							<option value="1"> Yes </option>
						</select>
					</div>

					<div class="col-9">
						<label class="form-label"> Society Type <small>(Club/Association/Communities)</small> <span class="text-danger">*</span></label>
						<select id="has_position_update" name="has_position" class="form-control">
							<option value="" selected> None </option>
							<option value="1"> JPK </option>
							<option value="2"> MPP </option>
							<option value="3"> Athlete </option>
							<option value="4"> Uniform </option>
						</select>
					</div>
				</div>
			</div>

			<div class="col-12 mt-4">
				<div class="row">
					<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
						<i class="fa fa-graduation-cap label-icon"></i><strong> Education Information </strong>
					</div>
				</div>

				<div id="branchSelectInputUpdate" class="row mb-2" style="display: none;">
					<div class="col-12">
						<label class="form-label"> Branch <span class="text-danger">*</span></label>
						<select id="branch_id_update" name="branch_id" class="form-control selectSearchBranchUpdate" style="width: 100%;height: 100%">
							<option value=""> - Select - </option>
						</select>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-8 col-sm-12 mb-2">
						<label class="form-label"> Level <span class="text-danger">*</span></label>
						<select id="edu_level_id_update" name="edu_level_id" class="form-control" onchange="programSelect(this.value)" required>
							<option value=""> - Select - </option>
						</select>
					</div>
					<div class="col-lg-4 col-sm-12 mb-2">
						<label class="form-label"> Semester <span class="text-danger">*</span></label>
						<input type="text" id="semester_number_update" name="semester_number" maxlength="2" class="form-control" onkeypress="isNumeric()" autocomplete="off" required>
					</div>
				</div>

				<div class="row">
					<div class="col-12">
						<label class="form-label"> Programme <span class="text-danger">*</span></label>
						<select id="program_id_update" name="program_id" class="form-control selectSearchProgram" required>
							<option value=""> - Select - </option>
						</select>
					</div>
				</div>
			</div>

			<div class="col-12 mt-4">
				<div class="row">
					<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
						<i class="fa fa-university label-icon"></i><strong> College Information </strong>
					</div>
				</div>

				<div class="row">
					<div class="col-lg-4 col-sm-12 mb-2">
						<label class="form-label"> College <span class="text-danger">*</span></label>
						<select id="college_id_update" name="college_id" class="form-control" onchange="getListRoomNo()" required>
							<option value=""> - Select - </option>
						</select>
					</div>

					<div class="col-lg-4 col-sm-12 mb-2">
						<label class="form-label"> Room No. <span class="text-danger">*</span></label>
						<select id="college_room_id_update" name="college_room_id" class="form-control" required>
							<option value=""> - Select - </option>
						</select>
					</div>
					<div class="col-lg-4 col-sm-12 mb-2">
						<label class="form-label"> Bed No. <span class="text-danger">*</span></label>
						<input type="text" id="college_bed_no_update" name="college_bed_no" maxlength="8" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
					</div>
				</div>
			</div>

		</div>

		<div class="row mt-2">
			<div class="col-lg-12">
				<span class="text-danger">* Indicates a required field</span>
				<center class="mt-4">
					<input type="hidden" id="user_id_update" name="user_id" placeholder="branch_id" readonly>
					<input type="hidden" id="stud_id_update" name="stud_id" placeholder="branch_id" readonly>
					<input type="hidden" id="profile_id_update" name="profile_id" placeholder="profile_id" readonly>
					<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
				</center>
			</div>
		</div>

	</form>

	<input type="hidden" id="college_id_directory_update" placeholder="college_id" readonly>
	<input type="hidden" id="branch_id_directory_update" placeholder="branch_id" readonly>

</div>

<script>
	async function getPassData(baseUrl, token, data) {
		await branchSelect();

		loading('#updateFormAssignCollege', true);
		$('#college_id_directory_update').val(data['college_id']);
		$('#branch_id_directory_update').val(data['branch_id']);

		setTimeout(async function() {
			await collegeSelect();
			await levelSelect();
			await programSelect(data['user']['edu_level_id'], data['user']['program_id']);

			setTimeout(async function() {
				$('#user_matric_code_update').val(data['user']['user_matric_code']);
				$('#user_full_name_update').val(data['user']['user_full_name']);
				$('#user_nric_update').val(data['user']['user_nric']);
				$('#user_contact_no_update').val(data['user']['user_contact_no']);
				$('#user_email_update').val(data['user']['user_email']);
				$('#user_id_update').val(data['user_id']);
				$('#stud_id_update').val(data['stud_id']);

				$('#branch_id_update').val(data['branch_id']);
				$('#edu_level_id_update').val(data['user']['edu_level_id']);
				$('#college_id_update').val(data['college_id']);
				// $('#program_id').val(data['user']['program_id']);
				$('#semester_number_update').val(data['semester_number']);

				$('#college_bed_no_update').val(data['college_bed_no']);

				$('#profile_id_update').val(data['user']['profileStudent']['profile_id']);
				$('#is_special_update').val(data['user']['profileStudent']['is_special']);
				$('#has_position_update').val(data['user']['profileStudent']['has_position']);

				await collegeRoomNo();
				$('#college_room_id_update').val(data['college_room_id']);

				setTimeout(function() {
					loading('#updateFormAssignCollege', false);
				}, 400);

			}, 235);
		}, 150);

		if (data['role_id'] == 1) {
			$('#branchSelectInputUpdate').show();
		}

		$('#user_matric_code_update').attr('readonly', data['role_id'] != 1 ? true : false);
		$('#user_full_name_update').attr('readonly', data['role_id'] != 1 ? true : false);
		$('#user_nric_update').attr('readonly', data['role_id'] != 1 ? true : false);
		$('#user_contact_no_update').attr('readonly', data['role_id'] != 1 ? true : false);
		$('#user_email_update').attr('readonly', data['role_id'] != 1 ? true : false);
		$('#edu_level_id_update').attr('disabled', data['role_id'] != 1 ? true : false);
		$('#program_id_update').attr('disabled', data['role_id'] != 1 ? true : false);
	}

	async function programSelect(eduID = null, progID = null) {
		const res = await callApi('get', 'management/program-select/' + eduID);

		if (isSuccess(res)) {
			$('#program_id_update').html(res.data);
			if (progID != null) {
				setTimeout(function() {
					$('#program_id_update').val(progID);
				}, 50);
			}
		}
	}

	async function collegeSelect() {
		const res = await callApi('get', 'college/college-select');

		if (isSuccess(res)) {
			$('#college_id_update').html(res.data);
		}
	}

	async function branchSelect() {
		const res = await callApi('get', 'branch/branch-select');

		if (isSuccess(res)) {
			$('#branch_id_update').html(res.data);
			$('.selectSearchBranchUpdate').select2({
				width: 'resolve', // need to override the changed default
				dropdownParent: $("#offCanvasContent-right")
			});
		}
	}

	async function levelSelect() {
		const res = await callApi('get', 'management/education-select');

		if (isSuccess(res)) {
			$('#edu_level_id_update').html(res.data);
		}
	}

	async function checkMatricExist(matricID) {
		$('#errMessageStudMatric').empty();

		if (matricID.length > 6) {
			const res = await callApi('user/checkMatricCodeExist', {
				'user_matric_code': matricID,
			});

			if (isSuccess(res)) {
				if (res.data != null) {
					$('#errMessageStudMatric').text('Students are already registered');
				}
			}
		}
	}

	async function collegeRoomNo() {
		const res = await callApi('get', 'college/college-room-select/' + $('#college_id_update').val());

		if (isSuccess(res)) {
			$('#college_room_id_update').html(res.data);
		}
	}

	async function syncStudentData() {
		noti(500, 'Function sync student not ready yet!');
	}

	async function resetForm() {
		await programSelect();
		$('#formStudentAssignUpdate')[0].reset(); // reset previous form
	}

	function getListRoomNo() {
		collegeRoomNo();
		$('#college_bed_no_update').val(''); //reset
	}

	$("#formStudentAssignUpdate").submit(function(event) {
		event.preventDefault();

		if (validateDataEnroll()) {

			const form = $(this);
			const url = form.attr('action');

			Swal.fire({
				title: 'Are you sure?',
				html: "Form will be submitted!",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Confirm!',
				reverseButtons: true
			}).then(
				async (result) => {
					if (result.isConfirmed) {
						const res = await submitApi(url, form.serializeArray(), 'formStudentAssignUpdate');
						if (isSuccess(res)) {

							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Save');
								await getDataListEnroll();
								resetForm();
								setTimeout(function() {
									$('#generaloffcanvas-right').offcanvas('toggle');
								}, 200);
							} else {
								noti(400, res.data.message)
							}

						}
					}
				})

		} else {
			validationJsError('toastr', 'single'); // single or multi
		}
	});

	function validateDataEnroll() {

		const rules = {
			'user_matric_code': 'required|min:5|max:15',
			'user_full_name': 'required|min:5|max:250',
			'user_nric': 'required|min:6|max:15',
			'user_contact_no': 'required|min:10|max:15',
			'user_email': 'required|email|min:5|max:150',
			'semester_number': 'required|integer|min:1|min_length:1|max_length:2',
			'college_bed_no': 'required|min:1|max:10',
			'college_id': 'required|integer',
			'college_room_id': 'required|integer',
			'edu_level_id': 'required|integer',
			'program_id': 'required|integer',
			'user_id': 'required|integer',
			'stud_id': 'required|integer',
			'branch_id': 'required|integer',
			'profile_id': 'required|integer',
			'is_special': 'required|integer',
		};

		const message = {
			'user_matric_code': 'Matric ID',
			'user_full_name': 'Student Name',
			'user_nric': 'NRIC',
			'user_contact_no': 'Contact No',
			'user_email': 'Email',
			'semester_number': 'Semester',
			'college_bed_no': 'Bed No.',
			'college_id': 'College',
			'college_room_id': 'Room No.',
			'edu_level_id': 'Education Level',
			'program_id': 'Programme',
			'user_id': 'User ID',
			'stud_id': 'Student ID',
			'branch_id': 'Branch ID',
			'profile_id': 'Profile ID',
			'is_special': 'Disability',
		};

		return validationJs(rules, message);
	}
</script>