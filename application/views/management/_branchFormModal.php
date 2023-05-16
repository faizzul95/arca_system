<form id="formBranch" action="branch/save" method="POST">

	<div class="row">
		<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
			<i class="fa fa-building-o label-icon"></i><strong> Branch Information</strong>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<label class="form-label"> Branch Name <span class="text-danger">*</span></label>
			<input type="text" id="branch_name" name="branch_name" maxlength="255" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-12">
			<label class="form-label"> Email <span class="text-danger">*</span></label>
			<input type="email" id="branch_email" name="branch_email" maxlength="250" class="form-control" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">

		<div class="col-6">
			<label class="form-label"> Code <span class="text-danger">*</span></label>
			<input type="text" id="branch_code" name="branch_code" maxlength="15" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>

		<div class="col-6">
			<label class="form-label"> Status <span class="text-danger">*</span></label>
			<select id="branch_status" name="branch_status" class="form-control" required>
				<option value="1" selected> Active </option>
				<option value="0"> Inactive </option>
			</select>
		</div>

	</div>

	<div class="row mt-4">
		<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
			<i class="fa fa-address-card label-icon"></i><strong> Address Information</strong>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<label class="form-label"> Address <span class="text-danger">*</span></label>
			<textarea id="branch_address" name="branch_address" rows="3" maxlength="250" class="form-control" onkeyup="this.value = this.value.toUpperCase();" required></textarea>
		</div>
	</div>

	<div class="row">

		<div class="col-lg-4 col-sm-12 mt-3">
			<label class="form-label"> Postal Code <span class="text-danger">*</span></label>
			<input type="text" id="branch_postcode" name="branch_postcode" maxlength="8" class="form-control" onkeypress="isNumeric()" autocomplete="off" required>
		</div>

		<div class="col-lg-4 col-sm-12 mt-3">
			<label class="form-label"> City <span class="text-danger">*</span></label>
			<input type="text" id="branch_city" name="branch_city" maxlength="20" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>

		<div class="col-lg-4 col-sm-12 mt-3">
			<label class="form-label"> State <span class="text-danger">*</span></label>
			<select id="branch_state" name="branch_state" class="form-control" required>
				<option value=""> - Select - </option>
				<option value="JOHOR">JOHOR</option>
				<option value="KEDAH">KEDAH</option>
				<option value="KELANTAN">KELANTAN</option>
				<option value="KUALA LUMPUR">KUALA LUMPUR</option>
				<option value="LABUAN">LABUAN</option>
				<option value="MELAKA">MELAKA</option>
				<option value="NEGERI SEMBILAN">NEGERI SEMBILAN</option>
				<option value="PAHANG">PAHANG</option>
				<option value="PULAU PINANG">PULAU PINANG</option>
				<option value="PERAK">PERAK</option>
				<option value="PERLIS">PERLIS</option>
				<option value="PUTRAJAYA">PUTRAJAYA</option>
				<option value="SABAH">SABAH</option>
				<option value="SARAWAK">SARAWAK</option>
				<option value="SELANGOR">SELANGOR</option>
				<option value="TERENGGANU">TERENGGANU</option>
			</select>
		</div>

	</div>

	<div class="row mt-4">
		<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
			<i class="fa fa-users label-icon"></i><strong> Person In Charge Information</strong>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<label class="form-label"> Person Name <span class="text-danger">*</span></label>
			<input type="text" id="branch_pic_name" name="branch_pic_name" maxlength="255" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-12">
			<label class="form-label"> Person Contact No. <span class="text-danger">*</span></label>
			<input type="text" id="branch_pic_office_no" name="branch_pic_office_no" maxlength="25" class="form-control" onkeypress="isNumeric()" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-lg-12">
			<span class="text-danger">* Indicates a required field</span>
			<center class="mt-4">
				<input type="hidden" id="branch_id" name="branch_id" placeholder="branch_id" readonly>
				<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
			</center>
		</div>
	</div>

</form>

<script>
	function getPassData(baseUrl, token, data) {}

	$("#formBranch").submit(function(event) {
		event.preventDefault();

		if (validateDataBranch()) {

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
						const res = await submitApi(url, form.serializeArray(), 'formBranch');
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

	function validateDataBranch() {

		const rules = {
			'branch_name': 'required|min:5|max:250',
			'branch_code': 'required|min:2|max:15',
			'branch_status': 'required|integer|min_length:1|max_length:1|min:0|max:1',
			'branch_address': 'required|min:2|max:250',
			'branch_postcode': 'required|min_length:4|max_length:8',
			'branch_city': 'required|min:2|max:20',
			'branch_state': 'required|min:2|max:25',
			'branch_email': 'required|email|min:5|max:250',
			'branch_pic_name': 'required|min:3|max:250',
			'branch_pic_office_no': 'required|min:5|max:20',
		};

		const message = {
			'branch_name': 'Name',
			'branch_code': 'Code',
			'branch_status': 'Status',
			'branch_address': 'Address',
			'branch_postcode': 'Postal Code',
			'branch_city': 'City',
			'branch_state': 'State',
			'branch_email': 'Email',
			'branch_pic_name': 'PIC Name',
			'branch_pic_office_no': 'PIC Contact No.',
		};

		return validationJs(rules, message);
	}
</script>