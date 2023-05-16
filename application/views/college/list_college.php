@extends('templates.desktop_blade')

@section('content')

<div class="row">

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header text-muted">
				<span class="ribbon ribbon-primary ribbon-shape"><span> College </span></span>
				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataList()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>

				@if ($permission['college-register'])
				<button type="button" class="btn btn-info btn-sm float-end me-2" onclick="formModal()" title="Add New">
					<i class="ri-add-fill"></i> Add College
				</button>
				@endif

			</div>
			<div class="card-body">

				@if ($permission['college-view'])
				<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataList" class="table table-hover table-striped table-bordered" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Name </th>
								<th> Code </th>
								<th> Capacity </th>
								<th> Total Room </th>
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
		generateDatatable('dataList', 'serverside', 'college/list-college', 'nodatadiv');
		loading('#bodyDiv', false);
	}

	function formModal(type = 'create', data = null) {
		const modalTitle = (type == 'create') ? 'REGISTER COLLEGE' : 'UPDATE COLLEGE';
		loadFormContent('college/_collegeFormModal.php', 'generalContent', 'lg', 'college/save-college', modalTitle, data, 'offcanvas');
	}

	async function updateRecord(id) {
		const res = await callApi('get', 'college/show/' + id);

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
					const res = await deleteApi(id, 'college/delete-college', getDataList);
					loading('#bodyDiv', false);

				}
			})
	}

	async function roomRecord(collegeID, collegeName) {
		loadFileContent('college/_roomFormModal.php', 'generalContent', 'fullscreen', 'ROOM FOR COLLEGE : ' + collegeName, {
			'college_id': collegeID,
		});
	}
</script>

@endsection