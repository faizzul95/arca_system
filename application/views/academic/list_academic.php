@extends('templates.desktop_blade')

@section('content')

<div class="row">

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header text-muted">
				<span class="ribbon ribbon-primary ribbon-shape"><span> Academic </span></span>
				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataList()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>

				@if ($permission['academic-register'])
				<button type="button" class="btn btn-info btn-sm float-end me-2" onclick="formModal()" title="Add New">
					<i class="ri-add-fill"></i> Add Academic
				</button>
				@endif

			</div>
			<div class="card-body">

				@if ($permission['academic-view'])
				<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataList" class="table table-hover table-striped table-bordered" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Name </th>
								<th> Year </th>
								<th> Date </th>
								<th> Status </th>
								<th> Action </th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				@else
				{{ nodataAccess() }}
				@endif

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
		generateDatatable('dataList', 'serverside', 'academic/list-academic', 'nodatadiv');
		loading('#bodyDiv', false);
	}

	function formModal(type = 'create', data = null) {
		const modalTitle = (type == 'create') ? 'REGISTER ACADEMIC' : 'UPDATE ACADEMIC';
		loadFormContent('academic/_academicFormModal.php', 'generalContent', '500px', 'academic/save', modalTitle, data, 'offcanvas');
	}

	async function updateRecord(id) {
		const res = await callApi('get', 'academic/show/' + id);

		if (isSuccess(res)) {
			formModal('update', res.data);
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
					const res = await deleteApi(id, 'academic/delete', getDataList);
					loading('#bodyDiv', false);

				}
			})
	}

	async function setDefaultAcademic(academicID, branchID, academicName, academicOrder) {
		Swal.fire({
			title: 'Are you sure?',
			html: "Switch to academic <b>" + academicName + "?",
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
					const res = await callApi('post', 'academic/switch-academic', {
						'academic_id': academicID,
						'branch_id': branchID,
						'academic_name': academicName,
						'academic_order': academicOrder,
					});

					if (isSuccess(res)) {
						noti(res.status, 'Current academic update');
						getDataList();
					}

					loading('#bodyDiv', false);
				}
			})
	}
</script>

@endsection