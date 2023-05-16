@extends('templates.desktop_blade')

@section('content')

<div class="row">
	<div class="col-xl-12">
		<div class="card crm-widget">
			<div class="card-body p-0">
				<div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
					<div class="col">
						<div class="py-4 px-3">
							<h5 class="text-muted text-uppercase fs-13"> Total Student </h5>
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0">
									<i class="ri-user-2-line display-6 text-primary"></i>
								</div>
								<div class="flex-grow-1 ms-3">
									<h2 class="mb-0"><span id="totalStudent" class="counter-value" data-target="0">0</span></h2>
								</div>
							</div>
						</div>
					</div><!-- end col -->
					<div class="col">
						<div class="mt-3 mt-md-0 py-4 px-3">
							<h5 class="text-muted text-uppercase fs-13"> Total Apply </h5>
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0">
									<i class="ri-file-edit-line display-6 text-info"></i>
								</div>
								<div class="flex-grow-1 ms-3">
									<h2 class="mb-0"><span id="totalApply" class="counter-value" data-target="0">0</span></h2>
								</div>
							</div>
						</div>
					</div><!-- end col -->
					<div class="col">
						<div class="mt-3 mt-md-0 py-4 px-3">
							<h5 class="text-muted text-uppercase fs-13"> Total Not Apply </h5>
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0">
									<i class="ri-chat-delete-line display-6 text-warning"></i>
								</div>
								<div class="flex-grow-1 ms-3">
									<h2 class="mb-0"><span id="totalNotApply" class="counter-value" data-target="0">0</span></h2>
								</div>
							</div>
						</div>
					</div><!-- end col -->
					<div class="col">
						<div class="mt-3 mt-lg-0 py-4 px-3">
							<h5 class="text-muted text-uppercase fs-13"> Offered College </h5>
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0">
									<i class="ri-check-double-line display-6 text-success"></i>
								</div>
								<div class="flex-grow-1 ms-3">
									<h2 class="mb-0"> <span id="totalScrutinizeSuccess" class="counter-value" data-target="0">0</span></h2>
								</div>
							</div>
						</div>
					</div><!-- end col -->
					<div class="col">
						<div class="mt-3 mt-lg-0 py-4 px-3">
							<h5 class="text-muted text-uppercase fs-13"> Unoffered College </h5>
							<div class="d-flex align-items-center">
								<div class="flex-shrink-0">
									<i class="ri-close-line display-6 text-danger"></i>
								</div>
								<div class="flex-grow-1 ms-3">
									<h2 class="mb-0"><span id="totalScrutinizeFailed" class="counter-value" data-target="0">0</span></h2>
								</div>
							</div>
						</div>
					</div><!-- end col -->
				</div><!-- end row -->
			</div><!-- end card body -->
		</div><!-- end card -->
	</div>
</div>

