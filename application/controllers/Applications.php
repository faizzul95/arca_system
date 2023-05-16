<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Applications extends Controller
{
	public function __construct()
	{
		parent::__construct();
		model('Student_enrollment_model', 'studM');
		model('Student_college_application_model', 'applicationM');
		model('Student_sticker_collection_model', 'stickerM');
	}

	public function index()
	{
		errorpage('404');
	}

	public function getApplicationListDt()
	{
		if (isAjax()) {
			echo $this->applicationM->getApplicationListDtByStatus(input('is_apply'), input('academic_id'), input('college_id'), input('approval_status'));
		} else {
			errorpage('404');
		}
	}

	public function getApplicationListSrkBulkDt()
	{
		if (isAjax()) {
			$data = $this->applicationM->getApplicationListBulkDt(input('academic_id'), input('college_id'), input('scrutinize_check_status'), input('is_college_eligible'));
			json(xssClean($data));
		} else {
			errorpage('404');
		}
	}

	public function getInfoCard()
	{
		if (isAjax()) {

			$academicID = input('academic_id') == 0 ? ['branch_id' => currentUserBranchID()] : ['branch_id' => currentUserBranchID(), 'academic_id' => input('academic_id')];
			$collegeID = empty(input('college_id')) ? [] : ['college_id' => input('college_id')];
			$condition = array_merge($academicID, $collegeID);

			json([
				'totalStudent' => $this->studM::countData($condition),
				'totalApply' => $this->applicationM::countData(array_merge($condition, ['is_apply' => 1])),
				'totalNotApply' => $this->applicationM::countData(array_merge($condition, ['is_apply' => 0])),
				'totalScrutinizePending' => $this->applicationM::countData(array_merge($condition, ['approval_status' => 0])),
				'totalScrutinizeSuccess' => $this->applicationM::countData(array_merge($condition, ['approval_status' => 1])),
				'totalScrutinizeFailed' => $this->applicationM::countData(array_merge($condition, ['approval_status' => 2])),
			]);
		} else {
			errorpage('404');
		}
	}

	public function getApplicationByAppID($id)
	{
		if (isAjax()) {
			$dataApp = $this->applicationM::find(xssClean($id), null, ['sticker']);
			json($dataApp);
		} else {
			errorpage('404');
		}
	}

	// checked application for SRK
	public function updateCheckStatusApplication()
	{
		json($this->applicationM::save([
			'application_id' => input('application_id'),
			'scrutinize_check_status' => input('scrutinize_check_status'),
		]));
	}

	// list scrutinize for SRK
	public function getApplicationListSrkDt()
	{
		if (isAjax()) {
			echo $this->applicationM->getApplicationListDtByStatusSRK(input('is_apply'), input('academic_id'), input('college_id'), input('approval_status'), input('is_college_eligible'));
		} else {
			errorpage('404');
		}
	}

	public function applicationApproval()
	{
		if (isAjax()) {

			$this->form_validation->set_rules('approval_status', 'Status', 'trim|required|integer|min_length[1]|max_length[2]');
			$this->form_validation->set_rules('approval_remark', 'Remark.', 'trim|min_length[3]|max_length[200]');
			$this->form_validation->set_rules('application_id', 'Application ID', 'trim|required|integer');
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required|integer');
			$this->form_validation->set_rules('stud_id', 'Student ID', 'trim|required|integer');

			if ($this->form_validation->run() == FALSE) {
				json(NULL, 'validate');
			} else {
				$_POST['approval_user_id'] = input('user_id');
				$_POST['approval_date'] = timestamp();
				$_POST['approval_status'] = (input('approval_status') == "2") ? input('approval_status') : 1;
				$_POST['scrutinize_check_status'] = 0;
				json($this->applicationM::save($_POST));
			}
		} else {
			errorpage('404');
		}
	}

	public function bulkApprove()
	{
		if (isAjax()) {

			if (count($_POST['bulkChecked']) > 0) {
				$approveData = [];
				foreach ($_POST['bulkChecked'] as $appID) {
					array_push($approveData, [
						'application_id' => $appID,
						'approval_user_id' => currentUserID(),
						'approval_date' => timestamp(),
						'approval_status' => 1,
						'approval_remark' => NULL,
						'scrutinize_check_status' => 0,
					]);
				}
				json($this->applicationM::updateBatch($approveData, 'application_id'));
			} else {
				json(['resCode' => 400, 'message' => 'No data to update']);
			}
		} else {
			errorpage('404');
		}
	}

	public function bulkReject()
	{
		if (isAjax()) {
			if (count($_POST['bulkChecked']) > 0) {
				$rejectData = [];
				foreach ($_POST['bulkChecked'] as $key => $appID) {
					array_push($rejectData, [
						'application_id' => $appID,
						'approval_user_id' => currentUserID(),
						'approval_date' => timestamp(),
						'approval_status' => 2,
						'approval_remark' => NULL,
						'scrutinize_check_status' => 0,
						'approval_remark' => $_POST['reason'][$key],
					]);
				}
				json($this->applicationM::updateBatch($rejectData, 'application_id'));
			} else {
				json(['resCode' => 400, 'message' => 'No data to update']);
			}
		} else {
			errorpage('404');
		}
	}

	public function getListApplicationUnoffered()
	{
		if (isAjax()) {
			$data =  $this->applicationM->getUnofferedListByAcademicID(input('academic_id'), input('college_id'), input('approval_status'));
			json(xssClean($data));
		} else {
			errorpage('404');
		}
	}
}
