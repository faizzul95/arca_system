<form id="formCollege" action="college/save-college" method="POST">

	<div class="row">
		<div class="col-12">
			<label class="form-label"> College Name <span class="text-danger">*</span></label>
			<input type="text" id="college_name" name="college_name" maxlength="100" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">

		<div class="col-6">
			<label class="form-label"> Code <span class="text-danger">*</span></label>
			<input type="text" id="college_code" name="college_code" maxlength="10" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>

		<div class="col-6">
			<label class="form-label"> Capacity <span class="text-danger">*</span></label>
			<input type="text" id="college_capacity" name="college_capacity" maxlength="5" class="form-control" onkeypress="isNumeric()" autocomplete="off" required>
		</div>

	</div>

	<div class="row mt-2">

		<div class="col-6">
			<label class="form-label"> Gender Prefer <span class="text-danger">*</span></label>
			<select id="college_gender_prefer" name="college_gender_prefer" class="form-control" required>
				<option value="" selected> - Select - </option>
				<option value="1"> Male </option>
				<option value="2"> Female </option>
				<option value="3"> Other </option>
			</select>
		</div>

		<div class="col-6">
			<label class="form-label"> Level Prefer <span class="text-danger">*</span></label>
			<select id="college_level_prefer" name="college_level_prefer" class="form-control" required>
				<option value="" selected> - Select - </option>
				<option value="1"> Pra Diploma </option>
				<option value="2"> Diploma </option>
				<option value="3"> Degree </option>
				<option value="4"> Master </option>
				<option value="5"> Others </option>
			</select>
		</div>

	</div>

	<div class="row mt-2">
		<div class="col-12">
			<label class="form-label"> Status <span class="text-danger">*</span></label>
			<select id="college_status" name="college_status" class="form-control" required>
				<option value="1" selected> Active </option>
				<option value="0"> Inactive </option>
			</select>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-lg-12">
			<span class="text-danger">* Indicates a required field</span>
			<center class="mt-4">
				<input type="hidden" id="college_id" name="college_id" placeholder="college_id" readonly>
				<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
			</center>
		</div>
	</div>

</form>

<script>
	function getPassData(baseUrl, token, data) {}

	$("#formCollege").submit(function(event) {
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
						const res = await submitApi(url, form.serializeArray(), 'formCollege');
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
			'college_name': 'required|min:3|max:100',
			'college_code': 'required|min:2|max:10',
			'college_status': 'required|integer',
			'college_capacity': 'required|integer',
			'college_gender_prefer': 'required|integer',
			'college_level_prefer': 'required|integer',
		};

		const message = {
			'college_name': 'Level Name',
			'college_code': 'Code',
			'college_status': 'Status',
			'college_capacity': 'Capacity',
			'college_gender_prefer': 'Gender Prefer',
			'college_level_prefer': 'Level Prefer',
		};

		return validationJs(rules, message);
	}
</script>