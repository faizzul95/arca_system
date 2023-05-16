<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function sentMail($recipientData = NULL, $subject = NULL, $dataBody = NULL, $attachment = NULL)
{
	// Create an instance; passing `true` enables exceptions
	$mail = new PHPMailer(true);

	try {

		// Server settings
		if (filter_var(env('MAIL_DEBUG'), FILTER_VALIDATE_BOOLEAN)) {
			$mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
		}

		if (filter_var(env('MAIL_IS_SMTP'), FILTER_VALIDATE_BOOLEAN)) {
			// $mail->isSMTP();  									// Send using SMTP
			// $mail->SMTPAuth   = true;                 			// Enable SMTP authentication
		}

		// $mail->Host       = env('MAIL_HOST', 'smtp.gmail.com'); // Set the SMTP server to send through
		// $mail->Username   = env('MAIL_USERNAME', '');       	// SMTP username
		// $mail->Password   = env('MAIL_PASSWORD', '');      		// SMTP password
		// $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'TLS');  	// Enable implicit TLS encryption
		// $mail->Port       = env('MAIL_PORT', 587);          	// TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

		// Recipients
		$mail->setFrom(env('MAIL_FROM_ADDRESS', 'do-no-reply@email.test'), env('MAIL_FROM_NAME'));
		$mail->addAddress($recipientData['recipient_email'], $recipientData['recipient_name']); // Add a recipient

		// Add a CC recipient
		if (array_key_exists("recipient_cc", $recipientData) && hasData($recipientData['recipient_cc'])) {
			$ccs = $recipientData['recipient_cc'];
			if (isArray($ccs)) {
				foreach ($ccs as $cc) {
					$mail->addCC($cc);
				}
			} else {
				$mail->addCC($ccs);
			}
		}

		// Add a BCC recipient
		if (array_key_exists("recipient_bcc", $recipientData) && hasData($recipientData['recipient_bcc'])) {
			$bccs = $recipientData['recipient_bcc'];
			if (isArray($bccs)) {
				foreach ($bccs as $bcc) {
					$mail->AddBCC($bcc);
				}
			} else {
				$mail->AddBCC($bccs);
			}
		}

		// Content
		$mail->isHTML(true); //Set email format to HTML
		$mail->Subject = $subject;
		$mail->Body    = $dataBody;

		if (!empty($attachment)) {
			if (isArray($attachment)) {
				foreach ($attachment as $files) {
					if (file_exists($files))
						$mail->addAttachment($files);
				}
			} else {
				if (file_exists($attachment))
					$mail->addAttachment($attachment);
			}
		}

		if ($mail->send()) {
			return ['success' => true, 'message' => 'Email sent successfully'];
		} else {
			return ['success' => false, 'message' => 'Email unable to sent'];
		}
	} catch (Exception $e) {
		log_message('debug', "Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
		return ['success' => false, 'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"];
	}
}

function replaceTextWithData($string = NULL, $arrayOfStringToReplace = array())
{
	$dataToReplace = arrayDataReplace($arrayOfStringToReplace);
	return str_replace(array_keys($dataToReplace), array_values($dataToReplace), $string);
}

function arrayDataReplace($data)
{
	$newKey = $newValue = $newData = [];
	foreach ($data as $key => $value) {
		array_push($newKey, '%' . $key . '%');
		array_push($newValue, $value);
	}

	foreach ($newKey as $key => $data) {
		$newData[$data] = $newValue[$key];
	}

	return $newData;
}
