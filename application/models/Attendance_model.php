<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Attendance_model extends CI_Model
{
	public $table = 'event_attendance';
	public $id = 'attendance_id';
	public $order = 'DESC';

	public function __construct()
	{
		parent::__construct();
		// $this->abilities = parent::permission();
	}

	protected $fillable = [
		'event_id',
		'slot_id',
		'stud_id',
		'user_id',
		'academic_id',
		'branch_id',
		'event_category',
		'attendance_time',
		'attendance_date',
		'attendance_timestamp',
		'attendance_device',
		'attendance_status',
	];

	public $with = [];

	public function getListAttendanceBySlotID($slotID, $eventID)
	{
		$serverside = serversideDT();
		$serverside->query("SELECT 
        usr.user_full_name,
        usr.user_matric_code,
        program.program_code,
        enroll.semester_number,
        attendance.slot_id,
        attendance.event_id,
        attendance.stud_id,
        attendance.user_id,
        attendance.attendance_timestamp,
        attendance.attendance_id
        FROM {$this->table} attendance
        LEFT JOIN student_enrollment enroll ON attendance.stud_id=enroll.stud_id
        LEFT JOIN user usr ON attendance.user_id=usr.user_id
        LEFT JOIN master_program program ON usr.program_id=program.program_id
        WHERE attendance.slot_id = " . $slotID . " AND attendance.event_id = " . $eventID . " AND attendance.branch_id = " . currentUserBranchID() . "
        ORDER BY {$this->id} {$this->order}");

		$serverside->hide('slot_id'); // hides column from the output
		$serverside->hide('event_id'); // hides column from the output
		$serverside->hide('stud_id'); // hides column from the output
		$serverside->hide('user_id'); // hides column from the output
		$serverside->hide('attendance_id'); // hides column from the output
		$serverside->hide('semester_number'); // hides column from the output

		$serverside->edit('user_full_name', function ($data) {
			return purify($data['user_full_name']);
		});

		$serverside->edit('user_matric_code', function ($data) {
			return purify($data['user_matric_code']);
		});

		$serverside->edit('user_matric_code', function ($data) {
			return purify($data['user_matric_code']);
		});

		$serverside->edit('program_code', function ($data) {
			return purify($data['program_code']) . ' / ' . purify($data['semester_number']);
		});

		$serverside->edit('attendance_timestamp', function ($data) {
			return formatDate($data['attendance_timestamp'], "h:i A");
		});

		echo $serverside->generate();
	}

	public function getListAttendanceByStudentIDDt($categoryID)
	{
		$serverside = serversideDT();
		$serverside->query("SELECT
        event.event_name,
        attendance.attendance_date,
        attendance.attendance_time,
        attendance.attendance_id
        FROM {$this->table} attendance
        LEFT JOIN student_enrollment enroll ON attendance.stud_id=enroll.stud_id
        LEFT JOIN event ON attendace.event_id=event.event_id
        WHERE attendance.attendance_status = '1' AND attendance.event_categories = " . $categoryID . " AND attendance.branch_id = " . currentUserBranchID() . "
        GROUP BY attendance.event_id
        ORDER BY {$this->id} {$this->order}");

		$serverside->hide('attendance_id'); // hides column from the output

		$serverside->edit('event_name', function ($data) {
			return purify($data['event_name']);
		});

		$serverside->edit('attendance_date', function ($data) {
			return formatDate($data['attendance_date'], 'D/M/YYYY');
		});

		$serverside->edit('attendance_time', function ($data) {
			return formatDate($data['attendance_time'], "h:i A");
		});

		echo $serverside->generate();
	}

	public function getDataExportPdf($slotID, $eventID)
	{
		$this->db->join('student_enrollment', 'student_enrollment.stud_id = ' . $this->table . '.stud_id');
		$this->db->join('user', 'user.user_id = ' . $this->table . '.user_id');
		$this->db->join('master_program', 'master_program.program_id = user.program_id');
		$this->db->where($this->table . '.slot_id', $slotID);
		$this->db->where($this->table . '.event_id', $eventID);
		return $this->db->get($this->table)->result_array();
	}
}
