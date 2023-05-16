<form id="formEvent" action="event/save" method="POST">

	<div class="row" id="bodyDiv">

		<!-- Event information -->
		<div class="col-lg-6 col-md-12 fill border-right p-4 overflow-hidden">

			<div class="row">
				<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
					<i class="ri-edit-box-line label-icon"></i><strong> Event Information </strong>
				</div>

				<div class="row">
					<div class="col-lg-9 col-md-12 mt-2">
						<label class="form-label"> Event Name <span class="text-danger">*</span></label>
						<input type="text" id="event_name" name="event_name" maxlength="200" class="form-control" autocomplete="off" onkeyup="this.value = this.value.toUpperCase();" required>
					</div>

					<div class="col-lg-3 col-md-12 mt-2">
						<label class="form-label"> Category <span class="text-danger">*</span></label>
						<select id="event_category" name="event_category" class="form-control" required>
							<option value=""> - Select - </option>
							<option value="1"> HEP </option>
							<option value="2"> University </option>
							<option value="3"> College </option>
							<option value="4"> Academic/Faculty </option>
							<option value="5"> Association/Club </option>
						</select>
					</div>
				</div>

			</div>

			<!-- Organizer information -->
			<div class="row mt-4">
				<div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">
					<i class="ri-user-2-line label-icon"></i><strong> Organizer Information </strong>
					<button type="button" class="btn btn-sm btn-info float-end" onclick="organizerForm()"> + Add Organizer </button>
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
				<ul class="nav nav-tabs" id="scheduleForm" role="tablist">
					<li class="nav-item" id="addButtonLi" role="presentation">
						<button class="nav-link" id="addBtn" onclick="addTabDays()" data-bs-toggle="tab" data-bs-target="#contact" type="button" role="tab" aria-controls="contact" aria-selected="false" style="background-color: #405189;">
							<svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
								<line x1="12" y1="5" x2="12" y2="19"></line>
								<line x1="5" y1="12" x2="19" y2="12"></line>
							</svg>
						</button>
					</li>
				</ul>
				<div class="tab-content" id="contentPanel"></div>
			</div>

			<div class="row mt-4 mb-2">
				<span class="text-danger">* Indicates a required field</span>
				<center>
					<input type="hidden" id="event_id" name="event_id" placeholder="event_id" readonly>
					<button id="cancelBtn" type="button" class="btn btn-danger" onclick="cancelEvent()" style="display: none;"> <i class='fa fa-times'></i> Cancel Event </button>
					<button type="submit" id="submitBtn" class="btn btn-info"> <i class='fa fa-save'></i> Save </button>
				</center>
			</div>
		</div>

		<input type="hidden" id="event_status" name="event_status" value="2">

	</div>

</form>

