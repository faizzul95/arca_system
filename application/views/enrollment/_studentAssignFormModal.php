<style>
	.pointer {
		pointer-events: auto !important;
	}
</style>

<div class="row">

	<div class="col-lg-6 col-md-12 fill border-right p-4">
		<div class="row">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="fa fa-users label-icon"></i>
				<strong> Student Directory </strong>
			</div>
		</div>

		<div class="row mb-t">
			<div class="col-12">
				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataListStudent()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>
				<button id="directoryBtn" type="button" class="btn btn-info btn-sm float-end me-2" onclick="syncStudentData()" title="Sync data from UiTM">
					<i class="ri-refresh-line"></i> Sync Student
				</button>
			</div>
		</div>

		<div class="row mt-2">

			<div class="col-12">
				<div id="nodataStudentDiv" style="display: none;"> </div>
				<div id="dataListStudentDiv" style="display: none;">
					<table id="dataListStudent" class="table nowrap dt-responsive align-middle table-hover table-bordered dataTable no-footer dtr-inline collapsed" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Student </th>
								<th> Matric ID </th>
								<th> Program </th>
								<th> Gender </th>
								<th> Level </th>
								<th> Action </th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>

		</div>
	</div>

	<div class="col-lg-6 col-md-12 fill border-right p-4">
		<div class="row">
			<form id="formStudentAssign" action="student/save" method="POST">

				<div class="row">

					<!-- Student Information -->
					<div class="col-lg-6 col-sm-12">
						<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
							<i class="fa fa-user label-icon"></i><strong> Student Information </strong>
						</div>

						<div class="row">
							<div class="col-lg-12 col-sm-12 mb-2">
								<label class="form-label"> Student Name <span class="text-danger">*</span></label>
								<input type="text" id="user_full_name" name="user_full_name" maxlength="255" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
							</div>
						</div>

						<div class="row">

							<div class="col-lg-6 col-sm-12 mb-2">
								<label class="form-label"> Matric ID <span class="text-danger">*</span> <span id="errMessageStudMatric" class="text-danger float-end"></span> </label>
								<input type="text" id="user_matric_code" name="user_matric_code" maxlength="15" class="form-control" onkeypress="isNumeric();checkMatricExist(this.value)" autocomplete="off" required>
							</div>

							<div class="col-lg-6 col-sm-12 mb-2">
								<label class="form-label"> NRIC <span class="text-danger">*</span></label>
								<input type="text" id="user_nric" name="user_nric" maxlength="15" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
							</div>

							<div class="col-lg-6 col-sm-12 mb-2">
								<label class="form-label"> Contact No <span class="text-danger">*</span></label>
								<input type="text" id="user_contact_no" name="user_contact_no" maxlength="13" class="form-control" onkeypress="isNumeric()" autocomplete="off" required>
							</div>

							<div class="col-lg-6 col-sm-12 mb-2">
								<label class="form-label"> Email <span class="text-danger">*</span></label>
								<input type="email" id="user_email" name="user_email" maxlength="250" class="form-control" autocomplete="off" required>
							</div>

						</div>

					</div>

					<!-- Education Information -->
					<div class="col-lg-6 col-sm-12">
						<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
							<i class="fa fa-graduation-cap label-icon"></i><strong> Education Information </strong>
						</div>

						<div id="branchSelectInput" class="row mb-2" style="display: none;">
							<div class="col-12">
								<label class="form-label"> Branch <span class="text-danger">*</span></label>
								<select id="branch_id" name="branch_id" class="form-control selectSearchBranch" style="width: 100%;height: 100%">
									<option value=""> - Select - </option>
								</select>
							</div>
						</div>

						<div class="row">
							<div class="col-lg-8 col-sm-12 mb-2">
								<label class="form-label"> Level <span class="text-danger">*</span></label>
								<select id="edu_level_id" name="edu_level_id" class="form-control" onchange="programSelect(this.value)" required>
									<option value=""> - Select - </option>
								</select>
							</div>
							<div class="col-lg-4 col-sm-12 mb-2">
								<label class="form-label"> Semester <span class="text-danger">*</span></label>
								<input type="text" id="semester_number" name="semester_number" maxlength="2" class="form-control" onkeypress="isNumeric()" autocomplete="off" required>
							</div>
						</div>

						<div class="row">
							<div class="col-12">
								<label class="form-label"> Programme <span class="text-danger">*</span></label>
								<select id="program_id" name="program_id" class="form-control selectSearchProgram" required>
									<option value=""> - Select - </option>
								</select>
							</div>
						</div>
					</div>

				</div>

				<div class="row mt-4">

					<!-- Additional Information -->
					<div class="col-6">
						<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
							<i class="fa fa-info-circle label-icon"></i><strong> Additional Information </strong>
						</div>

						<div class="row">
							<div class="col-lg-6 col-sm-12 mb-2">
								<label class="form-label"> Disability <span class="text-danger">*</span></label>
								<select id="is_special" name="is_special" class="form-control">
									<option value="0" selected> No </option>
									<option value="1"> Yes </option>
								</select>
							</div>
							<div class="col-lg-6 col-sm-12 mb-2">
								<label class="form-label"> Society Type <span class="text-danger">*</span></label>
								<select id="has_position" name="has_position" class="form-control">
									<option value="" selected> None </option>
									<option value="1"> JPK </option>
									<option value="2"> MPP </option>
									<option value="3"> Athlete </option>
									<option value="4"> Uniform </option>
								</select>
							</div>
						</div>
					</div>

					<!-- College Information -->
					<div class="col-6">
						<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
							<i class="fa fa-university label-icon"></i><strong> College Information </strong>
						</div>

						<div class="row">
							<div class="col-lg-6 col-sm-12 mb-2">
								<label class="form-label"> Room No. <span class="text-danger">*</span></label>
								<select id="college_room_id" name="college_room_id" class="form-control" required>
									<option value=""> - Select - </option>
								</select>
							</div>
							<div class="col-lg-6 col-sm-12 mb-2">
								<label class="form-label"> Bed No. <span class="text-danger">*</span></label>
								<input type="text" id="college_bed_no" name="college_bed_no" maxlength="8" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
							</div>
						</div>
					</div>

				</div>


				<div class="row mt-2">
					<div class="col-lg-12">
						<span class="text-danger">* Indicates a required field</span>
						<center class="mt-4">
							<input type="hidden" id="college_id" name="college_id" placeholder="college_id" readonly>
							<input type="hidden" id="user_id" name="user_id" placeholder="user_id" readonly>
							<input type="hidden" id="profile_id" name="profile_id" placeholder="profile_id" readonly>
							<button type="button" id="resetBtn" class="btn btn-soft-danger mr-2" onclick="resetForm()"> Reset </button>
							<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
						</center>
					</div>
				</div>

			</form>
		</div>
	</div>

	<input type="hidden" id="college_id_directory" placeholder="college_id" readonly>
	<input type="hidden" id="branch_id_directory" placeholder="branch_id" readonly>

