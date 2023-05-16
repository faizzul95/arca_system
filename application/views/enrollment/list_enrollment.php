@extends('templates.desktop_blade')

@section('content')

<div class="row">

	<div class="col-lg-3 col-md-12">
		<div class="row">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="fa fa-university label-icon"></i><strong> List College </strong>
			</div>
			<div class="p-0 overflow-hidden mb-4" id="bodyCollegeDiv">
				<div id="contentCollegeList"></div>
			</div>
		</div>
	</div>

	<div class="col-lg-9 col-md-12">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header text-muted">
				<span class="ribbon ribbon-primary ribbon-shape"><span> Student </span></span>

				<button id="refreshBtn" type="button" class="btn btn-warning btn-sm float-end" onclick="getDataListEnroll()" title="Refresh" disabled>
					<i class="ri-refresh-line"></i>
				</button>

				@if ($permission['student-register'])
				<button id="registerBtn" type="button" class="btn btn-info btn-sm float-end me-2" onclick="formModal()" title="Add New Student" disabled>
					<i class="ri-add-fill"></i> Add Student
				</button>
				@endif

				@if ($permission['student-export-enrollment'])
				<button id="printBtn" type="button" class="btn btn-dark btn-sm float-end me-2" onclick="printData()" title="Print Student Enrollment Information" disabled>
					<i class="ri-printer-line"></i> Print
				</button>

				<button id="exportBtn" type="button" class="btn btn-dark btn-sm float-end me-2" onclick="exportData()" title="Export Student Enrollment Information" disabled>
					<i class="ri-file-excel-fill"></i> Export as Excel
				</button>
				@endif

				@if ($permission['student-register'])
				@if(currentAcademicOrder() > 1)
				<button id="replicateBtn" type="button" class="btn btn-outline-info btn-sm float-end me-2" onclick="copyData()" title="Replicate data from previous semester" disabled>
					<i class="ri-file-copy-2-line"></i> Copy from Previous
				</button>
				@endif
				@endif

			</div>
			<div class="card-body">

				<div id="studentNoCollegeSelect" style="display: block;"></div>
				<div id="studentListContent" style="display: none;">
					@if ($permission['student-view'])
					<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
					<div id="dataListDiv" class="card-datatable table-responsive" style="display: none;">
						<table id="dataList" class="table table-hover table-striped table-bordered" width="100%">
							<thead class="table-dark">
								<tr>
									<th> Name </th>
									<th> Matric ID </th>
									<th> Code / Semester </th>
									<th> Room No </th>
									<!-- <th> Status </th> -->
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

	<input type="hidden" id="master_college_id" placeholder="college id" readonly>
	<input type="hidden" id="master_college_name" placeholder="college name" readonly>

</div>

