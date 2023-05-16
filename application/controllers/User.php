<?php

defined('BASEPATH') or exit('No direct script access allowed');

class User extends Controller
{
	public function __construct()
	{
		parent::__construct();

		isLogin();
		model('User_model', 'userM');
		model('User_profile_model', 'profileM');
		model('Master_education_level_model', 'eduM');
		model('Master_program_model', 'progM');
		model('Files_model', 'filesM');
		model('Branch_model', 'branchM');
		model('Student_enrollment_model', 'studM');
		model('Student_sticker_collection_model', 'stickerM');
		model('Student_college_application_model', 'applicationM');
	}

	public function index()
	{
		render('user/list',  [
			'title' => 'Directory',
			'currentSidebar' => 'Directory',
			'currentSubSidebar' => 'List User',
			'permission' => permission(['user-view', 'user-register', 'student-batch-upload'])
		]);
	}

	public function getUserList()
	{
		if (isAjax())
			echo $this->userM->getListUserDt(input('role_id'), input('branch_id'));
		else
			errorpage('404');
	}

	public function getUserByID($id = NULL)
	{
		if (isAjax()) {
			$userID = !empty($id) ? xssClean($id) : currentUserID();
			$dataUser = $this->userM::find($userID, NULL, ['currentProfile', 'currentProfile.roles', 'currentProfile.qrCode']);

			if ($dataUser) {
				if (!empty($dataUser['branch_id'])) {
					$dataUser = array_merge($dataUser, [
						'branch' => $this->branchM::find($dataUser['branch_id'])
					]);
				}

				if (!empty($dataUser['program_id'])) {
					$dataUser = array_merge($dataUser, [
						'programme' => $this->progM::find($dataUser['program_id'], NULL, ['faculty'])
					]);
				}

				if (!empty($dataUser['edu_level_id'])) {
					$dataUser = array_merge($dataUser, [
						'education' => $this->eduM::find($dataUser['edu_level_id']),
						'enrollment' => $this->studM::where(
							['user_id' => $userID, 'academic_id' => currentAcademicID(), 'branch_id' => currentUserBranchID()],
							'row_array',
							NULL,
							['room']
						),
						'sticker' => $this->stickerM::where(['user_id' => $userID, 'academic_id' => currentAcademicID(), 'branch_id' => currentUserBranchID()], 'row_array'),
						'application' => $this->applicationM::where(['user_id' => $userID, 'academic_id' => currentAcademicID(), 'branch_id' => currentUserBranchID()], 'row_array')
					]);
				}
			}

			json($dataUser);
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

				if (empty($_POST['user_id'])) {
					$_POST['two_factor_type'] = '1';
					$_POST['two_factor_secret'] = generateSecretGA();
					$_POST['user_password'] = password_hash(input('user_nric'), PASSWORD_DEFAULT);
				}

				$saveUser = $this->userM::save($_POST);

				if (isSuccess($saveUser['resCode'])) {
					if (isInsertData($saveUser))
						$this->generateQR($this->userM::find($saveUser['id']));
				}

				json($saveUser);
			}
		} else {
			errorpage('404');
		}
	}

	public function saveStudentBulk()
	{
		if (isAjax()) {

			$countSuccess = 0;
			$countError = 0;
			$tableListSuccess = [];
			$tableListError = [];

			$decodeData = json_decode($_POST['dataSave'], true);

			foreach ($decodeData as $data) {

				unset($_POST); // reset post

				$checked = $data['addStudent'];

				$userID = !empty($data['user_id']) && $data['user_id'] != 'null' ? $data['user_id'] : NULL;
				$profileID = !empty($data['profile_id']) && $data['profile_id'] != 'null' ? $data['profile_id'] : NULL;

				$userData = [
					"user_id" => $userID,
					"user_full_name" => $data['user_full_name'],
					"user_nric" => $data['user_nric'],
					"user_email" => $data['user_email'],
					"user_contact_no" => $data['user_contact_no'],
					"user_gender" => $data['user_gender'],
					"user_matric_code" => $data['user_matric_code'],
					"program_id" => !empty($data['program_id'])  && $data['program_id'] != 'null' ? $data['program_id'] : NULL,
					"edu_level_id" => !empty($data['edu_level_id']) && $data['edu_level_id'] != 'null' ? $data['edu_level_id'] : NULL,
					"user_intake" => !empty($data['user_intake']) && $data['user_intake'] != 'null' ? $data['user_intake'] : NULL,
					// "user_password" => !empty($data['user_password']) ? $data['user_password'] : NULL,
					"user_password" => password_hash('1234qwer', PASSWORD_DEFAULT),
					"user_status" => !empty($data['user_status']) && $data['user_status'] != 'null' ? $data['user_status'] : 1,
					"branch_id" => !empty($data['branch_id']) && $data['branch_id'] != 'null' ? $data['branch_id'] : currentUserBranchID(),
				];

				$profileData = [
					"profile_id" => $profileID,
					"user_id" => $userID,
					"role_id" => !empty($data['role_id']) && $data['role_id'] != 'null' ? $data['role_id'] : 6,
					"is_main" => !empty($data['is_main']) && $data['is_main'] != 'null' ? $data['is_main'] : 1,
					"is_special" => !empty($data['is_special']) && $data['is_special'] != 'null' ? $data['is_special'] : 0,
					"has_position" => !empty($data['has_position']) && $data['has_position'] != 'null' ? $data['has_position'] : NULL,
					"branch_id" => !empty($data['branch_id']) && $data['branch_id'] != 'null' ? $data['branch_id'] : currentUserBranchID(),
				];

				// check if data is checked to saved
				if ($checked) {

					$_POST = array_merge($userData, $profileData);

					$this->form_validation->reset_validation(); // reset validation
					$this->form_validation->set_rules('user_id', 'User ID', 'trim|integer');
					$this->form_validation->set_rules('profile_id', 'Profile ID', 'trim|integer');
					$this->form_validation->set_rules('user_full_name', 'Name', 'trim|required|min_length[5]|max_length[250]');
					$this->form_validation->set_rules('user_nric', 'NRIC', 'trim|required|min_length[5]|max_length[15]');
					$this->form_validation->set_rules('user_email', 'Email', 'trim|required|min_length[5]|max_length[150]');
					$this->form_validation->set_rules('user_contact_no', 'Mobile No', 'trim|required|min_length[8]|max_length[15]');
					$this->form_validation->set_rules('user_matric_code', 'Matric Code', 'trim|required|min_length[4]|max_length[15]');
					$this->form_validation->set_rules('user_gender', 'Gender', 'trim|required|integer');
					$this->form_validation->set_rules('program_id', 'Program ID', 'trim|required|integer');
					$this->form_validation->set_rules('edu_level_id', 'Level ID', 'trim|required|integer');
					$this->form_validation->set_rules('user_intake', 'Intake', 'trim|required|min_length[1]|max_length[50]');
					$this->form_validation->set_rules('role_id', 'Role ID', 'trim|required|integer');
					$this->form_validation->set_rules('is_main', 'Is Main', 'trim|required|integer');
					$this->form_validation->set_rules('user_password', 'Password', 'trim|required|min_length[5]|max_length[255]');
					$this->form_validation->set_rules('branch_id', 'Branch ID', 'trim|required|integer');
					$this->form_validation->set_error_delimiters('<div class="error">', '</div>');

					if ($this->form_validation->run() == FALSE) {
						$userData['validation'] = validation_errors();
						$countError++;
						array_push($tableListError, $userData);
					} else {

						$saveUser = $this->userM::save($userData); // save data user
						if (isSuccess($saveUser['resCode'])) {
							$profileData['user_id'] = $saveUser['id'];
							$saveProfile = $this->profileM::save($profileData); // save data profile
							if (isSuccess($saveProfile['resCode'])) {
								$countSuccess++;

								// if new data then generate QR
								if (isInsertData($saveUser))
									$this->generateQR($userData); // generate QR

								$badgeColor = $saveUser['action'] == 'update' ? 'info' : 'success';
								$userData['action'] = '<span class="badge bg-' .  $badgeColor . '">' . $saveUser['action'] . '</span>';
								array_push($tableListSuccess, $userData);
							} else {
								$removeUser = $this->userM::delete($saveUser['id']); // remove user if profile not create

								$userData['validation'] = $saveProfile['message'];
								$countError++;
								array_push($tableListError, $userData);
							}
						} else {
							$userData['validation'] = $saveUser['message'];
							$countError++;
							array_push($tableListError, $userData);
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

	public function archive($id = NULL)
	{
		if (hasData($id)) {
			json($this->userM::save(['user_id' => xssClean($id), 'is_deleted' => 1]));
		} else {
			errorpage('404');
		}
	}

	public function delete($id = NULL)
	{
		if (hasData($id)) {
			$userID = xssClean($id);
			$dataDelete = $this->userM::delete($userID);

			if (isSuccess($dataDelete['resCode'])) {
				$profileDelete = $this->profileM::delete($userID, 'user_id');

				// // get previous data
				$dataPrev = $this->filesM::where(
					[
						'entity_type' => 'User_model',
						'entity_file_type' => 'QR_CODE',
						'entity_id' => $userID,
					],
					'row_array'
				);

				// if have prev data then remove
				if (!empty($dataPrev)) {
					$fileID = $dataPrev['files_id'];
					$filePath = $dataPrev['files_path'];
					$remove = $this->filesM::delete($fileID);
					if (isSuccess($remove['resCode'])) {
						if (file_exists($filePath)) {
							unlink($filePath);
						}
					}
				}
			}

			json($dataDelete);
		} else {
			errorpage('404');
		}
	}

	public function checkMatricCodeExist()
	{
		if (isAjax())
			json($this->userM::find(input('user_matric_code'), 'user_matric_code'));
		else
			errorpage('404');
	}

	public function uploadProfile()
	{
		$filename = input('filename');
		$user_id = input('user_id');
		$entity_type = input('entity_type');
		$entity_file_type = input('entity_file_type');

		$dataUser = $this->userM::find($user_id);
		$dataMainProfile = $this->profileM::where(
			[
				'user_id' => $user_id,
				'is_main' => 1,
				'profile_status' => 1,
			],
			'row_array'
		);

		// get previous data
		$dataPrev = $this->filesM::where(
			[
				'entity_type' => $entity_type,
				'entity_file_type' => $entity_file_type,
				'entity_id' => $user_id,
			],
			'row_array'
		);

		// // if have prev data then remove
		if (!empty($dataPrev)) {
			$fileID = $dataPrev['files_id'];
			$filePath = $dataPrev['files_path'];
			$remove = $this->filesM::delete($fileID);
			if ($remove['resCode'] == 200) {
				if (file_exists($filePath)) {
					unlink($filePath);
				}
			}
		}

		$roleID = $dataMainProfile['role_id'];
		$folderDirectory = ($roleID == 6) ? 'directory/student' : 'directory/staff';

		if (!in_array($roleID, [1, 2, 3, 4, 6])) {
			$folderDirectory = 'directory/others';
		}

		$folderName = $dataUser['user_matric_code'];

		// // generate folder
		$folder = folder($folderDirectory, $folderName, 'avatar');

		$image = $_POST['image'];
		list($type, $image) = explode(';', $image);
		list(, $image) = explode(',', $image);

		$imageUpload = base64_decode($image);

		$fileNameNew = $user_id . "_" . date('dFY') . "_" . date('his') . '.jpg';
		$path = $folder . '/' . $fileNameNew;

		// $fileSave = NULL;
		if (file_put_contents($path, $imageUpload)) {
			// move image from default
			$moveImg = moveFile(
				$fileNameNew,
				$path,
				$folder,
				[
					'type' => $entity_type,
					'file_type' => $entity_file_type,
					'entity_id' => $user_id,
					'user_id' => $user_id,
				],
				'rename'
			);

			if (!empty($moveImg)) {

				$fileSave = $this->filesM::save($moveImg);

				if (isSuccess($fileSave['resCode'])) {
					// update user info
					$this->userM::save([
						'user_id' => $user_id,
						'user_avatar' => $moveImg['files_path'],
					]);

					$currentUserID = currentUserID();
					if (currentUserID() == $user_id) {
						setSession([
							'userAvatar'  => $moveImg['files_path'],
						]);
					}
				}
			}
		}

		json($fileSave);
	}

	public function resetUsername()
	{
		if (isAjax()) {

			$this->form_validation->set_rules(
				'username',
				'Username',
				'required|min_length[5]|max_length[15]|is_unique[user.user_username]',
				array(
					'required'      => 'You have not provided %s.',
					'is_unique'     => 'This %s already exists.'
				)
			);

			if ($this->form_validation->run() == FALSE) {
				json(NULL, 'validate');
			} else {
				$responseData = $this->userM::save([
					'user_id' => currentUserID(),
					'user_username' => input('username')
				]);

				json($responseData);
			}
		} else {
			errorpage('404');
		}
	}

	public function get2FAInfo()
	{
		if (isAjax()) {
			$dataUser = $this->userM::find(currentUserID());
			$secret = $dataUser['two_factor_secret'];

			// check if secret is empty then generate new
			if (empty($secret)) {
				$secret = generateSecretGA();
				$this->userM::save([
					'user_id' => currentUserID(),
					'two_factor_secret' => $secret
				]);
			}

			json([
				'resCode' => 200,
				'message' => '',
				'imageQR' => generateImageGA($secret),
			]);
		} else {
			errorpage('404');
		}
	}

	public function changeStatus2FA()
	{
		if (isAjax()) {
			$responseData = $this->userM::save([
				'user_id' => currentUserID(),
				'two_factor_status' => input('status'),
				'two_factor_secret' => generateSecretGA()
			]);

			json($responseData);
		} else {
			errorpage('404');
		}
	}

	public function resetPassword()
	{
		if (isAjax()) {

			$this->form_validation->set_rules('oldpassword', 'Current password', 'trim|required|min_length[4]|max_length[20]');
			$this->form_validation->set_rules('newpassword', 'New password', 'trim|required|min_length[8]|max_length[20]');
			$this->form_validation->set_rules('confirmpassword', 'Confirm password', 'trim|required|min_length[8]|max_length[20]');

			if ($this->form_validation->run() == FALSE) {
				json(NULL, 'validate');
			} else {
				$oldPassword = input('oldpassword');
				$newpassword = input('newpassword');
				$confirmpassword = input('confirmpassword');

				$dataUser = $this->userM::find(currentUserID());
				$userPassword = $dataUser['user_password'];

				if (password_verify($oldPassword, $userPassword)) {
					if ($newpassword == $confirmpassword) {
						$responseData = $this->userM::save([
							'user_id' => currentUserID(),
							'user_password' => password_hash($newpassword, PASSWORD_DEFAULT)
						]);
					} else {
						$responseData = [
							'resCode' => 400,
							'message' => 'Confirm password not match.',
						];
					}
				} else {
					$responseData = [
						'resCode' => 400,
						'message' => 'Current password not match.',
					];
				}

				json($responseData);
			}
		} else {
			errorpage('404');
		}
	}

	public function generateQR($data = NULL)
	{
		// generate folder for qr
		$folderQr = folder('directory/QR', $data['user_matric_code'], 'qr_code');

		//generate QR Code
		$qrCode = generateQR(
			$data['user_matric_code'],
			$folderQr,
			['image' => 'public/common/images/favicon.png', 'size' => 130]
		);

		// move qr code to specific folder
		$moveQr = moveFile(
			$qrCode['qrFilename'],
			$qrCode['qrPath'],
			$folderQr,
			[
				'type' => 'User_model',
				'file_type' => 'QR_CODE',
				'entity_id' => $data['user_id'],
				'user_id' => $data['user_id'],
			],
			'rename'
		);

		if (!empty($moveQr)) {
			$this->filesM::save($moveQr);
		}
	}

	public function _rules()
	{
		$this->form_validation->set_rules('user_full_name', 'Name', 'trim|required|min_length[5]|max_length[200]');
		$this->form_validation->set_rules('user_nric', 'NRIC', 'trim|required|min_length[3]|max_length[15]');
		$this->form_validation->set_rules('user_email', 'Email', 'trim|required|valid_email|min_length[5]|max_length[150]');
		$this->form_validation->set_rules('user_contact_no', 'Mobile No', 'trim|required|min_length[5]|max_length[15]');
		$this->form_validation->set_rules('user_matric_code', 'Matric Code', 'trim|required|min_length[4]|max_length[15]');

		$this->form_validation->set_rules('user_id', 'user ID', 'trim|integer');
		// $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
	}
}
