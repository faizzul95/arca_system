<div class="row">

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header">
				<span class="ribbon ribbon-primary ribbon-shape"><span>Error Logs</span></span>
				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataList()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>
				<button type="button" class="btn btn-danger btn-sm float-end me-2" onclick="clearLogsError()" title="Clear All Error Logs">
					<i class="ri-delete-bin-6-line"></i> Clear Logs
				</button>
				<select id="error_type" class="form-control form-control-sm float-end me-2" onchange="getDataList()" style="width: 12%!important;">
					<option value=""> All </option>
					<option value="Error"> Error </option>
					<option value="Warning"> Warning </option>
					<option value="Parsing Error"> Parsing Error </option>
					<option value="Notice"> Notice </option>
					<option value="Core Error"> Core Error </option>
					<option value="Core Warning"> Core Warning </option>
					<option value="Compile Error"> Compile Error </option>
					<option value="Compile Warning"> Compile Warning </option>
					<option value="User Error"> User Error </option>
					<option value="User Warning"> User Warning </option>
					<option value="User Notice"> User Notice </option>
					<option value="Runtime Notice"> Runtime Notice </option>
					<option value="Catchable error"> Catchable error </option>
				</select>
				<input type="date" id="date_search" class="form-control form-control-sm float-end me-2" style="width: 13%!important;" onchange="getDataList()" max="<?= date('Y-m-d'); ?>">
			</div>
			<div class="card-body">

				<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataList" class="table table-hover table-striped table-bordered" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Error </th>
								<th> Details </th>
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
		generateDatatable('dataList', 'serverside', 'rbac/list-error', 'nodatadiv', {
			'date_search': $('#date_search').val(),
			'error_type': $('#error_type').val(),
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
					const res = await deleteApi(id, 'rbac/delete-error', getDataList);
					loading('#bodyDiv', false);
				}
			})
	}

	async function clearLogsError(data = null) {
		loadFileContent('rbac/_clearLogsModal.php', 'generalContent', 'xl', 'CLEAR LOGS', data, 'offcanvas');
	}
</script>
