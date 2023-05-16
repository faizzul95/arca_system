<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Event_model extends CI_Model
{
	public $table = 'event';
	public $id = 'event_id';
	public $order = 'DESC';

	public function __construct()
	{
		parent::__construct();
		$this->abilities = parent::permission(['event-delete', 'event-update', 'event-cancel', 'event-view', 'event-view-attendance', 'event-organizer-view-attendance']);
	}

	protected $fillable = [
		'event_name',
		'event_category',
		'event_start_date',
		'event_end_date',
		'register_user_id',
		'register_date',
		'approve_user_id',
		'approve_date',
		'event_remark',
		'event_status',
		'reopen_attendance_start',
		'reopen_attendance_end',
		'is_archive',
		'academic_id',
		'branch_id',
	];

	public $with = ['schedule', 'slot', 'organizer'];

	public function scheduleRelation($data)
	{
		return hasMany('Event_schedule_model', 'event_id', $data[$this->id], NULL, ['slot']);
	}

	public function slotRelation($data)
	{
		return hasMany('Event_slot_model', 'event_id', $data[$this->id]);
	}

	public function organizerRelation($data)
	{
		return hasMany('Event_organizer_model', 'event_id', $data[$this->id], NULL, ['user']);
	}

	public function getListEventDt($academicID = NULL, $status = NULL, $isArchive = 0)
	{
		$whereAcademic = (!empty($academicID)) ? "AND `event`.`academic_id` = '$academicID'" : ($academicID != 0 ? "AND `event`.`academic_id` = '" . currentAcademicID() . "'" : '');
		$whereStatus = (!empty($status)) ? "AND `event`.`event_status` = '$status'" : '';

		$serverside = serversideDT();
		$serverside->query("SELECT 
        `event`.`event_name`, 
        `event`.`event_category`, 
        `event`.`event_start_date`, 
        `event`.`event_end_date`, 
        `event`.`event_status`, 
        `event`.`branch_id`, 
        `event`.`event_id` 
        FROM  {$this->table} `event`
        WHERE `event`.`branch_id` = " . currentUserBranchID() . " $whereStatus $whereAcademic
        ORDER BY {$this->id} {$this->order}");

		$serverside->hide('branch_id'); // hides column from the output
		$serverside->hide('event_end_date'); // hides column from the output

		$serverside->edit('event_start_date', function ($data) {
			return 'Start Date : ' . formatDate($data['event_start_date']) . '<br> End Date : ' . formatDate($data['event_end_date']);
		});

		$serverside->edit('event_category', function ($data) {
			$category = [
				'1' => 'HEP',
				'2' => 'University',
				'3' => 'College',
				'4' => 'Academic/Faculty',
				'5' => 'Association/Club'
			];
			return $category[$data['event_category']];
		});

		$serverside->edit('event_status', function ($data) {

			$badge = [
				'0' => '<span class="badge badge-soft-warning"> Draft </span>',
				'1' => '<span class="badge badge-soft-info"> Pending Approval </span>',
				'2' => '<span class="badge badge-soft-info"> Incoming </span>',
				'3' => '<span class="badge badge-soft-success"> Ongoing </span>',
				'4' => '<span class="badge badge-soft-primary"> Re-open Attendance </span>',
				'5' => '<span class="badge bg-success"> Completed </span>',
				'6' => '<span class="badge bg-danger"> Canceled </span>',
				'7' => '<span class="badge bg-danger"> Rejected </span>',
			];

			return $badge[$data['event_status']];
		});

		$serverside->edit('event_id', function ($data) {

			$del = $edit = $attendance = $view = '';

			if ($this->abilities['event-delete'] and in_array($data['event_status'], [6])) {
				$del = '<button class="btn btn-soft-danger btn-sm" onclick="deleteRecord(' . $data[$this->id] . ')" data-id="' . $data[$this->id] . '" title="Delete"> <i class="fa fa-trash"></i> </button>';
			} else if ($this->abilities['event-cancel'] and in_array($data['event_status'], [2, 3])) {
				$del = '<button class="btn btn-soft-danger btn-sm" onclick="cancelEvent(' . $data[$this->id] . ')" data-id="' . $data[$this->id] . '" title="Cancel Event"> <i class="fa fa-times"></i> </button>';
			}

			if ($this->abilities['event-update'] and in_array($data['event_status'], [2, 3]))
				$edit = '<button class="btn btn-soft-info btn-sm" onclick="updateRecord(' . $data[$this->id] . ')" title="Update"><i class="fa fa-edit"></i> </button>';

			if ($this->abilities['event-view-attendance']) {
				if (in_array($data['event_status'], [3, 5]))
					$attendance = '<button class="btn btn-soft-primary btn-sm" onclick="viewAttendance(' . $data[$this->id] . ', ' . escape($data['event_name']) . ')" title="Attendance"><i class="ri-list-check-2"></i> Attendance </button>';
			}

			if ($this->abilities['event-view'])
				$view = '<button class="btn btn-soft-success btn-sm" onclick="viewRecord(' . $data[$this->id] . ')" title="View"><i class="fa fa-eye"></i> </button>';

			return "<center> $del $edit $view $attendance </center>";
		});

		echo $serverside->generate();
	}

	public function getListEventOrganizerDt($academicID = NULL, $status = NULL)
	{
		$whereAcademic = (!empty($academicID)) ? "AND `event`.`academic_id` = '$academicID'" : ($academicID != 0 ? "AND `event`.`academic_id` = '" . currentAcademicID() . "'" : '');
		$whereStatus = (!empty($status)) ? "AND `event`.`event_status` = '$status'" : '';

		$serverside = serversideDT();
		$serverside->query("SELECT 
        `event`.`event_name`, 
        `event`.`event_category`, 
        `event`.`event_start_date`, 
        `event`.`event_end_date`, 
        `event`.`event_status`, 
        `event`.`branch_id`, 
        `event`.`event_id` 
        FROM {$this->table} `event`
		LEFT JOIN event_organizer organizer ON event.event_id=organizer.event_id
        WHERE `event`.`branch_id` = " . currentUserBranchID() . " AND `organizer`.`user_id` = " . currentUserID() . "  $whereStatus $whereAcademic
        ORDER BY {$this->id} {$this->order}");

		$serverside->hide('branch_id'); // hides column from the output
		$serverside->hide('event_end_date'); // hides column from the output

		$serverside->edit('event_name', function ($data) {
			return purify($data['event_name']);
		});

		$serverside->edit('event_start_date', function ($data) {
			return 'Start Date : ' . formatDate(purify($data['event_start_date'])) . '<br> End Date : ' . formatDate(purify($data['event_end_date']));
		});

		$serverside->edit('event_category', function ($data) {
			$category = [
				'1' => 'HEP',
				'2' => 'University',
				'3' => 'College',
				'4' => 'Academic/Faculty',
				'5' => 'Association/Club'
			];
			return $category[$data['event_category']];
		});

		$serverside->edit('event_status', function ($data) {

			$badge = [
				'0' => '<span class="badge badge-soft-warning"> Draft </span>',
				'1' => '<span class="badge badge-soft-info"> Pending Approval </span>',
				'2' => '<span class="badge badge-soft-info"> Incoming </span>',
				'3' => '<span class="badge badge-soft-success"> Ongoing </span>',
				'4' => '<span class="badge badge-soft-primary"> Re-open Attendance </span>',
				'5' => '<span class="badge bg-success"> Completed </span>',
				'6' => '<span class="badge bg-danger"> Canceled </span>',
				'7' => '<span class="badge bg-danger"> Rejected </span>',
			];

			return $badge[$data['event_status']];
		});

		$serverside->edit('event_id', function ($data) {

			$record = $attendance = $view = '';

			if (in_array($data['event_status'], [3, 4]))
				$record = '<button class="btn btn-soft-info btn-sm" onclick="attendanceRecord(' . $data[$this->id] . ', ' . escape($data['event_name']) . ')" title="Update"><i class="ri-qr-code-line"></i> Record Attendances </button>';

			if ($this->abilities['event-organizer-view-attendance']) {
				if (in_array($data['event_status'], [3, 5]))
					$attendance = '<button class="btn btn-soft-primary btn-sm" onclick="viewAttendance(' . $data[$this->id] . ', ' . escape($data['event_name']) . ')" title="Attendance"><i class="ri-list-check-2"></i> Attendance </button>';
			}

			$view = '<button class="btn btn-soft-success btn-sm" onclick="viewRecord(' . $data[$this->id] . ')" title="View"><i class="fa fa-eye"></i> </button>';

			return "<center> $view $record $attendance </center>";
		});

		echo $serverside->generate();
	}

	public function getDashboardEventCalendarByCurrentMonth()
	{
		$this->db->from($this->table);
		$this->db->where('MONTH(event_start_date)', date('m'));
		$this->db->where('YEAR(event_start_date)', date('Y'));
		$this->db->where($this->table . '.event_status', 2); // incoming
		$this->db->where($this->table . '.academic_id', currentAcademicID());
		$this->db->where($this->table . '.branch_id', currentUserBranchID());
		$this->db->limit(4);
		$this->db->order_by($this->table . '.event_start_date', 'ASC');
		return $this->db->get()->result_array();
	}

	public function getDashboardEventCalendarByMonthSeleceted($date)
	{
		$this->db->from($this->table);
		$this->db->where($this->table . '.event_start_date', $date);
		$this->db->where($this->table . '.event_status', 2); // incoming
		$this->db->where($this->table . '.academic_id', currentAcademicID());
		$this->db->where($this->table . '.branch_id', currentUserBranchID());
		$this->db->limit(4);
		$this->db->order_by($this->table . '.event_start_date', 'ASC');
		return $this->db->get()->result_array();
	}

	public function countTotalEventByCategoryAcademic($categoryID)
	{
		return countData(['event_category' => $categoryID, 'academic_id' => currentAcademicID(), 'branch_id' => currentUserBranchID()], $this->table);
	}

	public function countPercentageByStatusAcademic($status = NULL, $totalEvent = 0)
	{
		$this->db->from($this->table);

		if (!empty($status))
			$this->db->where($this->table . '.event_status', $status);
		else
			$this->db->where_not_in($this->table . '.event_status', [2, 3, 5, 6]);

		$this->db->where($this->table . '.academic_id', currentAcademicID());
		$this->db->where($this->table . '.branch_id', currentUserBranchID());
		$sumEvent = $this->db->count_all_results();

		$percentage = 0.0;
		if ($sumEvent > 0) {
			$total = ($sumEvent / $totalEvent) * 100;
			$percentage = number_format($total, 1);
		}

		return ['total' => $sumEvent, 'percentage' => $percentage];
	}

	public function countPreviousAcademic($prevAcademicID)
	{
		// count prev academic event
		return countData(['academic_id' => $prevAcademicID, 'branch_id' => currentUserBranchID()], $this->table);
	}

	public function getListEventDetailsByEventIDArr($eventID = NULL)
	{
		return $this->db->where_in($this->table . '.event_id', $eventID)->get($this->table)->result_array();
	}

	public function getListEventStudentPWA($startDate = NULL, $endDate = NULL)
	{
		return $this->db->join('event_schedule schedule', 'schedule.event_id = event.event_id', 'left')
			->join('event_slot slot', 'slot.slot_schedule_id = schedule.schedule_id', 'left')
			->where("slot.slot_timestamp_start BETWEEN " . escape($startDate) . " AND " . escape($endDate))
			->where('event.academic_id', currentAcademicID())
			->where('event.branch_id', currentUserBranchID())
			->get($this->table . ' event')->result_array();
	}

	// ORGANIZER
	public function countEventOrganizerByUserID()
	{
		return $this->db->from($this->table)
			->join('event_organizer organizer', 'organizer.event_id = ' . $this->table . '.event_id', 'left')
			->where('organizer.user_id', currentUserID())
			->where($this->table . '.academic_id', currentAcademicID())
			->where($this->table . '.branch_id', currentUserBranchID())
			->count_all_results();
	}

	public function countSlotOrganizerByStatus($slotStatus = 1)
	{
		return $this->db->from($this->table)
			->join('event_slot slot', 'slot.event_id = ' . $this->table . '.event_id', 'left')
			->join('event_organizer organizer', 'organizer.event_id = ' . $this->table . '.event_id', 'left')
			->where('organizer.user_id', currentUserID())
			->where('slot.slot_status', $slotStatus)
			->where($this->table . '.academic_id', currentAcademicID())
			->where($this->table . '.branch_id', currentUserBranchID())
			->count_all_results();
	}
}
