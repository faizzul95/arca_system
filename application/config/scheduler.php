
<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Command
|--------------------------------------------------------------------------
|
| Define all files (namespace) to execute.
|
*/
$config['commands'] = [
	'App\services\general\commands\StatusActivityComplete',
	'App\services\general\commands\CurrentAcademicYear',
	'App\services\general\commands\BackupSystemDatabase',
];
