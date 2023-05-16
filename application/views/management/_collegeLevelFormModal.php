<form id="formCollegeLevel" action="management/save-college-level" method="POST">

	<div class="row">
		<div class="col-12">
			<label class="form-label"> Level Name <span class="text-danger">*</span></label>
			<input type="text" id="college_level_name" name="college_level_name" maxlength="20" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-12">
			<label class="form-label"> Code <span class="text-danger">*</span></label>
			<input type="text" id="college_level_code" name="college_level_code" maxlength="10" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-12">
			<label class="form-label"> Status <span class="text-danger">*</span></label>
			<select id="college_level_status" name="college_level_status" class="form-control" required>
				<option value="1" selected> Active </option>
				<option value="0"> Inactive </option>
			</select>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-lg-12">
			<span class="text-danger">* Indicates a required field</span>
			<center class="mt-4">
				<input type="hidden" id="college_level_id" name="college_level_id" placeholder="college_level_id" readonly>
				<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
			</center>
		</div>
	</div>

</form>

<script>
	function getPassData(baseUrl, token, data) {}

	$("#formCollegeLevel").submit(function(event) {
		event.preventDefault();

		if (validateDataCollegeLevel()) {

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
						const res = await submitApi(url, form.serializeArray(), 'formCollegeLevel');
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

	function validateDataCollegeLevel() {

		const rules = {
			'college_level_name': 'required|min:3|max:20',
			'college_level_code': 'required|min:2|max:10',
			'college_level_status': 'required|integer|min_length:1|max_length:1|min:0|max:1',
		};

		const message = {
			'college_level_name': 'Level Name',
			'college_level_code': 'Code',
			'college_level_status': 'Status',
		};

		return validationJs(rules, message);
	}
</script>