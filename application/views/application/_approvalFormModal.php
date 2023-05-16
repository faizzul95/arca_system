<div class="row">
	<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
		<i class="fa fa-user label-icon"></i><strong> Student Infromation </strong>
	</div>

	<div class="row mb-2">
		<div class="col-lg-12 col-sm-12">
			<label class="form-label"> Student Name </label><br>
			<span id="user_full_name_approve" style="font-weight:bold"></span>
		</div>
	</div>

	<div class="row mb-2">
		<div class="col-6">
			<label class="form-label"> Matric ID </label><br>
			<span id="user_matric_code_approve" style="font-weight:bold"></span>
		</div>
		<div class="col-6">
			<label class="form-label"> NRIC </label><br>
			<span id="user_nric_approve" style="font-weight:bold"></span>
		</div>
	</div>
</div>

<div class="row mt-3">
	<div class="col-12">
		<div class="row">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="fa fa-info-circle label-icon"></i><strong> Additional Information </strong>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-6">
		<label class="form-label"> Disability </label><br>
		<span id="is_special_approve" style="font-weight:bold"></span>
	</div>

	<div class="col-6">
		<label class="form-label"> Society Type </label><br>
		<span id="has_position_approve" style="font-weight:bold"></span>
	</div>
</div>

<div class="row mt-3">
	<div class="col-12">
		<div class="row">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="fa fa-sticky-note label-icon"></i><strong> Sticker Collection </strong>
			</div>
		</div>
	</div>

	<div class="col-12">
		<div class="row">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover">
					<thead class="table-dark">
						<tr>
							<th>Category</th>
							<th width="25%">Total</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td> HEP </td>
							<td><span id="totalHEPCount">0</span></td>
						</tr>
						<tr>
							<td> University </td>
							<td><span id="totalUniversityCount">0</span></td>
						</tr>
						<tr>
							<td> College </td>
							<td><span id="totalCollegeCount">0</span></td>
						</tr>
						<tr>
							<td> Academic/Faculty </td>
							<td><span id="totalFacultyCount">0</span></td>
						</tr>
						<tr>
							<td> Association/Club </td>
							<td><span id="totalClubCount">0</span></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>

<div class="row mt-3">
	<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
		<i class="fa fa-gavel label-icon"></i><strong> Application Approval </strong>
	</div>
</div>

<div class="row mt-2">
	<div class="col-lg-12">
		<form id="formApplicationApproval" action="applications/approval" method="POST">

			<div class="row">
				<div class="col-12">
					<div class="form-group">
						<label> Status <span class="text-danger">*</span> </label>
						<select id="approval_status" name="approval_status" class="form-control" onchange="rejectApp(this.value)" required>
							<option value="" selected> - Select - </option>
							<option value="1"> Offered </option>
							<option value="2"> Unoffered </option>
						</select>
					</div>
				</div>
			</div>

			<div id="reasonDiv" class="row mt-2" style="display: none;">
				<div class="col-12">
					<div class="form-group">
						<label> Remark / Reason <span class="text-danger">*</span> </label>
						<textarea id="approval_remark" name="approval_remark" class="form-control" rows="5" maxlength="150" placeholder="Type your reason.. (max length : 150)"></textarea>
					</div>
				</div>
			</div>

			<div class="row mt-4">
				<div class="col-lg-12">
					<span class="text-danger mb-2">* Indicates a required field</span>
					<center class="mt-4">
						<input type="hidden" id="application_id_approve" name="application_id" placeholder="application_id" readonly>
						<input type="hidden" id="user_id_approve" name="user_id" placeholder="user_id" readonly>
						<input type="hidden" id="stud_id_approve" name="stud_id" placeholder="stud_id" readonly>
						<button type="submit" id="submitBtn" class="btn btn-success"> <i class='fa fa-save'></i> Save </button>
					</center>
				</div>
			</div>

		</form>
	</div>
</div>

<script>
	async function getPassData(baseUrl, token, data) {

		$('#user_id_approve').val(data['user_id']);
		$('#stud_id_approve').val(data['stud_id']);
		$('#application_id_approve').val(data['application_id']);
		$('#approval_status').val(data['approval_status'] == 0 ? '' : data['approval_status']);
		$('#approval_remark').val(data['approval_remark']);

		rejectApp(data['approval_status']);

		const stickerDetail = data['sticker'];
		const userDetail = stickerDetail['user'];
		const studentProfile = userDetail['profileStudent'];

		$('#user_matric_code_approve').text(userDetail['user_matric_code']);
		$('#user_full_name_approve').text(userDetail['user_full_name']);
		$('#user_nric_approve').text(userDetail['user_nric']);
		$('#user_contact_no_approve').text(userDetail['user_contact_no']);

		var listPosition = {
			'1': 'JPK',
			'2': 'MPP',
			'3': 'Athlete',
			'4': 'Uniform',
		};

		$('#is_special_approve').text(studentProfile['is_special'] == 0 ? "NO" : "YES");
		$('#has_position_approve').text(hasData(studentProfile['has_position']) ? listPosition[studentProfile['has_position']] : 'None');
		$('#totalHEPCount').text(stickerDetail['total_hep_sticker']);
		$('#totalUniversityCount').text(stickerDetail['total_university_sticker']);
		$('#totalCollegeCount').text(stickerDetail['total_college_sticker']);
		$('#totalFacultyCount').text(stickerDetail['total_faculty_sticker']);
		$('#totalClubCount').text(stickerDetail['total_club_sticker']);
	}

	function rejectApp(status) {
		if (status == 1) {
			$('#reasonDiv').hide();
			$('#approval_remark').val(''); // reset
		} else if (status == 2) {
			$('#reasonDiv').show()
		}
	}

	$("#formApplicationApproval").submit(function(event) {
		event.preventDefault();

		if (validateDataScrutinize()) {

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
						const res = await submitApi(url, form.serializeArray(), 'formApplicationApproval');
						if (isSuccess(res)) {

							if (isSuccess(res.data.resCode)) {
								noti(res.status, 'Save');
								$('#formApplicationApproval')[0].reset(); // reset previous form
								await getDataList();
								setTimeout(function() {
									$('#generaloffcanvas-right').offcanvas('toggle');
								}, 200);
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

	function validateDataScrutinize() {

		const rules = {
			'user_id': 'required|integer',
			'stud_id': 'required|integer',
			'approval_status': 'required|integer',
			'approval_remark': 'required_if:approval_status,=,2|min:5|max:150',
			'application_id': 'required|integer',
		};

		const message = {
			'user_id': 'User ID',
			'stud_id': 'Student ID',
			'approval_status': 'Status',
			'approval_remark': 'Reason',
			'application_id': 'Application ID',
		};

		return validationJs(rules, message);
	}
</script>