<div class="row" id="bodyDiv">

	<!-- Event information -->
	<div class="col-lg-6 col-md-12 fill border-right p-4 overflow-hidden">

		<div class="row">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="ri-edit-box-line label-icon"></i><strong> Event Information </strong>
			</div>

			<div class="row">
				<div class="col-12">
					<label style="color : #b3b3cc"> Event Name</label><br>
					<span id="event_name_view" style="font-weight:bold"></span>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-6">
					<label style="color : #b3b3cc"> Event Category</label><br>
					<span id="event_category_view" style="font-weight:bold"></span>
				</div>

				<div class="col-6">
					<label style="color : #b3b3cc"> Event Status</label><br>
					<span id="event_status_view" style="font-weight:bold"></span>
				</div>
			</div>
		</div>

		<!-- Organizer information -->
		<div class="row mt-4">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="ri-user-2-line label-icon"></i><strong> Organizer Information </strong>
			</div>
		</div>

		<div id="listOrganizer"></div>

	</div>

	<!-- Schedule information -->
	<div class="col-lg-6 col-md-12 fill border-right p-4">
		<div class="row">
			<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
				<i class="ri-calendar-todo-line label-icon"></i><strong> Schedule Information </strong>
			</div>
		</div>

		<div class="row mt-2 mb-2">
			<div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-primary" id="listSchedule"></div>
		</div>
	</div>

</div>

