<?php

use App\middleware\core\traits\SecurityHeadersTrait;
use App\middleware\core\traits\ModuleStatusActiveTrait;

class Controller extends CI_Controller
{
	use SecurityHeadersTrait; // set header security
	use ModuleStatusActiveTrait; // check if module page is currently active

	public function __construct()
	{
		parent::__construct();

		$this->set_security_headers();
		$this->isModuleActive();

		isLogin();
		library('form_validation');
	}
}
