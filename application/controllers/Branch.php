<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Branch extends Controller
{
	public function __construct()
	{
		parent::__construct();
		model('Branch_model', 'branchM');
	}

	public function index()
	{
		errorpage('404');
	}

	public function listBranchManagement()
	{
		if (isAjax())
			view('management/list_branch');
		else
			errorpage('404');
	}

	public function getBranchByID($id)
	{
		if (isAjax() && $id != NULL)
			json($this->branchM::find(xssClean($id)));
		else
			errorpage('404');
	}

	public function getListBranch()
	{
		if (isAjax())
			echo $this->branchM->getListBranchDt();
		else
			errorpage('404');
	}

	public function save()
	{
		if (isAjax()) {
			$this->_rules();

			if ($this->form_validation->run() == FALSE) {
				json(NULL, 'validate');
			} else {
				json($this->branchM::save($_POST));
			}
		} else {
			errorpage('404');
		}
	}

	public function delete($id)
	{
		if (isAjax() && $id != NULL)
			json($this->branchM::delete(xssClean($id)));
		else
			errorpage('404');
	}

	public function getBranchSelect()
	{
		if (isAjax()) {
			$dataBranch = $this->branchM::all(['branch_status' => 1]);

			echo '<option value=""> - Select - </option>';

			if ($dataBranch) {
				foreach ($dataBranch as $row) {
					$selected = ($row['branch_id'] == currentUserBranchID()) ? 'selected' : '';
					echo '<option value="' . $row['branch_id'] . '" ' . $selected . '> ' . purify($row['branch_name']) . ' </option>';
				}
			}
		} else {
			errorpage('404');
		}
	}

	public function _rules()
	{
		$this->form_validation->set_rules('branch_name', 'Name', 'trim|required|min_length[2]|max_length[250]');
		$this->form_validation->set_rules('branch_code', 'Code', 'trim|required|min_length[2]|max_length[15]');
		$this->form_validation->set_rules('branch_status', 'Status', 'trim|required|integer');

		$this->form_validation->set_rules('branch_address', 'Address', 'trim|required|min_length[2]|max_length[250]');
		$this->form_validation->set_rules('branch_postcode', 'Postal Code', 'trim|required|min_length[4]|max_length[8]');
		$this->form_validation->set_rules('branch_city', 'City', 'trim|required|min_length[1]|max_length[25]');
		$this->form_validation->set_rules('branch_state', 'State', 'trim|required|min_length[4]|max_length[25]');

		$this->form_validation->set_rules('branch_email', 'Email', 'trim|required|valid_email|min_length[5]|max_length[250]');
		$this->form_validation->set_rules('branch_pic_name', 'Person In Charge', 'trim|required|min_length[3]|max_length[250]');
		$this->form_validation->set_rules('branch_pic_office_no', 'Office No.', 'trim|required|min_length[5]|max_length[20]');

		$this->form_validation->set_rules('branch_id', 'Branch ID', 'trim');
		// $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}
}
