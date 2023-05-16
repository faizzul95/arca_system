<?php

namespace App\services\general\processor;

defined('BASEPATH') or exit('No direct script access allowed');

class UserProfileSearchProcessor
{
	public $CI;

	public function __construct($scope = NULL)
	{
		$this->CI = &get_instance();
	}

	public static function hasSuperadminAccess($userid = NULL)
	{
		return countData(['user_id' => $userid, 'role_id' => 1, 'branch_id' => currentUserBranchID()], 'user_profile') > 0 ? true : false;
	}

	public static function hasUsedUserID($userid = NULL, $matricNo = NULL)
	{
		// condition to query
		$condition = ['user_id' => $userid, 'branch_id' => currentUserBranchID()];

		// count enrollment (student)
		$enrollment = countData($condition, 'student_enrollment');

		// count for event organizer
		$event = countData($condition, 'event_organizer');

		// count for event attendance
		$eventAttendance = countData($condition, 'event_attendance');

		// total all count value
		$total = $enrollment + $event + $eventAttendance;

		return $total > 0 ? true : false;
	}
}
