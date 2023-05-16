<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Student_college_application_model extends CI_Model
{
	public $table = 'student_college_application';
	public $id = 'application_id';
	public $order = 'DESC';

	public function __construct()
	{
		parent::__construct();
		// $this->abilities = parent::permission();

		$this->stickerRequired = NULL;
	}

	protected $fillable = [
		'is_apply',
		'apply_academic_id',
		'application_date',
		'stud_id',
		'user_id',
		'approval_user_id',
		'approval_date',
		'approval_status',
		'approval_remark',
		'scrutinize_check_status',
		'college_id',
		'academic_id',
		'branch_id',
	];

	public $with = ['enrollment', 'sticker'];

	public function enrollmentRelation($data)
	{
		return hasOne('Student_enrollment_model', 'stud_id', $data['stud_id'], NULL, ['user']);
	}

	public function stickerRelation($data)
	{
		return hasOne('Student_sticker_collection_model', 'stud_id', $data['stud_id'], ['academic_id' => currentAcademicID()], ['user']);
	}

	public function getApplicationListDtByStatus($status = NULL, $academicID = NULL, $collegeID = NULL, $approvalStatus = NULL)
	{
		$whereApply = empty($status) && $status != 0 ? NULL : 'AND app.is_apply=' . $status;
		$whereStatus = empty($approvalStatus) && $approvalStatus != 0 ? NULL : 'AND app.approval_status=' . $approvalStatus;

		$whereAcademic = NULL;
		if ($academicID != "0") {
			if (hasData(currentAcademicID()))
				$whereAcademic = empty($academicID) ? "AND app.academic_id=" . currentAcademicID() : 'AND app.academic_id=' . $academicID;
		}

		$whereCollege = NULL;
		if ($collegeID != "0")
			$whereCollege = empty($collegeID) ? NULL : 'AND app.college_id=' . $collegeID;

		$serverside = serversideDT();
		$serverside->query(" SELECT 
        usr.user_full_name,
        usr.user_matric_code,
        usr.program_id,
        program.program_code,
        app.stud_id,
        app.user_id,
        app.college_id,
        college.college_name,
        sticker.collection_id,
        sticker.total_hep_sticker,
        sticker.total_university_sticker,
        sticker.total_college_sticker,
        sticker.total_faculty_sticker,
        sticker.total_club_sticker,
        app.is_apply,
        app.application_date,
        app.approval_status,
        app.approval_remark,
        app.application_id
        FROM {$this->table} app
        LEFT JOIN student_sticker_collection sticker ON app.stud_id=sticker.stud_id 
        LEFT JOIN user usr ON app.user_id=usr.user_id
        LEFT JOIN config_college college ON app.college_id=college .college_id
        LEFT JOIN master_program program ON usr.program_id =program.program_id 
        WHERE app.branch_id = " . currentUserBranchID() . " $whereAcademic $whereApply $whereCollege $whereStatus
        ORDER BY app.{$this->id} {$this->order}");

		$serverside->hide('program_id'); // hides column from the output
		$serverside->hide('stud_id'); // hides column from the output
		$serverside->hide('user_id'); // hides column from the output
		$serverside->hide('college_id'); // hides column from the output
		$serverside->hide('application_date'); // hides column from the output
		$serverside->hide('approval_status'); // hides column from the output
		$serverside->hide('approval_remark'); // hides column from the output
		$serverside->hide('total_hep_sticker'); // hides column from the output
		$serverside->hide('total_university_sticker'); // hides column from the output
		$serverside->hide('total_college_sticker'); // hides column from the output
		$serverside->hide('total_faculty_sticker'); // hides column from the output
		$serverside->hide('total_club_sticker'); // hides column from the output
		$serverside->hide('application_id'); // hides column from the output

		$serverside->edit('user_full_name', function ($data) {
			return purify($data['user_full_name']);
		});

		$serverside->edit('user_matric_code', function ($data) {
			return purify($data['user_matric_code']);
		});

		$serverside->edit('program_code', function ($data) {
			return purify($data['program_code']);
		});

		$serverside->edit('college_name', function ($data) {
			return purify($data['college_name']);
		});

		$serverside->edit('is_apply', function ($data) {
			$status = $this->statusApplication($data['approval_status'], $data['approval_remark']);
			return $data['is_apply'] == 1 ? $status . '<span class="badge badge-label bg-success"> Apply - ' . formatDate($data['application_date']) . '</span>' : $status . '<span class="badge badge-label bg-danger"> Not Apply </span>';
		});

		$serverside->edit('collection_id', function ($data) {
			$others = $data['total_faculty_sticker'] +  $data['total_club_sticker'];
			return 'HEP : <b>' . $data['total_hep_sticker'] . '</b> | University : <b>' . $data['total_university_sticker'] . '</b> | College : <b>' . $data['total_college_sticker'] . '</b> | Others : <b>' . $others . '</b>';
		});

		echo $serverside->generate();
	}

	public function getApplicationListDtByStatusSRK($status = NULL, $academicID = NULL, $collegeID = NULL, $approvalStatus = NULL, $eligibleStatus = NULL)
	{
		$this->stickerRequired = find('config_sticker_college', [
			'academic_id' => empty($academicID) ? currentAcademicID() : $academicID,
			'branch_id' => currentUserBranchID(),
		], 'row_array');

		$whereApply = empty($status) && $status != 0 ? NULL : 'AND app.is_apply=' . $status;
		$whereEligible = empty($eligibleStatus) ? NULL : 'AND sticker.is_college_eligible=' . $eligibleStatus;
		$whereStatus = empty($approvalStatus) && $approvalStatus != 0 ? NULL : 'AND app.approval_status=' . $approvalStatus;

		$whereAcademic = NULL;
		if ($academicID != "0")
			$whereAcademic = empty($academicID) ? "AND app.academic_id=" . currentAcademicID() : 'AND app.academic_id=' . $academicID;

		$whereCollege = NULL;
		if ($collegeID != "0")
			$whereCollege = empty($collegeID) ? NULL : 'AND app.college_id=' . $collegeID;

		$serverside = serversideDT();
		$serverside->query("SELECT 
        app.scrutinize_check_status,
        usr.user_full_name,
        usr.user_matric_code,
        usr.program_id,
        program.program_code,
        app.user_id,
        app.stud_id,
        app.college_id,
        app.academic_id,
        sticker.collection_id,
        sticker.total_hep_sticker,
        sticker.total_university_sticker,
        sticker.total_college_sticker,
        sticker.total_faculty_sticker,
        sticker.total_club_sticker,
        sticker.is_college_eligible,
        app.is_apply,
        app.application_date,
        app.approval_status,
        app.approval_remark,
        app.application_id
        FROM {$this->table} app
        LEFT JOIN student_sticker_collection sticker ON app.stud_id=sticker.stud_id 
        LEFT JOIN user usr ON app.user_id=usr.user_id
        LEFT JOIN config_college college  ON app.college_id=college .college_id
        LEFT JOIN master_program program ON usr.program_id =program.program_id 
        WHERE app.branch_id = " . currentUserBranchID() . " $whereAcademic $whereApply $whereStatus $whereCollege $whereEligible
        ORDER BY {$this->id} {$this->order}");

		$serverside->hide('program_id'); // hides column from the output
		$serverside->hide('stud_id'); // hides column from the output
		$serverside->hide('user_id'); // hides column from the output
		$serverside->hide('college_id'); // hides column from the output
		$serverside->hide('academic_id'); // hides column from the output
		$serverside->hide('application_date'); // hides column from the output
		$serverside->hide('approval_status'); // hides column from the output
		$serverside->hide('approval_remark'); // hides column from the output
		$serverside->hide('total_hep_sticker'); // hides column from the output
		$serverside->hide('total_university_sticker'); // hides column from the output
		$serverside->hide('total_college_sticker'); // hides column from the output
		$serverside->hide('total_faculty_sticker'); // hides column from the output
		$serverside->hide('total_club_sticker'); // hides column from the output
		$serverside->hide('is_college_eligible'); // hides column from the output

		$serverside->edit('user_full_name', function ($data) {
			return purify($data['user_full_name']);
		});

		$serverside->edit('user_matric_code', function ($data) {
			return purify($data['user_matric_code']);
		});

		$serverside->edit('program_code', function ($data) {
			return purify($data['program_code']);
		});

		$serverside->edit('scrutinize_check_status', function ($data) {
			$currentStatus = $data['scrutinize_check_status'];
			$appID = $data['application_id'];
			$checked = $data['academic_id'] != currentAcademicID() ? "disabled" : ($currentStatus == 1 ? 'checked' : '');
			return '<center> <input id="ch' . $appID . '" type="checkbox" onclick="checkedStudent(' . $currentStatus . ',' . $appID . ')" ' . $checked . '> </center>';
		});

		$serverside->edit('is_apply', function ($data) {

			$collectionHEP = $data['total_hep_sticker'];
			$collectionUniversity = $data['total_university_sticker'];
			$collectionCollege = $data['total_college_sticker'];

			$academicHEP = $this->stickerRequired['sticker_hep_amount'];
			$academicUniversity = $this->stickerRequired['sticker_university_amount'];
			$academicCollege = $this->stickerRequired['sticker_college_amount'];

			$eligibleStatus = [
				'1' => "<span class='badge badge-label bg-success'> Eligible </span>",
				'2' => "<span class='badge badge-label bg-warning' data-bs-toggle='tooltip' data-bs-placement='bottom' title='insufficient sticker for college, university and HEP.'> Not Eligible </span>",
			];

			$status = $this->statusApplication($data['approval_status'], $data['approval_remark']);

			$eligible = !empty($data['is_college_eligible']) ? $eligibleStatus[$data['is_college_eligible']] : '';
			return $data['is_apply'] == 1 ? '<span class="badge badge-label bg-success"> Apply - ' . formatDate($data['application_date']) . '</span>' . $eligible . $status : '<span class="badge badge-label bg-danger"> Not Apply </span>' . $eligible . $status;
		});

		$serverside->edit('collection_id', function ($data) {
			$others = $data['total_faculty_sticker'] +  $data['total_club_sticker'];
			return 'HEP : <b>' . $data['total_hep_sticker'] . '</b> | University : <b>' . $data['total_university_sticker'] . '</b> | College : <b>' . $data['total_college_sticker'] . '</b> | Others : <b>' . $others . '</b>';
		});

		$serverside->edit('application_id', function ($data) {
			$view = $approve = '';
			$view = '<button class="btn btn-soft-success btn-sm" onclick="viewRecord(' . $data[$this->id] . ')" title="View"><i class="fa fa-eye"></i> </button>';
			$approve = '<button class="btn btn-soft-primary btn-sm" onclick="approveApplication(' . $data[$this->id] . ', ' . escape($data['user_full_name']) . ')" title="Approval"><i class="fa fa-gavel"></i> Approval </button>';

			return $data['academic_id'] == currentAcademicID() ? "<center> $approve </center>" : '<center> <span class="text-danger" style="font-size:11px"> CLOSE FOR EVALUATE </span></center>';
		});

		echo $serverside->generate();
	}

	public function getApplicationListBulkDt($academicID = NULL, $collegeID = NULL, $isChecked = NULL, $isEligible = NULL)
	{
		$this->db->join('student_sticker_collection sticker', 'app.stud_id = sticker.stud_id', 'left')
			->join('user usr', 'app.user_id = usr.user_id', 'left')
			->join('user_profile usr_pro', 'usr.user_id = usr_pro.user_id', 'left')
			->join('master_program program', 'usr.program_id = program.program_id', 'left')
			->where('app.branch_id', currentUserBranchID())
			->where('app.academic_id', currentAcademicID())
			->where('app.college_id', $collegeID)
			->where('usr_pro.role_id', 6);

		if ($isChecked) {
			$this->db->where('app.scrutinize_check_status', $isChecked);
		} else if ($isEligible) {
			$this->db->where('sticker.is_college_eligible', $isEligible);
		}

		return $this->db->get($this->table . " app", null)->result_array();
	}

	public function statusApplication($status = 0, $reason = NULL)
	{
		$applicationStatus = [
			'0' => "<span class='badge badge-label bg-info'> Pending </span>",
			'1' => "<span class='badge badge-label bg-success'> Offered </span>",
			'2' => "<span class='badge badge-label bg-danger' title=" . $reason . "> Unoffered </span>",
			'3' => "<span class='badge badge-label bg-warning'> On-hold </span>",
		];

		return $applicationStatus[$status];
	}

	public function getUnofferedListByAcademicID($academicID = NULL, $collegeID = NULL, $approvalStatus = 2)
	{
		$this->db->join('student_enrollment', 'student_enrollment.stud_id = ' . $this->table . '.stud_id');
		$this->db->join('user', 'user.user_id = ' . $this->table . '.user_id');
		$this->db->join('master_program', 'master_program.program_id = user.program_id');
		$this->db->where($this->table . '.approval_status', $approvalStatus);
		if (!empty($collegeID))
			$this->db->where($this->table . '.college_id', $collegeID);
		if (!empty($academicID))
			$this->db->where($this->table . '.academic_id', $academicID);
		$this->db->where($this->table . '.branch_id', currentUserBranchID());
		return $this->db->get($this->table)->result_array();
	}

	public function countTotalApplicationByApprovalStatus($status = 0, $collegeID = NULL)
	{
		$this->db->from($this->table);
		$this->db->where($this->table . '.academic_id', currentAcademicID());
		$this->db->where($this->table . '.branch_id', currentUserBranchID());
		$this->db->where($this->table . '.college_id', $collegeID);
		$this->db->where($this->table . '.approval_status', $status);

		return $this->db->count_all_results();
	}

	public function countApplyCollegePreviousAcademic($prevAcademicID = NULL, $isApply = 0, $collegeID = NULL)
	{
		// count prev academic application
		return countData([
			'is_apply' => $isApply,
			'college_id' => $collegeID,
			'academic_id' => $prevAcademicID,
			'branch_id' => currentUserBranchID()
		], $this->table);
	}

	public function countPercentageByApplyStatus($totalStudent = 0, $isApply = 0, $collegeID = NULL)
	{
		$this->db->from($this->table);
		$this->db->where($this->table . '.is_apply', $isApply);
		$this->db->where($this->table . '.academic_id', currentAcademicID());
		$this->db->where($this->table . '.branch_id', currentUserBranchID());

		if (!empty($collegeID))
			$this->db->where($this->table . '.college_id', $collegeID);

		$sumApp = $this->db->count_all_results();

		$percentage = 0.0;
		if ($sumApp > 0) {
			$total = ($sumApp / $totalStudent) * 100;
			$percentage = number_format($total, 1);
		}

		return ['total' => $sumApp, 'percentage' => $percentage];
	}
}
