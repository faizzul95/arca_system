<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ConfigStickerCollege extends Controller
{
	public function __construct()
	{
		parent::__construct();
		model('Academic_year_model', 'academicM');
		model('Config_college_sticker_model', 'stickerM');
	}

	public function index()
	{
		errorpage('404');
	}

	public function getCollegeStickerByID()
	{
		if (isAjax() && input('sticker_id') != NULL) {
			json($this->stickerM::find(input('sticker_id')));
		} else {
			errorpage('404');
		}
	}

	public function save()
	{
		if (isAjax()) {

			$_POST['branch_id'] = currentUserBranchID();
			$this->_rules();

			if ($this->form_validation->run() == FALSE) {
				json(NULL, 'validate');
			} else {
				json($this->stickerM::save($_POST));
			}
		} else {
			errorpage('404');
		}
	}

	public function _rules()
	{
		$this->form_validation->set_rules('sticker_college_amount', 'Sticker College', 'trim|required|min_length[1]|max_length[2]');
		$this->form_validation->set_rules('sticker_university_amount', 'Sticker University', 'trim|required|min_length[1]|max_length[2]');
		$this->form_validation->set_rules('sticker_hep_amount', 'Sticker HEP', 'trim|required|min_length[1]|max_length[2]');
		$this->form_validation->set_rules('academic_id', 'Academic ID', 'trim');
		$this->form_validation->set_rules('branch_id', 'Branch ID', 'trim');
		$this->form_validation->set_rules('sticker_id', 'Sticker ID', 'trim');

		// $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}
}
