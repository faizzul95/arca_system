<div class="col-lg-12 col-md-12">
	<div id="listDiv" class="card ribbon-box border shadow-none mb-lg-0">
		<div class="card-header text-muted">
			<span class="ribbon ribbon-primary ribbon-shape"><span> List Unoffered College </span></span>
			<button id="printUnofferedListBtn" type="button" class="btn btn-dark btn-sm float-end me-2" onclick="printUnoffered()" title="Print List">
				<i class="ri-printer-line"></i> Print Report
			</button>
		</div>
		<div id="dataListBodyDiv" class="card-body">
			<div id="nodataUnofferedDiv" style="display: block;"></div>
			<div id="dataListUnofferedDiv" class="card-datatable table-responsive" style="display: none;">
				<table id="tableList" class="table table-hover table-striped table-bordered">
					<thead class="table-dark">
						<tr>
							<th width="30%"> Name </th>
							<th width="15%"> Matric ID </th>
							<th width="10%"> NRIC </th>
							<th width="10%"> Program </th>
							<th width="35%"> Reason </th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>

			<input type="hidden" id="academic_id_print" placeholder="academic_id" readonly>
			<input type="hidden" id="approval_status_print" placeholder="approval_status" readonly>
			<input type="hidden" id="college_id_print" placeholder="college_id" readonly>

		</div>
	</div>
</div>

<script>
	async function getPassData(baseUrl, token, data) {

		$('#academic_id_print').val(data['academic_id']);
		$('#approval_status_print').val(data['approval_status']);
		$('#college_id_print').val(data['college_id']);

		$('#nodataUnofferedDiv').html(nodata());
		$('#dataListUnofferedDiv').hide();

		const res = await callApi('post', "applications/unoffered-list", data);

		if (isSuccess(res)) {
			const response = res.data;
			if (response.length > 0) {
				$('#nodataUnofferedDiv').hide();

				tableCopy = generateDatatable('tableList'); // generate client side datatable
				$.each(response, function(key, value) {

					tableCopy.row.add([
						trimData(response[key].user_full_name),
						trimData(response[key].user_matric_code),
						trimData(response[key].user_nric),
						trimData(response[key].program_code) + ' /  ' + trimData(response[key].semester_number),
						trimData(response[key].approval_remark)
					]).draw();

				});

				$('#dataListUnofferedDiv').show();

			} else {
				$('#nodataUnofferedDiv').show();
			}
		}
	}

	async function printUnoffered() {

		loading('#listDiv', true);

		const res = await callApi('post', "export/unoffered-print-list", {
			'academic_id': $('#academic_id_print').val(),
			'approval_status': $('#approval_status_print').val(),
			'college_id': $('#college_id_print').val(),
		});

		// check if request is success
		if (isSuccess(res)) {
			const data = res.data;
			$('#printListApplication').html(data.result);

			setTimeout(function() {
				loading('#listDiv', false);
			}, 450);

			if (isSuccess(data.resCode)) {
				printDiv('printListApplication', 'printUnofferedListBtn', $('#printUnofferedListBtn').html(), 'REPORT UNOFFERED COLLEGE');
			} else {
				noti(res.data.resCode, res.data.message);
			}

		}
	}
</script>