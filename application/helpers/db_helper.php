<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

if (!function_exists('escape')) {
	function escape($params =  NULL)
	{
		$ci = get_instance();

		if (!empty($params))
			return $ci->db->escape($params);
		else
			return $params;
	}
}

if (!function_exists('rawQuery')) {
	function rawQuery($statement = NULL, $dataArr = NULL, $fetchType = 'result_array')
	{
		$ci = get_instance();
		if (!empty($statement))
			return empty($dataArr) ? $ci->db->query($statement)->$fetchType() : $ci->db->query($statement, $dataArr)->$fetchType();
	}
}

if (!function_exists('find')) {
	function find($tableName = NULL, $condition = NULL, $fetchType = 'result_array')
	{
		$ci = get_instance();
		if (!empty($tableName))
			return $ci->db->get_where($tableName, $condition, 1)->$fetchType();
	}
}

if (!function_exists('findAll')) {
	function findAll($tableName = NULL, $condition = NULL, $orderBy = NULL, $limit = NULL)
	{
		$ci = get_instance();
		if (!empty($condition)) {
			foreach ($condition as $key => $value) {
				if (is_numeric($key))
					$ci->db->where($value);
				else
					$ci->db->where($key, $value);
			}
		}

		return $ci->db->order_by($orderBy)->get($tableName, $limit)->result_array();
	}
}

if (!function_exists('getwhere')) {
	function getwhere($tableName = NULL, $condition = NULL, $fetchType = 'result_array', $orderBy = NULL)
	{
		$ci = get_instance();
		if (!empty($tableName))
			return $ci->db->order_by($orderBy)->get_where($tableName, $condition)->$fetchType();
	}
}

if (!function_exists('minmax')) {
	function minmax($tableName = NULL, $columnName = NULL, $condition = NULL, $type = 'min')
	{
		$ci = get_instance();

		if ($type == 'min') {
			$ci->db->select_min($columnName);
		} else {
			$ci->db->select_max($columnName);
		}

		if (!empty($tableName))
			return $ci->db->get_where($tableName, $condition)->row_array();
	}
}

if (!function_exists('countData')) {
	function countData($condition = NULL, $dbName = NULL)
	{
		$ci = get_instance();
		if (!empty($dbName))
			return $ci->db->where($condition)->from($dbName)->count_all_results();
	}
}

// CanThink Solution

if (!function_exists('db_name')) {
	function db_name($debug = false)
	{
		if ($debug)
			dd(DB_NAME);
		else
			return DB_NAME;
	}
}

if (!function_exists('insert')) {
	function insert($table, $data, $enableXss = true)
	{
		if (!$enableXss) {

			$data['created_at'] = timestamp();
			$filterData = sanitizeInput($data, $table, $enableXss);

			try {
				$ci = get_instance();
				$resultInsert = $ci->db->insert($table, $filterData);
				$resCode = ($resultInsert['status']) ? 201 : 400;
				http_response_code($resCode);

				return [
					"action" => 'insert',
					"resCode" => $resCode,
					"message" =>  message($resCode, 'added'),
					"id" => $resultInsert['lastID'],
					"data" => $filterData
				];
			} catch (Exception $e) {
				log_message('error', $e->getMessage());
				http_response_code(400);
				return [
					'action' => 'insert',
					'resCode' => 400,
					'message' => 'Something when wrong.',
					'id' => NULL,
					'data' => $e->getMessage(),
				];
			}
		} else if (antiXss($data) === false) {

			$data['created_at'] = timestamp();
			$filterData = sanitizeInput($data, $table);

			try {
				$ci = get_instance();
				$resultInsert = $ci->db->insert($table, $filterData);
				$resCode = ($resultInsert['status']) ? 201 : 400;
				http_response_code($resCode);

				return [
					"action" => 'insert',
					"resCode" => $resCode,
					"message" =>  message($resCode, 'added'),
					"id" => $resultInsert['lastID'],
					"data" => $filterData
				];
			} catch (Exception $e) {
				log_message('error', $e->getMessage());
				http_response_code(400);
				return [
					'action' => 'insert',
					'resCode' => 400,
					'message' => 'Something when wrong.',
					'id' => NULL,
					'data' => $e->getMessage(),
				];
			}
		} else {
			http_response_code(400);
			return [
				'action' => 'insert',
				'resCode' => 400,
				'message' => 'Protection against <b><i> Cross-site scripting (XSS) </i></b> activated!',
				'id' => NULL,
				'data' => [],
			];
		}
	}
}

