<form id="formFaculty" action="management/save-faculty" method="POST">

	<div class="row">
		<div class="col-12">
			<label class="form-label"> Faculty Name <span class="text-danger">*</span></label>
			<input type="text" id="faculty_name" name="faculty_name" maxlength="250" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-lg-6 col-sm-12 mt-2">
			<label class="form-label"> Code <span class="text-danger">*</span></label>
			<input type="text" id="faculty_code" name="faculty_code" maxlength="10" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>

		<div class="col-lg-6 col-sm-12 mt-2">
			<label class="form-label"> Status <span class="text-danger">*</span></label>
			<select id="faculty_status" name="faculty_status" class="form-control" required>
				<option value="1" selected> Active </option>
				<option value="0"> Inactive </option>
			</select>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-lg-12">
			<span class="text-danger">* Indicates a required field</span>
			<center class="mt-4">
				<input type="hidden" id="faculty_id" name="faculty_id" placeholder="faculty_id" readonly>
				<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
			</center>
		</div>
	</div>

</form>

<script>
	function getPassData(baseUrl, token, data) {}

	$("#formFaculty").submit(function(event) {
		event.preventDefault();

		if (validateDataFaculty()) {

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
						const res = await submitApi(url, form.serializeArray(), 'formFaculty');
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

	function validateDataFaculty() {

		const rules = {
			'faculty_name': 'required|min:5|max:255',
			'faculty_code': 'required|min:2|max:10',
			'faculty_status': 'required|integer|min_length:1|max_length:1|min:0|max:1',
		};

		const message = {
			'faculty_name': 'Faculty Name',
			'faculty_code': 'Code',
			'faculty_status': 'Status',
		};

		return validationJs(rules, message);
	}
</script>