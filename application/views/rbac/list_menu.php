<div class="row">

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header">
				<span class="ribbon ribbon-primary ribbon-shape"><span>Menu</span></span>
				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataList()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>
				<button type="button" class="btn btn-info btn-sm float-end me-2" onclick="formModal()" title="Add New">
					<i class="ri-add-fill"></i> Add Menu
				</button>
			</div>
			<div class="card-body">

				<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataList" class="table table-hover table-striped table-bordered" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Title </th>
								<th> URL </th>
								<th> Type </th>
								<th> Order </th>
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
		generateDatatable('dataList', 'serverside', 'menu/list-menu', 'nodatadiv');
		loading('#bodyDiv', false);
	}

	function formModal(type = 'create', data = null) {
		const modalTitle = (type == 'create') ? 'REGISTER MENU' : 'UPDATE MENU';
		loadFormContent('rbac/_menuFormModal.php', 'generalContent', 'lg', 'menu/save', modalTitle, data, 'offcanvas');
	}

	async function updateRecord(id) {
		const res = await callApi('get', 'menu/update/' + id);

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
					const res = await deleteApi(id, 'menu/delete', getDataList);
					loading('#bodyDiv', false);
				}
			})
	}

	function abilitiesRecord(menuID, menuTitle) {
		const data = {
			menu_id: menuID,
			menu_title: menuTitle,
		};

		loadFileContent('rbac/_abilitiesFormModal.php', 'generalContent', 'fullscreen', 'ABILITIES MENU : ' + menuTitle, data);
	}
</script>