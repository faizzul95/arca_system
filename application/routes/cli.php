<?php

/**
 * CLI Routes
 *
 * This routes only will be available under a CLI environment
 */

// To enable Luthier-CI built-in cli commands
// uncomment the followings lines:

Luthier\Cli::maker();
Luthier\Cli::migrations();

// Backup Database to Google Drive
Route::cli('cron/database/{upload?}', 'CronController@BackupDatabase');
Route::cli('cron/system/{upload?}', 'CronController@BackupSystem');

Route::cli('JobController/listen', 'JobController@listen');
Route::cli('JobController/work', 'JobController@work');

Route::cli('jobs', 'JobController@work');

Route::cli('jobs/listen', 'JobController@listen');
Route::cli('jobs/launch', 'JobController@launch');
Route::cli('jobs/work', 'JobController@work');
Route::cli('jobs/single', 'JobController@single');

Route::cli('create/{type}/{fileName}/{tableName?}', function ($type, $name = NULL, $tableName = NULL) {

	$name = isset($name) ? trim($name) : NULL;
	$name = isset($name) ? ucfirst($name) : NULL;
	$tableName = isset($tableName) ? trim($tableName) : NULL;
	$column = NULL;

	if ($type == 'controller') {

		$fileNameGenerate = $name . 'Controller';
		$controllerStub = APPPATH . '' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'controller.stub';

		// // Load the stub file into a string
		$stubController = file_get_contents($controllerStub);

		// // Replace placeholders in the stub file with actual values
		// // For example, you might replace "%CLASS_NAME%" with the name of the class you want to generate
		$stubController = str_replace('%CLASS_CONTROLLER_NAME%', $fileNameGenerate, $stubController);
		$stubController = str_replace('%CLASS_NAME%', $name, $stubController);
		// // Generate the filename for the new controller
		$filename = APPPATH . 'controllers' . DIRECTORY_SEPARATOR . '' . $fileNameGenerate . '.php';
		// // Write the stub file to the new controller file
		file_put_contents($filename, $stubController);
	} else if ($type == 'model') {

		$fileNameGenerate = $name . '_model';
		$modelStub = APPPATH . '' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'model.stub';
		$table = '';
		$fillable = '';
		$pk = '';
		$dataTableEdit = '';

		if (!empty($tableName)) {
			if (isTableExist($tableName)) {
				$table = $tableName;
				$pk = primary_field_name($tableName);
				$allColumn = allTableColumn($table);

				if (!empty($allColumn)) {

					$removeFillable = [];
					$dataTableEditArr = [];
					array_push($removeFillable, $pk);

					if (in_array('created_at', $allColumn))
						array_push($removeFillable, 'created_at');

					if (in_array('updated_at', $allColumn))
						array_push($removeFillable, 'updated_at');

					if (in_array('deleted_at', $allColumn))
						array_push($removeFillable, 'deleted_at');

					$allColumn = array_diff($allColumn, $removeFillable);

					$fillable = "'" . implode("'," . PHP_EOL . " '", $allColumn) . "'";

					foreach ($allColumn as $key => $columnName) {
						$dataTableEdit .= '
					$serverside->edit("' . $columnName . '", function ($data) {
						return purify($data["' . $columnName . '"]);
					});
					';
					}

					$column = str_replace("'", "`", $fillable);
				}
			}
		}

		// // Load the stub file into a string
		$stubModel = file_get_contents($modelStub);
		$stubModel = str_replace('%CLASS_MODEL_NAME%', $name, $stubModel);
		$stubModel = str_replace('%TABLE%', $table, $stubModel);
		$stubModel = str_replace('%FILLABLE%', $fillable, $stubModel);
		$stubModel = str_replace('%COLUMN%', $column, $stubModel);
		$stubModel = str_replace('%PK%', $pk, $stubModel);
		$stubModel = str_replace('%DATATABLE_EDIT%', $dataTableEdit, $stubModel);
		$filename = APPPATH . 'models' . DIRECTORY_SEPARATOR . '' . $fileNameGenerate . '.php';
		file_put_contents($filename, $stubModel);
	}

	echo '';
	if (in_array($type, ['controller', 'model']))
		echo "Create $fileNameGenerate successfully\n\n";
	else
		echo "Error : The type only support for 'controller' or 'model', Your have enter : $type\n\n";
});