<script>
	$(document).ready(function() {
		$('#contentCollegeList').html(nodata(false));
		$('#studentNoCollegeSelect').html(noSelectDataLeft('College'));

		setTimeout(function() {
			getDataListCollege();
		}, 80);
	});

	async function getDataListCollege() {
		$('.cardColor').addClass("bg-info text-white");
		$('.textColor').addClass("text-white");

		loading('#bodyCollegeDiv', true);
		const res = await callApi('get', 'college/college-list-div');

		if (isSuccess(res)) {
			$('#contentCollegeList').html(res.data);
			loading('#bodyCollegeDiv', false);
		}
	}

	async function updateRecord(id) {
		const res = await callApi('get', "student/show/" + id);
		// check if request is success
		if (isSuccess(res)) {
			formModal('update', res.data);
		}
	}

	async function setViewCollege(collegeID, collegeName) {

		$('#master_college_id').val(collegeID);
		$('#master_college_name').val(collegeName);

		$('.cardColor').removeClass("bg-info text-white");
		$('#card-' + collegeID).addClass("bg-info text-white");

		$('.textColor').removeClass("text-white");
		$('#text-' + collegeID).addClass("text-white");

		$('#refreshBtn').prop('disabled', true);
		$('#directoryBtn').prop('disabled', true);
		$('#registerBtn').prop('disabled', true);
		$('#replicateBtn').prop('disabled', true);
		$('#printBtn').prop('disabled', true);
		$('#exportBtn').prop('disabled', true);

		if ($('#master_college_id').val() != '') {
			getDataListEnroll();
		}
	}

	async function getDataListEnroll() {

		$('#refreshBtn').prop('disabled', false);
		$('#directoryBtn').prop('disabled', false);
		$('#registerBtn').prop('disabled', false);
		$('#replicateBtn').prop('disabled', false);
		$('#printBtn').prop('disabled', false);
		$('#exportBtn').prop('disabled', false);
		$('#studentListContent').show();
		$('#studentNoCollegeSelect').hide();
		loadingBtn('exportBtn', false, '<i class="ri-file-excel-fill"></i> Export Excel');

		loading('#bodyDiv', true);
		generateDatatable('dataList', 'serverside', 'student/list-enrollment', 'nodatadiv', {
				'college_id': $('#master_college_id').val()
			},
			[{
				"width": "40%",
				"targets": 0
			}, {
				"width": "14%",
				"targets": 1
			}, {
				"width": "20%",
				"targets": 2
			}, {
				"width": "12%",
				"targets": 3
			}]);
		loading('#bodyDiv', false);
	}

	function formModal(type = 'create', data = null) {
		data = (data == null) ? {
			'college_id': $('#master_college_id').val(),
		} : data;

		data['role_id'] = "<?= currentUserRoleID(); ?>";
		data['branch_id'] = "<?= currentUserBranchID(); ?>";

		if (type == 'create') {
			loadFileContent('enrollment/_studentAssignFormModal.php', 'generalContent', 'fullscreen', 'Assign Student : ' + $('#master_college_name').val(), data);
		} else {
			loadFileContent('enrollment/_studentAssignUpdateFormModal.php', 'generalContent', '600px', 'Update Student : ' + $('#master_college_name').val(), data, 'offcanvas');
		}
	}

	async function copyData() {
		var data = {
			'college_id': $('#master_college_id').val(),
			'role_id': "<?= currentUserRoleID(); ?>",
			'branch_id': "<?= currentUserBranchID(); ?>",
			'academic_order': "<?= currentAcademicOrder(); ?>",
		};

		loadFileContent('enrollment/_studentCopyFormModal.php', 'generalContent', 'fullscreen', 'Duplicate Student : ' + $('#master_college_name').val(), data);
	}

	async function printData() {
		const res = await callApi('get', 'export/enrollment-print-list/' + $('#master_college_id').val());

		if (isSuccess(res)) {

			if (isSuccess(res.data.resCode)) {
				const divToPrint = document.createElement('div');
				divToPrint.setAttribute('id', 'generatePDF');
				divToPrint.innerHTML = res.data.result

				document.body.appendChild(divToPrint);
				printDiv('generatePDF', 'printBtn', $('#printBtn').html(), 'ENROLLMENT LIST');
				document.body.removeChild(divToPrint);
			} else {
				noti(res.data.resCode, res.data.message);
				console.log(res.data.resCode, res.data.message);
			}

			setTimeout(function() {
				loadingBtn('printBtn', false, '<i class="ri-printer-line"></i> Print');
			}, 600);
		}
	}

	async function exportData() {
		loadingBtn('exportBtn', true);

		const res = await callApi('get', 'export/enrollment-export-list/' + $('#master_college_id').val());

		if (isSuccess(res)) {
			noti(res.data.resCode, res.data.message);

			// Create a link to download the Excel file
			const link = document.createElement('a');
			link.href = res.data.path;
			link.download = res.data.filename;
			document.body.appendChild(link);

			// Click the link to start the download
			link.click();

			// Remove the link from the DOM
			document.body.removeChild(link);

		}

		setTimeout(function() {
			loadingBtn('exportBtn', false, '<i class="ri-file-excel-fill"></i> Export Excel');
		}, 350);
	}

	function searchCollege(value) {

		const searchEl = value.toLowerCase();
		const x = document.querySelectorAll('.cardCollege > div:nth-child(1) > div:nth-child(1) > div:nth-child(1) > div:nth-child(2)');
		$('.cardCollege').show();

		if (value != '') {
			x.forEach((list, index) => {
				const data = list.querySelector('h5');
				const title = list.querySelector('h5').innerText.toLowerCase();
				let ids = data.getAttribute('data-card');
				if (!title.includes(searchEl))
					$('#' + ids).hide();
			});
		}
	}

	async function deleteAssignRecord(id) {
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
					const res = await deleteApi(id, 'student/delete', getDataListEnroll);
					loading('#bodyDiv', false);
				}
			})
	}
</script>

@endsection