<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Management extends Controller
{
	public function __construct()
	{
		parent::__construct();
		model('Master_college_level_model', 'levelM');
		model('Master_education_level_model', 'eduM');
		model('Master_program_model', 'progM');
		model('Master_faculty_model', 'facultyM');
	}

	public function index()
	{
		render('management/list_management',  [
			'title' => 'Management',
			'currentSidebar' => 'Management',
			'currentSubSidebar' => 'List Management'
		]);
	}

	// FACULTY

	public function listFacultyManagement()
	{
		if (isAjax())
			view('management/list_faculty');
		else
			errorpage('404');
	}

	public function getListDtFaculty()
	{
		echo $this->facultyM->getListFacultyDt();
	}

	public function getFacultyByID($id)
	{
		json($this->facultyM::find(xssClean($id)));
	}

	public function deleteFaculty($id)
	{
		json($this->facultyM::delete(xssClean($id)));
	}

	public function saveFaculty()
	{
		$this->form_validation->set_rules('faculty_name', 'Faculty Name', 'trim|required|min_length[5]|max_length[255]');
		$this->form_validation->set_rules('faculty_code', 'Code', 'trim|required|min_length[2]|max_length[10]');
		$this->form_validation->set_rules('faculty_status', 'Status', 'trim|required|integer');
		$this->form_validation->set_rules('faculty_id', 'Faculty ID', 'trim');

		if ($this->form_validation->run() == FALSE) {
			json(NULL, 'validate');
		} else {
			$_POST['branch_id'] = currentUserBranchID();
			json($this->facultyM::save($_POST));
		}
	}

	public function getFacultySelect()
	{
		if (isAjax()) {
			$dataFac = $this->facultyM::all(['faculty_status' => 1]);

			echo '<option value=""> - Select - </option>';

			if ($dataFac) {
				foreach ($dataFac as $row) {
					echo '<option value="' . $row['faculty_id'] . '"> ' . purify($row['faculty_code']) . ' - ' . purify($row['faculty_name']) . ' </option>';
				}
			}
		} else {
			errorpage('404');
		}
	}

	// PROGRAM

	public function listProgramManagement()
	{
		if (isAjax())
			view('management/list_program');
		else
			errorpage('404');
	}

	public function getListDtProgram()
	{
		echo $this->progM->getListProgramDt();
	}

	public function getProgramByID($id)
	{
		json($this->progM::find(xssClean($id)));
	}

	public function deleteProgram($id)
	{
		json($this->progM::delete(xssClean($id)));
	}

	public function saveProgram()
	{
		$this->form_validation->set_rules('program_name', 'Program Name', 'trim|required|min_length[10]|max_length[250]');
		$this->form_validation->set_rules('program_code', 'Code', 'trim|required|min_length[2]|max_length[10]');
		$this->form_validation->set_rules('faculty_id', 'Faculty ID', 'trim|required|integer');
		$this->form_validation->set_rules('edu_level_id', 'Education ID', 'trim|required|integer');
		$this->form_validation->set_rules('program_status', 'Status', 'trim|required|integer');
		$this->form_validation->set_rules('program_id', 'Program ID', 'trim');

		if ($this->form_validation->run() == FALSE) {
			json(NULL, 'validate');
		} else {
			$_POST['branch_id'] = currentUserBranchID();
			json($this->progM::save($_POST));
		}
	}

	public function getProgramSelect($eduLevelID = NULL, $branchID = NULL)
	{
		$branch_id = !empty($branchID) ? xssClean($branchID) : currentUserBranchID();
		$dataEdu = $this->progM::all(['edu_level_id' => xssClean($eduLevelID), 'program_status' => 1, 'branch_id' =>  $branch_id]);

		echo '<option value=""> - Select - </option>';

		if ($dataEdu) {
			foreach ($dataEdu as $row) {
				echo '<option value="' . $row['program_id'] . '"> ' . purify($row['program_code']) . ' - ' . purify($row['program_name']) . ' </option>';
			}
		}
	}

	// COLLEGE LEVEL

	public function listLevelCollegeManagement()
	{
		if (isAjax())
			view('management/list_college_level');
		else
			errorpage('404');
	}

	public function getListDtCollegeLevel()
	{
		echo $this->levelM->getListCollegeLevelDt();
	}

	public function getCollegeLevelByID($id)
	{
		json($this->levelM::find(xssClean($id)));
	}

	public function deleteCollegeLevel($id)
	{
		json($this->levelM::delete(xssClean($id)));
	}

	public function saveCollegeLevel()
	{
		$this->form_validation->set_rules('college_level_name', 'Level Name', 'trim|required|min_length[2]|max_length[20]');
		$this->form_validation->set_rules('college_level_code', 'Code', 'trim|required|min_length[2]|max_length[10]');
		$this->form_validation->set_rules('college_level_status', 'Status', 'trim|required|integer|min_length[1]');
		$this->form_validation->set_rules('college_level_id', 'College Level ID', 'trim');

		if ($this->form_validation->run() == FALSE) {
			json(NULL, 'validate');
		} else {
			json($this->levelM::save($_POST));
		}
	}

	public function getCollegeLevelSelect()
	{
		$dataLevel = $this->levelM::all(['college_level_status' => 1]);

		echo '<option value=""> - Select - </option>';

		if ($dataLevel) {
			foreach ($dataLevel as $row) {
				echo '<option value="' . $row['college_level_id'] . '"> ' . purify($row['college_level_name']) . ' </option>';
			}
		}
	}

	// EDUCATION LEVEL

	public function listEducationManagement()
	{
		if (isAjax())
			view('management/list_education');
		else
			errorpage('404');
	}

	public function getListDtEducation()
	{
		if (isAjax())
			echo $this->eduM->getListEducationDt();
		else
			errorpage('404');
	}

	public function getEducationByID($id)
	{
		if (isAjax())
			json($this->eduM::find(xssClean($id)));
		else
			errorpage('404');
	}

	public function deleteEducation($id)
	{
		if (isAjax())
			json($this->eduM::delete(xssClean($id)));
		else
			errorpage('404');
	}

	public function saveEducation()
	{
		if (isAjax()) {
			$this->form_validation->set_rules('edu_level_name', 'Name', 'trim|required|min_length[2]|max_length[250]');
			$this->form_validation->set_rules('edu_level_code', 'Code', 'trim|required|min_length[2]|max_length[10]');
			$this->form_validation->set_rules('edu_level_status', 'Status', 'trim|required|integer');
			$this->form_validation->set_rules('edu_level_id', 'Education Level ID', 'trim');

			if ($this->form_validation->run() == FALSE) {
				json(NULL, 'validate');
			} else {
				json($this->eduM::save($_POST));
			}
		} else {
			errorpage('404');
		}
	}

	public function getEducationSelect()
	{
		if (isAjax()) {
			$dataEdu = $this->eduM::all(['edu_level_status' => 1]);

			echo '<option value=""> - Select - </option>';

			if ($dataEdu) {
				foreach ($dataEdu as $row) {
					echo '<option value="' . $row['edu_level_id'] . '"> ' . purify($row['edu_level_name']) . ' </option>';
				}
			}
		} else {
			errorpage('404');
		}
	}
}
