<?php

namespace App\middleware\core\traits;

defined('BASEPATH') or exit('No direct script access allowed');

trait ModuleStatusActiveTrait
{
	public function isModuleActive()
	{
		$CI = &get_instance();
		model('Menu_model', 'menuM');

		$currentURL = $CI->router->uri->uri_string();

		// get menu row data
		$dataMenu = $CI->menuM::find($currentURL, 'menu_url');

		if (hasData($dataMenu)) {
			// check if module is active. default is false
			$isActive = $dataMenu['is_active'] == 1 ? true : false;

			if (!isAjax() && !$isActive && !in_array(segment(1), ['rbac', 'management', 'error']) && !is_cli() && currentUserRoleID() != 1) {

				// Set the HTTP status code to 503
				$CI->output->set_status_header(503);
				include(APPPATH . 'views/errors/custom/error_503.php');

				exit;
			}
		}
	}
}
