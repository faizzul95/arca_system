<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Master_faculty_model extends CI_Model
{
	public $table = 'master_faculty';
	public $id = 'faculty_id';
	public $order = 'ASC';

	public function __construct()
	{
		parent::__construct();
	}

	protected $fillable = [
		'faculty_name',
		'faculty_code',
		'faculty_status',
		'branch_id'
	];

	public $with = [];

	public function getListFacultyDt()
	{
		$serverside = serversideDT();
		$serverside->query(" SELECT 
        fac.faculty_name, 
        fac.faculty_code, 
        fac.faculty_status, 
        fac.faculty_id
        FROM {$this->table} fac
        WHERE fac.branch_id = " . currentUserBranchID() . "
        ORDER BY {$this->id} {$this->order}");

		$serverside->edit('faculty_name', function ($data) {
			return purify($data['faculty_name']);
		});

		$serverside->edit('faculty_code', function ($data) {
			return purify($data['faculty_code']);
		});

		$serverside->edit('faculty_status', function ($data) {
			return $data['faculty_status'] == 1 ? '<span class="badge badge-label bg-success"> Active </span>' : '<span class="badge badge-label bg-danger"> In Active </span>';
		});

		$serverside->edit('faculty_id', function ($data) {
			$del = $edit = '';

			$disabled = $this->countFacultyProgram($data[$this->id]) > 0 ? 'disabled' : '';

			$del = '<button class="btn btn-soft-danger btn-sm" onclick="deleteRecord(' . $data[$this->id] . ')" data-id="' . $data[$this->id] . '" ' . $disabled . ' title="Delete"> <i class="fa fa-trash"></i> </button>';
			$edit = '<button class="btn btn-soft-info btn-sm" onclick="updateRecord(' . $data[$this->id] . ')" title="Update"><i class="fa fa-edit"></i> </button>';
			return "<center> $del $edit </center>";
		});

		echo $serverside->generate();
	}

	public function countFacultyProgram($facultyID)
	{
		return countData(['faculty_id' => $facultyID], 'master_program');
	}
}
