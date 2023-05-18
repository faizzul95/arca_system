<?php

defined('BASEPATH') or exit('No direct script access allowed');

use App\middleware\core\traits\SecurityHeadersTrait;

class Auth extends CI_Controller
{
	use SecurityHeadersTrait;
	public function __construct()
	{
		parent::__construct();
		$this->set_security_headers();

		if (isLoginCheck() && !in_array(segment(2), ['logout', 'switchProfile', 'reset', 'forgot'])) {
			redirect('dashboard', true);
		}

		model('User_model', 'userM');
		model('User_profile_model', 'profileM');
		model('Academic_year_model', 'academicM');
		model('Student_enrollment_model', 'studM');
		model('User_auth_attempt_model', 'attemptM');
		model('Master_email_templates_model', 'templateM');
		model('System_model', 'systemM');

		library('user_agent');
	}

	public function index()
	{
		view(isMobileDevice() ? 'auth/login_pwa' : 'auth/login',  [
			'title' => 'Sign In',
			'currentSidebar' => 'auth',
			'currentSubSidebar' => 'login'
		]);
	}

	public function forgot()
	{
		if (isMobileDevice())
			redirect('auth');
		else
			view('auth/forgot',  [
				'title' => 'Forgot Password',
				'currentSidebar' => 'auth',
				'currentSubSidebar' => 'login'
			]);
	}

