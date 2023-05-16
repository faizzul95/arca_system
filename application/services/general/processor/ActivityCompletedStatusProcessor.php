<?php

namespace App\services\general\processor;

defined('BASEPATH') or exit('No direct script access allowed');

class ActivityCompletedStatusProcessor
{
	public $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
		model('Event_model', 'eventM');
		model('Event_slot_model', 'slotM');
	}

	public function execute()
	{
		$data = $this->CI->db->select('slot_id,event_id,slot_time_start,slot_time_end,slot_timestamp_start,slot_timestamp_end,slot_status')
			->where('DATE(`slot_timestamp_start`) =' . escape(timestamp('Y-m-d')))
			->where_in('slot_status', [1, 2])
			->get('event_slot')->result_array();

		if (hasData($data)) {
			foreach ($data as $row) {

				// update status slot
				if (timestamp() >= $row['slot_timestamp_start'] && timestamp() <= $row['slot_timestamp_end']) {
					$this->CI->slotM::save([
						'slot_id' => $row['slot_id'],
						'slot_status' => 2,
					]);
				} else if (timestamp() > $row['slot_timestamp_end']) {
					$this->CI->slotM::save([
						'slot_id' => $row['slot_id'],
						'slot_status' => 3,
					]);
				}

				$dataEvent = $this->CI->db->where('event_id', $row['event_id'])->get('event')->row_array();

				if (hasData($dataEvent)) {

					// count all slot
					$allSlot = countData(['event_id' => $row['event_id']], 'event_slot');

					// count all completed slot
					$slotComplete = countData(['event_id' => $row['event_id'], 'slot_status' => 3], 'event_slot');

					// count all canceled slot
					$slotCancel = countData(['event_id' => $row['event_id'], 'slot_status' => 4], 'event_slot');

					// count all ongoing slot 
					$slotOngoing = countData(['event_id' => $row['event_id'], 'slot_status' => 2], 'event_slot');

					$totalSlotCompleteRunning = $slotComplete + $slotCancel;

					// update status event to completed/ended
					if ($allSlot == $totalSlotCompleteRunning) {
						$this->CI->eventM::save([
							'event_id' => $row['event_id'],
							'event_status' => 5,
						]);
					}
					// update event to ongoing
					else if ($slotOngoing > 0) {
						$this->CI->eventM::save([
							'event_id' => $row['event_id'],
							'event_status' => 3,
						]);
					}
				}
			}
		}
	}
}
