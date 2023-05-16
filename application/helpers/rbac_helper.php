<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

if (!function_exists('ci')) {
	function ci()
	{
		return get_instance();
	}
}

if (!function_exists('isLogin')) {
	function isLogin($param = 'isLoggedInSession', $redirect = 'auth/logout')
	{
		library('session');
		if (!ci()->session->userdata($param)) {
			redirect($redirect);
		}
	}
}

if (!function_exists('isLoginCheck')) {
	function isLoginCheck($param = 'isLoggedInSession')
	{
		library('session');
		if (!ci()->session->userdata($param))
			return false;
		else
			return true;
	}
}

if (!function_exists('apiRoleAccess')) {
	function apiRoleAccess($roleArr)
	{
		if (!in_array(currentUserRoleID(), $roleArr)) {
			json(
				[
					"code" => 403,
					"message" => "API Access forbidden"
				]
			);
			exit();
		}
	}
}

if (!function_exists('setSession')) {
	function setSession($param = NULL)
	{
		library('session');
		return ci()->session->set_userdata($param);
	}
}

if (!function_exists('getSession')) {
	function getSession($param = NULL)
	{
		library('session');
		return ci()->session->userdata($param);
	}
}

if (!function_exists('getAllSession')) {
	function getAllSession()
	{
		library('session');
		$allSession = ci()->session->userdata();
		unset($allSession['__ci_last_regenerate']);
		unset($allSession['isLoggedInSession']);
		return $allSession;
	}
}

if (!function_exists('destroySession')) {
	function destroySession($redirect = TRUE, $redirectUrl = 'auth')
	{
		library('session');
		ci()->session->sess_destroy();

		if ($redirect) {
			redirect($redirectUrl);
		}
	}
}

if (!function_exists('currentUserID')) {
	function currentUserID()
	{
		library('session');
		return decodeID(ci()->session->userdata('userID'));
	}
}

if (!function_exists('currentUserAvatar')) {
	function currentUserAvatar()
	{
		library('session');
		return ci()->session->userdata('userAvatar');
	}
}

if (!function_exists('currentUserFullName')) {
	function currentUserFullName()
	{
		library('session');
		return ci()->session->userdata('userFullName');
	}
}

if (!function_exists('currentUserNickName')) {
	function currentUserNickName()
	{
		library('session');
		return ci()->session->userdata('userNickName');
	}
}

if (!function_exists('currentUserProfileID')) {
	function currentUserProfileID()
	{
		library('session');
		return decodeID(ci()->session->userdata('profileID'));
	}
}

if (!function_exists('currentUserStudID')) {
	function currentUserStudID()
	{
		library('session');
		return decodeID(ci()->session->userdata('studID'));
	}
}

if (!function_exists('getAllUserProfile')) {
	function getAllUserProfile()
	{
		$ci = ci();
		$ci->db->select('*')
			->from('user_profile up')
			->join('master_role role', 'role.role_id=up.role_id', 'left')
			->where('up.user_id', currentUserID())
			->order_by('up.role_id', "asc");

		return $ci->db->get()->result_array();
	}
}

if (!function_exists('currentUserProfileName')) {
	function currentUserProfileName()
	{
		library('session');
		return ci()->session->userdata('profileName');
	}
}

if (!function_exists('currentUserRoleID')) {
	function currentUserRoleID()
	{
		library('session');
		return decodeID(ci()->session->userdata('roleID'));
	}
}

if (!function_exists('currentRoleName')) {
	function currentRoleName()
	{
		library('session');
		return ci()->session->userdata('roleName');
	}
}

if (!function_exists('currentUserBranchID')) {
	function currentUserBranchID()
	{
		library('session');
		return decodeID(ci()->session->userdata('branchID'));
	}
}

if (!function_exists('currentUserBranchName')) {
	function currentUserBranchName()
	{
		library('session');
		return ci()->session->userdata('branchName');
	}
}

if (!function_exists('currentAcademicID')) {
	function currentAcademicID()
	{
		library('session');
		return decodeID(ci()->session->userdata('academicID'));
	}
}

if (!function_exists('currentAcademicName')) {
	function currentAcademicName()
	{
		library('session');
		return ci()->session->userdata('academicName');
	}
}

if (!function_exists('currentAcademicOrder')) {
	function currentAcademicOrder()
	{
		library('session');
		return ci()->session->userdata('academicOrder');
	}
}

if (!function_exists('currentMatricID')) {
	function currentMatricID()
	{
		library('session');
		return ci()->session->userdata('userMatricNo');
	}
}

