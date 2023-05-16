<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Config_college_sticker_model extends CI_Model
{
	public $table = 'config_sticker_college';
	public $id = 'sticker_id';
	public $order = 'DESC';

	public function __construct()
	{
		parent::__construct();
		$this->abilities = parent::permission();
	}

	protected $fillable = [
		'sticker_college_amount',
		'sticker_university_amount',
		'sticker_hep_amount',
		'academic_id',
		'branch_id'
	];

	public $with = [];
}
