<form id="formSentMail" action="rbac/sent-mail-backup" method="POST">

	<div class="row">
		<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
			<i class="ri-database-line label-icon"></i><strong> Backup Information</strong>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<label class="form-label"> Attachment File Name </label>
			<input type="text" id="backup_name" name="backup_name" maxlength="255" class="form-control" autocomplete="off" readonly>
			<input type="hidden" id="backup_location" name="backup_location" maxlength="255" class="form-control" autocomplete="off" readonly>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-12">
			<label class="form-label"> Backup Date </label>
			<input type="text" id="created_at" name="created_at" maxlength="255" class="form-control" autocomplete="off" readonly>
		</div>
	</div>

	<div class="row mt-4">
		<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
			<i class="ri-mail-send-line label-icon"></i><strong> Recipient Information</strong>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<label class="form-label"> Full Name <span class="text-danger">*</span></label>
			<input type="text" id="user_full_name" name="user_full_name" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-12">
			<label class="form-label"> Email <span class="text-danger">*</span></label>
			<input type="email" id="user_email" name="user_email" class="form-control" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-lg-12">
			<span class="text-danger">* Indicates a required field</span>
			<center class="mt-4">
				<input type="hidden" id="backup_id" name="backup_id" placeholder="backup_id" readonly>
				<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-envelope'></i> Sent Mail </button>
			</center>
		</div>
	</div>

</form>

<script>
	function getPassData(baseUrl, token, data) {}

	$("#formSentMail").submit(function(event) {
		event.preventDefault();

		if (validateDataSend()) {

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
						const res = await submitApi(url, form.serializeArray(), 'formSentMail');
						if (isSuccess(res)) {

							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Email has been sent');
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

	function validateDataSend() {

		const rules = {
			'user_full_name': 'required|min:5|max:250',
			'user_email': 'required|email|min:5|max:250',
			'backup_name': 'required|min:2|max:150',
		};

		const message = {
			'backup_name': 'Attachment File Name',
			'user_full_name': 'Recipient Name',
			'user_email': 'Recipient Email',
		};

		return validationJs(rules, message);
	}
</script>