</div>

<script>
	async function getPassData(baseUrl, token, data) {
		await branchSelect();

		if (data['permission'] == true)
			$('#directoryBtn').show();
		else
			$('#directoryBtn').hide();

		$('#college_id_directory').val(data['college_id']);
		$('#branch_id_directory').val(data['branch_id']);
		$('#nodataStudentDiv').html(nodata());
		await getDataListStudent();

		setTimeout(async function() {
			await levelSelect();
			await collegeRoomNo()
		}, 150);

		if (data['role_id'] == 1) {
			$('#branchSelectInput').show();
		}

		$('#user_matric_code').attr('readonly', data['role_id'] != 1 ? true : false);
		$('#user_full_name').attr('readonly', data['role_id'] != 1 ? true : false);
		$('#user_nric').attr('readonly', data['role_id'] != 1 ? true : false);
		$('#user_contact_no').attr('readonly', data['role_id'] != 1 ? true : false);
		$('#user_email').attr('readonly', data['role_id'] != 1 ? true : false);
		$('#edu_level_id').attr('disabled', data['role_id'] != 1 ? true : false);
		$('#program_id').attr('disabled', data['role_id'] != 1 ? true : false);
	}

	async function getDataListStudent() {
		loading('#dataListStudentDiv', true);
		generateDatatable('dataListStudent', 'serverside', 'student/list-directory', 'nodataStudentDiv', {
			college_id: $('#college_id_directory').val(),
			branch_id: $('#branch_id_directory').val()
		});
		loading('#dataListStudentDiv', false);
	}

	async function addEnrollRecord(user_id) {

		$('#formStudentAssign')[0].reset(); // reset previous form
		loading('#dataListStudentDiv', true);
		const res = await callApi('get', 'student/show-user/' + user_id);

		if (isSuccess(res)) {
			const userData = res.data.userData;
			const lastSemesterData = res.data.lastSemesterData;

			$('#user_matric_code').val(userData.user_matric_code);
			$('#user_full_name').val(userData.user_full_name);
			$('#user_nric').val(userData.user_nric);
			$('#user_contact_no').val(userData.user_contact_no);
			$('#user_email').val(userData.user_email);
			$('#user_id').val(userData.user_id);
			$('#college_id').val($('#college_id_directory').val());

			$('#branch_id').val(userData.branch_id);
			$('#edu_level_id').val(userData.edu_level_id);

			await programSelect(userData.edu_level_id, userData.program_id);
			$('#semester_number').val(lastSemesterData != null ? parseInt(lastSemesterData.semester_number) + 1 : null);
			// $('#college_room_id').val(lastSemesterData != null ? lastSemesterData.college_room_id : null);
			// $('#college_bed_no').val(lastSemesterData != null ? lastSemesterData.college_bed_no : null);

			$('#profile_id').val(userData['profileStudent']['profile_id']);
			$('#is_special').val(userData['profileStudent']['is_special']);
			$('#has_position').val(userData['profileStudent']['has_position']);

			loading('#dataListStudentDiv', false);
		}
	}

	async function programSelect(eduID = null, progID = null) {
		const res = await callApi('get', 'management/program-select/' + eduID);

		if (isSuccess(res)) {
			$('#program_id').html(res.data);
			if (progID != null) {
				setTimeout(function() {
					$('#program_id').val(progID);
				}, 50);
			}
		}
	}

	async function branchSelect() {
		const res = await callApi('get', 'branch/branch-select');

		if (isSuccess(res)) {
			$('#branch_id').html(res.data);
			$('.selectSearchBranch').select2({
				width: 'resolve', // need to override the changed default
				dropdownParent: $("#generalContent-fullscreen")
			});
		}
	}

	async function levelSelect() {
		const res = await callApi('get', 'management/education-select');

		if (isSuccess(res)) {
			$('#edu_level_id').html(res.data);
		}
	}

	async function checkMatricExist(matricID) {
		$('#errMessageStudMatric').empty();

		if (matricID.length > 6) {
			const res = await callApi('post', 'user/check-matric-code', {
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
		const res = await callApi('get', 'college/college-room-select/' + $('#college_id_directory').val());

		if (isSuccess(res)) {
			$('#college_room_id').html(res.data);
		}
	}

	async function syncStudentData() {
		noti(500, 'Function sync student not ready yet!');
	}

	async function resetForm() {
		$('#formStudentAssign')[0].reset(); // reset previous form
		setTimeout(async function() {
			await programSelect();
		}, 50);
	}

	$("#formStudentAssign").submit(function(event) {
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
						const res = await submitApi(url, form.serializeArray(), 'formStudentAssign');

						if (isSuccess(res)) {

							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Save');
								await getDataListStudent();
								resetForm();

								setTimeout(async function() {
									await getDataListEnroll();
								}, 80);

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
			'branch_id': 'required|integer',
			'profile_id': 'required|integer',
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
			'branch_id': 'Branch ID',
			'profile_id': 'Profile ID',
		};

		return validationJs(rules, message);
	}
</script>