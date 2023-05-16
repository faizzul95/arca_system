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

		<div class="card ribbon-box border shadow-none mb-3" style="display: none;" id="formRecord">
			<div class="card-header text-muted">
				<span class="ribbon ribbon-primary ribbon-shape"> Attendance Record Section </span>
				<!-- <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle float-end" data-toggle="fullscreen">
					<i class="bx bx-fullscreen fs-22"></i>
				</button> -->
			</div>
			<div class="card-body">
				<div id="inputQrAccessCode" class="input-group">
					<input id="attendanceKeySecret" type="text" class="form-control" oninput="accessCode()" maxlength="30" placeholder="Slot Access Code">
					<button class="btn btn-primary" type="button" id="button-addon2" onclick="accessAttendance()">
						<i class="ri-qr-code-line"></i>
						Scan QR
					</button>
				</div>
				<center>
					<img id="qrDynamicStudent" src="" width="50%" class="img-fluid mt-2 mb-2">
				</center>
			</div>
		</div>

		<div class="card ribbon-box border shadow-none mb-lg-0" id="listDiv">
			<div class="card-header text-muted">
				<span class="ribbon ribbon-primary ribbon-shape"> List Attendance </span>

				<button id="refreshAttendanceListBtn" type="button" class="btn btn-warning btn-sm float-end" onclick="getDataListSlot()" title="Refresh" disabled>
					<i class="ri-refresh-line"></i>
				</button>

				<button id="exportAttendanceListBtn" type="button" class="btn btn-dark btn-sm float-end me-2" onclick="exportAttendanceToPdf()" title="Export to PDF" style="display: none;" disabled>
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
	<input type="hidden" id="session_qr_code" placeholder="session qr code" readonly>

</div>

<script>
	var base_url = null;

	var config_dynamic_qr = {
		'refresh': true,
		'timeout': 12000,
	};

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

					listData += '<li id="slot-' + slotid + '" class="list-group-item cardColor" onclick="setSlotID(' + slotid + ', ' + status + ')">\
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

	function setSlotID(slotid, status) {
		$('#nodataSlotdiv').html(nodata());

		if (status == 2) {
			$('#exportAttendanceListBtn').hide();
			$('#formRecord').show();
		} else {
			status == 1 ? $('#exportAttendanceListBtn').hide() : $('#exportAttendanceListBtn').show();
			$('#formRecord').hide();
		}

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
				printDiv('generatePDF', 'exportAttendanceListBtn', $('#exportAttendanceListBtn').html(), 'REPORT ATTENDANCE');
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

	// Attendance Record
	function accessCode() {
		var codeEnter = $('#attendanceKeySecret').val();
		if (codeEnter.length == 15) {
			accessAttendance(codeEnter);
		}
	}

	function qrAccessCode() {
		$('#attendanceKeySecret').val(''); // reset
	}

	async function accessAttendance(accessCode = null) {

		const res = await callApi('post', "attendance/access-code-organizer", {
			'event_id': $('#attendance_event_id').val(),
			'slot_id': $('#attendance_slot_id').val(),
			'slot_access_code': accessCode,
		});

		if (isSuccess(res)) {
			const data = res.data;

			if (isSuccess(data.resCode)) {
				$('#inputQrAccessCode').hide();
				$('#session_qr_code').val(data.codeSession);
				await createDynamicQR();
			} else {
				$('#attendanceKeySecret').val(''); // reset
				noti(res.data.resCode, res.data.message);
			}

		}
	}

	async function createDynamicQR() {
		const res = await callApi('post', "attendance/dynamic-qrcode-generate", {
			'event_id': $('#attendance_event_id').val(),
			'slot_id': $('#attendance_slot_id').val(),
			'slot_session_code': $('#session_qr_code').val(),
		});

		if (isSuccess(res)) {
			$('#qrDynamicStudent').attr('src', "public/upload/event/temp_session_qr/qr_slot_" + $('#attendance_slot_id').val() + "/" + $('#session_qr_code').val() + ".png");

			if (config_dynamic_qr.refresh) {
				setTimeout(function() {
					updateQRSession()
				}, config_dynamic_qr.timeout);
			}

		}
	}

	async function updateQRSession() {
		const res = await callApi('post', "attendance/session-update", {
			'event_id': $('#attendance_event_id').val(),
			'slot_id': $('#attendance_slot_id').val(),
			'slot_session_code': $('#session_qr_code').val(),
		});

		if (isSuccess(res)) {
			const data = res.data;
			if (isSuccess(data.resCode)) {
				$('#session_qr_code').val(data.codeSession);
				setTimeout(async function() {
					await createDynamicQR();
				}, 50);
			} else {
				$('#session_qr_code').val(''); // reset
				$('#formRecord').hide();
				noti(res.data.resCode, res.data.message);
			}
		}
	}
</script>