Route::cli('structure/{name}/{tableName?}', function ($name, $tableName = NULL) {

	$name = isset($name) ? trim($name) : NULL;
	$name = isset($name) ? ucfirst($name) : NULL;
	$tableName = isset($tableName) ? trim($tableName) : NULL;
	$column = NULL;

	$controllerStub = APPPATH . '' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'controller.stub';

	// // Load the stub file into a string
	$stubController = file_get_contents($controllerStub);

	// // Replace placeholders in the stub file with actual values
	// // For example, you might replace "%CLASS_NAME%" with the name of the class you want to generate
	$stubController = str_replace('%CLASS_CONTROLLER_NAME%', $name . 'Controller', $stubController);
	$stubController = str_replace('%CLASS_NAME%', $name, $stubController);
	// // Generate the filename for the new controller
	$filename = APPPATH . 'controllers' . DIRECTORY_SEPARATOR . '' . $name . 'Controller.php';
	// // Write the stub file to the new controller file
	file_put_contents($filename, $stubController);

	// ===================================================================

	$modelStub = APPPATH . '' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'model.stub';
	$table = '';
	$fillable = '';
	$pk = '';
	$dataTableEdit = '';

	if (!empty($tableName)) {
		if (isTableExist($tableName)) {
			$table = $tableName;
			$pk = primary_field_name($tableName);
			$allColumn = allTableColumn($table);

			if (!empty($allColumn)) {

				$removeFillable = [];
				$dataTableEditArr = [];
				array_push($removeFillable, $pk);

				if (in_array('created_at', $allColumn))
					array_push($removeFillable, 'created_at');

				if (in_array('updated_at', $allColumn))
					array_push($removeFillable, 'updated_at');

				if (in_array('deleted_at', $allColumn))
					array_push($removeFillable, 'deleted_at');

				$allColumn = array_diff($allColumn, $removeFillable);

				$fillable = "'" . implode("'," . PHP_EOL . " '", $allColumn) . "'";

				foreach ($allColumn as $key => $columnName) {
					$dataTableEdit .= '
					$serverside->edit("' . $columnName . '", function ($data) {
						return purify($data["' . $columnName . '"]);
					});
					';
				}

				$column = str_replace("'", "`", $fillable);
			}
		}
	}

	// // Load the stub file into a string
	$stubModel = file_get_contents($modelStub);
	$stubModel = str_replace('%CLASS_MODEL_NAME%', $name, $stubModel);
	$stubModel = str_replace('%TABLE%', $table, $stubModel);
	$stubModel = str_replace('%FILLABLE%', $fillable, $stubModel);
	$stubModel = str_replace('%COLUMN%', $column, $stubModel);
	$stubModel = str_replace('%PK%', $pk, $stubModel);
	$stubModel = str_replace('%DATATABLE_EDIT%', $dataTableEdit, $stubModel);

	// // Generate the filename for the new model
	$filename = APPPATH . 'models' . DIRECTORY_SEPARATOR . '' . $name . '_model.php';
	// // Write the stub file to the new model file
	file_put_contents($filename, $stubModel);

	echo "Create controller & models $name successfully\n\n";
});

Route::cli('clear/{type}', function ($type) {

	$folderCache = APPPATH . 'cache' . DIRECTORY_SEPARATOR . 'ci_session';
	$folderViewCache = APPPATH . 'cache' . DIRECTORY_SEPARATOR . 'blade_cache';
	$folderLogs = APPPATH . 'logs';

	if (in_array($type, ['all', 'cache', 'view', 'views', 'log', 'logs', 'optimize'])) {

		if ($type == 'cache') {
			if (is_dir($folderCache))
				deleteFolder($folderCache);
		} else if (in_array($type, ['view', 'views'])) {
			if (is_dir($folderViewCache))
				deleteFolder($folderViewCache);
		} else if (in_array($type, ['log', 'logs'])) {
			if (is_dir($folderLogs))
				deleteFolder($folderLogs);
		} else if ($type == 'all') {
			$folders = [];
			array_push($folders, $folderCache, $folderViewCache, $folderLogs);
			foreach ($folders as $key => $path) {
				if (is_dir($path))
					deleteFolder($path);
			}
			$type = 'Cache, views, logs';
		} else if ($type == 'optimize') {
			$folders = [];
			array_push($folders, $folderViewCache, $folderLogs);
			foreach ($folders as $key => $path) {
				if (is_dir($path))
					deleteFolder($path);
			}
			$type = 'views cache & logs';
		}

		$message = $type . ' folder clear successfully';
	} else {
		$message = "Error : The type only support for 'cache','view' or 'log' : Your enter " . $type;
	}

	echo $message . "\n\n";
});

Route::cli('optimize', function () {
	echo shell_exec('php struck clear optimize');
});

// schedule
Route::cli('schedule:run', function () {
	$CI = &get_instance();
	$CI->load->config('scheduler');
	$allNamespace = $CI->config->item('commands');

	if (hasData($allNamespace)) {
		$scheduler = cronScheduler();

		$scheduler->clearJobs(); // clear previous jobs
		foreach ($allNamespace as $namspaces) {
			app($namspaces)->handle($scheduler);
		}

		echo "Task Scheduling is running . . \n\n";
		// Reset the scheduler after a previous run
		$scheduler->resetRun()->run(); // now we can run it again
	} else {
		echo "No task/command to execute\n\n";
	}
});

Route::cli('schedule:list', function () {
	dd(cronScheduler()->getExecutedJobs());
});

Route::cli('schedule:fail', function () {
	$scheduler = cronScheduler();

	// get all failed jobs and select first
	$failedJob = $scheduler->getFailedJobs()[0];

	// exception that occurred during job
	$exception = $failedJob->getException();

	// job that failed
	$job = $failedJob->getJob();

	dd($failedJob, $exception, $job);
});

Route::cli('schedule:work', function () {
	$scheduler = cronScheduler();

	$CI = &get_instance();
	$CI->load->config('scheduler');
	$allNamespace = $CI->config->item('commands');

	if (hasData($allNamespace)) {
		$scheduler = cronScheduler();

		$scheduler->clearJobs(); // clear previous jobs
		foreach ($allNamespace as $namspaces) {
			app($namspaces)->handle($scheduler);
		}

		echo "Task Scheduling is running . . \n\n";
		$scheduler->work();
	} else {
		echo "No task/command to execute\n\n";
	}
});

Route::cli('maintenance/{type}', function ($type = 'on') {
	if (in_array($type, ['on', 'off'])) {
		$filename = 'maintenance.flag';

		if ($type == 'on') {
			if (!file_exists($filename)) {
				fopen($filename, 'w');
			};
			print "[" . timestamp('d/m/Y h:i A') . "]: System is currently offline!\n\n";
		} else if ($type == 'off') {
			if (file_exists($filename)) {
				unlink($filename);
			}
			print "[" . timestamp('d/m/Y h:i A') . "]:System is back online!\n\n";
		}
	}
});
