<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Config_college_room_model extends CI_Model
{
	public $table = 'config_college_room';
	public $id = 'college_room_id';
	public $order = 'DESC';

	public function __construct()
	{
		parent::__construct();
		$this->abilities = parent::permission(['college-room-delete', 'college-room-update']);
	}

	protected $fillable = [
		'college_room_number',
		'college_room_allocation',
		'college_room_status',
		'college_id',
		'college_level_id',
		'branch_id'
	];

	public $with = ['college'];

	public function collegeRelation($data)
	{
		return hasOne('Config_college_model', 'college_id', $data['college_id']);
	}

	public function getListCollegeRoomDt($collegeID)
	{
		$serverside = serversideDT();
		$serverside->query("SELECT 
        room.college_room_number, 
        room.college_room_allocation, 
        cl.college_level_name, 
        room.college_room_status, 
        room.college_room_id 
        FROM {$this->table} room
        LEFT JOIN master_college_level cl ON room.college_level_id=cl.college_level_id
        WHERE branch_id = '" . currentUserBranchID() . "' AND college_id = " . escape($collegeID) . " 
        ORDER BY {$this->id} {$this->order}");

		// $serverside->hide('branch_id'); // hides column from the output

		$serverside->edit('college_room_number', function ($data) {
			return purify($data['college_room_number']);
		});

		$serverside->edit('college_room_allocation', function ($data) {
			return purify($data['college_room_allocation']);
		});

		$serverside->edit('college_level_name', function ($data) {
			return purify($data['college_level_name']);
		});

		$serverside->edit('college_room_status', function ($data) {
			return $data['college_room_status'] == 1 ? '<span class="badge badge-label bg-success"> Active </span>' : '<span class="badge badge-label bg-danger"> In Active </span>';
		});

		$serverside->edit('college_room_id', function ($data) {

			$del = $edit = '';
			$disabledUsedRoom = $this->countRoomStudentByCollegeRoomID($data[$this->id]) > 0 ? 'disabled' : '';

			if ($this->abilities['college-room-delete'])
				$del = '<button  class="btn btn-sm btn-soft-danger" onclick="deleteCollegeRoomRecord(' . $data[$this->id] . ')" data-id="' . $data[$this->id] . '" ' . $disabledUsedRoom . ' title="Delete"> <i class="fa fa-trash"></i> </button>';

			if ($this->abilities['college-room-update'])
				$edit = '<button class="btn btn-sm btn-soft-info" onclick="updateCollegRoomRecord(' . $data[$this->id] . ')" title="Update"><i class="fa fa-edit"></i> </button>';

			return "<center> $del $edit</center>";
		});

		echo $serverside->generate();
	}

	public function countRoomStudentByCollegeRoomID($collegeRoomID)
	{
		return countData(['college_room_id' => $collegeRoomID], 'student_enrollment');
	}
}