<div class="row">

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header text-muted">
				<span class="ribbon ribbon-primary ribbon-shape"><span> Application </span></span>

				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataList()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>

				@if($permission['college-application-bulk-approval'])
				<div class="btn-group float-end me-2" role="group">
					<button id="btnGroupDrop1" type="button" class="btn btn-sm btn-soft-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
						Bulk Action
					</button>
					<ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
						<li><a class="dropdown-item" href="javascript:void(0);" onclick="bulkApproveMarked()"> Bulk Offered (Marked) </a></li>
						<li><a class="dropdown-item" href="javascript:void(0);" onclick="bulkRejectMarked()"> Bulk Reject (Marked) </a></li>
						<li><a class="dropdown-item" href="javascript:void(0);" onclick="bulkApproveEligible()"> Bulk Offered (Eligible) </a></li>
						<li><a class="dropdown-item" href="javascript:void(0);" onclick="bulkRejectNotEligible()"> Bulk Reject (Not Eligible) </a></li>
					</ul>
				</div>
				@endif

				<a id="advanceSearchBtn" href="javascript:void(0)" class="float-end me-2" onclick="advanceSearch('open')" title="Click to open/close advanced search">
					<i class="ri-search-2-line"></i>
					Advanced search
				</a>
			</div>
			<div class="card-body">

				<div id="advanceSearch" class="row" style="display: none;">
					<div class="col-lg-3 col-md-12 mb-2">
						<div class="form-group">
							<label for="academic_id_filter">Academic</label>
							<select id="academic_id_filter" class="form-control form-control-sm" onchange="getDataList()">
								<option value="All"> All Academic </option>
							</select>
						</div>
					</div>

					<div class="col-lg-3 col-md-12 mb-2">
						<div class="form-group">
							<label for="is_apply_filter">Application Status</label>
							<select id="is_apply_filter" class="form-control form-control-sm" onchange="getDataList()">
								<option value=""> All Application </option>
								<option value="1"> Apply </option>
								<option value="0"> Not Apply </option>
							</select>
						</div>
					</div>

					<div class="col-lg-3 col-md-12 mb-2">
						<div class="form-group">
							<label for="approval_status_filter">Scrutinize Status</label>
							<select id="approval_status_filter" class="form-control form-control-sm" onchange="getDataList()">
								<option value=""> All Status </option>
								<option value="0"> Pending </option>
								<option value="1"> Offered </option>
								<option value="2"> Unoffered </option>
							</select>
						</div>
					</div>

					<div class="col-lg-2 col-md-12 mb-2">
						<div class="col-10">
							<div class="form-group">
								<label for="eligible_status_filter">Eligible Status</label>
								<select id="eligible_status_filter" class="form-control form-control-sm" onchange="getDataList()">
									<option value=""> All Status </option>
									<option value="1"> Eligible </option>
									<option value="2"> Not Eligible </option>
								</select>
							</div>
						</div>
					</div>

					<div class="col-lg-1 col-md-12 mb-3">
						<div class="row">
							<div class="col-lg-8">
								<div class="form-group">
									<label for="button_close_filter" style="visibility: hidden;">Action</label>
									<button class="btn btn-sm btn-primary form-control" title="Reset filter" onclick="advanceReset()">
										<i class="ri-refresh-fill" class="text-danger"></i> Reset
									</button>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="form-group">
									<label for="button_close_filter" style="visibility: hidden;">Action</label>
									<button class="btn btn-sm btn-outline-danger float-end" title="close advanced search" onclick="advanceSearch('close')">
										<i class="ri-close-fill" class="text-danger"></i>
									</button>
								</div>
							</div>
						</div>
					</div>

				</div>

				@if($permission['college-application-list'])
				<h3 id="statusCount" class="mt-2 mb-4" style="display: none;"> SCRUTINIZE STATUS : <span id="statusScrutinize"></span>
					<button id="printUnoffered" style="display: none;" onclick="printUnofferedList()" class="btn btn-sm btn-dark ms-2">
						<i class="fa fa-print" aria-hidden="true"></i> Print Unoffered
					</button>
				</h3>

				<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataList" class="table nowrap dt-responsive align-middle table-hover table-bordered dataTable no-footer dtr-inline collapsed" width="100%">
						<thead class="table-dark">
							<tr>
								<th> # </th>
								<th> Full Name </th>
								<th> Matric ID </th>
								<th> Program </th>
								<th> Sticker Collection </th>
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

<div id="printListApplication"></div>

