<?php

defined('BASEPATH') or exit('No direct script access allowed');

class EventSchedule extends Controller
{
	public function __construct()
	{
		parent::__construct();
		model('Event_schedule_model', 'scheduleM');
		model('Event_slot_model', 'slotM');
		model('Files_model', 'filesM');
	}

	public function index()
	{
		errorpage('404');
	}

	public function getScheduleByID()
	{
		if (isAjax() && input('schedule_id') != NULL) {
			json($this->scheduleM::find(input('schedule_id')));
		} else {
			errorpage('404');
		}
	}

	public function delete($id)
	{
		if (isAjax()) {

			$schedule_id = xssClean($id);
			$deleteSchedule = $this->scheduleM::delete($schedule_id);

			if (isSuccess($deleteSchedule['resCode'])) {
				// get slot data before delete
				$slotData = $this->slotM::all(['slot_schedule_id' => $schedule_id], NULL, ['qrCode']);

				$deleteSlot = $this->slotM::delete($schedule_id, 'slot_schedule_id');

				// remove all qrcode
				if (isSuccess($deleteSlot['resCode'])) {
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

			json($deleteSchedule);
		} else {
			errorpage('404');
		}
	}

	// Slot
	public function getSlotByID($id)
	{
		if (isAjax() && $id != NULL) {
			json($this->slotM::find(xssClean($id), NULL, ['schedule']));
		} else {
			errorpage('404');
		}
	}
}
