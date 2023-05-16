<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');
class Academic_year_model extends CI_Model
{
	public $table = 'config_academic_year';
	public $id = 'academic_id';
	public $order = 'ASC';

	public function __construct()
	{
		parent::__construct();
		$this->abilities = parent::permission(['academic-update', 'academic-delete']);
		$this->currentAcademicOrder = find($this->table, ['academic_id' => currentAcademicID()], 'row_array');
	}

	protected $fillable = [
		'academic_display_name',
		'academic_year',
		'academic_start_date',
		'academic_end_date',
		'academic_status',
		'is_current',
		'academic_order',
		'is_archive',
		'branch_id'
	];

	public $with = ['sticker'];

	//  relation
	public function stickerRelation($data)
	{
		return hasOne('Config_college_sticker_model', 'academic_id', $data[$this->id], ['branch_id' => currentUserBranchID()]);
	}

	public function getListAcademicDt($isArchieve = 0)
	{
		$serverside = serversideDT();
		$serverside->query("SELECT 
        academic_display_name, 
        academic_year, 
        academic_start_date, 
        academic_end_date, 
        is_current, 
        academic_status,
        branch_id,
        academic_order,
        academic_id 
        FROM {$this->table} 
        WHERE `branch_id` = " . currentUserBranchID() . " AND `is_archive` = " . $isArchieve . "
        ORDER BY {$this->id} {$this->order}");

		$serverside->hide('academic_end_date'); // hides column from the output
		$serverside->hide('branch_id'); // hides column from the output
		$serverside->hide('academic_order'); // hides column from the output
		$serverside->hide('academic_status'); // hides column from the output

		$serverside->edit('academic_display_name', function ($data) {
			return purify($data['academic_display_name']);
		});

		$serverside->edit('academic_year', function ($data) {
			return purify($data['academic_year']);
		});

		$serverside->edit('academic_start_date', function ($data) {
			return 'Start Date : ' . formatDate(purify($data['academic_start_date'])) . '<br> End Date : ' . formatDate(purify($data['academic_end_date']));
		});

		$serverside->edit('is_current', function ($data) {
			$currentOrder = $this->currentAcademicOrder['academic_order'];
			$nextOrder = $currentOrder + 1;

			if ($data['academic_order'] == $nextOrder) {
				$statusOrder = ' - <span class="badge bg-success"> Next academic </span>';
			} else if ($data['academic_order'] < $currentOrder) {
				$statusOrder = ' - <span class="badge bg-danger"> Previous </span>';
			} else if ($data['academic_order'] > $nextOrder) {
				$statusOrder = ' - <span class="badge bg-primary"> Incoming </span>';
			} else {
				$statusOrder = '';
			}

			$btnSet = "setDefaultAcademic(" . $data['academic_id'] . ", " . $data['branch_id'] . ", '" . $data['academic_display_name'] . "', " . $data['academic_order'] . ")";
			$setDefault = '<button class="btn btn-soft-info btn-sm" onclick="' . $btnSet . '" title="Set as current academic"><i class="ri-lock-unlock-line"></i> Set Current </button>';
			return $data['is_current'] == 1 ? '<span class="badge bg-info"> Current </span>' : $setDefault . '' . $statusOrder;
		});

		$serverside->edit('academic_id', function ($data) {
			$del = $edit = '';

			$disabled = $this->countAcademicUser($data[$this->id]) > 0 ? 'disabled' : '';
			$disabledCurrent = $data['is_current'] == 1 ? 'disabled' : '';

			if ($this->abilities['academic-delete'])
				$del = '<button class="btn btn-soft-danger btn-sm" onclick="deleteRecord(' . $data[$this->id] . ')" data-id="' . $data[$this->id] . '" ' . $disabled . ' ' . $disabledCurrent . ' title="Delete"> <i class="fa fa-trash"></i> </button>';

			if ($this->abilities['academic-update'])
				$edit = '<button class="btn btn-soft-info btn-sm" onclick="updateRecord(' . $data[$this->id] . ')" title="Update"><i class="fa fa-edit"></i> </button>';

			return "<center> $del $edit </center>";
		});

		echo $serverside->generate();
	}

	public function countAcademicUser($academicID)
	{
		return countData(['academic_id' => $academicID], 'student_enrollment');
	}

	public function getCurrentAcademicByBranchID($branchID)
	{
		$this->db->where('is_current', 1);
		$this->db->where('branch_id', $branchID);
		return $this->db->get($this->table)->row_array();
	}

	public function sortAcademic($academic_order = NULL, $current_order = NULL, $formType = 'create')
	{
		if ($academic_order != NULL) {

			$typeAction = '+'; // set default action to plus

			if ($formType == 'create') {

				$query = $this->db->where('academic_order >', $academic_order)
					->where('branch_id', currentUserBranchID())
					->order_by("academic_order", "asc")
					->get($this->table)->result_array();

				$academic_order = $academic_order + 1;
			} else {

				if ($current_order > $academic_order) {

					$this->db->where('academic_order >', $academic_order);
					$this->db->where('academic_order <', $current_order);
					$this->db->where('branch_id', currentUserBranchID());
					$this->db->order_by("academic_order", "asc");
					$query = $this->db->get($this->table)->result_array();

					$academic_order = $academic_order + 1;
				} else if ($current_order < $academic_order) {

					$this->db->where('academic_order <=', $academic_order);
					$this->db->where('academic_order >', $current_order);
					$this->db->where('branch_id', currentUserBranchID());
					$this->db->order_by("academic_order", "asc");
					$query = $this->db->get($this->table)->result_array();

					$typeAction = '-'; // change action to minus
				}
			}

			if ($query) {
				foreach ($query as $row) {
					$db_order = $row['academic_order'];

					$dataInsert = array();
					$dataInsert = [
						'academic_order' => ($typeAction == '+') ? $db_order + 1 : $db_order - 1
					];

					update($this->table, $dataInsert, $row['academic_id']);
				}
			}

			return $academic_order;
		} else {

			// sort main academic back (update)
			$resultMenu = $this->db->where('branch_id', currentUserBranchID())->order_by("academic_order", "asc")->get($this->table)->result_array();

			$arrangement = 1;

			foreach ($resultMenu as $row) {

				$dataInsert = array();
				$dataInsert = [
					'academic_order' => $arrangement,
					'updated_at' => timestamp()
				];

				update($this->table, $dataInsert, $row['academic_id']);
				$arrangement++;
			}
		}
	}

	public function deleteAcademic($id)
	{
		$data = $this->db->where($this->id, $id)->get($this->table)->row_array(); // get data
		$deleteResult = delete($this->table, $id);

		if (isSuccess($deleteResult['resCode'])) {

			$resultAcademic = $this->db->where('academic_order >', $data['academic_order'])
				->where('branch_id', currentUserBranchID())
				->get($this->table)->result_array();

			foreach ($resultAcademic as $row) {
				$dataAcademic = array(); // reset
				$dataAcademic = [
					'academic_order' => $row['academic_order'] - 1,
					'updated_at' => timestamp()
				];
				update($this->table, $dataAcademic, $row['academic_id']);
			}
		}

		return $deleteResult;
	}

	public function previousAcademic()
	{
		$prevOrder = (currentAcademicOrder() > 1) ? currentAcademicOrder() - 1 : 1;
		return $this->db->where('branch_id', currentUserBranchID())->where('academic_order', $prevOrder)->get($this->table)->row_array(); // get data
	}
}
