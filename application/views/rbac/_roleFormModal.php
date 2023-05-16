<form id="formRoles" action="roles/save" method="POST">

	<div class="row">
		<div class="col-lg-12">
			<label class="form-label"> Name <span class="text-danger">*</span></label>
			<input type="text" id="role_name" name="role_name" maxlength="100" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">

		<div class="col-lg-6 col-sm-6">
			<label class="form-label"> Code <span class="text-danger">*</span></label>
			<input type="text" id="role_code" name="role_code" maxlength="10" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>

		<div class="col-lg-6 col-sm-6">
			<label class="form-label"> Status <span class="text-danger">*</span></label>
			<select id="role_status" name="role_status" class="form-control" required>
				<option value="1" selected> Active </option>
				<option value="0"> Inactive </option>
			</select>
		</div>

	</div>

	<div class="row mt-2">
		<div class="col-lg-12">
			<span class="text-danger">* Indicates a required field</span>
			<center class="mt-4">
				<input type="hidden" id="role_id" name="role_id" placeholder="role_id" readonly>
				<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
			</center>
		</div>
	</div>

</form>

<script>
	// get data array from general function
	function getPassData(baseUrl, token, data) {}

	$("#formRoles").submit(function(event) {
		event.preventDefault();

		if (validateDataRole()) {

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
						const res = await submitApi(url, form.serializeArray(), 'formRoles');
						if (isSuccess(res)) {
							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Save');
								getDataListRole();
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

	function validateDataRole() {

		const rules = {
			'role_name': 'required|min:3|max:100',
			'role_code': 'required|min:2|max:10',
			'role_status': 'required|integer',
		};

		const message = {
			'role_name': 'Name',
			'role_code': 'Code',
			'role_status': 'Status',
		};

		return validationJs(rules, message);
	}
</script>