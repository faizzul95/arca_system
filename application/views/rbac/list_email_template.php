<div class="row">

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header">
				<span class="ribbon ribbon-primary ribbon-shape"><span> Email Template </span></span>

				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataList()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>

				<?php if (currentUserRoleID() == 1) : ?>
					<button type="button" class="btn btn-info btn-sm float-end me-2" onclick="formModal()" title="Add New">
						<i class="ri-add-fill"></i> Add Template
					</button>
				<?php endif; ?>

				<select id="status_search" class="form-control form-control-sm float-end me-2" onchange="getDataList()" style="width: 12%!important;">
					<option value=""> All </option>
					<option value="1"> Active </option>
					<option value="0"> Inactive </option>
				</select>
			</div>
			<div class="card-body">

				<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataList" class="table table-hover table-striped table-bordered" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Type </th>
								<th> Subject </th>
								<th> Details </th>
								<th> Status </th>
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
		generateDatatable('dataList', 'serverside', 'rbac/list-email-template', 'nodatadiv', {
			'status': $('#status_search').val(),
		});
		loading('#bodyDiv', false);
	}

	async function updateRecord(id) {
		const res = await callApi('get', 'rbac/show-template/' + id);

		if (isSuccess(res)) {
			formModal('update', res.data);
		}
	}

	function formModal(type = 'create', data = null) {
		const modalTitle = (type == 'create') ? 'REGISTER TEMPLATE' : 'UPDATE TEMPLATE';
		loadFileContent('rbac/_emailTemplateFormModal.php', 'generalContent', 'fullscreen', modalTitle, data);
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
</script>