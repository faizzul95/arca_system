<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

use App\services\general\processor\UserProfileSearchProcessor as SearchUser;

class User_model extends CI_Model
{
	public $table = 'user';
	public $id = 'user_id';
	public $order = 'DESC';

	public function __construct()
	{
		parent::__construct();
		$this->abilities = parent::permission(['user-delete', 'user-update', 'user-assign-role']);
	}

	protected $fillable = [
		'user_full_name',
		'user_preferred_name',
		'user_nric',
		'user_email',
		'user_contact_no',
		'user_gender',
		'user_matric_code',
		'program_id',
		'edu_level_id',
		'user_intake',
		'user_username',
		'user_password',
		'user_avatar',
		'user_status',
		'two_factor_status',
		'two_factor_type',
		'two_factor_secret',
		'branch_id',
		'is_deleted',
	];

	public $with = ['profile', 'currentProfile', 'profileStudent', 'programme', 'eduLevel', 'profileAvatar'];

	//  relation
	public function currentProfileRelation($data)
	{
		return hasOne('User_profile_model', 'user_id', $data[$this->id], ['profile_id' => currentUserProfileID()]);
	}

	//  relation
	public function profileRelation($data)
	{
		return hasMany('User_profile_model', 'user_id', $data[$this->id]);
	}

	//  relation
	public function profileStudentRelation($data, $roleID = 6)
	{
		return hasOne('User_profile_model', 'user_id', $data[$this->id], ['role_id' => $roleID]);
	}

	//  relation
	public function programmeRelation($data)
	{
		return hasOne('Master_program_model', 'program_id', $data['program_id']);
	}

	//  relation
	public function eduLevelRelation($data)
	{
		return hasOne('Master_education_level_model', 'edu_level_id', $data['edu_level_id']);
	}

	public function profileAvatarRelation($data)
	{
		return hasOne('Files_model', 'entity_id', $data[$this->id], ['entity_file_type' => 'USER_PROFILE']);
	}

