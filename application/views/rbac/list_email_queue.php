<div class="row">

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header">
				<span class="ribbon ribbon-primary ribbon-shape"><span> Email Queue </span></span>
				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataList()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>

				<select id="status_search" class="form-control form-control-sm float-end me-2" onchange="getDataList()" style="width: 12%!important;">
					<option value=""> All </option>
					<option value="1"> Pending </option>
					<option value="2"> Running </option>
					<option value="3"> Completed </option>
					<option value="4"> Failed </option>
				</select>

				<input type="date" id="created_at" class="form-control form-control-sm float-end me-2" style="width: 13%!important;" onchange="getDataList()" value="<?= date('Y-m-d'); ?>" max="<?= date('Y-m-d'); ?>">
			</div>
			<div class="card-body">

				<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataList" class="table table-hover table-striped table-bordered" width="100%">
						<thead class="table-dark">
							<tr>
								<th> UuID </th>
								<th> Payload </th>
								<th> Attempt </th>
								<th> Status </th>
								<th> Message </th>
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
		generateDatatable('dataList', 'serverside', 'rbac/list-email-queue', 'nodatadiv', {
			'status': $('#status_search').val(),
			'created_at': $('#created_at').val(),
		});
		loading('#bodyDiv', false);
	}

	async function viewPreviewEmail(id) {
		const res = await callApi('get', 'rbac/show-preview-email/' + id);

		if (isSuccess(res)) {
			loadFileContent('rbac/_emailQueuePreviewModal.php', 'generalContent', 'lg', 'Preview Email', res.data);
		}
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
					const res = await deleteApi(id, 'rbac/delete-queue-email', getDataList);
					loading('#bodyDiv', false);
				}
			})
	}
</script>