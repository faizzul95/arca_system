<div class="row">

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header text-muted">
				<span class="ribbon ribbon-primary ribbon-shape"><span>Roles</span></span>
				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataListRole()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>
				<button type="button" class="btn btn-info btn-sm float-end me-2" onclick="formModal()" title="Add New">
					<i class="ri-add-fill"></i> Add Roles
				</button>
			</div>
			<div class="card-body">

				<div id="nodataRolediv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListRoleDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataListRole" class="table table-hover table-striped table-bordered" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Name </th>
								<th> Code </th>
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
		getDataListRole();
	});

	async function getDataListRole() {
		loading('#bodyDiv', true);
		generateDatatable('dataListRole', 'serverside', 'roles/list-roles', 'nodataRolediv');
		loading('#bodyDiv', false);
	}

	function formModal(type = 'create', data = null) {
		const modalTitle = (type == 'create') ? 'REGISTER ROLES' : 'UPDATE ROLES';
		loadFormContent('rbac/_roleFormModal.php', 'generalContent', 'lg', 'roles/save', modalTitle, data, 'offcanvas');
	}

	async function updateRecord(id) {
		const res = await callApi('get', 'roles/update/' + id);

		if (isSuccess(res)) {
			formModal('update', res.data)
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
					const res = await deleteApi(id, 'roles/delete', getDataListRole);
					loading('#bodyDiv', false);
				}
			})
	}

	function permissionRecord(roleID, roleName) {
		const data = {
			role_id: roleID,
			role_name: roleName,
		};
		loadFileContent('rbac/_permissionFormModal.php', 'generalContent', 'fullscreen', 'PERMISSION ROLE : ' + roleName, data);
	}
</script>