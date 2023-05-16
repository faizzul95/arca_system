<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Export extends Controller
{
	public function __construct()
	{
		parent::__construct();
		model('Student_college_application_model', 'applicationM');
		model('Event_model', 'eventM');
		model('Event_schedule_model', 'scheduleM');
		model('Event_slot_model', 'slotM');
		model('Attendance_model', 'attendanceM');
		model('Branch_model', 'branchM');
		model('Academic_year_model', 'academicM');
		model('Config_college_model', 'collegeM');
		model('Config_college_room_model', 'roomM');
		model('Student_enrollment_model', 'studM');
	}

	public function index()
	{
		errorpage('404');
	}

	public function exportSlotAttendanceBySlotID()
	{
		$eventID = input('event_id');
		$slotID = input('slot_id');

		$countAttendance = $this->attendanceM::countData(['event_id' => $eventID, 'slot_id' => $slotID]);

		if ($countAttendance > 0) {
			$dataAttendance = $this->attendanceM->getDataExportPdf($slotID, $eventID);

			if (hasData($dataAttendance)) {

				$dataEvent = $this->eventM::find($eventID);
				$dataSlot = $this->slotM::find($slotID);
				$dataBranch = $this->branchM::find($dataEvent['branch_id']);
				// <img src="' . asset('common/images/favicon.png', null, false) . '" width="75%">

				$header = '<p>
					<table cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td colspan="2">
							<table cellspacing="0" cellpadding="0">
								<tr>
									<td width="15%">
										<img width="60px" src="' . asset('common/images/favicon.png', null, false) . '">
									</td>
									<td width="85%">
										UNIVERSITI TEKNOLOGI MARA <br>	
										Cawangan Perlis Kampus Arau<br>
										' . $dataBranch['branch_postcode'] . ' ' . $dataBranch['branch_city'] . ', ' . $dataBranch['branch_state'] . ', Malaysia.
									</td>
								</tr>
							</table>
						</td>
						<td colspan="2" align="center" style="font-size:90%;">&nbsp;&nbsp;&nbsp; EVENT ATTENDANCE </td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr>
						<td> Name </td>
						<td>&nbsp;: ' . $dataEvent['event_name'] . '</td>
						<td>&nbsp;Campus</td>
						<td>&nbsp;: ' . $dataBranch['branch_name'] . '</td>
					</tr>
					<tr>
						<td> Date </td>
						<td>&nbsp;: ' . formatDate($dataSlot['slot_timestamp_start'], 'd/m/Y') . ' </td>
						<td>&nbsp;Semester</td>
						<td>&nbsp;: ' . currentAcademicName() . '</td>
					</tr>
					<tr>
						<td> Time </td>
						<td>&nbsp;: ' . formatDate($dataSlot['slot_time_start'], 'h:i A') . ' - ' . formatDate($dataSlot['slot_time_end'], 'h:i A') . '</td>
						<td>&nbsp;Date Printed</td>
						<td>&nbsp;: ' . timestamp('d/m/Y h:i A') . '</td>
					</tr>
					</table>
				</p>';

				$body = '<table border="1" cellpadding="0" cellspacing="0" class="table table-bordered table-striped" width="100%">';
				$body .= '<thead class="table-dark">
                            <th style="width:53%;font-size: 13.5px;"> NAME </th>
                            <th style="width:15%;font-size: 13.5px;"> MATRIC ID </th>
                            <th style="width:15%;font-size: 13.5px;"> PROGRAM </th>
                            <th style="width:17%;font-size: 13.5px;"> TIME ATTEND </th>
                         </thead>';

				foreach ($dataAttendance as $row) {
					$studName = $row['user_full_name'];
					$matricID = $row['user_matric_code'];
					$programCode = $row['program_code'];
					$semester = $row['semester_number'];
					$attendTime = $row['attendance_time'];
					$attendTimestamp = $row['attendance_timestamp'];

					$body .= '<tr>';
					$body .= '<td style="height:20px;font-size:12px;"> &nbsp; ' . purify($studName) . '</td>';
					$body .= '<td style="height:20px;font-size:12px;"><center>' . purify($matricID) . '</center></td>';
					$body .= '<td style="height:20px;font-size:12px;"><center>' . purify($programCode . ' / ' . $semester) . '</center></td>';
					$body .= '<td style="height:20px;font-size:12px;"><center>' . formatDate($attendTime, 'h:i A') . '</center></td>';
					$body .= '</tr>';
				}
				$body .= '</table>';

				$footer = '';
				$dataToPrint = $header . $body . $footer;

				json(['resCode' => 200, 'message' => 'Export attendance', 'result' => $dataToPrint]);
			} else {
				json(['resCode' => 400, 'message' => 'Attendance failed to retrieve']);
			}
		} else {
			json(['resCode' => 400, 'message' => 'No attendance found']);
		}
	}

	public function printListUnofferedCollege()
	{
		$countApplication = $this->applicationM::countData(['academic_id' => input('academic_id'), 'college_id' => input('college_id'), 'approval_status' => input('approval_status')]);

		if ($countApplication > 0) {
			$dataApplication  = $this->applicationM->getUnofferedListByAcademicID(input('academic_id'), input('college_id'), input('approval_status'));

			if ($dataApplication) {

				$dataBranch = $this->branchM::find(currentUserBranchID());
				$dataAcademic = $this->academicM::find(input('academic_id'));
				// <img src="' . asset('common/images/favicon.png', null, false) . '" width="75%">

				$srkDetails = NULL;
				if (currentUserRoleID() == 4) {
					$dataCollege  = $this->collegeM::find(input('college_id'));
					$srkDetails = ' <tr>
                                        <td style="font-size: 10px;"> Name </td>
                                        <td style="font-size: 10px;">&nbsp;: ' . currentUserFullName() . '</td>
                                        <td style="font-size: 10px;"> College </td>
                                        <td style="font-size: 10px;">&nbsp;: ' . $dataCollege['college_name'] . ' </td>
                                    </tr>
                                    <tr>
                                        <td style="font-size: 10px;"> Staff ID </td>
                                        <td style="font-size: 10px;">&nbsp;: ' . getSession('userMatricNo') . '</td>
                                        <td style="font-size: 10px;">  Campus </td>
                                        <td style="font-size: 10px;">&nbsp;: R - UiTM Kampus Arau </td>
                                    </tr>';
				}

				$header = '<p>
                <table cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td colspan="2">
                        <table cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="15%">
                                    <img width="60px" src="' . asset('common/images/favicon.png', null, false) . '">
                                </td>
                                <td width="85%">
                                    UNIVERSITI TEKNOLOGI MARA <br>	
                                    Cawangan Perlis Kampus Arau<br>
                                    ' . $dataBranch['branch_postcode'] . ' ' . $dataBranch['branch_city'] . ', ' . $dataBranch['branch_state'] . ', Malaysia.
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td colspan="2" align="center" style="font-size:75%;">&nbsp;&nbsp;&nbsp; <b> COLLEGE APPLICATION (UNOFFERED)</b>  </td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                ' . $srkDetails . '
                <tr>
                    <td style="font-size: 10px;"> Semester</td>
                    <td style="font-size: 10px;">&nbsp;: ' . $dataAcademic['academic_display_name'] . '</td>
                    <td style="font-size: 10px;"> Date Printed</td>
                    <td style="font-size: 10px;">&nbsp;: ' . timestamp('d/m/Y h:i A') . ' </td>
                </tr>
                </table>
            </p>';

				$body = '<table border="1" cellpadding="0" cellspacing="0" class="table table-bordered table-striped" width="100%">';
				$body .= '<thead>
                            <th style="width:43%;font-size: 10px;"> NAME </th>
                            <th style="width:13%;font-size: 10px;"> MATRIC ID </th>
                            <th style="width:13%;font-size: 10px;"> PROGRAM </th>
                            <th style="width:31%;font-size: 10px;"> REASON </th>
                         </thead>';

				foreach ($dataApplication as $row) {
					$studName = $row['user_full_name'];
					$matricID = $row['user_matric_code'];
					$programCode = $row['program_code'];
					$semester = $row['semester_number'];
					$reason = $row['approval_remark'];

					$body .= '<tr>';
					$body .= '<td style="height:20px;font-size:10px;"> &nbsp; ' . $studName . '</td>';
					$body .= '<td style="height:20px;font-size:10px;"> <center>' . $matricID . '</center></td>';
					$body .= '<td style="height:20px;font-size:10px;"> <center>' . $programCode . ' / ' . $semester . '</center></td>';
					$body .= '<td style="height:20px;font-size:10px;"> &nbsp; ' . $reason . '</td>';
					$body .= '</tr>';
				}
				$body .= '</table>';

				$footer = '';
				$dataToPrint = $header . $body . $footer;

				json(['resCode' => 200, 'message' => 'Export unoffered college', 'result' => $dataToPrint]);
			} else {
				json(['resCode' => 400, 'message' => 'List failed to retrieve']);
			}
		} else {
			json(['resCode' => 400, 'message' => 'Only one academic are allowed']);
		}
	}

	public function exportListEnrollmentByCollegeID($collegeID = NULL, $branchID = NULL, $academicID = NULL)
	{
		$branchID = empty($branchID) ? currentUserBranchID() : $branchID;
		$academicID = empty($academicID) ? currentAcademicID() : $academicID;
		$academicData = $this->academicM::find($academicID);
		$collegeData = $this->collegeM::find($collegeID);

		$dataEnroll = $this->studM::all(
			['academic_id' => $academicID, 'branch_id' => $branchID, 'college_id' => $collegeID],
			NULL,
			['user', 'user.programme', 'room']
		);

		if (!empty($dataEnroll)) {
			if (!isset($dataEnroll[0])) {
				json(['resCode' => 400, 'message' => 'No enrollment found in college ' . $collegeData['college_name'] . '']);
			} else {
				$dataBranch = $this->branchM::find($branchID);

				$header = '<p>
					<table cellspacing="0" cellpadding="0" width="100%">
					<tr>
						<td colspan="2">
							<table cellspacing="0" cellpadding="0">
								<tr>
									<td width="15%">
										<img width="60px" src="' . asset('common/images/favicon.png', null, false) . '">
									</td>
									<td width="85%">
										UNIVERSITI TEKNOLOGI MARA <br>	
										Cawangan Perlis Kampus Arau<br>
										' . $dataBranch['branch_postcode'] . ' ' . $dataBranch['branch_city'] . ', ' . $dataBranch['branch_state'] . ', Malaysia.
									</td>
								</tr>
							</table>
						</td>
						<td colspan="2" align="center" style="font-size:90%;">&nbsp;&nbsp;&nbsp; ENROLLMENT LIST </td>
					</tr>
					<tr>
						<td colspan="4">&nbsp;</td>
					</tr>
					<tr>
						<td> College </td>
						<td>&nbsp;: ' . $collegeData['college_name'] . '</td>
						<td>&nbsp;Campus</td>
						<td>&nbsp;: ' . $dataBranch['branch_name'] . '</td>
					</tr>
					<tr>
						<td>Semester</td>
						<td>&nbsp;: ' . currentAcademicName() . '</td>
						<td>&nbsp;Date Printed</td>
						<td>&nbsp;: ' . timestamp('d/m/Y h:i A') . '</td>
					</tr>
					</table>
				</p>';

				$body = '<table border="1" cellpadding="0" cellspacing="0" class="table table-bordered table-striped" width="100%">';
				$body .= '<thead class="table-dark">
		                    <th style="width:5%;font-size: 13px;"> NO. </th>
		                    <th style="width:53%;font-size: 13px;"> NAME </th>
		                    <th style="width:14%;font-size: 13px;"> MATRIC ID </th>
		                    <th style="width:14%;font-size: 13px;"> CODE/PART </th>
		                    <th style="width:14%;font-size: 13px;"> ROOM </th>
		                 </thead>';

				$no = 1;
				foreach ($dataEnroll as $row) {
					$studName = $row['user']['user_full_name'];
					$matricID = $row['user']['user_matric_code'];
					$programCode = $row['user']['programme']['program_code'];
					$semester = $row['semester_number'];
					$roomNo = $row['room']['college_room_number'];
					$bedNo = $row['college_bed_no'];

					$body .= '<tr>';
					$body .= '<td style="height:20px;font-size:12px;"><center>' . $no++ . '</center></td>';
					$body .= '<td style="height:20px;font-size:12px;"> &nbsp; ' . purify(truncate($studName, 50)) . '</td>';
					$body .= '<td style="height:20px;font-size:12px;"><center>' . purify($matricID) . '</center></td>';
					$body .= '<td style="height:20px;font-size:12px;"><center>' . purify($programCode) . ' / ' . purify($semester) . '</center></td>';
					$body .= '<td style="height:20px;font-size:12px;"><center>' . purify($roomNo) . ' / ' . purify($bedNo) . '</center></td>';
					$body .= '</tr>';
				}
				$body .= '</table>';

				$footer = '';
				$dataToPrint = $header . $body . $footer;

				json(['resCode' => 200, 'message' => 'Print list enrollment', 'result' => $dataToPrint]);
			}
		} else {
			json(['resCode' => 400, 'message' => 'List enrollment failed to retrieve']);
		}
	}

	public function exportListEnrollmentExcelByCollegeID($collegeID = NULL, $branchID = NULL, $academicID = NULL)
	{
		$branchID = empty($branchID) ? currentUserBranchID() : $branchID;
		$academicID = empty($academicID) ? currentAcademicID() : $academicID;
		$academicData = $this->academicM::find($academicID);
		$collegeData = $this->collegeM::find($collegeID);

		$dataEnroll = $this->studM::all(
			['academic_id' => $academicID, 'branch_id' => $branchID, 'college_id' => $collegeID],
			NULL,
			['user', 'user.programme', 'room']
		);

		if (!empty($dataEnroll)) {
			if (!isset($dataEnroll[0])) {
				json(['resCode' => 400, 'message' => 'No enrollment found in college ' . $collegeData['college_name'] . '']);
			} else {

				// reset @ initialize data
				$dataToExport = [];

				// push header into data
				array_push($dataToExport, [
					'Student Name',
					'Matric ID',
					'Program Code',
					'Program',
					'Semester',
					'College Room No',
					'Bed No',
				]);

				foreach ($dataEnroll as $row) {
					array_push($dataToExport, [
						purify($row['user']['user_full_name']),
						purify($row['user']['user_matric_code']),
						purify($row['user']['programme']['program_code']),
						purify($row['user']['programme']['program_name']),
						purify($row['semester_number']),
						purify($row['room']['college_room_number']),
						purify($row['college_bed_no']),
					]);
				}

				$fileName = replaceFolderName($collegeData['college_name'] . '_' . $academicData['academic_display_name']) . '.xls';
				json(exportToExcel($dataToExport, $fileName));
			}
		} else {
			json(['resCode' => 400, 'message' => 'List enrollment failed to retrieve']);
		}
	}
}
