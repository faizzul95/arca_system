<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Event extends Controller
{
	public function __construct()
	{
		parent::__construct();
		model('Event_model', 'eventM');
		model('Event_organizer_model', 'organizerM');
		model('Event_schedule_model', 'scheduleM');
		model('Event_slot_model', 'slotM');
		model('Files_model', 'filesM');
		helper('string');
	}

	public function index()
	{
		render('event/admin_list',  [
			'title' => 'Event',
			'currentSidebar' => 'Event',
			'currentSubSidebar' => 'List event',
			'permission' => permission(
				[
					'event-register',
					'event-admin-view-list',
				]
			)
		]);
	}

	public function list()
	{
		// organizer
		if (currentUserRoleID() == 5) {
			$file = 'organizer_list';
		}

		// student
		else if (currentUserRoleID() == 6) {
			$file = 'student_list';
		}

		render('event/' . $file,  [
			'title' => 'Event',
			'currentSidebar' => 'Event',
			'currentSubSidebar' => 'List event',
			'permission' => permission(
				[
					'event-student-view-list',
					'event-organizer-view-list',
				]
			)
		]);
	}

	public function getEventList()
	{
		if (isAjax())
			echo $this->eventM->getListEventDt(input('academic_id'), input('event_status'));
		else
			errorpage('404');
	}

	public function getEventOrganizerList()
	{
		if (isAjax())
			echo $this->eventM->getListEventOrganizerDt(input('academic_id'), input('event_status'));
		else
			errorpage('404');
	}

	public function getEventByID($eventID = NULL)
	{
		if (isAjax() && $eventID != NULL) {
			$dataEvent = $this->eventM::find(xssClean($eventID), NULL, ['schedule', 'schedule.slot']);

			if (!empty($dataEvent['event_id'])) {
				$dataEvent = array_merge($dataEvent, [
					'organizer' => $this->organizerM::all(['event_id' => $dataEvent['event_id']], NULL, ['user'])
				]);
			}

			json($dataEvent);
		} else {
			errorpage('404');
		}
	}

	public function qrCodeScanner()
	{
		// only student can access this page using mobile
		if (isMobileDevice() && in_array(currentUserRoleID(), ['6'])) {
			render('event/student_qr_scanner_pwa',  [
				'title' => 'QR Scanner',
				'currentSidebar' => 'Scanner',
				'currentSubSidebar' => NULL
			]);
		} else {
			errorpage('404');
		}
	}

	public function save()
	{
		if (isAjax()) {
			$branch_id = currentUserBranchID();
			$academic_id = currentAcademicID();
			$scheduleIdArr = [];
			$slotIdArr = [];

			// Save Event
			$eventSave = $this->eventM::save([
				"event_id" => input('event_id'),
				"event_name" => input('event_name'),
				"event_category" => input('event_category'),
				"register_user_id" => currentUserID(),
				"register_date" => timestamp(),
				"approve_user_id" => in_array(currentUserProfileID(), ['1', '2', '3']) ? currentUserID() : NULL,
				"approve_date" => in_array(currentUserProfileID(), ['1', '2', '3']) ? timestamp() : NULL,
				"event_status" => in_array(currentUserProfileID(), ['1', '2', '3']) ? 2 : input('event_status'),
				"branch_id" => $branch_id,
				"academic_id" => $academic_id,
			]);

			// Check if event save successfully
			if (isSuccess($eventSave['resCode'])) {

				// get event_id
				$event_id = $eventSave['id'];

				// Check if organizer exist
				if (count($_POST['organizer_id']) > 0) {
					// Save Organizer
					foreach ($_POST['organizer_id'] as $key => $organizerID) {
						$this->organizerM::save([
							"organizer_id" => $organizerID,
							"event_id" => $event_id,
							"user_id" => $_POST['user_id'][$key],
							"branch_id" => $branch_id,
						]);
					}
				}

				// Check if schedule exist
				if (count($_POST['schedule_date']) > 0) {
					$startDate = NULL;
					$endDate = NULL;
					$startDateTemp = NULL;
					$endDateTemp = NULL;

					// Save schedule
					foreach ($_POST['schedule_id'] as $key => $scheduleID) {

						$scheduleSave = $this->scheduleM::save([
							"schedule_id" => $scheduleID,
							"event_id" => $event_id,
							"schedule_date" => $_POST['schedule_date'][$key],
							"schedule_day_id" => $_POST['schedule_day_id'][$key],
							"schedule_day_name" => $_POST['schedule_day_name'][$key],
							"schedule_venue" => $_POST['schedule_venue'][$key],
						]);

						$startDateTemp = empty($startDate) ? $_POST['schedule_date'][$key] : $startDate;
						$endDateTemp =  empty($endDate) ? $_POST['schedule_date'][$key] : $endDate;

						// Check if schedule save successfully
						if (isSuccess($scheduleSave['resCode'])) {

							// get slot_schedule_id
							$slot_schedule_id = $scheduleSave['id'];

							// Check if slot exist
							if (count($_POST['slot_id'][$key]) > 0) {
								$slotNo = 1;
								foreach ($_POST['slot_id'][$key] as $slotKey => $slotID) {

									$generateCodeEvent = random_string('alnum', 2) . formatDate(timestamp(), 'Ymds') . '' . $slotKey . '' . random_string('alnum', 2); // (organizer scan) 
									$saveSlot = $this->slotM::save([
										"slot_id" => $slotID,
										"event_id" => $event_id,
										"slot_schedule_id" => $slot_schedule_id,
										"slot_no" => $slotNo,
										"slot_time_start" => $_POST['slot_time_start'][$key][$slotKey],
										"slot_time_end" => $_POST['slot_time_end'][$key][$slotKey],
										"slot_timestamp_start" => $_POST['schedule_date'][$key] . ' ' . $_POST['slot_time_start'][$key][$slotKey],
										"slot_timestamp_end" => $_POST['schedule_date'][$key] . ' ' . $_POST['slot_time_end'][$key][$slotKey],
										"slot_access_code" => empty($_POST['slot_access_code'][$key][$slotKey]) ? $generateCodeEvent : $_POST['slot_access_code'][$key][$slotKey],
										"slot_participant" => $_POST['slot_participant'][$key][$slotKey],
										"slot_sticker_acquired" => $_POST['slot_sticker_acquired'][$key][$slotKey],
										"slot_remark" => $_POST['slot_remark'][$key][$slotKey],
									]);

									if (isSuccess($saveSlot['resCode'])) {
										$slotNo++;
										array_push($slotIdArr, $saveSlot['id']);

										// If empty than generate QR for slot event
										if (empty($_POST['slot_access_code'][$key][$slotKey])) {
											$this->generateEventQr($saveSlot['id'], $generateCodeEvent, input('event_name'));
										}
									}
								}
							}

							// push schedule_id into arr
							array_push($scheduleIdArr, $scheduleSave['id']);

							$startDate = min($startDateTemp, $_POST['schedule_date'][$key]);
							$endDate = max($endDateTemp, $_POST['schedule_date'][$key]);
						}
					}

					// Update Event
					$eventSave = $this->eventM::save([
						"event_id" => $event_id,
						"event_start_date" => $startDate,
						"event_end_date" => $endDateTemp,
					]);
				}

				json($eventSave);
			} else {
				json([
					'resCode' => 400,
					'message' => 'Register event unsuccessful',
					'id' => NULL,
					'data' => [],
				]);
			}
		} else {
			errorpage('404');
		}
	}

	public function cancelEvent()
	{
		if (isAjax() && input('event_id') != NULL) {
			$eventSave = $this->eventM::save([
				"event_id" => input('event_id'),
				"event_status" => input('event_status'),
				"branch_id" => currentUserBranchID(),
			]);

			if (isSuccess($eventSave['resCode'])) {
				$slotData = $this->slotM::all(['event_id' => input('event_id')]);

				// check if has slot
				if (!empty($slotData)) {
					foreach ($slotData as $slot) {
						$this->slotM::save([
							"slot_id" => $slot['slot_id'],
							"slot_status" => 4, // set to cancel
						]);
					}
				}
			}

			json($eventSave);
		} else {
			errorpage('404');
		}
	}

	public function delete($id)
	{
		if (isAjax()) {

			$event_id = xssClean($id);
			$deleteEvent = $this->eventM::delete($event_id);

			if (isSuccess($deleteEvent['resCode'])) {

				// get slot data before delete
				$slotData = $this->slotM::all(['event_id' => $event_id], NULL, ['qrCode']);

				$dataDelete = $deleteEvent['data'];
				$deleteOrganizer = $this->organizerM::delete($event_id, 'event_id');
				$deleteSchedule = $this->scheduleM::delete($event_id, 'event_id');
				$deleteSlot = $this->slotM::delete($event_id, 'event_id');

				// remove all qr code
				if (isSuccess($deleteSlot['resCode'])) {

					if (!empty($slotData)) {
						foreach ($slotData as $slot) {
							// check if qr code data not empty
							if (!empty($slot['qrCode'])) {
								$filePath = $slot['qrCode']['files_path'];
								if (file_exists($filePath)) {
									if (unlink($filePath))
										$deleteFiles = $this->filesM::delete($slot['qrCode']['files_id']);
								}
							}
						}
					}
				}
			}

			json($deleteEvent);
		} else {
			errorpage('404');
		}
	}

	public function generateEventQr($slotID = NULL, $accessCode = NULL, $eventName = NULL)
	{
		// generate folder for qr
		$folderQr = folder('event', $eventName, 'qr_slot_' . $slotID);

		//generate QR Code
		$qrCode = generateQR(
			$accessCode,
			$folderQr,
			['image' => 'public/common/images/favicon.png', 'size' => 130]
		);

		// move qr code to specific folder
		$moveQr = moveFile(
			$qrCode['qrFilename'],
			$qrCode['qrPath'],
			$folderQr,
			[
				'type' => 'Event_slot_model',
				'file_type' => 'QR_EVENT_SLOT',
				'entity_id' => $slotID,
				'user_id' => NULL,
			],
			'rename'
		);

		if (!empty($moveQr)) {
			$this->filesM::save($moveQr);
		}
	}

	public function getListEventStudentDashboard()
	{
		date_default_timezone_set('Asia/Kuala_Lumpur');

		$start = date('Y-m-d H:i:s');
		$finish = date('Y-m-d H:i:s', strtotime($start . '+1 week next Saturday'));
		// $start = (date('D') != 'Mon') ? date('Y-m-d H:i:s', strtotime('last Monday')) : date('Y-m-d H:i:s');
		// $finish = (date('D') != 'Sat') ? date('Y-m-d H:i:s', strtotime('+2 week next Saturday')) : date('Y-m-d H:i:s');
		$checkEvent = $this->eventM->getListEventStudentPWA(date('Y-m-d H:i:s', strtotime($start . ' -1 day')), date('Y-m-d H:i:s', strtotime($finish . ' -1 day')));

		if (hasData($checkEvent)) {

			$earlier = new DateTime(date('Y-m-d H:i:s', strtotime($start . ' -1 day')));
			$later = new DateTime(date('Y-m-d H:i:s', strtotime($finish . ' -1 day')));
			$totalDate = $earlier->diff($later)->format("%r%a");

			echo '<div class="card">
                    <div class="card-body p-3">
                        <div class="form-group mb-0">
                            <input class="form-control" id="eventSearchInput" type="text" onkeyup="activitySearch()" placeholder="Search Event...">
                        </div>
                    </div>
                </div>';

			for ($x = 0; $x <= $totalDate; $x++) {
				$dateEventStart = date('Y-m-d', strtotime($start . $x . ' day'));
				$dayName = date('l', strtotime($dateEventStart));

				$countSlot = $this->slotM->countSlotByStartDate($dateEventStart);

				if ($countSlot > 0) {
					echo '<div class="affan-element-item activity-list-item">
                            <div class="element-heading-wrapper">
                                <div class="heading-text">
                                    <h6 class="mb-1">' . $dayName . ', ' .  formatDate($dateEventStart, 'M d') . '</h6>
                                </div>
                            </div>
                        </div>';

					$getSlotByStartDate = $this->slotM->getListSlotEventStudentPWAByStartDate($dateEventStart);

					if ($getSlotByStartDate) {
						foreach ($getSlotByStartDate as $slotEvent) {
							$eventName = $slotEvent['event_name'];
							$eventStatus = $slotEvent['event_status'];
							$slotNo = $slotEvent['slot_no'];
							$slotID = $slotEvent['slot_id'];
							$slotStatus = $slotEvent['slot_status'];
							$countSlot =  $this->slotM::countData(['slot_schedule_id' => $slotEvent['slot_schedule_id']]);

							$showSlotNo = ($countSlot > 1) ? ' (Slot #' . $slotNo . ')' : NULL;
							$eventNameShow = $eventName . '' . $showSlotNo;

							$badgeEvent = [
								'0' => '<span class="m-1 badge rounded-pill bg-warning"> D </span>',
								'1' => '<span class="m-1 badge rounded-pill bg-primary"> PA </span>',
								'2' => '<span class="m-1 badge rounded-pill bg-info"> IN </span>',
								'3' => '<span class="m-1 badge rounded-pill bg-primary"> ON </span>',
								'4' => '<span class="m-1 badge rounded-pill bg-primary"> Re-open </span>',
								'5' => '<span class="m-1 badge rounded-pill bg-success"> F </span>',
								'6' => '<span class="m-1 badge rounded-pill bg-danger"> C </span>',
								'7' => '<span class="m-1 badge rounded-pill bg-danger"> R </span>',
							];

							$badgeSlot = [
								'1' => '<span class="m-1 badge rounded-pill bg-info"> IN </span>',
								'2' => '<span class="m-1 badge rounded-pill bg-primary"> ON </span>',
								'3' => '<span class="m-1 badge rounded-pill bg-success"> F </span>',
								'4' => '<span class="m-1 badge rounded-pill bg-danger"> C </span>',
							];

							echo '<a class="affan-element-item activity-list-item" href="javascript:void(0)" onclick="viewSlotInfo(' . $slotID . ', ' . escape($eventNameShow) . ')">
                                            ' . $badgeSlot[$slotStatus] . '
                                            <span class="text-truncate">' . $eventNameShow . '</span>
                                            <i class="bi bi-caret-right-fill fz-12"></i>
                                        </a>';
						}
					} else {
						echo '<a class="affan-element-item activity-list-item" href="javascript:void(0)">
                                    NO EVENT ON THIS DATE
                                </a>';
					}
				}
			}
		}
	}
}
