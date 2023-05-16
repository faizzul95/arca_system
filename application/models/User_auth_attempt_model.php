<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class User_auth_attempt_model extends CI_Model
{
	public $table = 'user_login_attempt';
	public $id = 'attempt_id';
	public $order = 'DESC';

	private $allow_attempt_count = 5;

	public function __construct()
	{
		parent::__construct();
	}

	protected $fillable = [
		'user_id',
		'ip_address',
		'time',
		'branch_id',
	];

	public $with = ['user', 'branch'];

	//  relation
	public function userRelation($data)
	{
		return hasOne('User_model', 'user_id', $data['user_id']);
	}

	public function branchRelation($data)
	{
		return hasOne('Branch_model', 'branch_id', $data['branch_id']);
	}

	public function login_attempt_exceeded($userid)
	{
		$query = $this->db->where('user_id', $userid)
			->where('ip_address', $this->input->ip_address())
			->where('time > NOW() - INTERVAL 10 MINUTE')
			->get($this->table);

		return [
			'isExceed' => !($this->allow_attempt_count <= $query->num_rows()),
			'count' => $query->num_rows()
		];
	}

	public function clear_login_attempts($userid)
	{
		return $this->db->where('user_id', $userid)->where('ip_address', $this->input->ip_address())->delete($this->table);
	}
}
