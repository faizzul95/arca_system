<div class="row">
	<div id="nodataStudentRejectedCheckedDiv" style="display: none;"> </div>
	<form id="formStudentRejectedChecked" action="applications/bulk-reject" method="POST">

		<div class="col-12">
			<table id="tableList" class="table table-hover table-striped table-bordered">
				<thead class="table-dark">
					<tr>
						<th width="30%"> Name </th>
						<th width="8%"> Matric ID </th>
						<th width="8%"> Program </th>
						<th width="3%"> Disability </th>
						<th width="8%"> Society Type </th>
						<th width="5%"> Status </th>
						<th width="19%"> Reason / Remark </th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>

		<div class="row mt-2">
			<div class="col-lg-12">
				<!-- <span class="text-danger">* Indicates a required field</span> -->
				<center class="mt-4">
					<input type="hidden" id="college_id_checked_reject" placeholder="college_id" readonly>
					<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Reject </button>
				</center>
			</div>
		</div>

	</form>
</div>

<script>
	var tableRejectedChecked = null;

	async function getPassData(baseUrl, token, data) {
		$('#college_id_checked_reject').val(data['college_id']);
		$('#nodataStudentRejectedCheckedDiv').html(nodata());

		await getDataListStudent();
	}

	async function getDataListStudent() {

		const res = await callApi('post', "applications/bulk-approval-list", {
			'scrutinize_check_status': 1,
			'college_id': $('#college_id_checked_reject').val(),
		});

		if (isSuccess(res)) {
			const response = res.data;

			if (response.length > 0) {

				var eligibleStatus = {
					'1': "<span class='badge badge-label bg-success'> Eligible </span>",
					'2': "<span class='badge badge-label bg-warning' data-bs-toggle='tooltip' data-bs-placement='bottom' title='insufficient sticker for college, university and HEP.'> Not Eligible </span>",
				};

				var disability = {
					'0': "No",
					'1': "Yes ",
				};

				var hasPosition = {
					'1': "JPK",
					'2': "MPP",
					'3': "Athelte",
					'4': "Uniform",
				};

				tableRejectedChecked = generateDatatable('tableList'); // generate client side datatable
				$.each(response, function(key, value) {

					tableRejectedChecked.row.add([
						'<span class="text-truncate">' + trimData(response[key].user_full_name) + '</span>',
						trimData(response[key].user_matric_code),
						trimData(response[key].program_code),
						disability[response[key].is_special],
						hasData(response[key].has_position) ? hasPosition[response[key].has_position] : '<span><i>(None)</i></span>',
						eligibleStatus[response[key].is_college_eligible],
						'<input type="text" name="reason[]" class="form-control form-control-sm" maxlength="150" placeholder="Write reason here (max length : 100)" required>\
                         <input type="hidden" name="bulkChecked[]" value="' + trimData(response[key].application_id) + '"">',
					]).draw();

				});

			} else {
				$('#nodataStudentRejectedCheckedDiv').show();
				$('#formStudentRejectedChecked').hide();
			}
		}
	}

	$("#formStudentRejectedChecked").submit(function(event) {
		event.preventDefault();

		const form = $(this);
		const url = form.attr('action');

		if (validateBulkReject()) {
			Swal.fire({
				title: 'Are you sure?',
				html: "<small> All remark student will be exclude to college for next semester </small>",
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes, Confirm!',
				reverseButtons: true
			}).then(
				async (result) => {
					if (result.isConfirmed) {
						const res = await submitApi(url, form.serializeArray(), 'formStudentRejectedChecked');

						if (isSuccess(res)) {
							if (isSuccess(res.data.resCode)) {
								noti(res.data.resCode, res.data.message);
								await getDataList();
								$('#formStudentRejectedChecked')[0].reset(); // reset previous form
								closeModal('#generalModal-fullscreen');
							} else {
								noti(500, res.data.message)
							}
						}
					}
				})
		} else {
			validationJsError('toastr', 'single'); // single or multi
		}

	});

	function validateBulkReject() {

		const rules = {
			'bulkChecked': 'required|integer',
			'reason': 'required|array|min:3|max:100',
		};

		const message = {
			'bulkChecked': 'IDs',
			'reason': 'Reason',
		};

		return validationJs(rules, message);
	}
</script>