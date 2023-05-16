<div class="row" id="bodyDiv">

	<div class="col-lg-4 col-md-12">

		<div class="row">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="ri-edit-box-line label-icon"></i><strong> Event Information </strong>
			</div>

			<div class="row">
				<div class="col-12">
					<label style="color : #b3b3cc"> Event Name</label><br>
					<span id="event_name_attendance_view" style="font-weight:bold"></span>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-6">
					<label style="color : #b3b3cc"> Event Category</label><br>
					<span id="event_category_attendance_view" style="font-weight:bold"></span>
				</div>

				<div class="col-6">
					<label style="color : #b3b3cc"> Event Status</label><br>
					<span id="event_status_attendance_view" style="font-weight:bold"></span>
				</div>
			</div>
		</div>

		<div class="row mt-4">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="ri-calendar-todo-fill label-icon"></i><strong> Schedule Information </strong>
			</div>
			<div class="p-0 overflow-hidden mb-4" id="bodyScheduleDiv">
				<div id="contentScheduleList"></div>
			</div>
		</div>
	</div>

	<div class="col-lg-8 col-md-12">
		<div class="card ribbon-box border shadow-none mb-lg-0" id="listDiv">
			<div class="card-header text-muted">
				<span class="ribbon ribbon-primary ribbon-shape"><span> List Attendance </span></span>

				<button id="refreshAttendanceListBtn" type="button" class="btn btn-warning btn-sm float-end" onclick="getDataListSlot()" title="Refresh" disabled>
					<i class="ri-refresh-line"></i>
				</button>

				<button id="exportAttendanceListBtn" type="button" class="btn btn-dark btn-sm float-end me-2" onclick="exportAttendanceToPdf()" title="Export to PDF" disabled>
					<i class="ri-printer-line"></i> Print Report
				</button>

			</div>
			<div id="dataListBodyDiv" class="card-body">
				<div id="nodataSlotdiv" style="display: block;"></div>
				<div id="dataListAttendanceDiv" class="card-datatable table-responsive" style="display: none;">
					<table id="dataListAttendance" class="table table-hover table-striped table-bordered" width="100%">
						<thead class="table-dark">
							<tr>
								<th> Name </th>
								<th> Matric ID </th>
								<th> Code / Semester </th>
								<th> Time Attend </th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<input type="hidden" id="attendance_event_id" placeholder="event id" readonly>
	<input type="hidden" id="attendance_slot_id" placeholder="slot id" readonly>

</div>