<script>
	var organizerDtList = {};
	var scheduleList = {};
	var base_url = null;
	var userRoleID = null;

	function getPassData(baseUrl, token, data) {

		base_url = baseUrl;
		userRoleID = data.role_id;

		if (Object.keys(data).length > 1) {

			var category = {
				'1': 'HEP',
				'2': 'University',
				'3': 'College',
				'4': 'Academic/Faculty',
				'5': 'Association/Club',
			};

			var badge = {
				'0': '<span class="badge badge-soft-warning"> Draft </span>',
				'1': '<span class="badge badge-soft-info"> Pending Approval </span>',
				'2': '<span class="badge badge-soft-info"> Incoming </span>',
				'3': '<span class="badge badge-soft-success"> Ongoing </span>',
				'4': '<span class="badge badge-soft-primary"> Re-open Attendance </span>',
				'5': '<span class="badge bg-success"> Completed </span>',
				'6': '<span class="badge bg-danger"> Canceled </span>',
				'7': '<span class="badge bg-danger"> Rejected </span>',
			};

			$('#event_name_view').html(data.event_name);
			$("#event_category_view").html(category[data.event_category]);
			$("#event_status_view").html(badge[data.event_status]);

			if (data.organizer.length > 0) {
				for (i = 0; i < data.organizer.length; i++) {
					var orgUserID = data.organizer[i]['user_id'];
					organizerDtList[orgUserID] = {
						"user_id": data.organizer[i]['user_id'],
						"user_full_name": data.organizer[i]['user']['user_full_name'],
						"user_matric_code": data.organizer[i]['user']['user_matric_code'],
						"program_code": data.organizer[i]['user']['program_code'],
						"user_contact_no": data.organizer[i]['user']['user_contact_no'],
						"user_email": data.organizer[i]['user']['user_email'],
						"organizer_id": data.organizer[i]['organizer_id'],
					};
				}

				generateTableOrganizer();
			} else {
				$('#listOrganizer').html(nodata());
			}

			if (data.schedule.length > 0) {
				for (i = 0; i < data.schedule.length; i++) {
					var scheduleID = data.schedule[i]['schedule_id'];
					scheduleList[scheduleID] = {
						"schedule_id": data.schedule[i]['schedule_id'],
						"schedule_date": data.schedule[i]['schedule_date'],
						"schedule_day_name": data.schedule[i]['schedule_day_name'],
						"schedule_venue": data.schedule[i]['schedule_venue'],
						"slot": data.schedule[i]['slot'],
					};
				}

				generateScheduleAccordion();
			} else {
				$('#listSchedule').html(nodata());
			}

		} else {
			$('#listOrganizer').html(nodata());
			$('#listSchedule').html(nodata());
		}
	}

	// organizer
	function generateTableOrganizer() {

		if (Object.keys(organizerDtList).length > 0) {
			$('#listOrganizer').empty(); // reset table

			$('#listOrganizer').append('<div style="overflow-x:auto;">\
                        <div class="table-responsive">\
                            <table class="table table-bordered table-hover table-striped w-100">\
                                <thead class="table-dark">\
                                    <th> Details </th>\
                                    <th> Contact Info </th>\
                                </thead>\
                                <tbody id="organizerData"></tbody>\
                            </table>\
                        </div>\
                     </div>');

			setTimeout(function() {
				var no = 1;
				for (let i in organizerDtList) {
					$('#organizerData').append('<tr>\
                    <td> Name : ' + organizerDtList[i]['user_full_name'] + ' <br> Matric ID : ' + organizerDtList[i]['user_matric_code'] + '</td>\
                    <td> Phone : ' + organizerDtList[i]['user_contact_no'] + ' <br> Email : ' + organizerDtList[i]['user_email'] + '</td>\
                </tr>');
					no++;
				}
			}, 220);
		} else {
			$('#listOrganizer').html(nodata());
		}

	}

	// schedule
	function generateScheduleAccordion() {

		if (Object.keys(scheduleList).length > 0) {
			$('#listSchedule').empty(); // reset table

			var badgeSlot = {
				'1': '<span class="badge badge-soft-info"> Incoming </span>',
				'2': '<span class="badge badge-soft-success"> Ongoing </span>',
				'3': '<span class="badge bg-success"> Completed </span>',
				'4': '<span class="badge bg-danger"> Canceled </span>',
			};

			for (let i in scheduleList) {
				// var collapseItem = (i != 1) ? 'collapse' : '';
				// var collapseBtn = (i != 1) ? 'collapsed' : '';
				// var collapseExpand = (i != 1) ? 'false' : 'true';
				var collapseItem = (i != 1) ? 'collapse' : 'collapse';
				var collapseBtn = (i != 1) ? 'collapsed' : 'collapsed';
				var collapseExpand = (i != 1) ? 'false' : 'false';
				$('#listSchedule').append('<div class="card accordion-item">\
                    <h2 class="accordion-header d-flex align-items-center">\
                        <button type="button" class="accordion-button ' + collapseBtn + '" data-bs-toggle="collapse" data-bs-target="#schedule-' + i + '" aria-expanded="' + collapseExpand + '">\
                            <i class="bx bx-calendar me-2"></i>\
                            ' + scheduleList[i].schedule_day_name + ' (' + moment(scheduleList[i].schedule_date).format("DD/MM/YYYY") + ')\
                        </button>\
                    </h2>\
                    <div id="schedule-' + i + '" class="accordion-collapse ' + collapseItem + '">\
                        <div class="accordion-body">\
                            <div class="row">\
                                <div class="col-lg-12">\
                                    <div class="row">\
                                        <label style="color : #b3b3cc"> Venue</label><br>\
                                        <span style="font-weight:bold"> ' + scheduleList[i].schedule_venue + ' </span>\
                                    </div>\
                                </div>\
                            </div>\
                            <div class="row mt-4">\
                                <div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">\
                                    <i class="ri-timer-line label-icon"></i><strong> Slot Information </strong>\
                                </div>\
                            </div>\
                            <div class="row mt-2">\
                                <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-info" id="listSlot-' + i + '"></div>\
                            </div>\
                        </div>\
                    </div>\
                </div>');

				if (Object.keys(scheduleList[i].slot).length > 0) {
					setTimeout(function() {
						for (let j in scheduleList[i].slot) {

							var sticker = (scheduleList[i].slot[j].slot_sticker_acquired == 1) ? 'Yes' : 'No';
							var time_start = scheduleList[i].schedule_date + " " + scheduleList[i].slot[j].slot_time_start;
							var time_end = scheduleList[i].schedule_date + " " + scheduleList[i].slot[j].slot_time_end;
							var qrcode = scheduleList[i].slot[j].qrCode.files_path;

							var collapseItem = (i != 1) ? 'collapse' : 'collapse';
							var collapseBtn = (i != 1) ? 'collapsed' : 'collapsed';
							var collapseExpand = (i != 1) ? 'false' : 'false';

							var remark = scheduleList[i].slot[j].slot_remark == '' ? '-' : scheduleList[i].slot[j].slot_remark

							if (userRoleID == 1 || userRoleID == 2) {
								$('#listSlot-' + i).append('<div class="card accordion-item">\
                                <h2 class="accordion-header d-flex align-items-center">\
                                    <button type="button" class="accordion-button ' + collapseBtn + '" data-bs-toggle="collapse" data-bs-target="#slot-' + i + '-' + j + '" aria-expanded="' + collapseExpand + '">\
                                        <i class="bx bx-timer me-2"></i>\
                                        Slot #' + (parseInt(j) + 1) + '\
                                    </button>\
                                </h2>\
                                <div id="slot-' + i + '-' + j + '" class="accordion-collapse collapse">\
                                    <div class="accordion-body">\
                                        <div style="overflow-x:auto;">\
                                            <table class="table table-bordered table-sm">\
                                                <tbody>\
                                                    <tr>\
                                                        <td>\
                                                            <label style="color : #b3b3cc"> Time Start</label><br>\
                                                            <span style="font-weight:bold"> ' + moment(time_start).format("h:mm A") + ' </span>\
                                                        </td>\
                                                        <td>\
                                                            <label style="color : #b3b3cc"> Time End </label><br>\
                                                            <span style="font-weight:bold"> ' + moment(time_end).format("h:mm A") + ' </span>\
                                                        </td>\
                                                    </tr>\
                                                    <tr>\
                                                        <td>\
                                                            <label style="color : #b3b3cc"> Participant </label><br>\
                                                            <span style="font-weight:bold"> ' + scheduleList[i].slot[j].slot_participant + ' </span>\
                                                        </td>\
                                                        <td>\
                                                            <label style="color : #b3b3cc"> Sticker Acquired </label><br>\
                                                            <span style="font-weight:bold"> ' + sticker + ' </span>\
                                                        </td>\
                                                    </tr>\
                                                    <tr>\
                                                        <td width="30%">\
                                                            <label style="color : #b3b3cc"> Access Code </label><br>\
                                                            <span style="font-weight:bold"> ' + scheduleList[i].slot[j].slot_access_code + ' </span>\
                                                        </td>\
                                                        <td width="35%">\
                                                            <label style="color : #b3b3cc"> Status </label><br>\
                                                            <span style="font-weight:bold"> ' + badgeSlot[scheduleList[i].slot[j].slot_status] + ' </span>\
                                                        </td>\
                                                    </tr>\
                                                    <tr>\
                                                        <td colspan="2">\
                                                            <label style="color : #b3b3cc"> Tentative / Remark </label><br>\
                                                            <span style="font-weight:bold"> ' + remark + ' </span>\
                                                        </td>\
                                                    </tr>\
                                                    <tr>\
                                                        <td colspan="2">\
                                                            <label style="color : #b3b3cc"> QR CODE </label><br>\
                                                            <img src="' + qrcode + '" width="25%" class="img-fluid" alt="QR CODE Event">\
                                                        </td>\
                                                    </tr>\
                                                </tbody>\
                                            </table>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>');
							} else {
								$('#listSlot-' + i).append('<div class="card accordion-item">\
                                <h2 class="accordion-header d-flex align-items-center">\
                                    <button type="button" class="accordion-button ' + collapseBtn + '" data-bs-toggle="collapse" data-bs-target="#slot-' + i + '-' + j + '" aria-expanded="' + collapseExpand + '">\
                                        <i class="bx bx-timer me-2"></i>\
                                        Slot #' + (parseInt(j) + 1) + '\
                                    </button>\
                                </h2>\
                                <div id="slot-' + i + '-' + j + '" class="accordion-collapse collapse">\
                                    <div class="accordion-body">\
                                        <div style="overflow-x:auto;">\
                                            <table class="table table-bordered table-sm">\
                                                <tbody>\
                                                    <tr>\
                                                        <td>\
                                                            <label style="color : #b3b3cc"> Time Start</label><br>\
                                                            <span style="font-weight:bold"> ' + moment(time_start).format("h:mm A") + ' </span>\
                                                        </td>\
                                                        <td>\
                                                            <label style="color : #b3b3cc"> Time End </label><br>\
                                                            <span style="font-weight:bold"> ' + moment(time_end).format("h:mm A") + ' </span>\
                                                        </td>\
                                                    </tr>\
                                                    <tr>\
                                                        <td width="30%">\
                                                            <label style="color : #b3b3cc"> Participant </label><br>\
                                                            <span style="font-weight:bold"> ' + scheduleList[i].slot[j].slot_participant + ' </span>\
                                                        </td>\
                                                        <td width="35%">\
                                                            <label style="color : #b3b3cc"> Status </label><br>\
                                                            <span style="font-weight:bold"> ' + badgeSlot[scheduleList[i].slot[j].slot_status] + ' </span>\
                                                        </td>\
                                                    </tr>\
                                                    <tr>\
                                                        <td colspan="2">\
                                                            <label style="color : #b3b3cc"> Tentative / Remark </label><br>\
                                                            <span style="font-weight:bold"> ' + remark + ' </span>\
                                                        </td>\
                                                    </tr>\
                                                </tbody>\
                                            </table>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>');
							}

						}
					}, 220);
				} else {
					$('#listSlot').html(nodata());
				}
			}

		} else {
			$('#listSchedule').html(nodata());
		}

	}
</script>
