<div class="row mb-2">
	<div class="col-12">
		<button id="addForm" type="button" class="btn btn-info btn-sm float-end" onclick="showForm(1)" title="Add Profile">
			<i class="ri-add-fill"></i> Add Profile
		</button>
		<button id="closeForm" type="button" class="btn btn-danger btn-sm float-end" onclick="showForm(0)" title="Close form" style="display: none;">
			<i class="ri-close-circle-line"></i> Close
		</button>
	</div>
</div>

<div id="branchSelectInput" class="row mb-4" style="display: none;">
	<div class="alert alert-primary" role="alert">
		<h6 class="alert-heading fw-bold mb-0"> Branch </h6>
	</div>

	<div class="col-12">
		<select id="branch_id_assign" class="form-control" onchange="roleSelect()">
			<option value=""> - Select - </option>
		</select>
	</div>
</div>

<div class="row mb-4" id="profile" style="display: none;">
	<div class="alert alert-primary" role="alert">
		<h6 class="alert-heading fw-bold mb-0"> Register Profile </h6>
	</div>

	<form id="formProfile" action="profile/save" method="POST">

		<div class="row mt-2">
			<div class="col-12">
				<label class="form-label"> Profile <span class="text-danger">*</span></label>
				<select id="role_profile_id" name="role_id" class="form-control" onchange="showDetail(this.value)" required>
					<option value=""> - Select - </option>
				</select>
				<input type="hidden" id="branch_id_assignform" name="branch_id" class="form-control" placeholder="branch_id">
			</div>
		</div>

		<div id="collegeSelect" class="row mt-2" style="display: none;">
			<div class="col-12">
				<label class="form-label"> College <span class="text-danger">*</span></label>
				<select id="college_id" name="college_id" class="form-control">
					<option value=""> - Select - </option>
				</select>
			</div>
		</div>

		<div id="studentSelect" class="row mt-2" style="display: none;">

			<div class="col-12">
				<label class="form-label"> Level <span class="text-danger">*</span></label>
				<select id="edu_level_id" name="edu_level_id" onchange="programSelect(this.value)" class="form-control">
					<option value=""> - Select - </option>
				</select>
			</div>

			<div class="col-12 mt-2">
				<label class="form-label"> Program <span class="text-danger">*</span></label>
				<select id="program_id" name="program_id" class="form-control selectSearchProgram">
					<option value=""> - Select - </option>
				</select>
			</div>

			<div class="col-6 mt-2">
				<label class="form-label"> Intake <span class="text-danger">*</span></label>
				<input type="text" id="user_intake" name="user_intake" class="form-control" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" maxlength="20">
			</div>

			<div class="col-6 mt-2">
				<label class="form-label"> Disability <span class="text-danger">*</span></label>
				<select id="is_special" name="is_special" class="form-control">
					<option value="0" selected> No </option>
					<option value="1"> Yes </option>
				</select>
			</div>

			<div class="col-12 mt-2">
				<label class="form-label"> Society Type (Club/Association/Communities) <span class="text-danger">*</span></label>
				<select id="has_position" name="has_position" class="form-control">
					<option value="" selected> None </option>
					<option value="1"> JPK </option>
					<option value="2"> MPP </option>
					<option value="3"> Athlete </option>
					<option value="4"> Uniform </option>
				</select>
			</div>

		</div>

		<div class="row mt-2 mb-2">
			<div class="col-lg-12">
				<span class="text-danger">* Indicates a required field</span>
				<center class="mt-4">
					<input type="hidden" id="user_id_profile" name="user_id" placeholder="user_id" readonly>
					<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
				</center>
			</div>
		</div>

	</form>
</div>

<div class="row">

	<input type="hidden" id="user_id_master" name="user_id" placeholder="college_id" readonly>

	<div class="alert alert-primary" role="alert">
		<h6 class="alert-heading fw-bold mb-0"> List Profile </h6>
	</div>

	<div class="col-12">
		<div id="nodataProfileDiv" style="display: none;"> </div>
		<div id="dataListProfileDiv" style="display: none;">
			<table id="dataListProfile" class="table nowrap dt-responsive align-middle table-hover table-bordered dataTable no-footer dtr-inline collapsed" width="100%">
				<thead class="table-dark">
					<tr>
						<th> Profile </th>
						<th> Status </th>
						<th> Action </th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>
	</div>

</div>