<script type="text/javascript">
	$(document).ready(async function() {
		await getAcademicListSelect();

		setTimeout(async function() {
			await getDataList();
		}, 20);
	});

	async function getAcademicListSelect() {
		const res = await callApi('get', 'academic/academic-event-select');

		if (isSuccess(res)) {
			$('#academic_id_filter').html(res.data);
		}
	}

	async function getDataList() {
		await getInfoData();

		loading('#bodyDiv', true);
		generateDatatable('dataList', 'serverside', 'applications/list-application-srk', 'nodatadiv', {
			'is_apply': $('#is_apply_filter').val(),
			'academic_id': $('#academic_id_filter').val(),
			'approval_status': $('#approval_status_filter').val(),
			'is_college_eligible': $('#eligible_status_filter').val(),
			'college_id': '<?= decodeID(getSession('collegeID')); ?>',
		});
		loading('#bodyDiv', false);
	}

	async function getInfoData() {
		const res = await callApi('post', 'applications/card-info', {
			'is_apply': $('#is_apply_filter').val(),
			'academic_id': $('#academic_id_filter').val(),
			'college_id': <?= decodeID(getSession('collegeID')); ?>,
		});

		if (isSuccess(res)) {
			const data = res.data;
			$('#totalStudent').text(data.totalStudent);
			$('#totalApply').text(data.totalApply);
			$('#totalNotApply').text(data.totalNotApply);
			$('#totalScrutinizeSuccess').text(data.totalScrutinizeSuccess);
			$('#totalScrutinizeFailed').text(data.totalScrutinizeFailed);

			if (data.totalStudent > 0) {

				if (data.totalScrutinizePending > 0) {
					$('#statusScrutinize').html(data.totalScrutinizePending + ' STUDENTS REMAINING');
					$('#statusScrutinize').attr('class', 'badge bg-danger');
					$('#printUnoffered').hide();

				} else {
					$('#statusScrutinize').html('COMPLETED');
					$('#statusScrutinize').attr('class', 'badge bg-success');
					$('#printUnoffered').show();
				}

				$('#statusCount').show();
				$('#bulkApproveBtn').show();
				$('#bulkRejectBtn').show();

			} else {
				$('#statusCount').hide();
				$('#bulkApproveBtn').hide();
				$('#bulkRejectBtn').hide();
			}

		}
	}

	function bulkApprove() {
		Swal.fire({
			title: 'Are you sure?',
			html: "<small> All remark student will be offer to college for next semester </small>",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Confirm!',
			reverseButtons: true
		}).then(
			async (result) => {
				if (result.isConfirmed) {
					const res = await callApi('post', 'applications/bulk-approve', {
						'academic_id': $('#academic_id_filter').val(),
						'college_id': <?= decodeID(getSession('collegeID')); ?>,
					});

					if (isSuccess(res)) {
						noti(res.data.resCode, res.data.message);
						await getDataList();
					}
				}
			})
	}

	function bulkReject() {
		Swal.fire({
			title: 'Are you sure?',
			html: "Write the reason <br> <small> (All marked students will have the same reason) </small> :",
			input: 'textarea',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, Confirm!',
			reverseButtons: true,
			inputPlaceholder: "Write reason here (max length : 150)",
			inputAttributes: {
				maxlength: 150,
				autocapitalize: 'off',
				autocorrect: 'off'
			},
			inputValidator: (value) => {
				if (!value) {
					return 'You need to write something!'
				}
			}
		}).then(
			async (result) => {
				if (result.isConfirmed) {
					const res = await callApi('post', 'applications/bulk-reject', {
						'academic_id': $('#academic_id_filter').val(),
						'college_id': <?= decodeID(getSession('collegeID')); ?>,
						'approval_remark': result.value,
					});

					if (isSuccess(res)) {
						noti(res.data.resCode, res.data.message);
						await getDataList();
					}
				}
			})
	}

	async function checkedStudent(currentStatus, appID) {

		var newStatus = currentStatus == 1 ? 0 : 1;

		const res = await callApi('post', 'applications/checked-status-application', {
			'application_id': appID,
			'scrutinize_check_status': newStatus,
		});

		if (isSuccess(res)) {
			$('#ch' + appID).attr('onclick', 'checkedStudent(' + newStatus + ',' + appID + ')');
		}
	}

	async function approveApplication(id, userfullname = null) {
		const res = await callApi('get', "applications/show/" + id);

		if (isSuccess(res)) {
			loadFileContent('application/_approvalFormModal.php', 'generalContent', '550px', 'Approval Form', res.data, 'offcanvas');
		}
	}

	async function printUnofferedList() {
		loadFileContent('application/_printUnofferedCollegeModal.php', 'generalContent', 'fullscreen', 'Application : Unoffered List', {
			'academic_id': $('#academic_id_filter').val(),
			'college_id': <?= decodeID(getSession('collegeID')); ?>,
			'approval_status': 2,
		});
	}

	function advanceSearch(type) {
		if (type == 'open') {
			$('#advanceSearch').show();
			$("#advanceSearchBtn").attr("onclick", "advanceSearch('close')");
		} else {
			$('#advanceSearch').hide();
			$("#advanceSearchBtn").attr("onclick", "advanceSearch('open')");
		}
	}

	async function advanceReset() {
		$('#academic_id_filter').val('');
		$('#is_apply_filter').val('');
		$('#approval_status_filter').val('');
		$('#eligible_status_filter').val('');

		await getAcademicListSelect();

		setTimeout(async function() {
			getDataList();
		}, 20);
	}

	function bulkApproveMarked() {
		loadFileContent('application/_bulkOfferedCheckedFormModal.php', 'generalContent', 'fullscreen', 'Application : Bulk Offered (Marked)', {
			'academic_id': $('#academic_id_filter').val(),
			'college_id': <?= decodeID(getSession('collegeID')); ?>,
			'scrutinize_check_status': 1,
		});
	}

	function bulkRejectMarked() {
		loadFileContent('application/_bulkRejectCheckedFormModal.php', 'generalContent', 'fullscreen', 'Application : Bulk Reject (Marked)', {
			'academic_id': $('#academic_id_filter').val(),
			'college_id': <?= decodeID(getSession('collegeID')); ?>,
			'scrutinize_check_status': 0,
		});
	}

	function bulkApproveEligible() {
		loadFileContent('application/_bulkOfferedEligibleFormModal.php', 'generalContent', 'fullscreen', 'Application : Bulk Offered (Eligible)', {
			'academic_id': $('#academic_id_filter').val(),
			'college_id': <?= decodeID(getSession('collegeID')); ?>,
			'is_college_eligible': 1, // from table student_sticker_collection
		});
	}

	function bulkRejectNotEligible() {
		loadFileContent('application/_bulkRejectNotEligibleFormModal.php', 'generalContent', 'fullscreen', 'Application : Bulk Reject (Not Eligible)', {
			'academic_id': $('#academic_id_filter').val(),
			'college_id': <?= decodeID(getSession('collegeID')); ?>,
			'is_college_eligible': 2, // from table student_sticker_collection
		});
	}
</script>

@endsection