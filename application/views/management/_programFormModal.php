<form id="formProgram" action="management/save-program" method="POST">

	<div class="row">
		<div class="col-12">
			<label class="form-label"> Program Name <span class="text-danger">*</span></label>
			<input type="text" id="program_name" name="program_name" maxlength="250" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-9 col-sm-12 mt-2">
			<label class="form-label"> Faculty <span class="text-danger">*</span></label>
			<select id="faculty_id" name="faculty_id" class="form-control" required>
				<option value=""> - Select - </option>
			</select>
		</div>

		<div class="col-lg-3 col-sm-12 mt-2">
			<label class="form-label"> Code <span class="text-danger">*</span></label>
			<input type="text" id="program_code" name="program_code" maxlength="10" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-9 col-sm-12 mt-2">
			<label class="form-label"> Education Level <span class="text-danger">*</span></label>
			<select id="edu_level_id" name="edu_level_id" class="form-control" required>
				<option value=""> - Select - </option>
			</select>
		</div>

		<div class="col-lg-3 col-sm-12 mt-2">
			<label class="form-label"> Status <span class="text-danger">*</span></label>
			<select id="program_status" name="program_status" class="form-control" required>
				<option value="1" selected> Active </option>
				<option value="0"> Inactive </option>
			</select>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-lg-12">
			<span class="text-danger">* Indicates a required field</span>
			<center class="mt-4">
				<input type="hidden" id="program_id" name="program_id" placeholder="program_id" readonly>
				<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
			</center>
		</div>
	</div>

</form>

<script>
	async function getPassData(baseUrl, token, data) {
		await getEducationLevel(data);
		await getFaculty(data);
	}

	async function getEducationLevel(data = null) {
		var eduID = (data != null) ? data['edu_level_id'] : null;

		const res = await callApi('get', 'management/education-select');

		if (isSuccess(res)) {
			$('#edu_level_id').html(res.data);
			$('#edu_level_id').val(eduID);
		}
	}

	async function getFaculty(data = null) {
		var facultyID = (data != null) ? data['faculty_id'] : null;

		const res = await callApi('get', 'management/faculty-select');

		if (isSuccess(res)) {
			$('#faculty_id').html(res.data);
			$('#faculty_id').val(facultyID);
		}
	}

	$("#formProgram").submit(function(event) {
		event.preventDefault();

		if (validateDataEducation()) {

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
						const res = await submitApi(url, form.serializeArray(), 'formProgram');
						if (isSuccess(res)) {

							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Save');
								getDataList();
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

	function validateDataEducation() {

		const rules = {
			'program_name': 'required|min:10|max:250',
			'program_code': 'required|min:2|max:10',
			'program_status': 'required|integer|min_length:1|max_length:1|min:0|max:1',
			'edu_level_id': 'required|integer|min:1',
			'faculty_id': 'required|integer|min:1',
		};

		const message = {
			'program_name': 'Program Name',
			'program_code': 'Code',
			'program_status': 'Status',
			'edu_level_id': 'Education Level',
			'faculty_id': 'Faculty',
		};

		return validationJs(rules, message);
	}
</script>