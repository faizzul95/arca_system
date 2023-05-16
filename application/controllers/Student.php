<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Student extends Controller
{
	public function __construct()
	{
		parent::__construct();
		model('User_model', 'userM');
		model('User_profile_model', 'profileM');
		model('Config_college_model', 'collegeM');
		model('Config_college_room_model', 'roomM');
		model('Student_enrollment_model', 'studM');
		model('Student_sticker_collection_model', 'stickerM');
		model('Student_college_application_model', 'applicationM');
		model('Student_devices_list_model', 'deviceM');
		model('Academic_year_model', 'academicM');
		model('Master_education_level_model', 'eduM');
		model('Master_program_model', 'progM');
		model('Branch_model', 'branchM');
	}

	public function index()
	{
		render('enrollment/list_enrollment',  [
			'title' => 'Student',
			'currentSidebar' => 'Student',
			'currentSubSidebar' => 'List Enrollment',
			'permission' => permission(['student-view', 'student-register', 'student-batch-upload', 'student-export-enrollment'])
		]);
	}

	public function getListDtEnrollment()
	{
		if (isAjax())
			echo $this->studM->getListStudentEnrollDt(input('college_id'));
		else
			errorpage('404');
	}

	public function getStudByStudID($studID)
	{
		if (isAjax()) {
			$dataStud = $this->studM::find(xssClean($studID), null, ['user']);

			if ($dataStud) {
				if (!empty($dataStud['branch_id'])) {
					$dataStud = array_merge($dataStud, [
						'branch' => $this->branchM::find($dataStud['branch_id'])
					]);
				}

				if (!empty($dataStud['user']['program_id'])) {
					$dataStud = array_merge($dataStud, [
						'programme' => $this->progM::find($dataStud['user']['program_id'], NULL, ['faculty'])
					]);
				}

				if (!empty($dataStud['user']['edu_level_id'])) {
					$dataStud = array_merge($dataStud, [
						'education' => $this->eduM::find($dataStud['user']['edu_level_id'])
					]);
				}
			}

			json($dataStud);
		} else {
			errorpage('404');
		}
	}

	public function getStudByUserID($userID)
	{
		if (isAjax()) {
			$currentAcademicData = $this->academicM::find(currentAcademicID());
			$currentAcademicOrder = $currentAcademicData['academic_order'];

			$previousAcademicData = $currentAcademicOrder <= 1 ? NULL : $this->academicM::where([
				'academic_order' => $currentAcademicOrder - 1
			], 'row_array');

			json([
				'userData' => $this->userM::find(xssClean($userID), null, ['profileStudent']),
				'lastSemesterData' => $this->studM::where([
					'user_id' => xssClean($userID),
					'academic_id' => empty($previousAcademicData) ? NULL : $previousAcademicData['academic_id'],
				], 'row_array'),
			]);
		} else {
			errorpage('404');
		}
	}

	public function delete($id)
	{
		if (isAjax()) {

			$data = $this->studM::delete(xssClean($id));

			if (isSuccess($data['resCode'])) {
				$this->stickerM::delete(xssClean($id), 'stud_id');
				$this->applicationM::delete(xssClean($id), 'stud_id');
			}

			json($data);
		} else {
			errorpage('404');
		}
	}

	public function getListDtDirectory()
	{
		if (isAjax())
			echo $this->studM->getListDirectoryDt(input('college_id'));
		else
			errorpage('404');
	}

	public function getListDtCopy()
	{
		if (isAjax()) {
			json($this->studM->getListDataCopyFilter(input('college_id'), input('previous_academic_id')));
		} else {
			errorpage('404');
		}
	}

	public function save()
	{
		if (isAjax()) {

			if (currentUserRoleID() == 1) {
				$this->form_validation->set_rules('user_matric_code', 'Matric ID', 'trim|required|integer|min_length[5]|max_length[15]');
				$this->form_validation->set_rules('user_full_name', 'Student Name', 'trim|required|min_length[5]|max_length[250]');
				$this->form_validation->set_rules('user_nric', 'NRIC', 'trim|required|min_length[8]|max_length[15]');
				$this->form_validation->set_rules('user_contact_no', 'Contact No.', 'trim|required|integer|min_length[10]|max_length[15]');
				$this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email|min_length[5]|max_length[150]');
				$this->form_validation->set_rules('edu_level_id', 'Education Level', 'trim|required|integer|min_length[1]|max_length[250]');
				$this->form_validation->set_rules('program_id', 'Programme', 'trim|required|integer|min_length[1]|max_length[250]');
				$this->form_validation->set_rules('is_special', 'Disability', 'trim|required|integer');
				$this->form_validation->set_rules('has_position', 'Society Type', 'trim|integer');
			}

			$this->form_validation->set_rules('semester_number', 'Semester', 'trim|required|integer|min_length[1]|max_length[2]');
			$this->form_validation->set_rules('college_bed_no', 'Bed No.', 'trim|required|min_length[1]|max_length[10]');
			$this->form_validation->set_rules('college_id', 'College ID', 'trim|required|integer');
			$this->form_validation->set_rules('college_room_id', 'College Room ID', 'trim|required|integer');
			$this->form_validation->set_rules('stud_id', 'College ID', 'trim|integer');
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required|integer');
			$this->form_validation->set_rules('branch_id', 'Branch ID', 'trim|required|integer');

			if ($this->form_validation->run() == FALSE) {
				json(NULL, 'validate');
			} else {
				$findLevel = $this->roomM::find(input('college_room_id'));

				$_POST['college_level_id'] = $findLevel['college_level_id'];
				$_POST['branch_id'] = $_POST['branch_id'] ?? currentUserBranchID();
				$_POST['academic_id'] = currentAcademicID();

				$studentSave = $this->studM::save($_POST);

				if (isSuccess($studentSave['resCode'])) {

					$userUpdate = $this->userM::save($_POST);

					if (isSuccess($userUpdate['resCode'])) {
						$profileUpdate = $this->profileM::save([
							'profile_id' => input('profile_id'),
							'is_special' => input('is_special'),
							'has_position' => input('has_position'),
						]);
					}

					if (isInsertData($studentSave['action'])) {
						$stickerSave = $this->stickerM::save([
							'stud_id' => $studentSave['id'],
							'user_id' => input('user_id'),
							'academic_id' => currentAcademicID(),
							'branch_id' =>  currentUserBranchID(),
						]);

						$applicationSave = $this->applicationM::save([
							'stud_id' => $studentSave['id'],
							'user_id' => input('user_id'),
							'college_id' => input('college_id'),
							'academic_id' => currentAcademicID(),
							'branch_id' =>  currentUserBranchID(),
						]);
					}
				}

				json($studentSave);
			}
		} else {
			errorpage('404');
		}
	}

	public function saveCopy()
	{
		if (isAjax()) {

			$countSuccess = 0;
			$countError = 0;
			$tableListSuccess = [];
			$tableListError = [];

			$decodeData = json_decode($_POST['dataSave'], true);

			foreach ($decodeData as $key => $data) {

				unset($_POST); // reset post

				$_POST = [
					'semester_number' => $data['semester_number'],
					'college_bed_no' => $data['college_bed_no'],
					'college_id' => $data['college_id'],
					'college_room_id' => $data['college_room_id'],
					'user_id' => $data['user_id'],
					'branch_id' => $data['branch_id'],
				];

				$checked = $data['addStudent'];

				$findLevel = $this->roomM::find($data['college_room_id']);
				$data['college_level_id'] = $findLevel['college_level_id'];
				$data['branch_id'] = $data['branch_id'] ?? currentUserBranchID();
				$data['academic_id'] = currentAcademicID();

				// check if data is checked to saved
				if ($checked) {

					$this->form_validation->reset_validation(); // reset validation
					$this->form_validation->set_rules('semester_number', 'Semester', 'trim|required|integer|min_length[1]|max_length[2]');
					$this->form_validation->set_rules('college_bed_no', 'Bed No.', 'trim|required|min_length[1]|max_length[10]');
					$this->form_validation->set_rules('college_id', 'College ID', 'trim|required|integer');
					$this->form_validation->set_rules('college_room_id', 'College Room ID', 'trim|required|integer');
					$this->form_validation->set_rules('user_id', 'User ID', 'trim|required|integer');
					$this->form_validation->set_rules('branch_id', 'Branch ID', 'trim|required|integer');

					if ($this->form_validation->run() == FALSE) {
						$data['validation'] = validation_errors();
						$countError++;
						array_push($tableListError, $data);
					} else {
						$studUpdate = $this->studM::save($data); // save data 

						$user = $this->userM::find($data['user_id']);

						$data['user_full_name'] = $user['user_full_name'];
						$data['user_matric_code'] = $user['user_matric_code'];
						$data['user_nric'] = $user['user_nric'];
						$data['college_room_number'] = $findLevel['college_room_number'];

						if (isSuccess($studUpdate['resCode'])) {

							$stickerSave = $this->stickerM::save([
								'stud_id' => $studUpdate['id'],
								'user_id' => $data['user_id'],
								'academic_id' => currentAcademicID(),
								'branch_id' =>  currentUserBranchID(),
							]);

							$applicationSave = $this->applicationM::save([
								'stud_id' => $studUpdate['id'],
								'user_id' => $data['user_id'],
								'college_id' => input('college_id'),
								'academic_id' => currentAcademicID(),
								'branch_id' =>  currentUserBranchID(),
							]);

							$countSuccess++;
							array_push($tableListSuccess, $data);
						} else {
							$data['validation'] = $studUpdate['message'];
							$countError++;
							array_push($tableListError, $data);
						}
					}
				}
			}

			json([
				'resCode' => 200,
				'message' => 'Success',
				'countSuccess' => $countSuccess,
				'countError' => $countError,
				'totalData' => count($decodeData),
				'tableListError' => $tableListError,
				'tableListSuccess' => $tableListSuccess,
			]);
		} else {
			errorpage('404');
		}
	}

	public function assignCollege()
	{
		if (isAjax()) {
			$this->form_validation->set_rules('user_id', 'User ID', 'trim|required|integer');
			$this->form_validation->set_rules('college_id', 'College ID', 'trim|required|integer');

			if ($this->form_validation->run() == FALSE) {
				json(NULL, 'validate');
			} else {
				$_POST['academic_id'] = currentAcademicID();
				$_POST['branch_id'] = currentUserBranchID();
				json($this->studM::save($_POST));
			}
		} else {
			errorpage('404');
		}
	}

	public function qrLockDeviceID()
	{
		if (isAjax()) {

			// generate uuid
			$data = PHP_MAJOR_VERSION < 7 ? openssl_random_pseudo_bytes(16) : random_bytes(16);
			$data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // Set version to 0100
			$data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // Set bits 6-7 to 10

			$uuid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));

			$saveDevice = $this->deviceM::save([
				'user_id' => currentUserID(),
				'device_uuid' => $uuid,
				'device_user_agent' => input('useragent'),
				'branch_id' => currentUserBranchID(),
			]);

			json($saveDevice);
		} else {
			errorpage('404');
		}
	}

	public function qrCheckDeviceID()
	{
		if (isAjax()) {
			json($this->deviceM::countData([
				'user_id' => currentUserID(),
				'device_uuid' => input('deviceid'),
				'branch_id' => currentUserBranchID(),
			], 'row_array'));
		} else {
			errorpage('404');
		}
	}
}
