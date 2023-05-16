<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

use App\services\general\processor\UserProfileSearchProcessor as SearchUser;

class User_profile_model extends CI_Model
{
	public $table = 'user_profile';
	public $id = 'profile_id';
	public $order = 'DESC';

	public function __construct()
	{
		parent::__construct();
		$this->abilities = parent::permission(['user-default-profile']);
	}

	protected $fillable = [
		'user_id',
		'role_id',
		'college_id',
		'profile_status',
		'is_main',
		'is_special',
		'has_position',
		'branch_id',
	];

	public $with = ['user', 'roles', 'college', 'qrCode'];

	//  relation
	public function userRelation($data)
	{
		return hasOne('User_model', 'user_id', $data['user_id']);
	}

	public function rolesRelation($data)
	{
		return hasOne('Roles_model', 'role_id', $data['role_id']);
	}

	public function collegeRelation($data)
	{
		return hasOne('Config_college_model', 'college_id', $data['college_id']);
	}

	public function qrCodeRelation($data)
	{
		return hasOne('Files_model', 'entity_id', $data['user_id'], ['entity_file_type' => 'QR_CODE']);
	}

	public function getAllProfileByUserID($userID = NULL)
	{
		$this->db->join('master_role role', 'role.role_id=up.role_id', 'left')
			->where('up.user_id', $userID)
			->order_by('up.role_id', "asc");

		return $this->db->get($this->table . ' up')->result_array();
	}

	public function getMainProfileByUserID($userID = NULL, $status = NULL)
	{
		$this->db->join('master_role role', 'role.role_id=up.role_id', 'left')
			->join('master_branch branch', 'branch.branch_id=up.branch_id', 'left')
			->where('up.user_id', $userID)
			->where('up.is_main', 1);

		if (!empty($status)) {
			$this->db->where('up.profile_status', $status);
		}

		return $this->db->get($this->table . ' up')->row_array();
	}

	public function getProfileByProfileID($profileID = NULL)
	{
		$this->db->join('master_role role', 'role.role_id=up.role_id', 'left')
			->join('master_branch branch', 'branch.branch_id=up.branch_id', 'left')
			->where('up.profile_id', $profileID);

		return $this->db->get($this->table . ' up')->row_array();
	}

