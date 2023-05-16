<form id="formAcademic" action="academic/save" method="POST">

	<div class="row">
		<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
			<i class="ri-calendar-todo-fill label-icon"></i>
			<strong> Academic Information </strong>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<label class="form-label"> Academic / Display Name <span class="text-danger">*</span></label>
			<input type="text" id="academic_display_name" name="academic_display_name" maxlength="30" class="form-control" onkeyup="this.value = this.value.toUpperCase();" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">

		<div class="col-6">
			<label class="form-label"> Year <span class="text-danger">*</span></label>
			<input type="text" id="academic_year" name="academic_year" maxlength="4" class="form-control" onkeypress="isNumeric()" autocomplete="off" required>
		</div>

		<div class="col-6">
			<label class="form-label"> Academic Order No <span class="text-danger">*</span></label>
			<select id="academic_order" name="academic_order" class="form-control" required>
				<option value=""> - Select - </option>
			</select>
		</div>
	</div>

	<div class="row mt-2">

		<div class="col-6">
			<label class="form-label"> Start Date <span class="text-danger">*</span></label>
			<input type="date" id="academic_start_date" name="academic_start_date" class="form-control" autocomplete="off" onchange="setMaxDate(this.value)" required>
		</div>

		<div class="col-6">
			<label class="form-label"> End Date <span class="text-danger">*</span></label>
			<input type="date" id="academic_end_date" name="academic_end_date" class="form-control" autocomplete="off" required>
		</div>

	</div>

	<div class="row mt-4">
		<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
			<i class="ri-sticky-note-line label-icon"></i>
			<strong> Sticker Information </strong>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-4 col-sm-12 mt-2">
			<label for="sticker_college_amount" class="form-label"> College Sticker <span class="text-danger">*</span></label>
			<input type="number" id="sticker_college_amount" name="sticker_college_amount" class="form-control" min="0" max="20" value="3" autocomplete="off" required>
		</div>

		<div class="col-lg-4 col-sm-12 mt-2">
			<label for="sticker_university_amount" class="form-label"> University Sticker <span class="text-danger">*</span></label>
			<input type="number" id="sticker_university_amount" name="sticker_university_amount" class="form-control" min="0" max="20" value="3" autocomplete="off" required>
		</div>

		<div class="col-lg-4 col-sm-12 mt-2">
			<label for="sticker_hep_amount" class="form-label"> HEP Sticker <span class="text-danger">*</span></label>
			<input type="number" id="sticker_hep_amount" name="sticker_hep_amount" class="form-control" min="0" max="20" value="3" autocomplete="off" required>
		</div>
	</div>

	<div class="row mt-2">
		<div class="col-lg-12">
			<span class="text-danger">* Indicates a required field</span>
			<center class="mt-4">
				<input type="hidden" id="academic_status" name="academic_status" value="1">
				<input type="hidden" id="old_academic_order" name="old_academic_order">
				<input type="hidden" id="sticker_id" name="sticker_id" placeholder="sticker_id" readonly>
				<input type="hidden" id="academic_id" name="academic_id" placeholder="academic_id" readonly>
				<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
				<button type="button" id="stickerBtn" class="btn btn-info" onclick="saveSticker()" style="display: none;"> <i class='fa fa-save'></i> Update Sticker Only </button>
			</center>
		</div>
	</div>

</form>

<script>
	function getPassData(baseUrl, token, data) {

		var order = null;
		$('#academic_start_date').attr('min', getCurrentDate());
		$('#academic_end_date').attr('min', getCurrentDate());

		if (data == null) {
			$('#academic_end_date').prop('readonly', true);
		} else {
			order = data['academic_order'];
			$('#academic_end_date').attr('min', data['academic_start_date']);
			$('#academic_end_date').prop('readonly', false);

			$('#academic_status').val(data['academic_status']);
			$('#sticker_id').val(data['sticker']['sticker_id']);
			$('#sticker_college_amount').val(data['sticker']['sticker_college_amount']);
			$('#sticker_university_amount').val(data['sticker']['sticker_university_amount']);
			$('#sticker_hep_amount').val(data['sticker']['sticker_hep_amount']);
			$('#stickerBtn').show();
		}

		$('#old_academic_order').val(order);
		getAcademicOrder((data == null) ? null : data['academic_order']);
	}

	async function getAcademicOrder(academicOrder = null) {
		const res = await callApi('post', 'academic/academic-order-list', {
			academic_id: $('academic_id').val(),
			academic_order: academicOrder
		});

		if (isSuccess(res.status)) {
			$('#academic_order').html(res.data);

			if (academicOrder != 1) {
				$('#academic_order').val(academicOrder - 1);
			} else {
				$('#academic_order').val(0);
			}
		}
	}

	function setMaxDate(date) {
		$('#academic_end_date').val('');
		$('#academic_end_date').attr('min', date);
		$('#academic_end_date').prop('readonly', false);
	}

	$("#formAcademic").submit(function(event) {
		event.preventDefault();

		if (validateDataAcademic()) {

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
						const res = await submitApi(url, form.serializeArray(), 'formAcademic');
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

	function saveSticker() {

		if (validateDataSticker()) {
			const saveBtnText = $('#stickerBtn').html();

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
						loadingBtn('stickerBtn', true);

						const res = await callApi('post', 'academic/save-config-sticker', {
							'sticker_id': $('#sticker_id').val(),
							'sticker_college_amount': $('#sticker_college_amount').val(),
							'sticker_university_amount': $('#sticker_university_amount').val(),
							'sticker_hep_amount': $('#sticker_hep_amount').val(),
							'academic_id': $('#academic_id').val(),
						});

						if (isSuccess(res)) {
							noti(res.status, 'Save sticker');
							loadingBtn('stickerBtn', false, saveBtnText);
						}
					}
				})

		} else {
			validationJsError('toastr', 'single'); // single or multi
		}

	}

	function validateDataAcademic() {

		const rules = {
			'academic_display_name': 'required|min:1|max:30',
			'academic_year': 'required|integer|min_length:4|max_length:4',
			'academic_order': 'required|integer',
			'academic_status': 'required|integer|min:1|max:1',
			'sticker_college_amount': 'required|integer|min:1|max:20',
			'sticker_university_amount': 'required|integer|min:1|max:20',
			'sticker_hep_amount': 'required|integer|min:1|max:20',
			'old_academic_order': 'integer',
		};

		const message = {
			'academic_display_name': 'Academic Name',
			'academic_year': 'Year',
			'academic_order': 'Order',
			'academic_status': 'Status',
			'sticker_college_amount': 'Sticker College',
			'sticker_university_amount': 'Sticker University',
			'sticker_hep_amount': 'Sticker HEP',
			'old_academic_order': 'Old Academic Order',
		};

		return validationJs(rules, message);
	}

	function validateDataSticker() {

		const rules = {
			'academic_id': 'required|integer|min:1',
			'sticker_college_amount': 'required|integer|min:1|max:20',
			'sticker_university_amount': 'required|integer|min:1|max:20',
			'sticker_hep_amount': 'required|integer|min:1|max:20',
		};

		const message = {
			'academic_id': 'Academic ID',
			'sticker_college_amount': 'Sticker College',
			'sticker_university_amount': 'Sticker University',
			'sticker_hep_amount': 'Sticker HEP',
		};

		return validationJs(rules, message);
	}
</script>