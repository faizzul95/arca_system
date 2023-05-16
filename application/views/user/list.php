@extends('templates.desktop_blade')

@section('content')

<div class="row">

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header text-muted">
				<span class="ribbon ribbon-primary ribbon-shape"><span>Directory</span></span>

				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataList()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>

				@if ($permission['user-register'])
				<button type="button" class="btn btn-info btn-sm float-end me-2" onclick="formModal()" title="Add User">
					<i class="ri-add-fill"></i> Add User
				</button>
				@endif

				@if ($permission['student-batch-upload'])
				<button type="button" class="btn btn-primary btn-sm float-end me-2" onclick="formBatchStudentUpload()" title="Add User">
					<i class="ri-upload-2-line"></i> Add Student (Batch)
				</button>
				@endif

				<select id="role_id" class="form-control form-control-sm float-end me-2" onchange="getDataList()" style="width: 15%!important;">
					<option value=""> All </option>
				</select>

				@if (currentUserRoleID() == 1)
				<select id="branch" class="form-control form-control-sm float-end me-2" onchange="getDataList()" style="width: 15%!important;">
					<option value=""> All Branch </option>
				</select>
				@endif

			</div>
			<div class="card-body">

				@if($permission['user-view'])
				<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataList" class="table nowrap dt-responsive align-middle table-hover table-bordered dataTable no-footer dtr-inline collapsed" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Avatar </th>
								<th> Full Name </th>
								<th> Contact Info </th>
								<th> Profile </th>
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
	$(document).ready(async function() {
		await getListRole();
		await getListBranch();

		setTimeout(async function() {
			await getDataList();
		}, 15);
	});

	async function getListRole() {
		const res = await callApi('get', 'roles/role-select');

		if (isSuccess(res)) {
			$('#role_id').html(res.data);
		}
	}

	async function getListBranch() {
		const res = await callApi('get', 'branch/branch-select');

		if (isSuccess(res)) {
			$('#branch').html(res.data);
		}
	}

	async function getDataList() {
		loading('#bodyDiv', true);
		generateDatatable('dataList', 'serverside', 'user/list', 'nodatadiv', {
				'role_id': $('#role_id').val(),
				'branch_id': $('#branch').val()
			},
			[{
				"width": "6%",
				"targets": 0
			}, {
				"width": "30%",
				"targets": 1
			}, {
				"width": "27%",
				"targets": 2
			}, {
				"width": "18%",
				"targets": 3
			}, {
				"width": "5%",
				"targets": 4
			}]);
		loading('#bodyDiv', false);
	}

	async function updateRecord(id) {
		const res = await callApi('get', 'user/show/' + id);

		if (isSuccess(res)) {
			formModal('update', res.data);
		}
	}

	function formModal(type = 'create', data = null) {
		const modalTitle = (type == 'create') ? 'REGISTER USER' : 'UPDATE USER';

		let selectBranch = $('#branch').val();

		let additionalData = {
			sessionUser: {
				'userID': "<?= currentUserID(); ?>",
				'roleID': "<?= currentUserRoleID(); ?>",
				'branchID': "<?= currentUserBranchID(); ?>",
				'profileID': "<?= currentUserProfileID(); ?>",
			},
			currentSelectBranch: selectBranch == '' ? '1' : selectBranch
		};

		let dataForm = {
			...data,
			...additionalData
		};

		loadFormContent('user/_userForm.php', 'generalContent', '600px', 'user/save', modalTitle, dataForm, 'offcanvas');
	}

	function formBatchStudentUpload() {
		loadFileContent('user/_batchUploadStudentForm.php', 'generalContent', 'fullscreen', 'Batch Register Student', {
			'role_id': 6
		});
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
					const res = await deleteApi(id, 'user/delete', getDataList);
					loading('#bodyDiv', false);
				}
			})
	}

	async function archiveRecord(id) {
		Swal.fire({
			title: 'Are you sure?',
			html: "This user will be archive!",
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
					const res = await callApi('get', 'user/archive/' + id);

					if (isSuccess(res)) {
						noti(res.status, 'Archieve ');
						await getDataList();
					}

					loading('#bodyDiv', false);
				}
			})
	}

	async function profileRecord(id, userfullname = null) {
		let selectBranch = $('#branch').val();
		loadFileContent('user/_assignProfileFormModal.php', 'generalContent', '500px', 'PROFILE : ' + userfullname, {
			'user_id': id,
			'role_id': "<?= currentUserRoleID(); ?>",
			'branch_id': hasData(selectBranch) ? selectBranch : "<?= currentUserBranchID(); ?>"
		}, 'offcanvas');
	}
</script>

@endsection