<script>
	var base_url = null;

	function getPassData(baseUrl, token, data) {
		base_url = baseUrl;

		$('#attendance_event_id').val(data.event_id);
		$('#event_name_attendance_view').text(data.event_name);
		$('#contentScheduleList').html(nodata());
		$('#nodataSlotdiv').html(noSelectDataLeft('slots'));

		setTimeout(function() {
			eventDetails(data.event_id);
			generateListSchedule(data.schedule);
		}, 100);
	}

	async function eventDetails(id) {

		var categoryAtt = {
			'1': 'HEP',
			'2': 'University',
			'3': 'College',
			'4': 'Academic/Faculty',
			'5': 'Association/Club',
		};

		var badgeAtt = {
			'0': '<span class="badge badge-soft-warning"> Draft </span>',
			'1': '<span class="badge badge-soft-info"> Pending Approval </span>',
			'2': '<span class="badge badge-soft-info"> Incoming </span>',
			'3': '<span class="badge badge-soft-success"> Ongoing </span>',
			'4': '<span class="badge badge-soft-primary"> Re-open Attendance </span>',
			'5': '<span class="badge bg-success"> Completed </span>',
			'6': '<span class="badge bg-danger"> Canceled </span>',
			'7': '<span class="badge bg-danger"> Rejected </span>',
		};

		const res = await callApi('get', "event/show/" + id);

		// check if request is success
		if (isSuccess(res)) {
			const data = res.data;
			$('#event_category_attendance_view').text(categoryAtt[data.event_category]);
			$('#event_status_attendance_view').html(badgeAtt[data.event_status]);

		}
	}

	function generateListSchedule(schedule = null) {
		loading('#bodyScheduleDiv', true);

		var listData = noSelectDataLeft();

		var badgeSlot = {
			'1': '<span class="badge badge-soft-info"> Incoming </span>',
			'2': '<span class="badge badge-soft-success"> Ongoing </span>',
			'3': '<span class="badge bg-success"> Completed </span>',
			'4': '<span class="badge bg-danger"> Canceled </span>',
		};

		if (isset(schedule)) {

			listData = '<div class="simplebar-content" style="padding: 0px;">\
                            <ul class="list-group">';
			var slotCount = 1;

			for (let i in schedule) {
				var dayDate = schedule[i].schedule_date;
				var dayName = schedule[i].schedule_day_name;

				var slot = schedule[i].slot;

				for (let j in slot) {
					var slotid = slot[j].slot_id;
					var status = slot[j].slot_status;

					var time_start = slot[j].slot_timestamp_start;
					var time_end = slot[j].slot_timestamp_end;

					listData += '<li id="slot-' + slotid + '" class="list-group-item cardColor" onclick="setSlotID(' + slotid + ')">\
                                    <div class="d-flex align-items-center" role="button">\
                                        <div class="flex-grow-1">\
                                            <div class="d-flex">\
                                                <div class="flex-shrink-0 ms-2">\
                                                    <h6 id="text-' + slotid + '" data-slot="slot-' + slotid + '" class="textColor mt-2"> <b> Slots #' + slotCount + '</b> <small>[' + moment(time_start).format("DD/MM/YYYY") + ', ' + dayName + ', ' + moment(time_start).format("h:mm A") + ' - ' + moment(time_end).format("h:mm A") + ']</small></h6>\
                                                </div>\
                                            </div>\
                                        </div>\
                                        <div class="flex-shrink-0">\
                                            ' + badgeSlot[status] + '\
                                        </div>\
                                    </div>\
                                </li>';

					slotCount++;
				}
			}

			listData += '</ul>\
                        </div>';
		}

		loading('#bodyScheduleDiv', false);
		$('#contentScheduleList').html(listData);
	}

	function setSlotID(slotid) {
		$('#nodataSlotdiv').html(nodata());

		$('#attendance_slot_id').val(slotid);

		$('.cardColor').removeClass("bg-info text-white");
		$('#slot-' + slotid).addClass("bg-info text-white");

		$('.textColor').removeClass("text-white");
		$('#text-' + slotid).addClass("text-white");

		setTimeout(function() {
			getDataListSlot();
		}, 100);
	}

	function getDataListSlot() {
		loading('#listDiv', true);
		generateDatatable('dataListAttendance', 'serverside', 'attendance/list-attendance-slot', 'nodataSlotdiv', {
			'slot_id': $('#attendance_slot_id').val(),
			'event_id': $('#attendance_event_id').val()
		});
		loading('#listDiv', false);
		$('#refreshAttendanceListBtn').attr('disabled', false);
		$('#exportAttendanceListBtn').attr('disabled', false);
	}

	async function exportAttendanceToPdf() {

		loading('#listDiv', true);
		loading('#bodyScheduleDiv', true);
		$('#refreshAttendanceListBtn').attr('disabled', true);

		const res = await callApi('post', "export/attendance-print-list", {
			'event_id': $('#attendance_event_id').val(),
			'slot_id': $('#attendance_slot_id').val(),
		});

		// check if request is success
		if (isSuccess(res)) {
			const data = res.data;
			$('#generatePDF').html(data.result);

			if (isSuccess(data.resCode)) {
				const divToPrint = document.createElement('div');
				divToPrint.setAttribute('id', 'generatePDF');
				divToPrint.innerHTML = data.result

				document.body.appendChild(divToPrint);
				printDiv('generatePDF', 'exportAttendanceListBtn', $('#exportAttendanceListBtn').html(), 'REPORT ATTENDANCE');
				document.body.removeChild(divToPrint);
			} else {
				noti(res.data.resCode, res.data.message);
			}
		}

		setTimeout(function() {
			loading('#listDiv', false);
			loading('#bodyScheduleDiv', false);
			$('#refreshAttendanceListBtn').attr('disabled', false);
		}, 450);

	}
</script>