if (!function_exists('hasAccess')) {
	function hasAccess()
	{
		if (!isAjax()) {
			$menu = ci()->uri->segment(1);
			$submenu = ci()->uri->segment(2);

			if (!empty($submenu)) {
				$menu = $menu . '/' . $submenu;
			}

			// check if current role is not superadmin
			if (currentUserRoleID() != 1) {

				$specialAccess = [
					'menu',
					'menu/permission',
					'menu/abilities',
					'management',
				];

				if (in_array($menu, $specialAccess)) {
					if (currentUserRoleID() == 2) {
						return true;
						exit;
					} else {
						errorpage('403');
						exit;
					}
				}

				// get menu
				$ci = ci();
				$ci->db->where('menu_url', $menu);
				$menuData = $ci->db->get('menu')->row_array();

				if ($menuData) {
					$menu_id = $menuData['menu_id'];

					$deviceID = isMobileDevice() ? 2 : 1;

					// get access
					$ci->db->where('menu_id', $menu_id);
					$ci->db->where('role_id', currentUserRoleID());
					$ci->db->where('access_device_type', $deviceID);
					$roleAccess = $ci->db->get('menu_permission')->result_array();

					if (count($roleAccess) > 0) {
						return true;
					} else {
						errorpage('403');
						exit;
					}
				} else {
					errorpage('403');
					exit;
				}
			} else {
				return true; // superadmin has all access
			}
		}
	}
}

if (!function_exists('getMenu')) {
	function getMenu($menuLocation = 0)
	{
		$roleID = currentUserRoleID();
		$menuData = getMenuByRoleID($roleID, $menuLocation);
		$arrayMenu = array();

		if ($menuData) {

			foreach ($menuData as $main) {
				if ($main['menu_location'] == $menuLocation) {
					array_push($arrayMenu, [
						'menu_id' => $main['menu_id'],
						'menu_title' => $main['menu_title'],
						'menu_url' => $main['menu_url'],
						'menu_order' => $main['menu_order'],
						'menu_icon' => $main['menu_icon'],
						'submenu' => getSubMenuByMenuID($roleID, $main['menu_id']),
					]);
				}
			}
		}

		return $arrayMenu;
	}
}

if (!function_exists('getMenuByRoleID')) {
	function getMenuByRoleID($roleID = 1, $menuloc = 1, $main_menu = 0)
	{
		$deviceID = isMobileDevice() ? 2 : 1;

		$ci = ci();
		$ci->db->select('*');
		$ci->db->from('menu_permission mp');
		$ci->db->join('menu m', 'm.menu_id=mp.menu_id', 'left');
		$ci->db->where('m.is_active', '1');
		$ci->db->where('m.menu_location', $menuloc);
		$ci->db->where('m.is_main_menu', $main_menu);
		$ci->db->where('mp.role_id', $roleID);
		$ci->db->where('mp.access_device_type', $deviceID);
		$ci->db->order_by('m.menu_order', 'asc');
		return $ci->db->get()->result_array();
	}
}

if (!function_exists('getSubMenuByMenuID')) {
	function getSubMenuByMenuID($roleID = 1, $menuID = NULL)
	{
		$deviceID = isMobileDevice() ? 2 : 1;

		$ci = ci();
		$ci->db->select('*');
		$ci->db->from('menu_permission mp');
		$ci->db->join('menu m', 'm.menu_id=mp.menu_id', 'left');
		$ci->db->where('m.is_active', '1');
		$ci->db->where('m.is_main_menu', $menuID);
		$ci->db->where('mp.role_id', $roleID);
		$ci->db->where('mp.access_device_type', $deviceID);
		$ci->db->order_by('m.menu_order', 'asc');
		return $ci->db->get()->result_array();
	}
}

if (!function_exists('permission')) {
	function permission($slug = NULL)
	{
		$roleid = currentUserRoleID();

		$ci = ci();
		$hasPermission = NULL;

		if (!empty($slug)) {
			if (!isArray($slug)) {

				$ci->db->where('abilities_slug', $slug);
				$abilitiesData = $ci->db->get('menu_abilities')->row_array();

				if ($abilitiesData) {
					$owned = $abilitiesData['only_owned'];
					if (!empty($owned)) {
						$ids = explode(',', $owned);
						$hasPermission = (in_array($roleid, $ids)) ? TRUE : FALSE;
					}
				}
			} else {
				$ci->db->where_in('abilities_slug', $slug);
				$abilitiesData = $ci->db->get('menu_abilities')->result_array();

				if ($abilitiesData) {
					$checkAbilities = [];
					foreach ($abilitiesData as $data) {
						$newslug = $data['abilities_slug'];
						$owned = $data['only_owned'];

						if (!empty($owned)) {
							$ids = explode(',', $owned);
							$checkAbilities[$newslug] = (in_array($roleid, $ids)) ? TRUE : FALSE;
						} else {
							$checkAbilities[$newslug] = FALSE;
						}
					}

					$hasPermission =  $checkAbilities;
				}
			}
		} else {
			$abilitiesData = $ci->db->get('menu_abilities')->result_array();

			if ($abilitiesData) {
				$checkAbilities = [];
				foreach ($abilitiesData as $data) {
					$newslug = $data['abilities_slug'];
					$owned = $data['only_owned'];

					if (!empty($owned)) {
						$ids = (!empty($owned)) ? explode(',', $owned) : NULL;
						$checkAbilities[$newslug] = (in_array($roleid, $ids)) ? TRUE : FALSE;
					} else {
						$checkAbilities[$newslug] = FALSE;
					}
				}

				$hasPermission =  $checkAbilities;
			}
		}

		return $hasPermission;
	}
}

if (!function_exists('segment')) {
	function segment($segmentNo = 1)
	{
		return ci()->uri->segment($segmentNo);
	}
}
