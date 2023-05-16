<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Academic extends Controller
{
	public function __construct()
	{
		parent::__construct();
		model('Academic_year_model', 'academicM');
		model('Config_college_sticker_model', 'stickerM');
	}

	public function index()
	{
		render('academic/list_academic',  [
			'title' => 'Academic',
			'currentSidebar' => 'Academic',
			'currentSubSidebar' => 'List Academic',
			'permission' => permission(['academic-view', 'academic-register'])
		]);
	}

	public function getAcademicByID($id)
	{
		if (isAjax() && $id != NULL) {
			json($this->academicM::find(xssClean($id), NULL, ['sticker']));
		} else {
			errorpage('404');
		}
	}

	public function getListDtAcademicYear()
	{
		if (isAjax()) {
			echo $this->academicM->getListAcademicDt();
		} else {
			errorpage('404');
		}
	}

	public function save()
	{
		if (isAjax()) {

			$this->_rules();

			if ($this->form_validation->run() == FALSE) {
				json(NULL, 'validate');
			} else {

				$typeForm = (empty(input('academic_id'))) ? 'create' : 'update';
				$current_order = (empty(input('academic_id'))) ? 0 : input('old_academic_order');

				$_POST['branch_id'] = currentUserBranchID();
				$_POST['academic_order'] = $this->academicM->sortAcademic(input('academic_order'), $current_order, $typeForm);
				$dataSaveAcademic = $this->academicM::save($_POST);

				// save config sticker college
				if (isSuccess($dataSaveAcademic['resCode'])) {
					$_POST['academic_id'] = $dataSaveAcademic['id'];
					$this->stickerM::save($_POST);
				}

				json($dataSaveAcademic);
			}
		} else {
			errorpage('404');
		}
	}

	public function delete($id)
	{
		if (isAjax() && $id != NULL) {
			json($this->academicM->deleteAcademic(xssClean($id)));
		} else {
			errorpage('404');
		}
	}

	public function getListAcademicOrderSelect()
	{
		if (isAjax()) {
			$academic_order = input('academic_order');
			$dataAcademic = $this->academicM::all(['academic_status' => 1, 'branch_id' => currentUserBranchID()], "academic_order ASC");

			echo '<option value=""> - Select - </option>';
			echo '<option value="0"> At beginning </option>';

			if ($dataAcademic) {
				foreach ($dataAcademic as $row) {
					if ($academic_order != $row['academic_order'])
						echo '<option value="' . $row['academic_order'] . '"> After ' . purify($row['academic_display_name']) . ' </option>';
				}
			}
		} else {
			errorpage('404');
		}
	}

	public function getPreviousAcademicByOrderNo()
	{
		if (isAjax() && input('previous_academic_order') != NULL) {
			json($this->academicM::where([
				'academic_order' => input('previous_academic_order'),
				'branch_id' => input('branch_id')
			], 'row_array'));
		} else {
			errorpage('404');
		}
	}

	public function getAcademicSelect()
	{
		if (isAjax()) {
			$dataAcademic = $this->academicM::all(['academic_status' => 1, 'branch_id' => currentUserBranchID()]);

			echo '<option value=""> - Select - </option>';

			if ($dataAcademic) {
				foreach ($dataAcademic as $row) {
					$selected = ($row['academic_id'] == currentAcademicID()) ? 'selected' : '';
					echo '<option value="' . $row['academic_id'] . '" ' . $selected . '> ' . purify($row['academic_display_name']) . ' </option>';
				}
			}
		} else {
			errorpage('404');
		}
	}

	public function getAcademicEventSelect()
	{
		if (isAjax()) {
			$dataAcademic = $this->academicM::all(['academic_status' => 1, 'branch_id' => currentUserBranchID()]);

			if (currentAcademicOrder() > 1)
				echo '<option value="0"> All Academic </option>';

			if ($dataAcademic) {
				foreach ($dataAcademic as $row) {
					if ($row['academic_order'] <= currentAcademicOrder()) {
						$selected = ($row['academic_id'] == currentAcademicID()) ? 'selected' : '';
						echo '<option value="' . $row['academic_id'] . '" ' . $selected . '> ' . purify($row['academic_display_name']) . ' </option>';
					}
				}
			}
		} else {
			errorpage('404');
		}
	}

	public function switchDefaultAcademic()
	{
		if (isAjax()) {
			update('config_academic_year', ['is_current' => 0], input('branch_id'), 'branch_id'); // set all to 0

			// set new main profile
			$_POST['is_current'] = 1;
			$result = $this->academicM::save($_POST);

			if (isSuccess($result['resCode'])) {
				// set academic
				setSession([
					'academicID' => encodeID(input('academic_id')),
					'academicName' => input('academic_name'),
					'academicOrder' => input('academic_order'),
				]);
			}

			json($result);
		} else {
			errorpage('404');
		}
	}

	public function _rules()
	{
		$this->form_validation->set_rules('academic_display_name', 'Academic Name', 'trim|required|min_length[2]|max_length[30]');
		$this->form_validation->set_rules('academic_year', 'Year', 'trim|required|integer|min_length[4]|max_length[4]');

		$this->form_validation->set_rules('academic_start_date', 'Start Date', 'trim|required');
		$this->form_validation->set_rules('academic_end_date', 'End Date', 'trim|required');
		$this->form_validation->set_rules('academic_status', 'Status', 'trim|required|integer');

		$this->form_validation->set_rules('academic_id', 'Academic ID', 'trim');

		// sticker college
		$this->form_validation->set_rules('sticker_college_amount', 'Sticker College', 'trim|required|min_length[1]|max_length[2]');
		$this->form_validation->set_rules('sticker_university_amount', 'Sticker University', 'trim|required|min_length[1]|max_length[2]');
		$this->form_validation->set_rules('sticker_hep_amount', 'Sticker HEP', 'trim|required|min_length[1]|max_length[2]');
		$this->form_validation->set_rules('sticker_id', 'Sticker ID', 'trim');

		// $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}
}
