<div class="row">

	<div class="col-lg-6 col-md-12 fill border-right p-4 overflow-hidden">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyTemplateDiv">

			<div class="card-header">
				<span class="ribbon ribbon-primary ribbon-shape"><span> Preview </span></span>
				<button type="button" class="btn btn-warning btn-sm float-end" onclick="refreshPreview()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>
			</div>

			<div class="card-body">
				<div id="previewDiv" style="display: block;"></div>
			</div>

		</div>
	</div>

	<div class="col-lg-6 col-md-12 fill border-right p-4">

		<div class="row">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="fa fa-edit label-icon"></i><strong> Register / Update Form </strong>
			</div>

			<form id="formTemplate" action="rbac/template-email-save" method="POST">

				<div class="row">

					<div class="col-12 col-sm-12">
						<label class="form-label"> Subject <span class="text-danger">*</span></label>
						<input type="text" id="email_subject" name="email_subject" maxlength="255" class="form-control" autocomplete="off" required>
					</div>

				</div>

				<div class="row mt-2">

					<div class="col-6 col-sm-6">
						<label class="form-label"> CC </label>
						<input type="email" id="email_cc" name="email_cc" maxlength="255" class="form-control" autocomplete="off">
					</div>

					<div class="col-6 col-sm-6">
						<label class="form-label"> BCC </label>
						<input type="email" id="email_bcc" name="email_bcc" maxlength="255" class="form-control" autocomplete="off">
					</div>

				</div>

				<div class="row mt-2">
					<div class="col-lg-12">
						<label class="form-label"> Description <span class="text-danger">*</span></label>
						<input type="hidden" id="email_body" name="email_body" class="form-control" readonly>
						<textarea id="editor"></textarea>
					</div>
				</div>

				<div class="row mt-2" style="display: none;">

					<div class="col-12 col-sm-12">
						<label class="form-label"> Footer </label>
						<input type="email" id="email_footer" name="email_footer" maxlength="255" class="form-control" autocomplete="off">
					</div>

				</div>

				<div class="row mt-2">

					<div class="col-6 col-sm-6">
						<label class="form-label"> Email Type <span class="text-danger">*</span></label>
						<input type="text" id="email_type" name="email_type" maxlength="255" class="form-control" autocomplete="off" readonly>
					</div>

					<div class="col-6 col-sm-6">
						<label class="form-label"> Status <span class="text-danger">*</span></label>
						<select id="email_status" name="email_status" class="form-control form-control" required>
							<option value="1"> Active </option>
							<option value="0"> Inactive </option>
						</select>
					</div>

				</div>

				<div class="row mt-2 mb-2">
					<div class="col-lg-12">
						<span class="text-danger">* Indicates a required field</span>
						<center class="mt-4">
							<input type="hidden" id="email_id" name="email_id" placeholder="email_id" readonly>
							<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
						</center>
					</div>
				</div>
			</form>

		</div>

	</div>

</div>

<script>
	function getPassData(baseUrl, token, data) {

		$('#editor').summernote({
			callbacks: {
				onChange: function() {
					refreshPreview();
				}
			},
			placeholder: 'Type anything here...',
			tabsize: 2,
			height: 250,
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'underline', 'clear']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'picture', 'video']],
				['view', ['fullscreen', 'codeview', 'help']]
			]
		});

		if (hasData(data)) {

			$('#email_body').val(data['email_body']);
			$('#email_type').val(data['email_type']);
			$('#email_subject').val(data['email_subject']);
			$('#email_footer').val(data['email_footer']);
			$('#email_cc').val(data['email_cc']);
			$('#email_bcc').val(data['email_bcc']);
			$('#email_status').val(data['email_status']);
			$('#email_id').val(data['email_id']);

			$('#editor').summernote('code', data['email_body']);

			$('#previewDiv').html(data['email_body']);

		} else {
			$('#previewDiv').html(nodata());
		}
	}

	function refreshPreview() {
		let editorData = $('#editor').summernote('code');
		$('#email_body').val(editorData);

		if (hasData(editorData)) {
			$('#previewDiv').html(editorData);
		} else {
			$('#previewDiv').html(nodata());
		}
	}

	$("#formTemplate").submit(function(event) {
		event.preventDefault();

		if (validateDataTemplate()) {
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
						const res = await submitApi(url, form.serializeArray(), 'formTemplate', null, false);
						if (isSuccess(res)) {

							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Save');
								refreshPreview();
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

	function validateDataTemplate() {

		const rules = {
			'email_type': 'required|min:5|max:255',
			'email_subject': 'required|min:5|max:255',
			'email_body': 'required|min:5',
			'email_footer': 'max:255',
			'email_cc': 'email|min:5|max:255',
			'email_bcc': 'email|min:5|max:255',
			'email_status': 'required|integer',
			'email_id': 'required|integer',
		};

		const message = {
			'email_type': 'Type',
			'email_subject': 'Subject',
			'email_body': 'Description',
			'email_footer': 'Footer',
			'email_cc': 'CC',
			'email_bcc': 'BCC',
			'email_status': 'Status',
			'email_id': 'Email ID',
		};

		return validationJs(rules, message);
	}
</script>