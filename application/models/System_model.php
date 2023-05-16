<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class System_model extends CI_Model
{
	public $table = [
		'database' => 'system_backup_db',
		'audit' => 'system_audit_trails',
		'log' => 'system_logger',
		'job' => 'system_queue_job',
	];

	public $id = [
		'database' => 'backup_id',
		'audit' => 'audit_id',
		'log' => 'log_id',
		'job' => 'queue_id',
	];

	public $order = 'DESC';

	public function __construct()
	{
		parent::__construct();
	}

	// DATABASE BACKUP

	public function getSpecificDbBackupByID($backupID)
	{
		return find($this->table['database'], [$this->id['database'] => $backupID], 'row_array');
	}

	public function getListBackupDbDt()
	{
		$serverside = serversideDT();
		$serverside->query("SELECT backup_name, backup_location, backup_storage_type, created_at, backup_id FROM {$this->table['database']} ORDER BY {$this->id['database']} {$this->order}");

		$serverside->edit('backup_name', function ($data) {
			return purify($data['backup_name']);
		});

		$serverside->edit('backup_location', function ($data) {
			$loc = purify($data['backup_location']);
			$type = purify($data['backup_storage_type']);
			if ($type != 'local') {
				$loc = '<a href="' . $loc . '" target="_blank"> Preview Link </a>';
			}
			return $loc;
		});

		$serverside->edit('created_at', function ($data) {
			return formatDate($data['created_at'], 'd.m.Y h:i A');
		});

		$serverside->edit('backup_id', function ($data) {
			$del = $download = $email = '';
			$del = '<button class="btn btn-soft-danger btn-sm" onclick="deleteRecord(' . $data[$this->id['database']] . ')" data-id="' . $data[$this->id['database']] . '" title="Delete"> <i class="fa fa-trash"></i> </button>';
			$email = '<button class="btn btn-soft-info btn-sm" onclick="emailBackup(' . $data[$this->id['database']] . ')" data-id="' . $data[$this->id['database']] . '" title="Email"> <i class="fa fa-envelope"></i> </button>';
			return "<center> $del $download $email </center>";
		});

		echo $serverside->generate();
	}

	public function deleteDb($backup_id = NULL)
	{
		$dataBackup = find($this->table['database'], [$this->id['database'] => $backup_id], 'row_array');
		$result = delete($this->table['database'], $backup_id);

		if (!empty($dataBackup)) {
			$location = $dataBackup['backup_location'];
			$filename = $location . $dataBackup['backup_name'];
			if (isSuccess($result['resCode']) && file_exists($location)) {
				unlink($filename);
			}
		}

		return $result;
	}

	// ERROR LOGS

	public function getErrorListDt($dateSearch = NULL, $errorType = NULL)
	{
		$dateFrom = !empty($dateSearch) ? escape($dateSearch . ' 00:00:00') : '';
		$dateEnd = !empty($dateSearch) ? escape($dateSearch . ' 23:59:59') : '';

		$errorQuery = NULL;
		if (!empty($errorType)) {
			$errorQuery = !empty($dateSearch) ? " AND `errtype` = " . escape($errorType) : " WHERE `errtype` = " . escape($errorType);
		}

		$searchQuery = !empty($dateSearch) ? " WHERE `time` BETWEEN $dateFrom AND $dateEnd $errorQuery" : $errorQuery;

		$serverside = serversideDT();
		$serverside->query("SELECT errstr, errfile, errline, errtype, ip_address, user_agent, `time`, log_id FROM {$this->table['log']} {$searchQuery} ORDER BY {$this->id['log']} {$this->order}");

		$serverside->hide('errline'); // hides column from the output
		$serverside->hide('errtype'); // hides column from the output
		$serverside->hide('time'); // hides column from the output
		$serverside->hide('ip_address'); // hides column from the output
		$serverside->hide('user_agent'); // hides column from the output

		$serverside->edit('errfile', function ($data) {

			if (in_array($data['errtype'], [
				'Error',
				'Warning',
				'Parsing Error',
				'Notice',
				'Core Error',
				'Core Warning',
				'Compile Error',
				'Compile Warning',
				'User Error',
				'User Warning',
				'User Notice',
				'Runtime Notice',
				'Catchable Notice',
			])) {
				$badge = [
					'Warning' => '<span class="badge badge-label bg-warning"> ' . $data['errtype'] . ' </span>',
					'Core Warning' => '<span class="badge badge-label bg-warning"> ' . $data['errtype'] . ' </span>',
					'Compile Warning' => '<span class="badge badge-label bg-warning"> ' . $data['errtype'] . ' </span>',
					'User Warning' => '<span class="badge badge-label bg-warning"> ' . $data['errtype'] . ' </span>',
					'Notice' => '<span class="badge badge-label bg-info"> ' . $data['errtype'] . ' </span>',
					'User Notice' => '<span class="badge badge-label bg-info"> ' . $data['errtype'] . ' </span>',
					'Runtime Notice' => '<span class="badge badge-label bg-info"> ' . $data['errtype'] . ' </span>',
					'Error' => '<span class="badge badge-label bg-danger"> ' . $data['errtype'] . ' </span>',
					'Parsing Error' => '<span class="badge badge-label bg-danger"> ' . $data['errtype'] . ' </span>',
					'Core Error' => '<span class="badge badge-label bg-danger"> ' . $data['errtype'] . ' </span>',
					'Compile Error' => '<span class="badge badge-label bg-danger"> ' . $data['errtype'] . ' </span>',
					'User Error' => '<span class="badge badge-label bg-danger"> ' . $data['errtype'] . ' </span>',
					'Catchable Error' => '<span class="badge badge-label bg-danger"> ' . $data['errtype'] . ' </span>',
				];
				$typeErr = $badge[$data['errtype']];
			} else {
				$typeErr = '<span class="badge badge-label bg-primary"> ' . $data['errtype'] . ' </span>';
			}

			return "<ul>
                        <li> <b> File </b> : <small> " . $data['errfile'] . " </small> </li>
                        <li> <b> Line </b> : <small> " . $data['errline'] . "  </small> </li>
                        <li> <b> Type </b> : " . $typeErr . " </li>
                        <li> <b> IP Address </b> : <small> " . $data['ip_address'] . " </small> </li>
                        <li> <b> User Agent </b> : <small> " . $data['user_agent'] . " </small> </li>
                        <li> <b> Timestamp </b> : <small> " . formatDate($data['time'], 'd.m.Y h:i A') . " </small> </li>
                     </ul>";
		});

		$serverside->edit('log_id', function ($data) {
			$del = '';
			$del = '<button class="btn btn-soft-danger btn-sm" onclick="deleteRecord(' . $data[$this->id['log']] . ')" data-id="' . $data[$this->id['log']] . '" title="Delete"> <i class="fa fa-trash"></i> </button>';
			return "<center> $del  </center>";
		});

		echo $serverside->generate();
	}

	public function deleteLogs($log_id = NULL)
	{
		return delete($this->table['log'], $log_id);
	}

	public function truncateLogTable($log_id = NULL)
	{
		return delete($this->table['log']);
	}

	public function deleteLogsByFilter($dateFrom = NULL, $dateTo = NULL, $errorType = NULL)
	{
		date_default_timezone_set("Asia/Kuala_Lumpur");

		if (!empty($dateFrom) && !empty($dateTo)) {
			$from = escape(min($dateFrom, $dateTo) . ' 00:00:00');
			$to = escape(max($dateFrom, $dateTo) . ' 23:59:59');
			return deleteWithCondition($this->table['log'], [
				"time BETWEEN $from AND $to",
				"errtype" => $errorType,
			]);
		} else if (!empty($dateFrom) && empty($dateTo)) {
			$dateFrom = escape($dateFrom . ' 00:00:00');
			return deleteWithCondition($this->table['log'], [
				"time >= $dateFrom",
				"errtype" => $errorType,
			]);
		} else if (empty($dateFrom) && !empty($dateTo)) {
			$dateTo = $dateTo . ' 23:59:59';
			return deleteWithCondition($this->table['log'], [
				"time <= $dateTo",
				"errtype" => $errorType,
			]);
		} else if (empty($dateFrom) && empty($dateTo)) {
			$dateFrom = escape(date('Y-m-d 00:00:00'));
			$dateTo = escape(date('Y-m-d 23:59:59'));
			return deleteWithCondition($this->table['log'], [
				"time BETWEEN $dateFrom AND $dateTo",
				"errtype" => $errorType,
			]);
		}
	}

	// AUDIT TRAILS

	public function getAuditTrailsListDt($dateSearch = NULL, $eventType = NULL)
	{
		date_default_timezone_set("Asia/Kuala_Lumpur");

		$eventQuery = NULL;
		if (!empty($eventType)) {
			$eventQuery = !empty($dateSearch) ? " AND `event` = " . escape($eventType) : " WHERE `event` = " . escape($eventType);
		}

		$searchQuery = !empty($dateSearch) ? " WHERE `audit`.`created_at` BETWEEN " . escape($dateSearch . ' 00:00:00') . " AND " . escape($dateSearch . ' 23:59:59') . " $eventQuery" : $eventQuery;

		$serverside = serversideDT();
		$serverside->query("SELECT 
        `audit`.`user_id`,
        `audit`.`role_id`,
        `roles`.`role_name`,
        `audit`.`user_fullname`, 
        `audit`.`event`, 
        `audit`.`table_name`, 
        `audit`.`created_at`, 
        `audit`.`old_values`, 
        `audit`.`new_values`, 
        `audit`.`audit_id`
        FROM {$this->table['audit']} `audit`
        LEFT JOIN master_role roles ON `audit`.role_id = `roles`.role_id
        $searchQuery
        ORDER BY {$this->id['audit']} {$this->order}");

		$serverside->hide('user_id'); // hides column from the output
		$serverside->hide('role_id'); // hides column from the output
		$serverside->hide('role_name'); // hides column from the output
		$serverside->hide('table_name'); // hides column from the output
		$serverside->hide('old_values'); // hides column from the output
		$serverside->hide('new_values'); // hides column from the output

		$serverside->edit('user_fullname', function ($data) {
			return purify($data['user_fullname']) . '<br> <b> Profile </b> : <small> ' . purify($data['role_name']) . '</small>';
		});

		$serverside->edit('event', function ($data) {
			$badge = [
				'insert' => '<span class="badge bg-info"> ' . $data['event'] . ' </span>',
				'update' => '<span class="badge bg-success"> ' . $data['event'] . ' </span>',
				'delete' => '<span class="badge bg-danger"> ' . $data['event'] . ' </span>',
			];

			return "<ul>
                        <li> <b> Type </b> : <small> " . $badge[$data['event']] . " </small> </li>
                        <li> <b> Table </b> : <small> " . $data['table_name'] . "  </small> </li>
                    </ul>";
		});

		$serverside->edit('created_at', function ($data) {
			return formatDate($data['created_at'], 'd.m.Y h:i A');
		});

		$serverside->edit('audit_id', function ($data) {
			$del = $view = '';
			$del = '<button class="btn btn-soft-danger btn-sm" onclick="deleteRecord(' . $data[$this->id['audit']] . ')" data-id="' . $data[$this->id['audit']] . '" title="Delete"> <i class="fa fa-trash"></i> </button>';
			$view = '<button class="btn btn-soft-success  btn-sm" onclick="viewRecord(' . $data[$this->id['audit']] . ')" data-id="' . $data[$this->id['audit']] . '" title="View"> <i class="ri-eye-line"></i> View </button>';
			return "<center> $del $view </center>";
		});

		echo $serverside->generate();
	}

	public function getAuditDataByID($audit_id = NULL)
	{
		return find($this->table['audit'], [$this->id['audit'] => $audit_id], 'row_array');
	}

	public function deleteAudit($audit_id = NULL)
	{
		return delete($this->table['audit'], $audit_id);
	}

	// JOB QUEUE (WORKER)

	public function getEmailQueueListDt($status = NULL, $dateSearch = NULL)
	{
		date_default_timezone_set("Asia/Kuala_Lumpur");

		$statusQuery = hasData($status) ? "AND `job`.`status` = " . escape($status) : NULL;
		$searchQuery = "AND `job`.`created_at` BETWEEN " . escape($dateSearch . ' 00:00:00') . " AND " . escape($dateSearch . ' 23:59:59') . $statusQuery;

		$serverside = serversideDT();
		$serverside->query("SELECT 
        `job`.`queue_uuid`,
        `job`.`payload`,
        `job`.`attempt`,
        `job`.`status`,
        `job`.`message`,
        `job`.`queue_id`
        FROM {$this->table['job']} `job`
		WHERE `job`.`type` = 'email' $searchQuery
        ORDER BY {$this->id['job']} {$this->order}");

		$serverside->edit('payload', function ($data) {
			$dataDecode = json_decode($data['payload'], true);

			if (hasData($dataDecode)) {
				$name = hasData($dataDecode['name']) ? purify($dataDecode['name']) : '<small> - </small>';
				$to = purify($dataDecode['to']);
				$subject = purify($dataDecode['subject']);
				$cc = hasData($dataDecode['cc']) ? $dataDecode['cc'] : '<small><i> (not set) </i></small>';
				$bcc = hasData($dataDecode['bcc']) ? $dataDecode['bcc'] : '<small><i> (not set) </i></small>';
				$body = $dataDecode['body'];

				$previewMessage = '<a href="javascript:void(0)" onclick="viewPreviewEmail(' . $data[$this->id['job']] . ')"> Click to Preview Email </a>';

				return "<ul>
                        <li> <b> Name </b> : <small> " . $name . " </small> </li>
                        <li> <b> To </b> : <small> " . $to . "  </small> </li>
                        <li> <b> Subject </b> : " . $subject . " </li>
                        <li> <b> CC </b> : <small> " . $cc . " </small> </li>
                        <li> <b> BCC </b> : <small> " . $bcc . " </small> </li>
                        <li> <b> Body </b> : <small> " . $previewMessage . " </small> </li>
                     </ul>";
			} else {
				return 'No payload detected';
			}
		});

		$serverside->edit('status', function ($data) {
			$badge = [
				'1' => '<span class="badge bg-warning"> Pending </span>',
				'2' => '<span class="badge bg-info"> Running </span>',
				'3' => '<span class="badge bg-success"> Completed </span>',
				'4' => '<span class="badge bg-danger"> Failed </span>',
			];

			return $badge[$data['status']];
		});

		$serverside->edit('queue_id', function ($data) {
			$del = '<button class="btn btn-soft-danger btn-sm" onclick="deleteRecord(' . $data[$this->id['job']] . ')" data-id="' . $data[$this->id['job']] . '" title="Delete"> <i class="fa fa-trash"></i> </button>';
			return "<center> $del </center>";
		});

		echo $serverside->generate();
	}


	public function getJobByID($jobID = NULL)
	{
		return find($this->table['job'], [$this->id['job'] => $jobID], 'row_array');
	}

	public function saveQueue($data)
	{
		return $this->db->insert($this->table['job'], $data);
	}

	public function deleteQueue($job_id = NULL)
	{
		return delete($this->table['job'], $job_id);
	}

	public function getJob()
	{
		return $this->db->where_in('status', [1, 2, 4])->where('attempt <', 10)->get($this->table['job'])->row_array();
	}

	public function updateQueueJob($data = NULL)
	{
		return $this->db->where($this->id['job'], $data['queue_id'])->update($this->table['job'], [
			'status' => $data['status'],
			'attempt' => $data['attempt'],
			'message' => $data['message'],
		]);
	}
}