	public function getListUserDt($roleID = NULL, $branchID = NULL, $isArchive = 0)
	{
		$branchID = empty($branchID) ? currentUserBranchID() : $branchID;
		$hideSuperadmin = currentUserRoleID() != 1 ? ' AND `user`.`user_id` != 1' : '';
		$userBranchID = ' AND `user`.`branch_id` = ' . escape($branchID);
		$join = (!empty($roleID)) ? 'LEFT JOIN `user_profile` `profile` ON `user`.`user_id`=`profile`.`user_id`
                    WHERE `user`.`is_deleted` = ' . $isArchive . ' AND `profile`.`branch_id` = ' . escape($branchID) . ' AND `profile`.`role_id` = ' . escape($roleID) . '' . $hideSuperadmin : 'WHERE `user`.`is_deleted` = ' . $isArchive . '' . $hideSuperadmin;

		$serverside = serversideDT();
		$serverside->query("SELECT 
        `user`.`user_avatar`, 
        `user`.`user_full_name`, 
        `user`.`user_nric`, 
        `user`.`user_matric_code`, 
        `user`.`user_email`, 
        `user`.`user_contact_no`, 
        `user`.`branch_id`, 
        `user`.`user_status`, 
        `user`.`user_id` 
        FROM 
        {$this->table} `user`
        $join
		$userBranchID
        ORDER BY {$this->id} {$this->order}");

		$serverside->hide('user_nric'); // hides column from the output
		$serverside->hide('user_matric_code'); // hides column from the output
		$serverside->hide('user_contact_no'); // hides column from the output
		// $serverside->hide('user_avatar'); // hides column from the output

		$serverside->edit('user_avatar', function ($data) {
			$profilePath = purify($data['user_avatar']);
			return '<center><img src="' . purify($data['user_avatar']) . '" alt="avatar" class="avatar-sm rounded-circle"></center>';
		});

		$serverside->edit('user_full_name', function ($data) {
			$nric = (!empty($data['user_nric'])) ? $data['user_nric'] : '-';
			return purify($data['user_full_name']) . "<br> NRIC : " . purify($nric);
		});

		$serverside->edit('user_email', function ($data) {
			$contactNo = (!empty($data['user_contact_no'])) ? $data['user_contact_no'] : '-';
			return 'Email : ' . purify($data['user_email']) . "<br> Contact No : " . purify($contactNo);
		});

		$serverside->edit('user_status', function ($data) {
			return $data['user_status'] == 1 ? '<span class="badge badge-label bg-success"> Active </span>' : '<span class="badge badge-label bg-danger"> In Active </span>';
		});

		$serverside->edit('branch_id', function ($data) {
			$dataProfile = $this->getProfileData($data['user_id'], $data['branch_id']);
			$profileList = '';

			if ($dataProfile) {
				$profileArr = [];
				foreach ($dataProfile as $profile) {
					$mainProfile = ($profile['is_main'] == 1) ? ' &nbsp; <i class="ri-star-fill" style="color:orange" title="Main profile"></i>' : '';
					$isSpecial = $profile['is_special'] == 0 ? NULL : ' (OKU)';

					$position = [
						'1' =>  'JPK',
						'2' =>  'MPP',
						'3' =>  'ATHLETE',
						'4' =>  'UNIFORM',
					];

					$hasPosition = !empty($profile['has_position']) ? ' [' . $position[$profile['has_position']] . ']' : NULL;

					$badge = [
						'1' => '<span class="badge badge-soft-info"> ' . $profile['role_name'] . '' . $mainProfile . ' </span>',
						'2' => '<span class="badge badge-soft-secondary"> ' . $profile['role_name'] . '' . $mainProfile . ' </span>',
						'3' => '<span class="badge badge-soft-success"> ' . $profile['role_name'] . '' . $mainProfile . ' </span>',
						'4' => '<span class="badge badge-soft-primary"> ' . $profile['role_name'] . '' . $mainProfile . ' </span>',
						'5' => '<span class="badge badge-soft-danger"> ' . $profile['role_name'] . '' . $mainProfile . ' </span>',
						'6' => '<span class="badge badge-soft-warning"> ' . $profile['role_name'] . '' . $isSpecial . '' . $hasPosition . ' ' . $mainProfile . ' </span>',
					];

					array_push($profileArr, $badge[$profile['role_id']]);
				}

				$profileList = '<ul><li>' . implode('</li><li>', $profileArr) . '</li></ul>';
			}

			return $profileList;
		});

		$serverside->edit('user_id', function ($data) {

			$del = $edit = $profile = '';

			$superadmin = SearchUser::hasSuperadminAccess($data['user_id']);
			$disabledDelSession = ($data['user_id'] == currentUserID()) ? true : false;
			$countUsersLinkTable = SearchUser::hasUsedUserID($data['user_id'], $data['user_matric_code']);

			if ($this->abilities['user-delete'] && !$superadmin && !$disabledDelSession && !$countUsersLinkTable)
				$del = '<button class="btn btn-soft-danger btn-sm" onclick="deleteRecord(' . $data[$this->id] . ')" data-id="' . $data[$this->id] . '" title="Delete"> <i class="fa fa-trash"></i> </button>';
			else if ($countUsersLinkTable)
				$del = '<button class="btn btn-soft-warning btn-sm" onclick="archiveRecord(' . $data[$this->id] . ')" data-id="' . $data[$this->id] . '" title="Archive Information"> <i class="ri-inbox-archive-line"></i> </button>';

			if ($this->abilities['user-update'])
				$edit = '<button class="btn btn-soft-info btn-sm" onclick="updateRecord(' . $data[$this->id] . ')" title="Update"><i class="fa fa-edit"></i> </button>';

			if ($this->abilities['user-assign-role'])
				$profile = '<button class="btn btn-soft-success btn-sm" onclick="profileRecord(' . $data[$this->id] . ', ' . escape($data['user_full_name']) . ')" title="Profile"><i class="ri-shield-user-line"></i> </button>';
			return "<center> $del $edit $profile </center>";
		});

		echo $serverside->generate();
	}

	public function getSpecificUser($param = NULL)
	{
		$this->db->where('user_id', $param)
			->or_where('user_nric', $param)
			->or_where('user_matric_code', $param)
			->or_where('user_email', $param)
			->or_where('user_contact_no', $param)
			->or_where('user_username', $param);

		return $this->db->get($this->table)->row_array();
	}

	public function getProfileData($userid = NULL, $branchid = NULL)
	{
		return $this->db->select('profile.profile_id, profile.college_id, profile.role_id, profile.is_main, profile.is_special, profile.has_position, profile.branch_id, role.role_name, college.college_name')
			->from('user_profile as profile')
			->join('master_role role', 'role.role_id = profile.role_id', 'left')
			->join('config_college college', 'college.college_id = profile.college_id', 'left')
			->where('user_id', $userid)
			->where('profile.branch_id', $branchid)
			->get()->result_array();
	}
}
