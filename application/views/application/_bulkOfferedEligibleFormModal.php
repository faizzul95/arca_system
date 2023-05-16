<div class="row">
	<div id="nodataStudentOfferedEligibleDiv" style="display: none;"> </div>
	<form id="formStudentOfferedEligible" action="applications/bulk-approve" method="POST">

		<div class="col-12">
			<table id="tableList" class="table table-hover table-striped table-bordered">
				<thead class="table-dark">
					<tr>
						<th width="30%"> Name </th>
						<th width="10%"> Matric ID </th>
						<th width="10%"> Program </th>
						<th width="5%"> Disability </th>
						<th width="8%"> Society Type </th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
		</div>

		<div class="row mt-2">
			<div class="col-lg-12">
				<!-- <span class="text-danger">* Indicates a required field</span> -->
				<center class="mt-4">
					<input type="hidden" id="college_id_eligible" placeholder="college_id" readonly>
					<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Approve </button>
				</center>
			</div>
		</div>

	</form>
</div>

<script>
	var tableOfferedEligible = null;

	async function getPassData(baseUrl, token, data) {
		$('#college_id_eligible').val(data['college_id']);
		$('#nodataStudentOfferedEligibleDiv').html(nodata());

		await getDataListStudent();
	}

	async function getDataListStudent() {

		const res = await callApi('post', "applications/bulk-approval-list", {
			'is_college_eligible': 1,
			'college_id': $('#college_id_eligible').val(),
		});

		if (isSuccess(res)) {
			const response = res.data;

			if (response.length > 0) {

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

				tableOfferedEligible = generateDatatable('tableList'); // generate client side datatable
				$.each(response, function(key, value) {

					tableOfferedEligible.row.add([
						'<span class="text-truncate">' + trimData(response[key].user_full_name) + '</span>',
						trimData(response[key].user_matric_code),
						trimData(response[key].program_code),
						disability[response[key].is_special],
						!hasData(response[key].has_position) ? '<span><i>(None)</i></span>' : hasPosition[response[key].has_position] +
						'<input type="hidden" name="bulkChecked[]" value="' + trimData(response[key].application_id) + '"">',
					]).draw();

				});

			} else {
				$('#nodataStudentOfferedEligibleDiv').show();
				$('#formStudentOfferedEligible').hide();
			}
		}
	}

	$("#formStudentOfferedEligible").submit(function(event) {
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
					const res = await submitApi(url, form.serializeArray(), 'formStudentOfferedEligible');

					if (isSuccess(res)) {
						if (isSuccess(res.data.resCode)) {
							noti(res.data.resCode, res.data.message);
							$('#formStudentOfferedEligible')[0].reset(); // reset previous form
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