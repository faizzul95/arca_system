<div class="row">

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header">
				<span class="ribbon ribbon-primary ribbon-shape"><span>Audit Trails</span></span>
				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataList()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>
				<select id="event_type" class="form-control form-control-sm float-end me-2" onchange="getDataList()" style="width: 12%!important;">
					<option value=""> All </option>
					<option value="insert"> Insert </option>
					<option value="update"> Update </option>
					<option value="delete"> Delete </option>
				</select>
				<input type="date" id="date_search" class="form-control form-control-sm float-end me-2" style="width: 13%!important;" onchange="getDataList()" max="<?= date('Y-m-d'); ?>">
			</div>
			<div class="card-body">

				<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListAuditDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataListAudit" class="table table-hover table-striped table-bordered" width="100%">
						<thead class="table-dark">
							<tr>
								<th> User Details </th>
								<th> Event </th>
								<th> Timestamp </th>
								<th> Action </th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>

			</div>
		</div>

	</div>

</div>

<script type="text/javascript">
	$(document).ready(function() {
		getDataList();
	});

	async function getDataList() {
		loading('#bodyDiv', true);
		generateDatatable('dataListAudit', 'serverside', 'rbac/list-audit', 'nodatadiv', {
			'date_search': $('#date_search').val(),
			'event_type': $('#event_type').val(),
		});
		loading('#bodyDiv', false);
	}

	async function deleteRecord(id) {
		Swal.fire({
			title: 'Are you sure?',
			html: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Confirm!',
			reverseButtons: true
		}).then(
			async (result) => {
				if (result.isConfirmed) {
					loading('#bodyDiv', true);
					const res = await deleteApi(id, 'rbac/delete-audit', getDataList);
					loading('#bodyDiv', false);

				}
			})
	}

	async function viewRecord(id) {
		const res = await callApi('get', 'rbac/view-audit/' + id);

		if (isSuccess(res)) {
			loadFileContent('rbac/_viewAuditModal.php', 'generalContent', 'xl', 'View Audit Trails', res.data, 'offcanvas');
		}
	}
</script>