	public function getListProfileByUserIdDt($userID, $branchID = NULL)
	{
		$branchID = hasData($branchID) ? $branchID : currentUserBranchID();

		$serverside = serversideDT();
		$serverside->query(" SELECT 
        profiles.branch_id, 
        profiles.user_id, 
        profiles.role_id, 
        roles.role_name, 
        profiles.profile_status, 
        profiles.is_main, 
        college.college_name, 
        program.program_code, 
        user.user_intake, 
        profiles.is_special,
        profiles.has_position,
        profiles.profile_id 
        FROM {$this->table} profiles
        LEFT JOIN user ON profiles.user_id=user.user_id
        LEFT JOIN master_program program ON user.program_id=program.program_id
        LEFT JOIN master_education_level edu ON user.edu_level_id=edu.edu_level_id
        LEFT JOIN master_role roles ON profiles.role_id=roles.role_id
        LEFT JOIN config_college college ON profiles.college_id=college.college_id
        WHERE profiles.user_id = " . escape($userID) . " AND profiles.branch_id = " . escape($branchID) . "
        ORDER BY {$this->id} {$this->order}");

		$serverside->hide('profile_status'); // hides column from the output
		$serverside->hide('role_id'); // hides column from the output
		$serverside->hide('college_name'); // hides column from the output
		$serverside->hide('branch_id'); // hides column from the output
		$serverside->hide('program_code'); // hides column from the output
		$serverside->hide('user_intake'); // hides column from the output
		$serverside->hide('has_position'); // hides column from the output
		$serverside->hide('is_special'); // hides column from the output
		$serverside->hide('user_id'); // hides column from the output

		$serverside->edit('role_name', function ($data) {
			$roleName = $data['role_name'];

			if ($data['role_id'] == 4) {
				$roleName = $data['role_name'] . '<br> COLLEGE : ' . $data['college_name'];
			} else if ($data['role_id'] == 6) {
				$roleName = $data['role_name'] . '<br> PROGRAMME : ' . $data['program_code'] . '<br> INTAKE : ' . $data['user_intake'];
			}

			return purify($roleName);
		});

		$serverside->edit('is_main', function ($data) {

			$btnSet = '';
			$defaultProfile = "javascript:void(0)";

			$superadmin = SearchUser::hasSuperadminAccess($data['user_id']);

			if ($this->abilities['user-default-profile']) {
				if (currentUserRoleID() == 1 and $superadmin) {
					$defaultProfile = "setDefaultProfile(" . $data['user_id'] . ", " . $data['profile_id'] . ", " . $data['branch_id'] . ", '" . $data['role_name'] . "')";
					$btnSet = '<button type="button" class="btn btn-soft-info btn-sm" onclick="' . $defaultProfile . '"> Set Default </button>';
				} else if (!$superadmin) {
					$defaultProfile = "setDefaultProfile(" . $data['user_id'] . ", " . $data['profile_id'] . ", " . $data['branch_id'] . ", '" . $data['role_name'] . "')";
					$btnSet = '<button type="button" class="btn btn-soft-info btn-sm" onclick="' . $defaultProfile . '"> Set Default </button>';
				}
			}

			return $data['is_main'] == 1 ? '<span class="badge badge-label bg-success"> Main Profile </span>' : $btnSet;
		});

		$serverside->edit('profile_id', function ($data) {
			$del = '';

			$disabled = $data['is_main'] == 1 ? true : false;

			if (!$disabled)
				$del = '<button class="btn btn-soft-danger btn-sm" onclick="deleteProfileRecord(' . $data[$this->id] . ')" data-id="' . $data[$this->id] . '" title="Delete"> <i class="fa fa-trash"></i> </button>';

			return "<center> $del </center>";
		});

		echo $serverside->generate();
	}

	public function getListAssignProfilOrganizerDt($userIDArr = NULL)
	{
		$excludeUserExist = !empty($userIDArr) ? "AND user.user_id NOT IN (" . $userIDArr . ")" : '';

		$serverside = serversideDT();
		$serverside->query("SELECT 
        profiles.branch_id, 
        profiles.role_id, 
        program.program_code, 
        user.program_id, 
        user.user_full_name, 
        user.user_matric_code, 
        user.user_contact_no, 
        user.user_email, 
        user.user_id
        FROM {$this->table} profiles
        LEFT JOIN user ON profiles.user_id=user.user_id
        LEFT JOIN master_program program ON user.program_id=program.program_id
        LEFT JOIN master_education_level edu ON user.edu_level_id=edu.edu_level_id
        LEFT JOIN master_role roles ON profiles.role_id=roles.role_id
        LEFT JOIN config_college college ON profiles.college_id=college.college_id
        WHERE profiles.role_id = '5' AND profiles.branch_id = " . currentUserBranchID() . " AND user.user_status = '1' " . $excludeUserExist . "
        ORDER BY {$this->id} {$this->order}");

		$serverside->hide('role_id'); // hides column from the output
		$serverside->hide('branch_id'); // hides column from the output
		$serverside->hide('program_code'); // hides column from the output
		$serverside->hide('program_id'); // hides column from the output
		$serverside->hide('user_contact_no'); // hides column from the output
		$serverside->hide('user_email'); // hides column from the output

		$serverside->edit('user_full_name', function ($data) {
			$program = (!empty($data['program_id'])) ? '<br> PROGRAMME : ' . $data['program_code'] : '';
			$userName = $data['user_full_name'] . '' . $program;
			return purify($userName);
		});

		$serverside->edit('user_matric_code', function ($data) {
			return purify($data['user_matric_code']);
		});

		$serverside->edit('user_id', function ($data) {
			$add = '';
			if (hasData($data['program_code'])) {
				$addBtn = "addOrganizer(" . $data['user_id'] . ", " . escape($data['user_full_name']) . ", " . escape($data['user_matric_code']) . ", " . escape($data['program_code']) . ", '" . $data['user_contact_no'] . "', " . escape($data['user_email']) . ")";
				$add = '<button class="btn btn-soft-info btn-sm" onclick="' . $addBtn . '" title="Add as Organizer"><i class="fa fa-plus"></i> Add </button>';
			} else {
				$add = '<small class="text-danger text-bold"><i>(Student information is required to update)</i></small>';
			}
			return "<center> $add </center>";
		});

		echo $serverside->generate();
	}
}
