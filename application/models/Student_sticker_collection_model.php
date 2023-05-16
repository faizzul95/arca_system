<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Student_sticker_collection_model extends CI_Model
{
	public $table = 'student_sticker_collection';
	public $id = 'collection_id';
	public $order = 'DESC';

	public function __construct()
	{
		parent::__construct();
		// $this->abilities = parent::permission();
	}

	protected $fillable = [
		'stud_id',
		'academic_id',
		'user_id',
		'total_hep_sticker',
		'hep_event_id',
		'total_university_sticker',
		'university_event_id',
		'total_college_sticker',
		'college_event_id',
		'total_faculty_sticker',
		'faculty_event_id',
		'total_club_sticker',
		'club_event_id',
		'total_sticker',
		'is_college_eligible',
		'branch_id',
	];

	public $with = ['user', 'enrollment'];

	//  relation
	public function userRelation($data)
	{
		return hasOne('User_model', 'user_id', $data['user_id'], NULL, ['profileStudent']);
	}

	public function enrollmentRelation($data)
	{
		return hasOne('Student_enrollment_model', 'stud_id', $data['stud_id'], NULL, ['user']);
	}
}