<script>
	async function getPassData(baseUrl, token, data) {

		showForm(0);
		$('#user_id_profile').val(data['user_id']);
		$('#user_id_master').val(data['user_id']);
		$('#nodataProfileDiv').html(nodata());

		await branchSelect();
		$("#branch_id_assign").val(data.branch_id);

		if (data['role_id'] == 1) {
			$('#branchSelectInput').show();
		}

		await roleSelect();

		setTimeout(async function() {
			await collegeSelect();
			await levelSelect();
		}, 400);
	}

	async function getDataListProfile() {
		loading('#dataListProfileDiv', true);
		generateDatatable('dataListProfile', 'serverside', 'profile/list-profile-userid', 'nodataProfileDiv', {
			user_id: $('#user_id_master').val(),
			branch_id: $("#branch_id_assign").val()
		});
		loading('#dataListProfileDiv', false);
	}

	function showForm(type) {
		if (type == 1) {
			$('#addForm').hide();
			$('#closeForm').show();
			$('#profile').show();
		} else {
			$('#addForm').show();
			$('#closeForm').hide();
			$('#profile').hide();
			$('#studentSelect').hide();
			$('#collegeSelect').hide();
			$('#role_profile_id').val(''); // reset selection
		}
	}

	function showDetail(roleID) {

		$('#collegeSelect').hide();
		$('#studentSelect').hide();

		$("#college_id").prop('required', false);
		$("#edu_level_id").prop('required', false);
		$("#program_id").prop('required', false);
		$("#user_intake").prop('required', false);
		$("#is_special").prop('required', false);

		if (roleID == 4) {
			$('#collegeSelect').show();
			$("#college_id").prop('required', true);
		} else if (roleID == 6) {
			$('#studentSelect').show();
			$("#program_id").val('');
			$("#edu_level_id").val('');
			$("#user_intake").val('');
			$("#is_special").val(0);
			$("#has_position").val('');

			$("#program_id").prop('required', true);
			$("#edu_level_id").prop('required', true);
			$("#user_intake").prop('required', true);
			$("#is_special").prop('required', true);
		}
	}

	async function roleSelect() {

		showDetail(0);
		$('#branch_id_assignform').val($('#branch_id_assign').val()); // set hidden value

		const res = await callApi('post', 'profile/select-profile-userid', {
			user_id: $('#user_id_master').val(),
			branch_id: $('#branch_id_assign').val()
		});

		if (isSuccess(res)) {
			$('#role_profile_id').html(res.data);
			await getDataListProfile();
		}
	}

	async function collegeSelect() {
		const res = await callApi('get', 'college/college-select/' + $('#branch_id_assign').val());

		if (isSuccess(res)) {
			$('#college_id').html(res.data);
		}
	}

	async function programSelect(eduID = NULL) {
		const res = await callApi('get', 'management/program-select/' + eduID + '/' + $('#branch_id_assign').val());

		if (isSuccess(res)) {
			$('#program_id').css("width", "100%");
			$('#program_id').css("height", "100%");
			$('#program_id').html(res.data);
			$('.selectSearchProgram').select2({
				width: 'resolve', // need to override the changed default
				dropdownParent: $("#generaloffcanvas-right")
			});
		}
	}

	async function branchSelect() {
		const res = await callApi('get', 'branch/branch-select');

		if (isSuccess(res)) {
			$('#branch_id_assign').html(res.data);
		}
	}

	async function levelSelect() {
		const res = await callApi('get', 'management/education-select');

		if (isSuccess(res)) {
			$('#edu_level_id').html(res.data);
		}
	}

	$("#formProfile").submit(function(event) {
		event.preventDefault();

		if (validateProfile()) {

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
						const res = await submitApi(url, form.serializeArray(), 'formProfile', null, false);
						if (isSuccess(res)) {

							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Assign');
								document.getElementById("formProfile").reset();
								await getDataList();
								setTimeout(async function() {
									await roleSelect();
								}, 100);
								showForm(0);
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

	function validateProfile() {

		const rules = {
			'branch_id': 'required|integer',
			'role_id': 'required|integer',
			'college_id': 'integer',
			'edu_level_id': 'integer',
			'program_id': 'integer',
			'user_intake': 'string|min:5|max:20',
			'is_special': 'integer',
			'has_position': 'integer',
		};

		const message = {
			'branch_id': 'Branch',
			'role_id': 'Profile',
			'college_id': 'College',
			'edu_level_id': 'Level',
			'program_id': 'Program',
			'user_intake': 'Intake',
			'is_special': 'Disability',
			'has_position': 'Society Type',
		};

		return validationJs(rules, message);
	}

	async function deleteProfileRecord(id) {
		Swal.fire({
			title: 'Are you sure?',
			html: "You won't be able to revert this!",
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
					const res = await deleteApi(id, 'profile/delete', getDataList);
					if (isSuccess(res)) {
						setTimeout(async function() {
							await roleSelect();
						}, 50);
					} else {
						noti(res.status);
					}
					loading('#dataListProfileDiv', false);
				}
			})
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
						await getDataList();
						setTimeout(async function() {
							await roleSelect();
						}, 40);
					}

					loading('#dataListProfileDiv', false);
				}
			})
	}
</script>