<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Student_devices_list_model extends CI_Model
{
	public $table = 'student_devices_list';
	public $id = 'device_id';
	public $order = 'DESC';

	public function __construct()
	{
		parent::__construct();
	}

	protected $fillable = [
		'user_id',
		'device_uuid',
		'device_user_agent',
		'branch_id',
	];

	public $with = [];
}