if (!function_exists('update')) {
	function update($table, $data, $pkValue, $pkTableCT = NULL, $enableXss = true)
	{
		if (!$enableXss) {
			$pkTable = (empty($pkTableCT)) ? primary_field_name($table) : $pkTableCT;

			$data['updated_at'] = timestamp();
			$filterData = sanitizeInput($data, $table, $enableXss);

			if (isset($filterData[$pkTable])) {
				unset($filterData[$pkTable]); // auto increment, no need to update or insert
			}

			try {
				$ci = get_instance();
				$resCode = ($ci->db->update($table, $filterData, [$pkTable => $pkValue])) ? 200 : 400;
				http_response_code($resCode);

				return [
					"action" => 'update',
					"resCode" => $resCode,
					"message" =>  message($resCode, 'update'),
					"id" => $pkValue,
					"data" => $filterData
				];
			} catch (Exception $e) {
				log_message('error', $e->getMessage());
				http_response_code(400);
				return [
					"action" => 'update',
					'resCode' => 400,
					'message' => 'Something when wrong.',
					'id' => NULL,
					'data' => $e->getMessage(),
				];
			}
		} else if (antiXss($data) === false) {
			$pkTable = (empty($pkTableCT)) ? primary_field_name($table) : $pkTableCT;

			$data['updated_at'] = timestamp();
			$filterData = sanitizeInput($data, $table);

			if (isset($filterData[$pkTable])) {
				unset($filterData[$pkTable]); // auto increment, no need to update or insert
			}

			try {
				$ci = get_instance();
				$resCode = ($ci->db->update($table, $filterData, [$pkTable => $pkValue])) ? 200 : 400;
				http_response_code($resCode);

				return [
					"action" => 'update',
					"resCode" => $resCode,
					"message" =>  message($resCode, 'update'),
					"id" => $pkValue,
					"data" => $filterData
				];
			} catch (Exception $e) {
				log_message('error', $e->getMessage());
				http_response_code(400);
				return [
					"action" => 'update',
					'resCode' => 400,
					'message' => 'Something when wrong.',
					'id' => NULL,
					'data' => $e->getMessage(),
				];
			}
		} else {
			http_response_code(400);
			return [
				'action' => 'update',
				'resCode' => 400,
				'message' => 'Protection against <b><i> Cross-site scripting (XSS) </i></b> activated!',
				'id' => NULL,
				'data' => [],
			];
		}
	}
}

if (!function_exists('delete')) {
	function delete($table, $pkValue = NULL, $pkTableCT = NULL)
	{
		if (antiXss($pkValue) === false) {

			try {
				$ci = get_instance();

				$previous_values = NULL;

				if (!empty($pkValue)) {
					$pkTable = (empty($pkTableCT)) ? primary_field_name($table) : $pkTableCT;
					$previous_values = find($table, [$pkTable => $pkValue], 'row_array');
					$resCode = ($ci->db->delete($table, [$pkTable => $pkValue])) ? 200 : 400;
					$typeAction = 'delete';
				} else {
					$resCode = $ci->db->truncate($table) ? 200 : 400;
					$typeAction = 'truncate';
				}

				http_response_code($resCode);
				return [
					'action' => $typeAction,
					"resCode" => $resCode,
					"message" =>  message($resCode, 'delete'),
					"id" => $pkValue,
					"data" => $previous_values
				];
			} catch (Exception $e) {
				log_message('error', $e->getMessage());
				http_response_code(400);
				return [
					'action' => 'delete',
					'resCode' => 400,
					'message' => 'Something when wrong.',
					'id' => NULL,
					'data' => $e->getMessage(),
				];
			}
		} else {
			http_response_code(400);
			return [
				'action' => 'delete',
				'resCode' => 400,
				'message' => 'Protection against <b><i> Cross-site scripting (XSS) </i></b> activated!',
				'id' => NULL,
				'data' => [],
			];
		}
	}
}

