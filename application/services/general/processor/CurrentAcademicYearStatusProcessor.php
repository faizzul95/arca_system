<?php

namespace App\services\general\processor;

defined('BASEPATH') or exit('No direct script access allowed');

class CurrentAcademicYearStatusProcessor
{
	public $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
		model('Branch_model', 'branchM');
		model('Academic_year_model', 'academicM');
	}

	public function execute()
	{
		// get all branch available (active)
		$allBranch = $this->CI->branchM::all(['branch_status' => 1]);

		foreach ($allBranch as $row) {

			// get branch id
			$branchID = $row['branch_id'];

			// count total academic register
			$totalAcademic = countData(['branch_id' => $branchID], 'config_academic_year');

			// check if total more then 0, if dont then skip
			if ($totalAcademic > 0) {

				// get current active academic
				$getCurrentAcademicOrder = $this->CI->academicM::where(['is_current' => 1, 'branch_id' => $branchID], 'row_array');

				// check if has current active academic
				if (hasData($getCurrentAcademicOrder)) {
					$currentAcademicID = $getCurrentAcademicOrder['academic_id'];
					$academicEnd = $getCurrentAcademicOrder['academic_end_date'];
					$currentOrder = $getCurrentAcademicOrder['academic_order'];

					// check if current academic is expired
					if (timestamp('Y-m-d') > $academicEnd) {

						// get next academic order
						$getNextAcademicOrder = $this->CI->academicM::where(['academic_order' => $currentOrder + 1, 'branch_id' => $branchID], 'row_array');

						// check if has next academic then update
						if (!empty($getNextAcademicOrder)) {
							$nextAcademicID = $getNextAcademicOrder['academic_id'];
							$nextAcademicStart = $getNextAcademicOrder['academic_start_date'];

							// check if next academic already start
							if (timestamp('Y-m-d') >= $nextAcademicStart) {
								$updateOldAcademic = $this->CI->academicM::save(['academic_id' => $currentAcademicID, 'is_current' => 0]);
								$updateNewAcademic = $this->CI->academicM::save(['academic_id' => $nextAcademicID, 'is_current' => 1]);
							}
						}
					}
				}
				// check if academic year order 1 is exist
				else {

					$getAcademicFirstOrder = $this->CI->academicM::where(['academic_order' => 1, 'branch_id' => $branchID], 'row_array');

					if (hasData($getAcademicFirstOrder)) {
						$currentAcademicID = $getAcademicFirstOrder['academic_id'];
						$academicStart = $getAcademicFirstOrder['academic_start_date'];

						// check if next academic already start
						if (timestamp('Y-m-d') >= $academicStart) {
							$updateFirstAcademic = $this->CI->academicM::save(['academic_id' => $currentAcademicID, 'is_current' => 1]);
						}
					}
				}
			}
		}
	}
}
