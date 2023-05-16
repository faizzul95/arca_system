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

				<a id="advanceSearchBtn" href="javascript:void(0)" class="float-end me-2" onclick="advanceSearch('open')" title="Click to open/close advanced search">
					<i class="ri-search-2-line"></i>
					Advanced search
				</a>

			</div>
			<div class="card-body">

				<div id="advanceSearch" class="row" style="display: none;">
					<div class="col-lg-3 col-md-12 mb-3">
						<div class="form-group">
							<label for="academic_id_filter">Academic</label>
							<select id="academic_id_filter" class="form-control form-control-sm" onchange="getDataList()">
								<option value="All"> All Academic </option>
							</select>
						</div>
					</div>

					<div class="col-lg-3 col-md-12 mb-3">
						<div class="form-group">
							<label for="approval_status_filter">College</label>
							<select id="college_id_filter" class="form-control form-control-sm" onchange="getDataList()">
								<option value=""> All College </option>
							</select>
						</div>
					</div>

					<div class="col-lg-3 col-md-12 mb-3">
						<div class="form-group">
							<label for="is_apply_filter">Application Status</label>
							<select id="is_apply_filter" class="form-control form-control-sm" onchange="getDataList()">
								<option value=""> All Application </option>
								<option value="1"> Apply </option>
								<option value="0"> Not Apply </option>
							</select>
						</div>
					</div>

					<div class="col-lg-2 col-md-12 mb-3">
						<div class="form-group">
							<label for="approval_status_filter"> Scrutinize Status </label>
							<select id="approval_status_filter" class="form-control form-control-sm" onchange="getDataList()">
								<option value=""> All Status </option>
								<option value="0"> Pending </option>
								<option value="1"> Offered </option>
								<option value="2"> Unoffered </option>
							</select>
						</div>
					</div>

					<div class="col-lg-1 col-md-12 mb-3">
						<div class="row">
							<div class="col-lg-12">
								<div class="form-group">
									<label for="button_close_filter" style="visibility: hidden;">Action</label>
									<button class="btn btn-sm btn-primary form-control" title="Reset filter" onclick="advanceReset()">
										<i class="ri-refresh-fill" class="text-danger mt-3"></i> Reset
									</button>
								</div>
							</div>
							<!-- <div class="col-lg-4">
								<div class="form-group">
									<label for="button_close_filter" style="visibility: hidden;">Action</label>
									<button class="btn btn-sm btn-outline-danger float-end" title="close advanced search" onclick="advanceSearch('close')">
										<i class="ri-close-fill" class="text-danger"></i>
									</button>
								</div>
							</div> -->
						</div>
					</div>

				</div>

				@if($permission['college-application-list'])
				<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataList" class="table nowrap dt-responsive align-middle table-hover table-bordered dataTable no-footer dtr-inline collapsed" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Full Name </th>
								<th> Matric ID </th>
								<th> Program </th>
								<th> College </th>
								<th> Sticker Collection </th>
								<th> Status </th>
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
		await getAcademicListSelect();
		await collegeSelect();

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

	async function collegeSelect() {
		const res = await callApi('get', 'college/college-select/null/true');

		if (isSuccess(res)) {
			$('#college_id_filter').html(res.data);
		}
	}

	async function getDataList() {
		await getIntoData();

		loading('#bodyDiv', true);
		generateDatatable('dataList', 'serverside', 'applications/list-application-admin', 'nodatadiv', {
			'is_apply': $('#is_apply_filter').val(),
			'academic_id': $('#academic_id_filter').val(),
			'college_id': $('#college_id_filter').val(),
			'approval_status': $('#approval_status_filter').val(),
		});
		loading('#bodyDiv', false);
	}

	async function getIntoData() {
		const res = await callApi('post', 'applications/card-info', {
			'is_apply': $('#is_apply_filter').val(),
			'academic_id': $('#academic_id_filter').val(),
			'college_id': $('#college_id_filter').val(),
		});

		if (isSuccess(res)) {
			const data = res.data;
			$('#totalStudent').text(data.totalStudent);
			$('#totalApply').text(data.totalApply);
			$('#totalNotApply').text(data.totalNotApply);
			$('#totalScrutinizeSuccess').text(data.totalScrutinizeSuccess);
			$('#totalScrutinizeFailed').text(data.totalScrutinizeFailed);
		}
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
		$('#college_id_filter').val('');
		$('#is_apply_filter').val('');
		$('#approval_status_filter').val('');

		await getAcademicListSelect();

		setTimeout(async function() {
			getDataList();
		}, 20);
	}
</script>

@endsection