if (!function_exists('deleteWithCondition')) {
	function deleteWithCondition($table, $condition = NULL)
	{
		if (antiXss($condition) === false) {

			try {
				$ci = get_instance();
				$previous_values = findAll($table, $condition);

				foreach ($condition as $key => $value) {
					if (is_numeric($key))
						$ci->db->where($value);
					else
						$ci->db->where($key, $value);
				}

				$resCode = ($ci->db->delete($table)) ? 200 : 400;

				http_response_code($resCode);
				return [
					"resCode" => $resCode,
					"message" =>  message($resCode, 'delete'),
					"id" => NULL,
					"data" => $previous_values
				];
			} catch (Exception $e) {
				http_response_code(400);
				return [
					'resCode' => 400,
					'message' => 'Something when wrong.',
					'id' => NULL,
					'data' => $e->getMessage(),
				];
			}
		} else {

			http_response_code(400);
			return [
				'resCode' => 400,
				'message' => 'Protection against <b><i> Cross-site scripting (XSS) </i></b> activated!',
				'id' => NULL,
				'data' => [],
			];
		}
	}
}

if (!function_exists('save')) {
	function save($table, $data, $enableXss = true)
	{
		$dataInsert = [];
		$pkColumnName = NULL;

		// get primary key
		$pkColumnName = primary_field_name($table);

		// search if data exist using PK
		$exist = (isset($data[$pkColumnName])) ? find($table, [$pkColumnName => $data[$pkColumnName]], 'row_array') : NULL;

		$dataInsert = (isAssociative($data)) ?  $data : ((!empty($exist)) ? $data[1] : merge($data[0], $data[1]));

		// // remove all column field that does't exist in db
		foreach ($dataInsert as $key => $value) {
			if (!isColumnExist($table, $key)) {
				unset($dataInsert[$key]);
			}
		}

		if (isset($dataInsert[$pkColumnName])) {
			unset($dataInsert[$pkColumnName]); // auto increment, no need to update or insert
		}

		if (!empty($exist)) {
			$id = $exist[$pkColumnName]; // get pk from table
			return update($table, $dataInsert, $id, $pkColumnName, $enableXss);
		} else {
			return insert($table, $dataInsert, $enableXss);
		}
	}
}

if (!function_exists('updateBatch')) {
	function updateBatch($table, $data, $pkColumnName)
	{
		if (antiXss($data) === false) {
			$pkColumnName = (empty($pkColumnName)) ? primary_field_name($table) : $pkColumnName;

			$filterData = [];
			foreach ($data as $columnData) {
				$sanitize = [];
				foreach ($columnData as $columnName => $value) {
					if (isColumnExist($table, $columnName)) {
						// check if empty field
						if ($value == '' || empty($value)) {
							$sanitize[$columnName] = $value != 0 || $value != '0' ? null : $value;
						} else {
							$sanitize[$columnName] = xssClean($value);
						}
					} else {
						unset($columnName);
					}
				}

				$sanitize['updated_at'] = timestamp();
				array_push($filterData, $sanitize);
			}

			try {;
				$ci = get_instance();
				$updateResult = $ci->db->update_batch($table, $filterData, $pkColumnName);
				$resCode = $updateResult > 0 ? 200 : 400;
				http_response_code($resCode);

				return [
					"action" => 'update',
					"resCode" => $resCode,
					"message" => isSuccess($resCode) ? $updateResult . ' data has been updated' : 'Please consult the system administrator',
					"totalUpdate" => $updateResult,
					"data" => $filterData
				];
			} catch (Exception $e) {
				http_response_code(400);
				return [
					"action" => 'update',
					'resCode' => 400,
					'message' => 'Something when wrong.',
					'id' => NULL,
					'data' => $e->getMessage(),
				];
			}
		} else {
			http_response_code(400);
			return [
				'action' => 'update',
				'resCode' => 400,
				'message' => 'Protection against <b><i> Cross-site scripting (XSS) </i></b> activated!',
				'id' => NULL,
				'data' => [],
			];
		}
	}
}

