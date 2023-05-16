<div class="row">

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header">
				<span class="ribbon ribbon-primary ribbon-shape"><span>Database</span></span>
				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataList()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>
				<button type="button" class="btn btn-info btn-sm float-end me-2" onclick="backupDB()" title="Add New" style="display: none;">
					<i class="ri-add-fill"></i> Backup Database
				</button>
				<button type="button" class="btn btn-danger btn-sm float-end me-2" onclick="clearcCacheFile()" title="Clear All Cache">
					<i class="ri-folders-line"></i> Clear Cache
				</button>
			</div>
			<div class="card-body">

				<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataList" class="table table-hover table-striped table-bordered" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Backup Name </th>
								<th> Path / Location </th>
								<th> Storage </th>
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
		// generateDatatable('dataList', 'serverside', 'rbac/list-backup-db', 'nodatadiv');
		generateDatatable('dataList', 'serverside', 'rbac/list-backup-db', 'nodatadiv', null,
			[{
				"width": "30%",
				"targets": 0
			}, {
				"width": "25%",
				"targets": 1
			}, {
				"width": "15%",
				"targets": 2
			}, {
				"width": "16%",
				"targets": 3
			}]);
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
					const res = await deleteApi(id, 'rbac/delete-backup', getDataList);
					loading('#bodyDiv', false);

				}
			})
	}

	async function emailBackup(id) {
		const res = await callApi('get', 'rbac/mail/' + id);

		if (isSuccess(res)) {
			res.data['user_full_name'] = "<?= getSession('userFullName') ?>";
			res.data['user_email'] = "<?= getSession('userEmail') ?>";
			loadFormContent('rbac/_dbEmailFormModal.php', 'generalContent', 'lg', 'rbac/sent-mail-backup', 'Email Database Backup', res.data, 'offcanvas');
		}
	}

	async function downloadBackup(filename, url) {
		var link = document.createElement("a");
		link.setAttribute('download', filename);
		link.href = url;
		document.body.appendChild(link);
		link.click();
		link.remove();
	}

	async function backupDB() {
		Swal.fire({
			title: 'Are you sure?',
			html: "Database will be backup!",
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

					const res = await callApi('get', 'rbac/backup-db');

					if (isSuccess(res)) {
						noti(res.data.resCode, res.data.message);
						getDataList();
					}

					loading('#bodyDiv', false);

				}
			})
	}

	async function clearcCacheFile() {
		Swal.fire({
			title: 'Are you sure?',
			html: "All cache will be deleted!",
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
					const res = await callApi('get', 'rbac/remove-cache');
					loading('#bodyDiv', false);
				}
			})
	}
</script>