	public function authorize()
	{
		if (isAjax()) {

			$username  = input('username');
			$enteredPassword = input('password');

			$validateRecaptcha = recaptchav2();

			// Check with recaptcha first
			if ($validateRecaptcha['success']) {
				$dataUser = $this->userM->getSpecificUser($username);
				if (!empty($dataUser)) {

					$userPassword = $dataUser['user_password'];
					$userID = $dataUser['user_id'];
					$attempt = $this->attemptM->login_attempt_exceeded($userID);
					$countAttempt = $attempt['count'];

					if ($attempt['isExceed']) {
						if (password_verify($enteredPassword, $userPassword)) {

							$two_factor_status = $dataUser['two_factor_status'];
							$this->attemptM->clear_login_attempts($userID);

							// if 2FA is disabled
							if ($two_factor_status != 1) {
								$responseData = $this->sessionLoginStart($dataUser);
							}
							// if 2FA is enable
							else {
								$responseData = [
									'resCode' => 200,
									'message' => 'Two-factor authentication (2FA) is enable',
									'verify' => true
								];
							}
						} else {

							$this->attemptM::save([
								'user_id' => $userID,
								'ip_address' => $this->input->ip_address(),
								'time' => timestamp(),
								'branch_id' => $dataUser['branch_id'],
							]);

							$countAttemptRemain = 5 - (int) $countAttempt;

							$responseData = [
								'resCode' => 400,
								'message' => ($countAttempt >= 2) ? 'Invalid username or password. Attempt remaining : ' . $countAttemptRemain : 'Invalid username or password',
								'verify' => false
							];
						}
					} else {
						$responseData = [
							'resCode' => 400,
							'message' => 'You have reached maximum number of login attempt. Your account has been suspended for 15 minutes.',
							'verify' => false
						];
					}
				} else {
					$responseData = [
						'resCode' => 400,
						'message' => 'Invalid username or password',
						'verify' => false
					];
				}
			} else {
				$responseData = array(
					"resCode" => 400,
					"message" => filter_var(env('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN) ? $validateRecaptcha['error_message'] : "Please verify that you're a human",
					'verify' => false,
					"redirectUrl" => NULL,
				);
			}

			json($responseData);
		} else {
			errorpage('404');
		}
	}

	public function socialite()
	{
		if (isAjax()) {
			$email  = input('email');
			$dataUser = $this->userM->getSpecificUser($email);

			if (!empty($dataUser) > 0) {
				$responseData = $this->sessionLoginStart($dataUser);
			} else {
				$responseData = array(
					"resCode" => 400,
					"message" => 'Email not found or not registered!',
					"redirectUrl" => NULL,
				);
			}

			json($responseData);
		} else {
			errorpage('404');
		}
	}

	public function Verify2FA()
	{
		if (isAjax()) {
			$dataUser = $this->userM->getSpecificUser(input('username_2fa'));

			if (!empty($dataUser)) {
				$codeEnter = input('code_2fa');
				$codeSecret = $dataUser['two_factor_secret'];

				if (verifyGA($codeSecret, $codeEnter)) {
					$responseData = $this->sessionLoginStart($dataUser);
				} else {
					$responseData = array(
						"resCode" => 400,
						"message" => 'Wrong code or code already expired',
						"redirectUrl" => NULL,
					);
				}
			} else {
				$responseData = [
					'resCode' => 400,
					'message' => 'Invalid username',
					'verify' => false
				];
			}

			json($responseData);
		} else {
			errorpage('404');
		}
	}

	public function switchProfile()
	{
		$profileID = input('profile_id');
		$userID = input('user_id');
		$dataUser = $this->userM->getSpecificUser($userID);
		$dataUserProfile = $this->profileM->getProfileByProfileID($profileID);

		$dataStudent = $this->studM::where(['user_id' => $userID, 'academic_id' => currentAcademicID(), 'branch_id' => currentUserBranchID()], 'row_array');
		$studID = (!empty($dataStudent)) ? $dataStudent['stud_id'] : '';

		setSession([
			'userID'  => encodeID($userID),
			'userFullName'  => purify($dataUser['user_full_name']),
			'userNickName'  => purify($dataUser['user_preferred_name']),
			'userMatricNo'  => purify($dataUser['user_matric_code']),
			'profileID'  => encodeID($profileID),
			'profileName' => purify($dataUserProfile['role_name']),
			'roleID' => encodeID($dataUserProfile['role_id']),
			'studID' => encodeID($studID),
			'branchID' => encodeID($dataUserProfile['branch_id']),
			'branchName' => $dataUserProfile['branch_name'],
			'collegeID' =>  encodeID($dataUserProfile['college_id']),
			'isLoggedInSession' => TRUE
		]);

		$responseData = [
			'resCode' => 200,
			'message' => NULL,
		];

		json($responseData);
	}

	private function sessionLoginStart($dataUser)
	{
		$userID  = $dataUser['user_id'];
		$userFullName = $dataUser['user_full_name'];
		$userNickName = $dataUser['user_preferred_name'];
		$userMatricID = $dataUser['user_matric_code'];
		$userEmail = $dataUser['user_email'];
		$userAvatar = $dataUser['user_avatar'];
		$userStatus = $dataUser['user_status'];

		if ($userStatus == 1) {
			$dataUserProfile = $this->profileM->getMainProfileByUserID($userID, 1);
			$profileID = $dataUserProfile['profile_id'];
			$branchID = $dataUserProfile['branch_id'];
			$roleID = $dataUserProfile['role_id'];
			$profileName = $dataUserProfile['role_name'];
			$collegeID = $dataUserProfile['college_id'];

			$dataAcademic = $this->academicM->getCurrentAcademicByBranchID($branchID);
			$academicID = (!empty($dataAcademic)) ? $dataAcademic['academic_id'] : '';
			$academicName = (!empty($dataAcademic)) ? $dataAcademic['academic_display_name'] : 'No Academic Year';
			$academicOrder = (!empty($dataAcademic)) ? $dataAcademic['academic_order'] : 1;

			$dataStudent = $this->studM::where(['user_id' => $userID, 'academic_id' => $academicID, 'branch_id' => $branchID], 'row_array');
			$studID = (!empty($dataStudent)) ? $dataStudent['stud_id'] : '';

			setSession([
				'userID'  => encodeID($userID),
				'userFullName'  => purify($userFullName),
				'userNickName'  => purify($userNickName),
				'userMatricNo'  => purify($userMatricID),
				'userEmail'  => purify($userEmail),
				'userAvatar'  => $userAvatar,
				'profileID'  => encodeID($profileID),
				'profileName' => purify($profileName),
				'roleID' => encodeID($roleID),
				'branchID' => encodeID($dataUserProfile['branch_id']),
				'branchName' => purify($dataUserProfile['branch_name']),
				'collegeID' =>  encodeID($collegeID),
				'academicID' => encodeID($academicID),
				'studID' => encodeID($studID),
				'academicName' => purify($academicName),
				'academicOrder' => $academicOrder,
				'isLoggedInSession' => TRUE
			]);

			// Sent email secure login
			$template = $this->templateM::where(['email_type' => 'SECURE_LOGIN', 'email_status' => '1'], 'row_array');

			$browsers = $this->agent->browser();
			$os = $this->agent->platform();
			$iplogin = $this->input->ip_address();

			// if template email is exist and active
			if (hasData($template)) {

				$bodyMessage = replaceTextWithData($template['email_body'], [
					'name' => purify($userFullName),
					'email' => purify($userEmail),
					'browsers' => $browsers,
					'os' => $os,
					'details' => '<table border="1" cellpadding="1" cellspacing="1" width="40%">
					<tr>
						<td style="width:30%">&nbsp; Username </td>
						<td style="width:70%">&nbsp; ' . purify($dataUser['user_username']) . ' </td>
					</tr>
					<tr>
						<td style="width:30%">&nbsp; Browser </td>
						<td style="width:70%">&nbsp; ' . $browsers . ' </td>
					</tr>
					<tr>
						<td style="width:30%">&nbsp; Operating System </td>
						<td style="width:70%">&nbsp; ' . $os . ' </td>
					</tr>
					<tr>
						<td style="width:30%">&nbsp; IP Address </td>
						<td style="width:70%">&nbsp; ' . $iplogin . ' </td>
					</tr>
					<tr>
						<td style="width:30%">&nbsp; Date </td>
						<td style="width:70%">&nbsp; ' . timestamp('d/m/Y') . ' </td>
					</tr>
					<tr>
						<td style="width:30%">&nbsp; Time </td>
						<td style="width:70%">&nbsp; ' . timestamp('h:i A') . ' </td>
					</tr>
				  </table>',
					'url' => baseURL()
				]);

				// add to queue
				$saveQueue = $this->systemM->saveQueue([
					'queue_uuid' => uuid(),
					'type' => 'email',
					'payload' => json_encode([
						'name' => purify($userFullName),
						'to' => purify($userEmail),
						'cc' => $template['email_cc'],
						'bcc' => $template['email_bcc'],
						'subject' => $template['email_subject'],
						'body' => $bodyMessage,
						'attachment' => NULL,
					]),
					'created_at' => timestamp()
				]);
			}

			$responseData = [
				'resCode' => 200,
				'message' => 'Login',
				'verify' => false,
				'redirectUrl' => url('dashboard'),
			];
		} else {
			$responseData = [
				'resCode' => 400,
				'message' => 'Your ID is inactive, Please contact college administrator',
				'verify' => false,
				'redirectUrl' => NULL,
			];
		}

		return $responseData;
	}

	public function reset()
	{
		if (isAjax()) {

			$email  = input('email');

			$validateRecaptcha = recaptchav2();

			// Check with recaptcha first
			if ($validateRecaptcha['success']) {
				$dataUser = $this->userM->getSpecificUser($email);

				if (!empty($dataUser)) {

					$expiredAt = date('Y-m-d H:i:s', strtotime(timestamp() . ' + 45 minutes'));
					$token = encodeID($dataUser['user_email'] . '/' . $expiredAt, 2); // generate token

					$url = 'auth/newpassword/' . $token;

					$template = $this->templateM::where(['email_type' => 'FORGOT_PASSWORD'], 'row_array');
					$bodyMessage = replaceTextWithData($template['email_body'], [
						'to' => $dataUser['user_full_name'],
						'url' => url($url)
					]);

					$recipientData = [
						'recipient_name' => $dataUser['user_full_name'],
						'recipient_email' => $dataUser['user_email'],
						'recipient_cc' => $template['email_cc'],
						'recipient_bcc' => $template['email_bcc'],
					];

					$sentMail = sentMail($recipientData, $template['email_subject'], $bodyMessage);

					// add to queue
					$saveQueue = $this->systemM->saveQueue([
						'queue_uuid' => uuid(),
						'type' => 'email',
						'payload' => json_encode([
							'name' => $dataUser['user_full_name'],
							'to' => $email,
							'cc' => $template['email_cc'],
							'bcc' => $template['email_bcc'],
							'subject' => $template['email_subject'],
							'body' => $bodyMessage,
							'attachment' => NULL,
						]),
						'created_at' => timestamp()
					]);

					if ($sentMail['success']) {

						$responseData = [
							'resCode' => 200,
							'message' => 'Email has been sent successfully',
							'redirectUrl' => url('auth'),
						];
					} else {
						$responseData = [
							'resCode' => 400,
							'message' => 'Email sent unsuccessfully',
							'redirectUrl' => NULL,
						];
					}
				} else {
					$responseData = [
						'resCode' => 400,
						'message' => 'Invalid email or email not register.',
					];
				}
			} else {
				$responseData = array(
					"resCode" => 400,
					"message" => filter_var(env('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN) ? $validateRecaptcha['error_message'] : "Please verify that you're a human",
					'verify' => false,
					"redirectUrl" => NULL,
				);
			}

			json($responseData);
		} else {
			errorpage('404');
		}
	}

	public function logout()
	{
		destroySession();
	}
}