function sanitizeInput($dataArr, $table = NULL, $enableXss = true)
{
	$sanitize = [];

	if (isAssociative($dataArr)) {
		foreach ($dataArr as $columnName => $value) {
			if (isColumnExist($table, $columnName)) {
				// check if empty field
				if ($value == '' || empty($value)) {
					$sanitize[$columnName] = $value != 0 || $value != '0' ? null : $value;
				} else {
					$sanitize[$columnName] = $enableXss ? xssClean($value) : $value;
				}
			} else {
				unset($columnName);
			}
		}
	} else {
		foreach ($dataArr[0] as $columnName => $value) {
			if (isColumnExist($table, $columnName)) {
				// check if empty field
				if ($value == '' || empty($value)) {
					$sanitize[$columnName] = $value != 0 || $value != '0' ? null : $value;
				} else {
					$sanitize[$columnName] = $enableXss ? xssClean($value) : $value;
				}
			} else {
				unset($columnName);
			}
		}
	}

	return $sanitize;
}

function isTableExist($table)
{
	if (ci()->db->table_exists($table))
		return true;
	else
		return false;
}

function isColumnExist($table, $columnName)
{
	if (ci()->db->field_exists($columnName, $table))
		return true;
	else
		return false;
}

function allTableColumn($table)
{
	if (isTableExist($table))
		return ci()->db->list_fields($table);
	else
		return [];
}

function primary_field_name($table)
{
	$getPKTable = rawQuery("SHOW KEYS FROM {$table} WHERE Key_name = 'PRIMARY'");
	return $getPKTable[0]['Column_name'];
}

function hasMany($modelRef, $columnRef, $condition, $option = NULL, $with = NULL)
{
	$dataArr = $obj = $tableRef = '';

	if (!empty($condition)) {
		$fileName = APPPATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $modelRef . '.php';
		if (file_exists($fileName)) {
			require_once $fileName;
			$className = getClassNameFromFile($fileName);
			$obj = new $className;
			$tableRef = $obj->table;
			$tableRefPK = $obj->id;

			// check table
			if (isTableExist($tableRef)) {

				$whereCon = NULL;
				if (!empty($option)) {
					foreach ($option as $key => $value) {
						$whereCon .= "AND {$key}='$value'";
					}
				}

				$dataArr = rawQuery("SELECT * FROM $tableRef WHERE {$columnRef}='$condition' $whereCon");

				if (!empty($with)) {
					$dataRelation = $objStore = array(); // reset array
					foreach ($with as $functionName) {
						if (in_array($functionName, $obj->with)) {
							$functionCall = $functionName . 'Relation';

							// check if function up is exist
							if (method_exists($obj, $functionCall)) {
								if (!isMultidimension($dataArr)) {
									$dataRelation = $obj->$functionCall($dataArr);
									$dataStore = [
										$functionName => [
											'data' => (isset($dataRelation['data'])) ? $dataRelation['data'] : NULL,
											'objData' => (isset($dataRelation['obj'])) ? $dataRelation['obj'] : NULL,
										]
									];
									array_push($objStore, $dataStore);
									$dataArr[$functionName] = (isset($dataRelation['data'])) ? $dataRelation['data'] : NULL;
								} else {
									foreach ($dataArr as $key => $subdata) {
										$dataRelation = $obj->$functionCall($subdata);
										$dataStore = [
											$functionName => [
												'data' => (isset($dataRelation['data'])) ? $dataRelation['data'] : NULL,
												'objData' => (isset($dataRelation['obj'])) ? $dataRelation['obj'] : NULL,
											]
										];
										array_push($objStore, $dataStore);
										$dataArr[$key][$functionName] = (isset($dataRelation['data'])) ? $dataRelation['data'] : NULL;
									}
								}
							}
						}
					}
				}
			}
		}
	}

	$data = [
		'obj' => $obj,
		'table' => $tableRef,
		'column' => $columnRef,
		'id' => $condition,
		'data' => (!empty($dataArr)) ? xssClean($dataArr) : NULL,
	];

	return $data;
}

