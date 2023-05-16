<div class="row">

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header text-muted">
				<span class="ribbon ribbon-primary ribbon-shape"><span> Faculty </span></span>
				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataList()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>
				<button type="button" class="btn btn-info btn-sm float-end me-2" onclick="formModal()" title="Add New">
					<i class="ri-add-fill"></i> Add Faculty
				</button>
			</div>
			<div class="card-body">

				<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataList" class="table table-hover table-striped table-bordered" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Faculty Name </th>
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
		getDataList();
	});

	async function getDataList() {
		loading('#bodyDiv', true);
		generateDatatable('dataList', 'serverside', 'management/list-faculty', 'nodatadiv', null,
			[{
				"width": "60%",
				"targets": 0
			}, {
				"width": "16%",
				"targets": 1
			}, {
				"width": "10%",
				"targets": 2
			}]);
		loading('#bodyDiv', false);
	}

	function formModal(type = 'create', data = null) {
		const modalTitle = (type == 'create') ? 'REGISTER FACULTY' : 'UPDATE FACULTY';
		loadFormContent('management/_facultyFormModal.php', 'generalContent', '550px', 'management/save-faculty', modalTitle, data, 'offcanvas');
	}

	async function updateRecord(id) {
		const res = await callApi('get', 'management/show-faculty/' + id);

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
					const res = await deleteApi(id, 'management/delete-faculty', getDataList);
					loading('#bodyDiv', false);

				}
			})
	}
</script>