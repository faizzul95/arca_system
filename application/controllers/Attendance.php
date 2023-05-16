<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Attendance extends Controller
{
	public function __construct()
	{
		parent::__construct();
		model('Event_model', 'eventM');
		model('Event_organizer_model', 'organizerM');
		model('Event_schedule_model', 'scheduleM');
		model('Event_slot_model', 'slotM');
		model('Attendance_model', 'attendanceM');
		model('Student_sticker_collection_model', 'stickerM');
		model('Config_college_sticker_model', 'configStickerM');
		helper('string');
	}

	public function index()
	{
		errorpage('404');
	}

	public function getListDtAttendanceBySlotID()
	{
		if (isAjax()) {
			echo $this->attendanceM->getListAttendanceBySlotID(input('slot_id'), input('event_id'));
		} else {
			errorpage('404');
		}
	}

	// student
	public function recordAttendance()
	{
		if (isAjax()) {

			$data = ['resCode' => 400, 'message' => 'QR Code is invalid']; // default return

			// query slot using slot_session_code
			$dataSlot = $this->slotM::find(input('slot_session_code'), 'slot_session_code'); // with event table

			// check if slot is exist
			if ($dataSlot) {

				// query attendance using slot id, current stud session, current academic id
				$checkAttendance = $this->attendanceM::where([
					'slot_id' => $dataSlot['slot_id'],
					'stud_id' => currentUserStudID(),
					'academic_id' => currentAcademicID(),
					'branch_id' => currentUserBranchID(),
				], 'row_array');

				// if attendance not taken yet
				if (empty($checkAttendance)) {
					if ($dataSlot['slot_status'] == 1) {
						// if slot should be started then update status to 2.
						if (timestamp() >= $dataSlot['slot_timestamp_start'] and timestamp() <= $dataSlot['slot_timestamp_end']) {
							$updateStatuSlot = $this->slotM::save([
								'slot_id' => $dataSlot['slot_id'],
								'slot_status' => 2,
							]);
							$data = $this->saveAttendance($dataSlot, $_POST); // save attendance
						} else {
							$data = ['resCode' => 400, 'message' => 'Event slots are not open yet'];
						}
					} else if ($dataSlot['slot_status'] == 4) {
						$data = ['resCode' => 400, 'message' => 'Event slots have been canceled'];
					} else if ($dataSlot['slot_status'] == 3 || timestamp() > $dataSlot['slot_timestamp_end']) {
						$data = ['resCode' => 400, 'message' => 'Event slot has been ended'];

						// if status slot is 2
						if ($dataSlot['slot_status'] == 2) {
							$updateStatuSlot = $this->slotM::save([
								'slot_id' => $dataSlot['slot_id'],
								'slot_status' => 3,
							]);
						}
					} else if ($dataSlot['slot_status'] == 2 and timestamp() >= $dataSlot['slot_timestamp_start'] and timestamp() <= $dataSlot['slot_timestamp_end']) {
						$data = $this->saveAttendance($dataSlot, $_POST);
					} else {
						$data = ['resCode' => 400, 'message' => 'QR Code is invalid or expired'];
					}
				} else {
					$data = ['resCode' => 400, 'message' => 'Attendance already taken for this event'];
				}
			}

			json($data);
		} else {
			errorpage('404');
		}
	}

	private function saveAttendance($dataSlot, $dataPost)
	{
		$dataEvent = $this->eventM::find($dataSlot['event_id']); // with event table
		$category = $dataEvent['event_category'];

		$saveAttendance = $this->attendanceM::save([
			'slot_id' => $dataSlot['slot_id'],
			'event_id' => $dataSlot['event_id'], // nnti dd tengok dia return ke tak with tu
			'event_category' => $category,
			'user_id' => currentUserID(),
			'stud_id' => currentUserStudID(),
			'academic_id' => currentAcademicID(),
			'branch_id' => currentUserBranchID(),
			'attendance_date' => timestamp('Y-m-d'),
			'attendance_time' => timestamp('H:i:s'),
			'attendance_timestamp' => timestamp(),
			'attendance_device' => $dataPost['attendance_device'],
			'attendance_status' => $dataPost['attendance_status'],
		]);

		// check if success save to db
		if (isSuccess($saveAttendance['resCode'])) {

			// if slot has sticker then update count
			if ($dataSlot['slot_sticker_acquired'] == 1) {

				$countSlotAcquired = $this->slotM::countData(
					[
						'event_id' => $dataSlot['event_id'],
						'slot_sticker_acquired' => 1
					]
				);

				if ($countSlotAcquired > 0) {

					$countEventAttendance = 0;

					// get all slot info sticker acquired
					$slotAquired = $this->slotM::where([
						'event_id' => $dataSlot['event_id'],
						'slot_sticker_acquired' => 1,
					], 'result_array');

					// get all attendance by event id and stud id
					$attendanceList = $this->attendanceM::where([
						'event_id' => $dataSlot['event_id'],
						'stud_id' => currentUserStudID(),
						'attendance_status' => 1
					], 'result_array');

					foreach ($attendanceList as $attend) {
						$slotIDattend = $attend['slot_id'];

						if (in_array($slotIDattend, array_column($slotAquired, 'slot_id'))) {
							$countEventAttendance++;
						}
					}

					// if attendance is equal to slot sticker acquired count, then add to collection
					if ($countEventAttendance == $countSlotAcquired) {

						$dataSticker = $this->stickerM::where([
							'stud_id' => currentUserStudID(),
							'academic_id' => currentAcademicID(),
							'branch_id' => currentUserBranchID(),
						], 'row_array');

						if ($dataSticker) {

							// get config sticker by academic id
							$dataConfigSticker = $this->configStickerM::where([
								'academic_id' => currentAcademicID(),
								'branch_id' => currentUserBranchID(),
							], 'row_array');

							$hep_event_id = ($category == 1) ? $this->pushEventID($dataSticker['hep_event_id'], $dataSlot['event_id']) : $dataSticker['hep_event_id'];
							$university_event_id = ($category == 2) ? $this->pushEventID($dataSticker['university_event_id'], $dataSlot['event_id'])  : $dataSticker['university_event_id'];
							$college_event_id = ($category == 3) ? $this->pushEventID($dataSticker['college_event_id'], $dataSlot['event_id']) : $dataSticker['college_event_id'];
							$faculty_event_id = ($category == 4) ? $this->pushEventID($dataSticker['faculty_event_id'], $dataSlot['event_id']) : $dataSticker['faculty_event_id'];
							$club_event_id = ($category == 5) ? $this->pushEventID($dataSticker['club_event_id'], $dataSlot['event_id']) : $dataSticker['club_event_id'];

							// sticker
							$hepStickerCollection = ($category == 1) ? $dataSticker['total_hep_sticker'] + 1 : $dataSticker['total_hep_sticker'];
							$universityCollection = ($category == 2) ? $dataSticker['total_university_sticker'] + 1 : $dataSticker['total_university_sticker'];
							$collegeCollection = ($category == 3) ? $dataSticker['total_college_sticker'] + 1 : $dataSticker['total_college_sticker'];

							// set eligible (status 1) and not eligible (status 2)
							$is_college_eligible = ($hepStickerCollection >= $dataConfigSticker['sticker_hep_amount'] && $universityCollection >= $dataConfigSticker['sticker_university_amount'] && $collegeCollection >= $dataConfigSticker['sticker_college_amount']) ? 1 : 2;

							$saveSticker = $this->stickerM::save([
								'collection_id' => $dataSticker['collection_id'],
								'total_hep_sticker' => $hepStickerCollection,
								'total_university_sticker' => $universityCollection,
								'total_college_sticker' => $collegeCollection,
								'total_faculty_sticker' => ($category == 4) ? $dataSticker['total_faculty_sticker'] + 1 : $dataSticker['total_faculty_sticker'],
								'total_club_sticker' => ($category == 5) ? $dataSticker['total_club_sticker'] + 1 : $dataSticker['total_club_sticker'],
								'total_sticker' => $dataSticker['total_sticker'] + 1,
								'hep_event_id' => $hep_event_id,
								'university_event_id' => $university_event_id,
								'college_event_id' => $college_event_id,
								'faculty_event_id' => $faculty_event_id,
								'club_event_id' => $club_event_id,
								'is_college_eligible' => $is_college_eligible
							]);
						}
					}
				}
			}

			return ['resCode' => 200, 'message' => 'Attendance record successfully'];
		} else {
			return ['resCode' => 400, 'message' => 'Attendance fail to record'];
		}
	}

	public function pushEventID($eventIDarr = NULL, $newEventID = NULL)
	{
		// explode array event
		$arrEvent = empty($eventIDarr) ? [] : explode(",", $eventIDarr);

		// push new id into array
		array_push($arrEvent, $newEventID);

		// return implode array as string
		return implode(",", $arrEvent);
	}

	public function getListAttendanceByStudentID($category)
	{
		if (isAjax()) {

			$category = xssClean($category);

			$dataSticker = $this->stickerM::where([
				'stud_id' => currentUserStudID(),
				'academic_id' => currentAcademicID(),
				'branch_id' => currentUserBranchID(),
			], 'row_array');


			if (hasData($dataSticker)) {
				$eventIDs = NULL; // set default

				if ($category == 1) {
					$eventIDs = hasData($dataSticker, 'hep_event_id') ? explode(",", $dataSticker['hep_event_id']) : [];
				} else if ($category == 2) {
					$eventIDs = hasData($dataSticker, 'university_event_id') ? explode(",", $dataSticker['university_event_id']) : [];
				} else if ($category == 3) {
					$eventIDs = hasData($dataSticker, 'college_event_id') ? explode(",", $dataSticker['college_event_id']) : [];
				} else if ($category == 4) {
					$eventIDs = hasData($dataSticker, 'faculty_event_id') ? explode(",", $dataSticker['faculty_event_id']) : [];
				} else if ($category == 5) {
					$eventIDs = hasData($dataSticker, 'club_event_id') ? explode(",", $dataSticker['club_event_id']) : [];
				} else if ($category == 6) {
					// combination of id 4 and 5
					$facultyData = hasData($dataSticker, 'faculty_event_id') ? explode(",", $dataSticker['faculty_event_id']) : [];
					$clubData = hasData($dataSticker, 'club_event_id') ? explode(",", $dataSticker['club_event_id']) : [];
					$eventIDs = array_merge($facultyData, $clubData);
				}

				$data = hasData($eventIDs) ? $this->eventM->getListEventDetailsByEventIDArr($eventIDs) : NULL;

				if (hasData($data)) {

					echo '<div class="row">
                            <div class="form-group mb-0">
                                <input class="form-control" id="elementsSearchInput" type="text" onkeyup="elementsSearch()" placeholder="Search activity...">
                            </div>
                        </div>
                        <ul id="elementsSearchList" class="ps-0 chat-user-list">
                        ';

					foreach ($data as $row) {

						$category = [
							'1' => 'HEP',
							'2' => 'University',
							'3' => 'College',
							'4' => 'Academic/Faculty',
							'5' => 'Association/Club',
						];

						echo '<li class="p-3 affan-element-item">
                                <a class="d-flex" href="javascript:void(0);">
                                    <div class="chat-user-info">
                                        <h6 class="text-truncate mb-0">' . $row['event_name'] . '</h6>
                                        <div class="last-chat">
                                            <p class="mb-0 text-truncate">
                                                ' . formatDate($row['event_start_date'], 'd/m/Y') . ' - ' . formatDate($row['event_end_date'], 'd/m/Y') . '
                                            </p>
                                        </div>
                                    </div>
                                </a>
                        
                                <!-- Options -->
                                <div class="dropstart chat-options-btn">
                                    ' . $category[$row['event_category']] . '
                                </div>
                            </li>';
					}

					echo '  </ul>';
				} else {
					nodata();
				}
			} else {
				nodata();
			}
		} else {
			errorpage('404');
		}
	}

	// organizer

	public function organizerAttendanceAccessCode()
	{
		if (isAjax()) {

			$dataSlot = $this->slotM::where(['slot_id' => input('slot_id'), 'slot_access_code' => input('slot_access_code')], 'row_array'); // with event table
			$dataOrganizer = $this->organizerM::where(['event_id' => input('event_id'), 'user_id' => currentUserID()], 'row_array');

			if (hasData($dataOrganizer)) {
				if (hasData($dataSlot)) {
					$event_id = input('event_id');
					$slot_id = input('slot_id');

					// check if organizer is exist to record this event
					$checkOrganizer = $this->organizerM::where([
						'event_id' => $event_id,
						'user_id' => currentUserID(),
						'branch_id' => currentUserBranchID(),
					], 'row_array');


					if (!empty($checkOrganizer)) {
						// check time start & time end
						if (timestamp() >= $dataSlot['slot_timestamp_start'] and timestamp() <= $dataSlot['slot_timestamp_end']) {

							$slotSessionCode = $dataSlot['slot_session_code'];

							if (empty($slotSessionCode)) {
								$slotSessionCode = random_string('alnum', 8) . formatDate(timestamp(), 'YmdHisA') . '' . $slot_id . '' . random_string('alnum', 8); // (student scan)

								$saveSlot = $this->slotM::save([
									"slot_id" => $slot_id,
									"event_id" => $event_id,
									"slot_session_code" => $slotSessionCode,
								]);
							}

							$data = ['resCode' => 200, 'codeSession' => $slotSessionCode];
						}
						// check if slot time has been ended
						else if ($dataSlot['slot_status'] == 3 || timestamp() > $dataSlot['slot_timestamp_end']) {
							$data = ['resCode' => 400, 'message' => 'Slot has been ended'];
						} else {
							$data = ['resCode' => 400, 'message' => 'Something when wrong'];
						}
					} else {
						$data = ['resCode' => 400, 'message' => 'You are not allowed to record this slot'];
					}
				} else {
					$data = ['resCode' => 400, 'message' => 'Invalid slot access code'];
				}
			} else {
				$data = ['resCode' => 400, 'message' => 'Only registered organizer can access this section'];
			}

			json($data);
		} else {
			errorpage('404');
		}
	}

	public function updateSessionQR()
	{
		if (isAjax()) {

			$dataSlot = $this->slotM::find(input('slot_id'));

			if ($dataSlot) {
				$event_id = input('event_id');
				$slot_id = input('slot_id');

				// check time start & time end
				if (timestamp() >= $dataSlot['slot_timestamp_start'] and timestamp() <= $dataSlot['slot_timestamp_end']) {

					$slotSessionCode = random_string('alnum', 8) . formatDate(timestamp(), 'YmdHisA') . '' . $slot_id . '' . random_string('alnum', 8); // (student scan)
					$saveSlot = $this->slotM::save([
						"slot_id" => $slot_id,
						"event_id" => $event_id,
						"slot_session_code" => $slotSessionCode,
					]);

					$data = ['resCode' => 200, 'codeSession' => $slotSessionCode];
				}
				// check if slot time has been ended
				else if ($dataSlot['slot_status'] == 3 || timestamp() > $dataSlot['slot_timestamp_end']) {
					$data = ['resCode' => 400, 'message' => 'Event slot has been ended'];

					// if status slot is 2
					if ($dataSlot['slot_status'] == 2) {
						$updateStatuSlot = $this->slotM::save([
							'slot_id' => $dataSlot['slot_id'],
							'slot_status' => 3,
						]);
					}
				} else {
					$data = ['resCode' => 400, 'message' => 'Something when wrong'];
				}
			}

			json($data);
		} else {
			errorpage('404');
		}
	}

	public function generateSessiontQr()
	{
		$accessCode = input('slot_session_code');
		$slot_id = input('slot_id');

		// generate folder for qr
		$folderQr = folder('event', 'temp_session_qr', 'qr_slot_' . $slot_id);

		// check if image already exist
		if (!file_exists("public/upload/event/temp_session_qr/qr_slot_" . $slot_id . "/" . $accessCode . ".png")) {
			//generate QR Code
			return generateQR(
				$accessCode,
				$folderQr,
				['image' => 'public/common/images/favicon.png', 'size' => 100],
				$accessCode . '.png',
				false
			);
		}
	}
}