function hasOne($modelRef, $columnRef, $condition, $option = NULL, $with = NULL)
{
	$dataArr = $obj = $tableRef = '';

	if (!empty($condition)) {
		$fileName = APPPATH . DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $modelRef . '.php';
		if (file_exists($fileName)) {
			require_once $fileName;
			$className = getClassNameFromFile($fileName);
			$obj = new $className;

			$tableRef = $obj->table;
			$tableRefPK = $obj->id;

			// check table
			if (isTableExist($tableRef)) {

				$whereCon = NULL;
				if (!empty($option)) {
					foreach ($option as $key => $value) {
						$whereCon .= "AND {$key}='$value'";
					}
				}

				$dataArr = rawQuery("SELECT * FROM $tableRef WHERE {$columnRef}='$condition' $whereCon LIMIT 1", 'row_array');

				if (!empty($with)) {
					$dataRelation = $objStore = array(); // reset array
					foreach ($with as $functionName) {
						if (in_array($functionName, $obj->with)) {
							$functionCall = $functionName . 'Relation';

							// check if function up is exist
							if (method_exists($obj, $functionCall)) {
								if (!isMultidimension($dataArr)) {
									$dataRelation = $obj->$functionCall($dataArr);
									$dataStore = [
										$functionName => [
											'data' => (isset($dataRelation['data'])) ? $dataRelation['data'] : NULL,
											'objData' => (isset($dataRelation['obj'])) ? $dataRelation['obj'] : NULL,
										]
									];
									array_push($objStore, $dataStore);
									$dataArr[$functionName] = (isset($dataRelation['data'])) ? $dataRelation['data'] : NULL;
								} else {
									foreach ($dataArr as $key => $subdata) {
										$dataRelation = $obj->$functionCall($subdata);
										$dataStore = [
											$functionName => [
												'data' => (isset($dataRelation['data'])) ? $dataRelation['data'] : NULL,
												'objData' => (isset($dataRelation['obj'])) ? $dataRelation['obj'] : NULL,
											]
										];
										array_push($objStore, $dataStore);
										$dataArr[$key][$functionName] = (isset($dataRelation['data'])) ? $dataRelation['data'] : NULL;
									}
								}
							}
						}
					}
				}
			}
		}
	}

	$data = [
		'obj' => $obj,
		'table' => $tableRef,
		'column' => $columnRef,
		'id' => $condition,
		'data' => (!empty($dataArr)) ? xssClean($dataArr[0]) : NULL,
	];

	return $data;
}

if (!function_exists('isUpdateData')) {
	function isUpdateData($typeAction)
	{
		$action = (isArray($typeAction)) ? $typeAction['action'] : $typeAction;
		if ($action == 'update') {
			return true;
		} else {
			return false;
		}
	}
}

if (!function_exists('isInsertData')) {
	function isInsertData($typeAction)
	{
		$action = (isArray($typeAction)) ? $typeAction['action'] : $typeAction;
		if ($action == 'insert') {
			return true;
		} else {
			return false;
		}
	}
}

