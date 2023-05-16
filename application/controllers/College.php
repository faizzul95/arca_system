<?php

defined('BASEPATH') or exit('No direct script access allowed');

class College extends Controller
{
	public function __construct()
	{
		parent::__construct();
		model('Config_college_model', 'collegeM');
		model('Config_college_room_model', 'roomM');
	}

	public function index()
	{
		render('college/list_college',  [
			'title' => 'College configuration',
			'currentSidebar' => 'College',
			'currentSubSidebar' => 'List College',
			'permission' => permission(['college-view', 'college-register'])
		]);
	}

	public function application()
	{
		// top management (superadmin, admin college)
		if (in_array(currentUserRoleID(), ['1', '2', '3'])) {
			$file = 'list_application_admin';
		}

		// staff residen kolej
		else if (currentUserRoleID() == 4) {
			$file = 'list_application_srk';
		}

		render('application/' . $file,  [
			'title' => 'College Application',
			'currentSidebar' => 'College',
			'currentSubSidebar' => 'List College Application',
			'permission' => permission(['college-application-list', 'college-application-bulk-approval'])
		]);
	}

	public function configuration()
	{
		render('college/list_college',  [
			'title' => 'College configuration',
			'currentSidebar' => 'College',
			'currentSubSidebar' => 'List College',
			'permission' => permission(['college-view', 'college-register'])
		]);
	}

	// College

	public function getListDtCollege()
	{
		if (isAjax()) {
			echo $this->collegeM->getListCollegeDt();
		} else {
			errorpage('404');
		}
	}

	public function getCollegeByID($id)
	{
		if (isAjax() && $id != NULL) {
			json($this->collegeM::find(xssClean($id)));
		} else {
			errorpage('404');
		}
	}

	public function deleteCollege($id)
	{
		if (isAjax() && $id != NULL) {
			json($this->collegeM::delete(xssClean($id)));
		} else {
			errorpage('404');
		}
	}

	public function saveCollege()
	{
		if (isAjax()) {
			$this->form_validation->set_rules('college_name', 'College Name', 'trim|required|min_length[3]|max_length[100]');
			$this->form_validation->set_rules('college_code', 'Code', 'trim|required|min_length[2]|max_length[10]');
			$this->form_validation->set_rules('college_capacity', 'Capacity', 'trim|required|integer|max_length[3]');
			$this->form_validation->set_rules('college_gender_prefer', 'Gender Level', 'trim|required|integer');
			$this->form_validation->set_rules('college_level_prefer', 'Level Prefer', 'trim|required|integer');
			$this->form_validation->set_rules('college_status', 'Status', 'trim|required|integer');
			$this->form_validation->set_rules('college_id', 'College ID', 'trim');

			if ($this->form_validation->run() == FALSE) {
				json(NULL, 'validate');
			} else {
				$_POST['branch_id'] = currentUserBranchID();
				json($this->collegeM::save($_POST));
			}
		} else {
			errorpage('404');
		}
	}

	public function getCollegeSelect($branchID = NULL, $filter = false)
	{
		if (isAjax()) {
			$branch_id = !empty($branchID) && $branchID != 'null' ? xssClean($branchID) : currentUserBranchID();
			$dataCollege = $this->collegeM::all(['college_status' => 1, 'branch_id' => $branch_id]);

			echo $filter ? '<option value=""> All College </option>' : '<option value=""> - Select - </option>';

			if ($dataCollege) {
				foreach ($dataCollege as $row) {
					echo '<option value="' . $row['college_id'] . '"> ' . purify($row['college_name']) . ' </option>';
				}
			}
		} else {
			errorpage('404');
		}
	}

	public function getListCollegeDiv()
	{
		if (isAjax()) {
			$dataCollege = $this->collegeM::all(['branch_id' => currentUserBranchID()]);

			echo '<input type="text" id="searchCollege" class="form-control mb-2" placeholder="Search..." maxlength="10" onkeyup="searchCollege(this.value)">';
			echo '<div data-simplebar style="max-height: 360px;"> 
                    <ul class="list-group">';

			if (!empty($dataCollege)) {
				foreach ($dataCollege as $row) {

					$college_id = $row['college_id'];
					$college_name = purify($row['college_name']);
					$college_status = $row['college_status'];
					$badgeStatus = ($college_status == 0) ? 'bg-danger' : 'bg-success';

					echo '<li id="card-' . $college_id . '" class="list-group-item cardColor cardCollege"  onclick="setViewCollege(' . $college_id . ', ' . escape($college_name) . ')">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="d-flex">
                                        <div class="flex-shrink-0 avatar-xs">
                                            <div class="avatar-title ' . $badgeStatus . ' text-white rounded">
                                            ' . $row['college_code'] . '
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ms-2">
                                            <h5 id="text-' . $college_id . '" data-card="card-' . $college_id . '" class="textColor mt-2">' . purify($row['college_name']) . '</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0">
                                </div>
                            </div>
                        </li>';
				}
			}

			echo '</ul>
                </div>';
		} else {
			errorpage('404');
		}
	}

	// College Room

	public function getListDtCollegeRoom()
	{
		if (isAjax()) {
			echo $this->roomM->getListCollegeRoomDt(input('college_id'));
		} else {
			errorpage('404');
		}
	}

	public function getCollegeRoomByID($id)
	{
		if (isAjax()) {
			json($this->roomM::find(xssClean($id)));
		} else {
			errorpage('404');
		}
	}

	public function deleteCollegeRoom($id)
	{
		if (isAjax()) {
			json($this->roomM::delete(xssClean($id)));
		} else {
			errorpage('404');
		}
	}

	public function getCollegeRoomSelectByCollegeID($collegeID)
	{
		if (isAjax()) {
			$dataCollegeRoom = $this->roomM::all(['college_id' => xssClean($collegeID), 'college_room_status' => 1, 'branch_id' => currentUserBranchID()]);

			echo '<option value=""> - Select - </option>';

			if ($dataCollegeRoom) {
				foreach ($dataCollegeRoom as $row) {
					echo '<option value="' . $row['college_room_id'] . '"> ' . purify($row['college_room_number']) . ' </option>';
				}
			}
		} else {
			errorpage('404');
		}
	}

	public function saveCollegeRoom()
	{
		if (isAjax()) {
			$this->form_validation->set_rules('college_room_number', 'Room No.', 'trim|required|min_length[1]|max_length[10]');
			$this->form_validation->set_rules('college_room_allocation', 'Allocation', 'trim|required|integer|min_length[1]|max_length[2]');
			$this->form_validation->set_rules('college_room_status', 'Status', 'trim|required|integer');
			$this->form_validation->set_rules('college_id', 'College ID', 'trim|required|integer');
			$this->form_validation->set_rules('college_level_id', 'Level', 'trim|required|integer');
			$this->form_validation->set_rules('college_room_id', 'College Room ID', 'trim');

			if ($this->form_validation->run() == FALSE) {
				json(NULL, 'validate');
			} else {
				$_POST['branch_id'] = currentUserBranchID();
				json($this->roomM::save($_POST));
			}
		} else {
			errorpage('404');
		}
	}
}
