<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Config_college_model extends CI_Model
{
	public $table = 'config_college';
	public $id = 'college_id';
	public $order = 'DESC';

	public function __construct()
	{
		parent::__construct();
		$this->abilities = parent::permission(['college-delete', 'college-update', 'college-room-view']);
	}

	protected $fillable = [
		'college_name',
		'college_code',
		'college_capacity',
		'college_gender_prefer',
		'college_level_prefer',
		'college_status',
		'branch_id'
	];

	public $with = [];

	public function getListCollegeDt()
	{
		$serverside = serversideDT();
		$serverside->query("SELECT college_name, college_code, college_capacity, branch_id, college_status, college_id FROM {$this->table} WHERE branch_id = '" . currentUserBranchID() . "' ORDER BY {$this->id} {$this->order}");

		// $serverside->hide('branch_id'); // hides column from the output

		$serverside->edit('branch_id', function ($data) {
			return $this->countTotalRoomByCollegeID($data['college_id'], $data['branch_id']);
		});

		$serverside->edit('college_name', function ($data) {
			return purify($data['college_name']);
		});

		$serverside->edit('college_code', function ($data) {
			return purify($data['college_code']);
		});

		$serverside->edit('college_capacity', function ($data) {
			return purify($data['college_capacity']);
		});

		$serverside->edit('college_status', function ($data) {
			return $data['college_status'] == 1 ? '<span class="badge badge-label bg-success"> Active </span>' : '<span class="badge badge-label bg-danger"> In Active </span>';
		});

		$serverside->edit('college_id', function ($data) {

			$del = $edit = $room = '';

			if ($this->abilities['college-delete'])
				$del = '<button  class="btn btn-sm btn-soft-danger" onclick="deleteRecord(' . $data[$this->id] . ')" data-id="' . $data[$this->id] . '" title="Delete"> <i class="fa fa-trash"></i> </button>';

			if ($this->abilities['college-update'])
				$edit = '<button class="btn btn-sm btn-soft-info" onclick="updateRecord(' . $data[$this->id] . ')" title="Update"><i class="fa fa-edit"></i> </button>';

			if ($this->abilities['college-room-view'])
				$room = '<button class="btn btn-sm btn-soft-success" onclick="roomRecord(' . $data[$this->id] . ', ' . escape($data['college_name']) . ')" title="View Room"><i class="ri-hotel-line"></i> </button>';

			return "<center> $del $edit $room</center>";
		});

		echo $serverside->generate();
	}

	public function countTotalRoomByCollegeID($collegeID, $branchID)
	{
		return countData(['college_id' => $collegeID, 'branch_id' => $branchID], 'config_college_room');
	}
}