if (!function_exists('backupDb')) {
	function backupDb()
	{
		$CI = get_instance();
		$CI->load->helper('file', 'text', 'form', 'string', 'download');
		$CI->load->dbutil();
		library('email');
		library('zip');

		date_default_timezone_set('Asia/Kuala_Lumpur');
		$file_name2 = '_db';
		$file_name1 = date("d_m_Y_h_i_s_A");

		$db_backup_path = 'public' . DIRECTORY_SEPARATOR . 'browsers' . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR;

		if (!file_exists($db_backup_path)) {
			mkdir($db_backup_path, 0755, true);
		}

		$day_bkp = 1; // days of backup interval; 0 = at each login o visit 
		$n_db = 10; // max number of files backup to store 

		$date_ref = date("Y-m-d H:i:s", strtotime("-" . $day_bkp . " day"));
		$CI->db->where('created_at >', $date_ref);
		$CI->db->order_by('created_at', 'DESC');
		$CI->db->limit(1);
		$row = $CI->db->get('system_backup_db')->row();

		if (!($row)) {

			$file_name = $file_name1 . $file_name2 . '.zip';
			$prefs = array(
				'ignore' => array('system_backup_db'), // List of tables to omit from the backup
				'format' => 'zip', // gzip, zip, txt
				'filename' => $file_name, // File name - NEEDED ONLY WITH ZIP FILES
				'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
				'add_insert' => TRUE, // Whether to add INSERT data to backup file
				'newline' => "\n" // Newline character used in backup file
			);

			//Backup your entire database 
			$backup = $CI->dbutil->backup($prefs);
			$file = $db_backup_path . $file_name;

			try {
				if (write_file($file, $backup)) {

					$data = array(
						'backup_name' => $file_name,
						'backup_location' => $db_backup_path,
						'created_at' => timestamp()
					);

					insert('system_backup_db', $data);

					// delete file and row of backup table
					$n_row = $CI->db->count_all('system_backup_db');

					if ($n_row > $n_db) {
						$CI->db->limit($n_row - $n_db);
						$CI->db->order_by('created_at', 'ASC');
						$todelete = $CI->db->get('system_backup_db')->result();

						foreach ($todelete as $to_delete) {

							//delete row from db table
							$CI->db->where('backup_id', $to_delete->backup_id);
							$CI->db->delete('system_backup_db');

							// delete file from backup directory
							$file_del = $db_backup_path . $to_delete->backup_name;
							if (file_exists($file_del)) {
								unlink($file_del);
							}
						} // end foreach delete
					} // end if

					return [
						'resCode' => 200,
						'message' => 'Database backup ',
						'id' => NULL,
						'data' => $data,
					];
				} else {
					return [
						'resCode' => 400,
						'message' => 'Database backup unsuccessfully',
						'id' => NULL,
						'data' => [],
					];
				}
			} catch (Exception $e) {
				return [
					'resCode' => 400,
					'message' => 'Something when wrong.',
					'id' => NULL,
					'data' => $e->getMessage(),
				];
			}
		} else {
			return [
				'resCode' => 400,
				'message' => 'Database backup unsuccessfully',
				'id' => NULL,
				'data' => [],
			];
		}
	}
}

function getClassNameFromFile($filePathName)
{
	$php_code = file_get_contents($filePathName);

	$classes = array();
	$tokens = token_get_all($php_code);
	$count = count($tokens);
	for ($i = 2; $i < $count; $i++) {
		if (
			$tokens[$i - 2][0] == T_CLASS
			&& $tokens[$i - 1][0] == T_WHITESPACE
			&& $tokens[$i][0] == T_STRING
		) {

			$class_name = $tokens[$i][1];
			$classes[] = $class_name;
		}
	}

	return $classes[0];
}
