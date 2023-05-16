<?php

namespace App\middleware\core\traits;

defined('BASEPATH') or exit('No direct script access allowed');

trait XssProtectionTrait
{
	/**
	 * Function to check if has xss code in $_POST or $_GET
	 */
	public function isXssAttack(): bool
	{
		// use voku/anti-xss library
		$data = hasData($_POST) && hasData($_GET) ? array_merge($_POST, $_GET) : (hasData($_POST) ? $_POST : $_GET);
		return antiXss($data);
	}
}
