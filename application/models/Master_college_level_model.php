<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Master_college_level_model extends CI_Model
{
	public $table = 'master_college_level';
	public $id = 'college_level_id';
	public $order = 'ASC';

	public function __construct()
	{
		parent::__construct();
	}

	protected $fillable = [
		'college_level_name',
		'college_level_code',
		'college_level_status'
	];

	public $with = [];

	public function getListCollegeLevelDt()
	{
		$serverside = serversideDT();
		$serverside->query("SELECT college_level_name, college_level_code, college_level_status, college_level_id  FROM {$this->table} ORDER BY {$this->id} {$this->order}");

		$serverside->edit('college_level_name', function ($data) {
			return purify($data['college_level_name']);
		});

		$serverside->edit('college_level_code', function ($data) {
			return purify($data['college_level_code']);
		});

		$serverside->edit('college_level_status', function ($data) {
			return $data['college_level_status'] == 1 ? '<span class="badge badge-label bg-success"> Active </span>' : '<span class="badge badge-label bg-danger"> In Active </span>';
		});

		$serverside->edit('college_level_id', function ($data) {
			$del = $edit = '';

			$disabled = $this->countCollegeLevel($data[$this->id]) > 0 ? 'disabled' : '';

			if (currentUserRoleID() == 1) {
				$del = '<button class="btn btn-soft-danger btn-sm" onclick="deleteRecord(' . $data[$this->id] . ')" data-id="' . $data[$this->id] . '" ' . $disabled . ' title="Delete"> <i class="fa fa-trash"></i> </button>';
				$edit = '<button class="btn btn-soft-info btn-sm" onclick="updateRecord(' . $data[$this->id] . ')" title="Update"><i class="fa fa-edit"></i> </button>';
			} else {
				$edit = '<small><i>(no access)</i></small>';
			}

			return "<center> $del $edit </center>";
		});

		echo $serverside->generate();
	}

	public function countCollegeLevel($levelID)
	{
		return countData(['college_level_id' => $levelID], 'config_college_room');
	}
}
