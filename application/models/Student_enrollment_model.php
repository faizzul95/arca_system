<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Student_enrollment_model extends CI_Model
{
	public $table = 'student_enrollment';
	public $id = 'stud_id';
	public $order = 'DESC';

	public function __construct()
	{
		parent::__construct();
		$this->abilities = parent::permission(['student-delete', 'student-update-assign-room']);
	}

	protected $fillable = [
		'user_id',
		'college_id',
		'college_level_id',
		'college_room_id',
		'college_bed_no',
		'semester_number',
		'academic_id',
		'branch_id'
	];

	public $with = ['user', 'sticker', 'room', 'currentAcademic'];

	//  relation
	public function userRelation($data)
	{
		if (hasData($data, 'user_id'))
			return hasOne('User_model', 'user_id', $data['user_id'], NULL, ['profileStudent']);
	}

	public function stickerRelation($data)
	{
		if (hasData($data, $this->id))
			return hasOne('Student_sticker_collection_model', 'stud_id', $data[$this->id], [
				'user_id' => currentUserID(),
				'academic_id' => currentAcademicID(),
				'branch_id' => currentUserBranchID()
			]);
	}

	public function roomRelation($data)
	{
		if (hasData($data, 'college_room_id'))
			return hasOne('Config_college_room_model', 'college_room_id', $data['college_room_id'], NULL, ['college']);
	}

	public function currentAcademicRelation($data)
	{
		if (hasData($data, 'academic_id'))
			return hasOne('Academic_year_model', 'academic_id', $data['academic_id']);
	}

	public function getListStudentEnrollDt($collegeID = NULL)
	{
		$serverside = serversideDT();
		$serverside->query("SELECT 
        `user`.`user_full_name`, 
        `user`.`user_nric`, 
        `user`.`user_email`, 
        `user`.`user_contact_no`, 
        `user`.`user_matric_code`, 
        `enroll`.`semester_number`, 
        `prog`.`program_code`, 
        `cr`.`college_room_number`, 
        `cl`.`college_level_name`, 
        `collection`.`total_sticker`, 
        `enroll`.`stud_id` 
        FROM {$this->table} `enroll`
        LEFT JOIN `student_sticker_collection` `collection` ON `enroll`.`stud_id`=`collection`.`stud_id`
        LEFT JOIN `user` ON `enroll`.`user_id`=`user`.`user_id`
        LEFT JOIN `user_profile` `profile` ON `enroll`.`user_id`=`profile`.`user_id`
        LEFT JOIN `master_college_level` `cl` ON `enroll`.`college_level_id`=`cl`.`college_level_id`
        LEFT JOIN `config_college_room` `cr` ON `enroll`.`college_room_id`=`cr`.`college_room_id`
        LEFT JOIN `master_program` `prog` ON `user`.`program_id`=`prog`.`program_id`
        WHERE `enroll`.`branch_id` = '" . currentUserBranchID() . "' AND `enroll`.`academic_id` = '" . currentAcademicID() . "' AND `profile`.`role_id` IN (6,7) AND `enroll`.`college_id` = " . escape($collegeID) . "
        ORDER BY {$this->id} {$this->order}");

		$serverside->hide('user_nric'); // hides column from the output
		$serverside->hide('user_email'); // hides column from the output
		$serverside->hide('user_contact_no'); // hides column from the output
		$serverside->hide('program_code'); // hides column from the output
		$serverside->hide('college_level_name'); // hides column from the output
		$serverside->hide('total_sticker'); // hides column from the output

		$serverside->edit('user_full_name', function ($data) {
			return purify($data['user_full_name']);
		});

		$serverside->edit('user_matric_code', function ($data) {
			return purify($data['user_matric_code']);
		});

		$serverside->edit('college_room_number', function ($data) {
			return purify($data['college_room_number']);
		});

		$serverside->edit('semester_number', function ($data) {
			return purify($data['program_code']) . ' / ' . purify($data['semester_number']);
		});

		$serverside->edit('stud_id', function ($data) {

			$disabledDelete = $data['total_sticker'] > 0 ? 'disabled' : '';

			$del = $edit = '';
			if ($this->abilities['student-delete'] && $data['total_sticker'] == 0)
				$del = '<button  class="btn btn-sm btn-soft-danger" onclick="deleteAssignRecord(' . $data[$this->id] . ')" data-id="' . $data[$this->id] . '" title="Delete Enrollment" ' . $disabledDelete . '> <i class="fa fa-trash"></i> </button>';

			if ($this->abilities['student-update-assign-room'])
				$edit = '<button class="btn btn-sm btn-soft-info" onclick="updateRecord(' . $data[$this->id] . ')" title="Edit Enrollment"><i class="fa fa-edit"></i> </button>';

			return "<center> $del $edit</center>";
		});

		echo $serverside->generate();
	}

	public function getListDirectoryDt($collegeID = NULL)
	{
		// get college info
		$collegeData = find('config_college', ['college_id' => $collegeID], 'row_array');
		$genderPrefer = $collegeData['college_gender_prefer'];
		$levelEduPrefer = $collegeData['college_level_prefer'];

		$levelEduID = (!in_array($levelEduPrefer, [4, 5])) ? "AND `u`.`edu_level_id` = " . $levelEduPrefer : '';
		$gender = ($genderPrefer != 3) ? "AND `u`.`user_gender` = " . $genderPrefer : '';

		$serverside = serversideDT();
		$serverside->query("SELECT
        `u`.`user_full_name`, 
        `u`.`user_nric`, 
        `u`.`user_email`, 
        `u`.`user_contact_no`, 
        `u`.`user_matric_code`,
        `prog`.`program_code`,
        `u`.`user_gender`,
        `prog`.`program_name`,
        `u`.`edu_level_id`,
        `level`.`edu_level_name`,
        `u`.`user_id`
        FROM `user` `u`
        LEFT JOIN `master_program` `prog` ON `u`.`program_id`=`prog`.`program_id`
        LEFT JOIN `user_profile` `profile` ON `u`.`user_id`=`profile`.`user_id`
        LEFT JOIN `master_education_level` `level` ON `u`.`edu_level_id`=`level`.`edu_level_id`
        WHERE NOT EXISTS
        (SELECT
            `enroll`.`stud_id` 
            FROM `student_enrollment` `enroll`
            LEFT JOIN `user_profile` `profile` ON `enroll`.`user_id`=`profile`.`user_id`
            WHERE `enroll`.`branch_id` = '" . currentUserBranchID() . "' AND `enroll`.`academic_id` = '" . currentAcademicID() . "' AND `enroll`.`user_id` = `u`.`user_id`) 
        AND `profile`.`role_id` = 6
        AND `u`.`is_deleted` != 1 $gender $levelEduID");

		$serverside->hide('user_nric'); // hides column from the output
		$serverside->hide('user_email'); // hides column from the output
		$serverside->hide('user_contact_no'); // hides column from the output
		$serverside->hide('edu_level_id'); // hides column from the output
		$serverside->hide('program_name'); // hides column from the output

		$serverside->edit('user_full_name', function ($data) {
			return purify($data['user_full_name']);
		});

		$serverside->edit('user_matric_code', function ($data) {
			return purify($data['user_matric_code']);
		});

		$serverside->edit('program_code', function ($data) {
			return purify($data['program_code']);
		});

		$serverside->edit('edu_level_name', function ($data) {
			return purify($data['edu_level_name']);
		});

		$serverside->edit('user_gender', function ($data) {
			return $data['user_gender'] == 1 ? 'Male' : 'Female';
		});

		$serverside->edit('user_id', function ($data) {

			$assign = '';
			$assign = '<button class="btn btn-sm btn-soft-info" onclick="addEnrollRecord(' . $data['user_id'] . ')" title="Add to College"><i class="fa fa-edit"></i> Add to College </button>';

			return "<center> $assign </center>";
		});

		echo $serverside->generate();
	}

	public function getListDataCopyFilter($collegeID = NULL, $previousAcademicID = NULL)
	{
		return rawQuery("SELECT
        `enroll`.`academic_id`, 
        `enroll`.`college_bed_no`, 
        `enroll`.`semester_number`, 
        `enroll`.`college_room_id`, 
        `enroll`.`college_id`, 
        `enroll`.`branch_id`, 
        `prog`.`program_code`,
        `prog`.`program_name`,
        `level`.`edu_level_name`,
        `app`.`is_apply`,
        `app`.`approval_status`,
        `u`.`user_full_name`, 
        `u`.`user_nric`, 
        `u`.`user_email`, 
        `u`.`user_contact_no`, 
        `u`.`user_matric_code`,
        `u`.`edu_level_id`,
        `u`.`user_gender`,
        `u`.`user_status`,
        `u`.`user_id`
        FROM `student_enrollment` `enroll`
        LEFT JOIN `student_college_application` `app` ON `enroll`.`stud_id`=`app`.`stud_id`
        LEFT JOIN `user` `u` ON `u`.`user_id`=`enroll`.`user_id`
        LEFT JOIN `master_program` `prog` ON `u`.`program_id`=`prog`.`program_id`
        LEFT JOIN `user_profile` `profile` ON `u`.`user_id`=`profile`.`user_id`
        LEFT JOIN `master_education_level` `level` ON `u`.`edu_level_id`=`level`.`edu_level_id`
        WHERE NOT EXISTS
        (SELECT
            `enroll`.`stud_id` 
            FROM `student_enrollment` `en`
            WHERE `en`.`branch_id` = " . escape(currentUserBranchID()) . " AND `en`.`academic_id` = " . escape(currentAcademicID()) . " AND `en`.`user_id` = `u`.`user_id`) 
        AND `enroll`.`college_id` = " . escape($collegeID) . "
        AND `enroll`.`academic_id` = " . escape($previousAcademicID) . "
        AND `profile`.`role_id` = '6'
        AND `u`.`user_status` = 1
        AND `u`.`is_deleted` != 1");
	}

	public function countAllStudentByAcademic($collegeID = NULL)
	{
		$this->db->from($this->table);
		$this->db->join('user', 'user.user_id = ' . $this->table . '.user_id');
		$this->db->where($this->table . '.academic_id', currentAcademicID());
		$this->db->where($this->table . '.branch_id', currentUserBranchID());

		if (!empty($collegeID)) {
			$this->db->where($this->table . '.college_id', $collegeID);
		}

		$this->db->where('user.user_status', 1);
		return $this->db->count_all_results();
	}

	public function countStudentByGenderAcademic($gender = 1, $collegeID = NULL)
	{
		$this->db->from($this->table);
		$this->db->join('user', 'user.user_id = ' . $this->table . '.user_id');
		$this->db->where($this->table . '.academic_id', currentAcademicID());
		$this->db->where($this->table . '.branch_id', currentUserBranchID());

		if (!empty($collegeID)) {
			$this->db->where($this->table . '.college_id', $collegeID);
		}

		$this->db->where('user.user_status', 1);
		$this->db->where('user.user_gender', $gender);
		return $this->db->count_all_results();
	}
}
