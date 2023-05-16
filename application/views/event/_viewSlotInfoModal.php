<div class="row" id="bodyDiv">

	<div class="col-12">

		<div class="row">

			<div class="alert alert-primary" role="alert">
				<i class="bi bi-activity"></i> &nbsp;
				<strong>
					Event Information
				</strong>
			</div>

			<div class="row">
				<div class="col-12">
					<label style="color : #b3b3cc"> Event Name </label><br>
					<span id="event_name_preview" style="font-weight:bold"></span>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-7 col-sm-7">
					<label style="color : #b3b3cc"> Event Category </label><br>
					<span id="event_category_preview" style="font-weight:bold"></span>
				</div>

				<div class="col-5 col-sm-5">
					<label style="color : #b3b3cc"> Event Status </label><br>
					<span id="slot_status_preview"></span>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-12">
					<label style="color : #b3b3cc"> Venue </label><br>
					<span id="event_venue_preview" style="font-weight:bold"></span>
				</div>
			</div>
		</div>

		<div class="row mt-4">

			<div class="alert alert-primary" role="alert">
				<i class="bi bi-calendar"></i> &nbsp;
				<strong>
					Schedule Information
				</strong>
			</div>

			<div class="row mt-2">
				<!-- <div class="col-6">
                    <label style="color : #b3b3cc"> Slot No </label><br>
                    <span id="slot_no_preview" style="font-weight:bold"></span>
                </div> -->

				<div class="col-6">
					<label style="color : #b3b3cc"> Day </label><br>
					<span id="schedule_day_name_preview" style="font-weight:bold"></span>
				</div>

				<div class="col-6">
					<label style="color : #b3b3cc"> Date </label><br>
					<span id="schedule_date_preview" style="font-weight:bold"></span>
				</div>
			</div>

			<div class="row mt-2">
				<div class="col-6">
					<label style="color : #b3b3cc"> Time Start </label><br>
					<span id="slot_start_preview" style="font-weight:bold"></span>
				</div>

				<div class="col-6">
					<label style="color : #b3b3cc"> Time End </label><br>
					<span id="slot_end_preview" style="font-weight:bold"></span>
				</div>
			</div>

		</div>

		<div class="row mt-4">

			<div class="alert alert-primary" role="alert">
				<i class="bi bi-person"></i> &nbsp;
				<strong>
					Organizer Information
				</strong>
			</div>

			<div class="p-0 overflow-hidden mb-4" id="bodyScheduleDiv">
				<div id="organizerList"></div>
			</div>
		</div>

	</div>
</div>

<script>
	async function getPassData(baseUrl, token, data) {

		var categoryAtt = {
			'1': 'HEP',
			'2': 'University',
			'3': 'College',
			'4': 'Academic/Faculty',
			'5': 'Association/Club',
		};

		var badgeSlot = {
			'1': '<span class="badge bg-info"> Incoming </span>',
			'2': '<span class="badge bg-primary"> Ongoing </span>',
			'3': '<span class="badge bg-success"> Completed </span>',
			'4': '<span class="badge bg-danger"> Canceled </span>',
		};

		const res = await callApi('get', "EventSchedule/show/" + data.slot_id);

		// check if request is success
		if (isSuccess(res)) {

			const data = res.data;

			const schedule = data.schedule;
			const event = schedule.event;
			const organizer = event.organizer;

			$('#event_name_preview').html(event.event_name + ' <small>(Slot #' + data.slot_no + ')</small>');
			$('#event_venue_preview').text(schedule.schedule_venue);
			$('#schedule_day_name_preview').text(schedule.schedule_day_name);
			$('#schedule_date_preview').text(moment(schedule.schedule_date).format("DD/MM/YYYY"));

			$('#event_category_preview').html(categoryAtt[event.event_category]);
			$('#slot_status_preview').html(badgeSlot[data.slot_status]);

			$('#slot_no_preview').text('#' + data.slot_no);
			$('#slot_start_preview').text(moment(data.slot_timestamp_start).format("h:mm A"));
			$('#slot_end_preview').text(moment(data.slot_timestamp_end).format("h:mm A"));

			if (Object.keys(organizer).length > 0) {
				$('#organizerList').empty(); // reset table

				$('#organizerList').append('<div style="overflow-x:auto;">\
                        <div class="table-responsive">\
                            <table class="table table-bordered table-hover w-100">\
                                <tbody id="organizerData"></tbody>\
                            </table>\
                        </div>\
                     </div>');

				setTimeout(function() {
					var no = 1;
					for (let i in organizer) {
						$('#organizerData').append('<tr>\
                            <td> ' + no + '. ' + organizer[i]['user']['user_full_name'] + '</td>\
                        </tr>');
						no++;
					}
				}, 20);
			} else {
				$('#organizerList').html(nodata());
			}

		}
	}
</script>