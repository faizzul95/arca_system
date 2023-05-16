<?php

defined('BASEPATH') or exit('No direct script access allowed');

$config['client_id'] = '< YOUR-CLIENT-ID >';
$config['client_secret'] = '< YOUR-CLIENT-SECRET >';
$config['redirect_uri'] = url('cron/backup');
$config['credentials_file_path'] = APPPATH . 'credentials.json';
$config['folder_id'] = [
	'database' => '< YOUR-FOLDER-ID >',
	'system' => '< YOUR-FOLDER-ID >',
];
