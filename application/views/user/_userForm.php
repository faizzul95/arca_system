<form id="formUser" action="user/save" method="POST">

	<div id="branchSelect" class="row mb-2" style="display: none;">
		<div class="col-12">
			<label class="form-label"> Branch <span class="text-danger">*</span></label>
			<select id="branch_id" name="branch_id" class="form-control">
				<option value=""> - Select - </option>
			</select>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<div class="form-group">
				<label> Name <span class="text-danger">*</span> </label>
				<input type="text" id="user_full_name" name="user_full_name" class="form-control" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" maxlength="200" required>
			</div>
		</div>
	</div>

	<div class="row  mt-2">
		<div class="col-12">
			<div class="form-group">
				<label> Preferred Name <span class="text-danger">*</span> </label>
				<input type="text" id="user_preferred_name" name="user_preferred_name" class="form-control" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" maxlength="10" required>
			</div>
		</div>
	</div>

	<div class="row mt-2">

		<div class="col-6">
			<div class="form-group">
				<label> NRIC / Passport <span class="text-danger">*</span> </label>
				<input type="text" id="user_nric" name="user_nric" class="form-control" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" maxlength="15" required>
			</div>
		</div>

		<div class="col-6">
			<div class="form-group">
				<label> Matric ID <span class="text-danger">*</span> </label>
				<input type="text" id="user_matric_code" name="user_matric_code" class="form-control" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" maxlength="15" required>
			</div>
		</div>

	</div>

	<div class="row mt-2">
		<div class="col-12">
			<div class="form-group">
				<label> Email <span class="text-danger">*</span> </label>
				<input type="email" id="user_email" name="user_email" class="form-control" autocomplete="off" maxlength="150" required>
			</div>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-6">
			<div class="form-group">
				<label> Gender <span class="text-danger">*</span> </label>
				<select id="user_gender" name="user_gender" class="form-control" required>
					<option value=""> - SELECT - </option>
					<option value="1"> Male </option>
					<option value="2"> Female </option>
				</select>
			</div>
		</div>

		<div class="col-6">
			<div class="form-group">
				<label> Mobile No <span class="text-danger">*</span> </label>
				<input type="text" id="user_contact_no" name="user_contact_no" class="form-control" autocomplete="off" maxlength="15" onkeypress="isNumeric()" required>
			</div>
		</div>
	</div>

	<div class="row mt-4">
		<div class="col-lg-12">
			<span class="text-danger mb-2">* Indicates a required field</span>
			<center class="mt-4">
				<input type="hidden" id="user_id" name="user_id" class="form-control" readonly>
				<button type="submit" id="submitBtn" class="btn btn-success"> <i class='fa fa-save'></i> Save </button>
			</center>
		</div>
	</div>

</form>

<script>
	async function getPassData(baseUrl, token, data) {

		await branchSelect();

		if (data != null) {
			var role = data.sessionUser.roleID;
			$("#branch_id").val(data.currentSelectBranch);

			if (role == 1) {
				$('#branchSelect').show();
			}

			if (role != 1 && role != 2) {
				$('#user_full_name').attr('readonly', true);
				$('#user_preferred_name').attr('readonly', true);
				$('#user_nric').attr('readonly', true);
				$('#user_matric_code').attr('readonly', true);
				$('#user_gender').attr('readonly', true);
				$('#user_gender').css('pointer-events', 'none');
			}
		}
	}

	async function branchSelect() {
		const res = await callApi('get', 'branch/branch-select');

		if (isSuccess(res)) {
			$('#branch_id').html(res.data);
		}
	}

	$("#formUser").submit(function(event) {
		event.preventDefault();

		if (validateData()) {

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
						const res = await submitApi(url, form.serializeArray(), 'formUser');
						if (isSuccess(res)) {
							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Save');
								getDataList();
							} else {
								noti(500, res.data.message)
							}
						}
					}
				});
		} else {
			validationJsError('toastr', 'single'); // single or multi
		}
	});

	function validateData() {

		const rules = {
			'user_full_name': 'required|min:5|max:255',
			'user_preferred_name': 'required|min:3|max:10',
			'user_nric': 'required|min:3|max:15',
			'user_email': 'required|email|min:5|max:150',
			'user_contact_no': 'required|integer|min_length:10|max_length:15',
			'user_matric_code': 'required|min:4|max:15',
			'user_gender': 'required|integer',
			'branch_id': 'required|integer',
		};

		const message = {
			'user_full_name': 'Full Name',
			'user_preferred_name': 'Preferred Name',
			'user_nric': 'NRIC',
			'user_email': 'Email',
			'user_contact_no': 'Mobile No',
			'user_matric_code': 'Matric Code',
			'user_gender': 'Gender',
			'branch_id': 'Branch ID',
		};

		return validationJs(rules, message);
	}
</script>