<script>
	var organizerDtList = {};
	var maxDayAllow = 14;
	var maxSlotAllow = 3;

	function getPassData(baseUrl, token, data) {
		if (data != null) {

			if (['2', '3'].includes(data.event_status)) {
				$('#cancelBtn').show();
			}

			if (data.schedule.length > 0) {
				for (i = 0; i < data.schedule.length; i++) {
					addTabDays(data.schedule[i]);
				}
			} else {
				addTabDays();
			}

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

		} else {
			addTabDays();
			$('#listOrganizer').html(nodata());
		}
	}

	function organizerForm() {
		loadFileContent('event/_assignOrganizerFormModal.php', 'generalContent', '680px', 'REGISTER ORGANIZER : ' + $('#event_name').val(), organizerDtList, 'offcanvas');
		$("#generaloffcanvas-right").css("z-index", "1500");
	}

	// schedule
	function addTabDays(data = null) {

		var i = $('.liSchedule_div').length;

		// reset all
		$('.liScheduleTab_div').removeClass('active');
		$('.liScheduleForm_div').removeClass('active');
		$('.liScheduleForm_div').removeClass('show');

		if (i < maxDayAllow) {

			var schedule_id = (data != null) ? data.schedule_id : '';
			var schedule_date = (data != null) ? data.schedule_date : '';
			var schedule_venue = (data != null) ? data.schedule_venue : '';
			var schedule_day_name = (data != null) ? data.schedule_day_name : '';
			var schedule_day_id = (data != null) ? data.schedule_day_id : '';

			var btnRemove = (data != null) ? 'onclick="deleteSchedule(' + schedule_id + ', ' + i + ')"' : 'onclick="removeSchedule(' + i + ')"';

			var tab = '<li class="nav-item liSchedule_div" data-row="' + i + '" role="presentation">\
                        <button class="nav-link liScheduleTab_div" data-row="' + i + '" data-bs-toggle="tab" data-bs-target="#scheduleTab' + i + '" type="button" role="tab" aria-controls="scheduleTab' + i + '" aria-selected="true">\
                            <span id="countSchedule' + i + '" class="countSchedule" data-row="' + i + '">Day #' + (i + 1) + '</span>\
                            <svg  ' + btnRemove + ' xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2 text-danger feather feather-x liSchedule_removebtn">\
                                <line x1="18" y1="6" x2="6" y2="18"></line>\
                                <line x1="6" y1="6" x2="18" y2="18"></line>\
                            </svg>\
                        </button>\
                    </li>';

			$('#scheduleForm').find(' > li:nth-last-child(1)').before(tab);

			var form = '<div class="tab-pane fade p-0 liScheduleForm_div" id="scheduleTab' + i + '" data-row="' + i + '" role="tabpanel" aria-labelledby="scheduleTab' + i + '-tab">\
                                <div class="row tab">\
                                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">\
                                        <div class="row mt-2 mb-2">\
                                            <div class="col-lg-12">\
                                                <span class="text-danger">Remark : Click the "+" button to add days. Maximum allowed is ' + maxDayAllow + ' day(s) only</span>\
                                            </div>\
                                        </div>\
                                        <div class="row">\
                                            <div class="col-md-6 mt-2">\
                                                <label class="form-label" for="schedule_date"> Date <span class="text-danger">*</span></label>\
                                                <input type="date" name="schedule_date[]" value="' + schedule_date + '" class="form-control schedule_date min_date' + i + '" onchange="getDayName(this.value, ' + i + ')" autocomplete="off" required>\
                                            </div>\
                                            <div class="col-md-6 mt-2">\
                                                <label class="form-label" for="schedule_day_name"> Day <span class="text-danger">*</span></label>\
                                                <input type="text" name="schedule_day_name[]" value="' + schedule_day_name + '" class="form-control schedule_day_name" data-row="' + i + '" autocomplete="off" readonly required>\
                                                <input type="hidden" name="schedule_day_id[]" value="' + schedule_day_id + '" class="schedule_day_id" data-row="' + i + '">\
                                            </div>\
                                        </div>\
                                        <div class="row">\
                                            <div class="col-md-12 mt-2">\
                                                <label class="form-label" for="schedule_venue"> Venue <span class="text-danger">*</span></label>\
                                                <input type="text" name="schedule_venue[]" value="' + schedule_venue + '" class="form-control schedule_venue" maxlength="35" onKeyUP="this.value = this.value.toUpperCase();" autocomplete="off" required>\
                                                <input type="hidden" name="schedule_id[]" value="' + schedule_id + '" class="schedule_id" placeholder="schedule_id' + i + '">\
                                            </div>\
                                        </div>\
                                        <div class="row">\
                                            <div class="col-md-12 mt-4">\
                                                <div class="alert alert-info alert-label-icon rounded-label fade show" role="alert">\
                                                    <i class="ri-timer-line label-icon"></i><strong> Slot Information </strong>\
                                                    <button type="button" class="btn btn-sm btn-info float-end mb-4" onclick="addSlot(' + i + ')"> + Add slot </button>\
                                                </div>\
                                            </div>\
                                            <div class="col-md-12 mt-2" data-simplebar style="max-height: 340px;">\
                                                <div class="row mb-2">\
                                                    <div class="col-lg-12">\
                                                        <span class="text-danger">Remark : Click the "+" button to add slot. Maximum allowed is ' + maxSlotAllow + ' slot(s) per day</span>\
                                                    </div>\
                                                </div>\
                                                <div id="slot' + i + '_row" data-row="' + i + '" class="slotRow"> </div>\
                                            </div>\
                                        </div>\
                                    </div>\
                                </div>\
                            </div>';

			$('#contentPanel').append(form);

			setTimeout(function() {
				if (data != null) {
					$('.min_date' + i).attr('min', schedule_date);
					for (j = 0; j < data.slot.length; j++) {
						addSlot(i, data.slot[j]);
					}
				} else {
					$('.schedule_date').attr('min', getCurrentDate());
					addSlot(i, data);
					console.log('index : ', i);
				}
			}, 200);

		} else {
			i--;
			noti(500, 'Only ' + maxDayAllow + ' days are allowed!');
		}

		$('.liScheduleTab_div[data-row="' + i + '"]').addClass('active');
		$('#scheduleTab' + i).addClass('active');
		$('#scheduleTab' + i).addClass('show');

	}

	function removeSchedule(index) {

		var slotArr = $('.slot' + parseInt(index) + 1 + '_div');

		$('.slot' + index + '_div').remove();
		$('.liSchedule_div[data-row="' + index + '"]').remove();
		$('.liScheduleForm_div[data-row="' + index + '"]').remove();
		$('.schedule_day_name[data-row="' + index + '"]').remove();
		$('.schedule_day_id[data-row="' + index + '"]').remove();

		var inputScheduleCount = $('.liSchedule_div').length;

		if (inputScheduleCount == 0) {
			addTabDays();
		} else {

			const divArr = document.getElementsByClassName('liSchedule_div');
			const divTabArr = document.getElementsByClassName('liScheduleTab_div');
			const divFormArr = document.getElementsByClassName('liScheduleForm_div');
			const btnArr = document.getElementsByClassName('liSchedule_removebtn');
			const ids = document.getElementsByClassName('schedule_id');
			const textCount = document.getElementsByClassName('countSchedule');

			const scheduleDate = document.getElementsByClassName('schedule_date');
			const dayID = document.getElementsByClassName('schedule_day_id');
			const dayName = document.getElementsByClassName('schedule_day_name');
			const slotRow = document.getElementsByClassName('slotRow');

			let curr = 0;

			// reset all
			$('.liScheduleTab_div').removeClass('active');
			$('.liScheduleForm_div').removeClass('active');
			$('.liScheduleForm_div').removeClass('show');

			// reset tab & form
			for (i = 0; i < divArr.length; i++) {
				var oldValue = divArr[i].attributes[1].value;
				var newValue = curr;

				// if (isset(ids[i])) {
				var id = ids[i].attributes[2].value;
				divArr[i].setAttribute('data-row', newValue);

				divTabArr[i].setAttribute('data-row', newValue);
				divTabArr[i].setAttribute('data-bs-target', "#scheduleTab" + newValue);
				divTabArr[i].setAttribute('aria-controls', "#scheduleTab" + newValue);

				divFormArr[i].setAttribute('data-row', newValue);
				divFormArr[i].setAttribute('id', "scheduleTab" + newValue);
				divFormArr[i].setAttribute('aria-labelledby', "#scheduleTab" + newValue + "-tab");

				scheduleDate[i].setAttribute('onchange', 'getDayName(this.value, ' + newValue + ')');

				dayID[i].setAttribute('data-row', newValue);
				dayName[i].setAttribute('data-row', newValue);

				var textCountids = textCount[i].attributes[0].value;
				$('#' + textCountids).text('Day #' + (newValue + 1));
				textCount[i].setAttribute('id', newValue);
				textCount[i].setAttribute('data-row', newValue);

				slotRow[i].setAttribute('data-row', newValue);
				slotRow[i].setAttribute('id', "slot" + newValue + "_row");

				if (id != '') {
					btnArr[i].setAttribute('onclick', 'deleteSchedule(' + id + ',' + oldValue + ')');
				} else {
					btnArr[i].setAttribute('onclick', 'removeSchedule(' + curr + ')');
				}
				// }

				curr++;
			}

			var activeTabID = index == 0 ? 0 : index - 1;

			setTimeout(function() {
				$('.liScheduleTab_div[data-row="' + activeTabID + '"]').addClass('active');
				$('#scheduleTab' + activeTabID).addClass('active');
				$('#scheduleTab' + activeTabID).addClass('show');
			}, 200);

		}
	}

	async function deleteSchedule(id, i) {
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
					const res = await deleteApi(id, 'EventSchedule/delete');
					if (isSuccess(res)) {
						removeSchedule(i);
					}
					loading('#bodyDiv', false);
				}
			})
	}

	function getDayName(dateString, index) {

		var id = new Date(dateString);
		var dayName = new Date(dateString).toLocaleString('en-us', {
			weekday: 'long'
		});

		$('.schedule_day_name[data-row="' + index + '"]').val(dayName);
		$('.schedule_day_id[data-row="' + index + '"]').val(id.getDay());
	}

	// slot
	function addSlot(scheduleIndex = 0, data = null) {

		var i = $('.slot' + scheduleIndex + '_div').length;

		if (i < maxSlotAllow) {
			var slot_sticker_acquired = (data != null) ? data.slot_sticker_acquired : '';
			var slot_time_start = (data != null) ? data.slot_time_start : '';
			var slot_time_end = (data != null) ? data.slot_time_end : '';
			var slot_participant = (data != null) ? data.slot_participant : '';
			var slot_remark = (data != null) ? data.slot_remark : '';
			var slot_access_code = (data != null) ? data.slot_access_code : '';
			var slot_id = (data != null) ? data.slot_id : '';
			var schedule_id = (data != null) ? data.slot_schedule_id : '';
			var acquiredYes = (slot_sticker_acquired == '1') ? 'selected' : '';
			var acquiredNo = (slot_sticker_acquired == '0') ? 'selected' : '';

			var btnRemove = (data != null) ? 'onclick="deleteSlot(' + slot_id + ', ' + scheduleIndex + ')"' : 'onclick="removeSlot(' + i + ', ' + scheduleIndex + ')"';

			$('#slot' + scheduleIndex + '_row').append('\
                <div class="row slot' + scheduleIndex + '_div" data-row="' + i + '">\
                    <table class="table table-bordered table-sm">\
                        <thead class="table-dark">\
                            <tr>\
                                <th colspan="3">\
                                    <span id="slotText' + i + '" class="countSlot' + scheduleIndex + '" data-row="' + i + '"> Slot #' + (i + 1) + '</span>\
                                    <button type="button" class="btn btn-danger btn-sm float-end slotRemove_btn' + scheduleIndex + '" ' + btnRemove + '>\
                                        <i class="fa fa-minus"></i> Remove\
                                    </button>\
                                </th>\
                            </tr>\
                        </thead>\
                        <tbody>\
                            <tr>\
                                <td>\
                                    <label class="form-label"> Time Start <span class="text-danger">*</span></label>\
                                    <input type="time" name="slot_time_start[' + scheduleIndex + '][]" class="form-control slot_time_start' + scheduleIndex + '" value="' + slot_time_start + '" autocomplete="off" required>\
                                </td>\
                                <td>\
                                    <label class="form-label"> Time End <span class="text-danger">*</span></label>\
                                    <input type="time" name="slot_time_end[' + scheduleIndex + '][]" class="form-control slot_time_end' + scheduleIndex + '" value="' + slot_time_end + '" autocomplete="off" required>\
                                </td>\
                            </tr>\
                            <tr>\
                                <td>\
                                    <label class="form-label"> Participant </label>\
                                    <input type="number" name="slot_participant[' + scheduleIndex + '][]" class="form-control slot_participant' + scheduleIndex + '" value="' + slot_participant + '" min="0" max="2000" maxlength="4" placeholder="Max: 2000 participant " autocomplete="off">\
                                </td>\
                                <td>\
                                    <label class="form-label"> Sticker Acquired <span class="text-danger">*</span></label>\
                                    <select name="slot_sticker_acquired[' + scheduleIndex + '][]" class="form-control slot_sticker_acquired' + scheduleIndex + '" required>\
                                        <option value="0" ' + acquiredNo + '> No </option>\
                                        <option value="1" ' + acquiredYes + '> Yes </option>\
                                    </select>\
                                </td>\
                            </tr>\
                            <tr>\
                                <td colspan="2">\
                                    <label class="form-label"> Tentative / Remark </label>\
                                    <input type="text" name="slot_remark[' + scheduleIndex + '][]" class="form-control slot_remark' + scheduleIndex + '" value="' + slot_remark + '" maxlength="255" placeholder="Max: 255 character" autocomplete="off">\
                                    <input type="hidden" name="slot_id[' + scheduleIndex + '][]" class="form-control slot_id' + scheduleIndex + '" value="' + slot_id + '" readonly>\
                                    <input type="hidden" name="slot_schedule_id[' + scheduleIndex + '][]" class="form-control schedule_id' + scheduleIndex + '" value="' + schedule_id + '" readonly>\
                                    <input type="hidden" name="slot_access_code[' + scheduleIndex + '][]" class="form-control slot_access_code' + scheduleIndex + '" value="' + slot_access_code + '" readonly>\
                                </td>\
                            </tr>\
                        </tbody>\
                    </table>\
                </div>');
			i++;
		} else {
			noti(500, 'Only ' + maxSlotAllow + ' slot are allowed!');
		}
	}

	function removeSlot(i, scheduleIndex) {

		$('.slot' + scheduleIndex + '_div[data-row="' + i + '"]').remove();

		var inputSlotCount = $('.slot' + scheduleIndex + '_div').length;
		if (inputSlotCount == 0) {
			addSlot(scheduleIndex);
		} else {
			const divArr = document.getElementsByClassName('slot' + scheduleIndex + '_div');
			const btnArr = document.getElementsByClassName('slotRemove_btn' + scheduleIndex);
			const ids = document.getElementsByClassName('slot_id' + scheduleIndex);
			const textCount = document.getElementsByClassName('countSlot' + scheduleIndex);

			let curr = 0;
			for (i = 0; i < divArr.length; i++) {
				var oldValue = divArr[i].attributes[1].value;
				var newValue = curr;
				var id = ids[i].attributes[3].value;

				divArr[i].setAttribute('data-row', newValue);

				var textCountids = textCount[i].attributes[0].value;
				$('#' + textCountids).text('Slot #' + (newValue + 1));
				textCount[i].setAttribute('id', newValue);
				textCount[i].setAttribute('data-row', newValue);

				if (id != '') {
					btnArr[i].setAttribute('onclick', 'deleteSlot(' + id + ',' + oldValue + ')');
				} else {
					btnArr[i].setAttribute('onclick', 'removeSlot(' + curr + ', ' + scheduleIndex + ')');
				}
				curr++;
			}
		}
	}

	async function deleteSlot(id, scheduleIndex) {

		$('.slot' + id + '_div[data-row="' + scheduleIndex + '"]').remove();
		var inputSlotCount = $('.slot' + scheduleIndex + '_div').length;

		console.log(id, scheduleIndex);
		console.log('inputSlotCount : ', inputSlotCount);
	}

	// organizer
	function generateTableOrganizer() {

		if (Object.keys(organizerDtList).length > 0) {
			$('#listOrganizer').empty(); // reset table

			$('#listOrganizer').append('<div class="table-responsive">\
                        <table class="table table-bordered table-hover table-striped w-100">\
                            <thead class="table-dark">\
                                <th> Details </th>\
                                <th> Contact Info </th>\
                                <th> # </th>\
                            </thead>\
                            <tbody id="organizerData"></tbody>\
                        </table>\
                     </div>');

			setTimeout(function() {
				var no = 1;
				for (let i in organizerDtList) {
					$('#organizerData').append('<tr>\
                    <td> Name : ' + organizerDtList[i]['user_full_name'] + ' <br> Matric ID : ' + organizerDtList[i]['user_matric_code'] + '</td>\
                    <td> Phone : ' + organizerDtList[i]['user_contact_no'] + ' <br> Email : ' + organizerDtList[i]['user_email'] + '</td>\
                    <td>\
                        <center>\
                            <input type="hidden" name="organizer_id[]" value="' + organizerDtList[i]['organizer_id'] + '">\
                            <input type="hidden" name="user_id[]" value="' + organizerDtList[i]['user_id'] + '" placeholder="user_id">\
                            <button type="button" class="btn btn-danger btn-sm" onclick="removeOrganizer(' + i + ', ' + organizerDtList[i]['organizer_id'] + ')">\
                                <i class="fa fa-minus"></i>\
                            </button>\
                        </center>\
                    </td>\
                </tr>');
					no++;
				}
			}, 200);
		} else {
			$('#listOrganizer').html(nodata());
		}

	}

	function removeOrganizer(index, organizerID = null) {
		if (organizerID == null || organizerID == '') {
			delete organizerDtList[index];
			generateTableOrganizer();
			getOrganizerList(organizerDtList);
		} else {
			if (Object.keys(organizerDtList).length > 1) {
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
							const res = await deleteApi(organizerID, 'EventOrganizer/delete');
							if (isSuccess(res)) {
								delete organizerDtList[index];
								generateTableOrganizer();
								getOrganizerList(organizerDtList);
							}
							loading('#bodyDiv', false);
						}
					})
			} else {
				noti(500, 'Please add another organizer first!');
			}
		}

		if (Object.keys(organizerDtList).length == 0) {
			$('#listOrganizer').empty(); // reset table
			$('#listOrganizer').html(nodata());
		}
	}

	$("#formEvent").submit(function(event) {
		event.preventDefault();

		if (validateDataEvent()) {

			if (Object.keys(organizerDtList).length > 0) {
				const form = $(this);
				const url = form.attr('action');

				Swal.fire({
					title: 'Are you sure?',
					html: "Form will be submitted!",
					icon: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Yes, Confirm!',
					reverseButtons: true
				}).then(
					async (result) => {
						if (result.isConfirmed) {
							const res = await submitApi(url, form.serializeArray(), 'formEvent');
							if (isSuccess(res)) {

								if (isSuccess(res.data.resCode)) {
									noti(res.status, 'Save');
									getDataList();
								} else {
									noti(500, res.data.message)
								}

							}
						}
					})

			} else {
				noti(500, 'Atleast one organizer required')
			}

		} else {
			validationJsError('toastr', 'single'); // single or multi
		}
	});

	function validateDataEvent() {

		// schedule_id - arr
		// schedule_date - arr
		// schedule_day_id - arr
		// schedule_day_name - arr
		// schedule_venue - arr

		// slot_remark - arr
		// slot_time_start - arr
		// slot_time_end - arr
		// slot_participant - arr
		// slot_sticker_acquired  - arr
		// slot_id - arr
		// slot_schedule_id - arr
		// slot_access_code - arr

		// event_name
		// event_category
		// event_status
		// event_id

		// organizer_id
		// user_id

		const rules = {
			'event_name': 'required|min:5|max:200',
			'event_category': 'required|integer',
			'event_status': 'required|integer',
			'event_id': 'integer',
		};

		const message = {
			'event_name': 'Event name',
			'event_category': 'Category',
			'event_status': 'Status',
		};

		return validationJs(rules, message);
	}
</script>