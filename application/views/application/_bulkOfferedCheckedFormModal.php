<div class="row">
	<div id="nodataStudentOfferedCheckedDiv" style="display: none;"> </div>
	<form id="formStudentOfferedChecked" action="applications/bulk-approve" method="POST">

		<div class="col-12">
			<table id="tableList" class="table table-hover table-striped table-bordered">
				<thead class="table-dark">
					<tr>
						<th width="30%"> Name </th>
						<th width="10%"> Matric ID </th>
						<th width="10%"> Program </th>
						<th width="5%"> Disability </th>
						<th width="8%"> Society Type </th>
						<th width="8%"> Status </th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>

		<div class="row mt-2">
			<div class="col-lg-12">
				<!-- <span class="text-danger">* Indicates a required field</span> -->
				<center class="mt-4">
					<input type="hidden" id="college_id_checked" placeholder="college_id" readonly>
					<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Approve </button>
				</center>
			</div>
		</div>

	</form>
</div>

<script>
	var tableOfferedChecked = null;

	async function getPassData(baseUrl, token, data) {
		$('#college_id_checked').val(data['college_id']);
		$('#nodataStudentOfferedCheckedDiv').html(nodata());

		await getDataListStudent();
	}

	async function getDataListStudent() {

		const res = await callApi('post', "applications/bulk-approval-list", {
			'scrutinize_check_status': 1,
			'college_id': $('#college_id_checked').val(),
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

				tableOfferedChecked = generateDatatable('tableList'); // generate client side datatable
				$.each(response, function(key, value) {

					tableOfferedChecked.row.add([
						'<span class="text-truncate">' + trimData(response[key].user_full_name) + '</span>',
						trimData(response[key].user_matric_code),
						trimData(response[key].program_code),
						disability[response[key].is_special],
						hasData(response[key].has_position) ? hasPosition[response[key].has_position] : '<span><i>(None)</i></span>',
						eligibleStatus[response[key].is_college_eligible] + '<input type="hidden" name="bulkChecked[]" value="' + trimData(response[key].application_id) + '"">',
					]).draw();

				});

			} else {
				$('#nodataStudentOfferedCheckedDiv').show();
				$('#formStudentOfferedChecked').hide();
			}
		}
	}

	$("#formStudentOfferedChecked").submit(function(event) {
		event.preventDefault();

		const form = $(this);
		const url = form.attr('action');

		Swal.fire({
			title: 'Are you sure?',
			html: "<small> All remark student will be offer to college for next semester </small>",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Confirm!',
			reverseButtons: true
		}).then(
			async (result) => {
				if (result.isConfirmed) {
					const res = await submitApi(url, form.serializeArray(), 'formStudentOfferedChecked');

					if (isSuccess(res)) {
						if (isSuccess(res.data.resCode)) {
							noti(res.data.resCode, res.data.message);
							$('#formStudentOfferedChecked')[0].reset(); // reset previous form
							await getDataList();
							closeModal('#generalModal-fullscreen');
						} else {
							noti(500, res.data.message)
						}
					}
				}
			})
	});
</script>