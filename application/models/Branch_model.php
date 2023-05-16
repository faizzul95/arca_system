<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Branch_model extends CI_Model
{
	public $table = 'master_branch';
	public $id = 'branch_id';
	public $order = 'ASC';

	public function __construct()
	{
		parent::__construct();
	}

	protected $fillable = [
		'branch_name',
		'branch_code',
		'branch_address',
		'branch_postcode',
		'branch_city',
		'branch_state',
		'branch_email',
		'branch_fax_no',
		'branch_pic_name',
		'branch_pic_office_no',
		'branch_status'
	];

	public $with = [];

	public function getListBranchDt()
	{
		$serverside = serversideDT();
		$serverside->query("SELECT branch_name, branch_code, branch_email, branch_status, branch_pic_name, branch_pic_office_no, branch_id FROM {$this->table} ORDER BY {$this->id} {$this->order}");

		$serverside->hide('branch_pic_name'); // hides column from the output
		$serverside->hide('branch_pic_office_no'); // hides column from the output

		$serverside->edit('branch_name', function ($data) {
			return purify($data['branch_name']);
		});

		$serverside->edit('branch_code', function ($data) {
			return purify($data['branch_code']);
		});

		$serverside->edit('branch_email', function ($data) {
			$email = (empty($data['branch_email'])) ? '<small><i> (not set) </i></small>' : $data['branch_email'];
			$pic = (empty($data['branch_pic_name'])) ? '<small><i> (not set) </i></small>' : $data['branch_pic_name'];
			$picNo = (empty($data['branch_pic_office_no'])) ? '<small><i> (not set) </i></small>' : $data['branch_pic_office_no'];

			return 'Email : ' . purify($email) . '<br> Person In Charge : ' . purify($pic) . '<br> Office No : ' . purify($picNo);
		});

		$serverside->edit('branch_status', function ($data) {
			return purify($data['branch_status']) == 1 ? '<span class="badge badge-label bg-success"> Active </span>' : '<span class="badge badge-label bg-danger"> In Active </span>';
		});

		$serverside->edit('branch_id', function ($data) {
			$del = $edit = '';

			$disabled = $this->countBranchUser($data[$this->id]) > 0 ? 'disabled' : '';

			if (currentUserRoleID() == 1)
				$del = '<button class="btn btn-soft-danger btn-sm" onclick="deleteRecord(' . $data[$this->id] . ')" data-id="' . $data[$this->id] . '" ' . $disabled . ' title="Delete"> <i class="fa fa-trash"></i> </button>';

			if (currentUserRoleID() == 1 || currentUserBranchID() == $data[$this->id])
				$edit = '<button class="btn btn-soft-info btn-sm" onclick="updateRecord(' . $data[$this->id] . ')" title="Update"><i class="fa fa-edit"></i> </button>';

			return "<center> $del $edit </center>";
		});

		echo $serverside->generate();
	}

	public function countBranchUser($branchID)
	{
		return countData(['branch_id' => $branchID], 'user');
	}
}
