@extends('templates.desktop_blade')

@section('content')

<div class="row">

	<div class="col-xl-12 mb-4">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="bodyDiv">
			<div class="card-header text-muted">
				<span class="ribbon ribbon-primary ribbon-shape"><span>Event</span></span>

				<button type="button" class="btn btn-warning btn-sm float-end" onclick="getDataList()" title="Refresh">
					<i class="ri-refresh-line"></i>
				</button>

				<select id="event_status_filter" class="form-control form-control-sm float-end me-2" onchange="getDataList()" style="width: 12%!important;">
					<option value=""> All </option>
					<option value="2"> Incoming </option>
					<option value="3"> Ongoing </option>
					<option value="5"> Completed </option>
					<option value="6"> Canceled </option>
				</select>

				<select id="academic_id_filter" class="form-control form-control-sm float-end me-2" onchange="getDataList()" style="width: 12%!important;">
					<option value=""> All </option>
				</select>

			</div>
			<div class="card-body">

				@if ($permission['event-student-view-list'])
				<div id="nodatadiv" style="display: none;"> <?php nodata() ?> </div>
				<div id="dataListDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataList" class="table nowrap dt-responsive align-middle table-hover table-bordered dataTable no-footer dtr-inline collapsed" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Event </th>
								<th> Category </th>
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
	$(document).ready(async function() {
		await getAcademicListSelect();
		await getDataList();
	});

	async function getDataList() {
		loading('#bodyDiv', true);
		generateDatatable('dataList', 'serverside', 'event/list-event', 'nodatadiv', {
			'academic_id': $('#academic_id_filter').val(),
			'event_status': $('#event_status_filter').val()
		});
		loading('#bodyDiv', false);
	}

	async function getAcademicListSelect() {
		const res = await callApi('get', 'academic/academic-event-select');

		if (isSuccess(res)) {
			$('#academic_id_filter').html(res.data);
		}
	}

	async function updateRecord(id) {
		const res = await callApi('get', "event/show/" + id);

		if (isSuccess(res)) {
			formModal('update', res.data)
		}
	}

	function formModal(type = 'create', data = null) {
		// data.push({
		//     'role_id': "<?= currentUserRoleID(); ?>",
		// });
		const modalTitle = (type == 'create') ? 'REGISTER EVENT' : 'UPDATE EVENT';
		loadFormContent('event/_eventForm.php', 'generalContent', 'fullscreen', 'event/save', modalTitle, data);
	}

	async function viewRecord(id) {
		const res = await callApi('get', "event/show/" + id);

		// check if request is success
		if (isSuccess(res)) {
			res.data['role_id'] = "<?= currentUserRoleID(); ?>";
			loadFileContent('event/_eventView.php', 'generalContent', 'fullscreen', 'Event Information', res.data);
		}
	}

	async function viewAttendance(id, eventName) {
		const res = await callApi('get', "event/show/" + id);

		// check if request is success
		if (isSuccess(res)) {
			loadFileContent('event/_eventViewAttendanceList.php', 'generalContent', 'fullscreen', 'Event Attendance View', res.data);
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
					const res = await deleteApi(id, 'event/delete', getDataList);
					loading('#bodyDiv', false);
				}
			})
	}

	function cancelEvent(eventID = null) {
		Swal.fire({
			title: 'Are you sure?',
			html: "Event will be canceled and cannot be change back",
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

					const res = await callApi('post', 'event/cancel-event', {
						'event_id': (eventID != null) ? eventID : $('#event_id').val(),
						'event_status': 6
					});

					if (isSuccess(res)) {
						noti(res.status, 'Event has been canceled');
						getDataList(); // refresh list
						closeModal('#generalModal-fullscreen');
					}

					loading('#bodyDiv', false);
				}
			})
	}
</script>

@endsection