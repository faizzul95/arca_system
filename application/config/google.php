<?php

defined('BASEPATH') or exit('No direct script access allowed');

// google auth
$config['client_id_auth'] = '< YOUR-OAUTH-CLIENT-ID >';
$config['cookie_policy'] = 'single_host_origin';
$config['redirect_uri_auth'] = '< YOUR-REDIRECT-URL >';

// google drive
$config['client_id'] = '< YOUR-CLIENT-ID >';
$config['client_secret'] = '< YOUR-CLIENT-SECRET >';
$config['redirect_uri'] = url('cron/backup');
$config['credentials_file_path'] = APPPATH . 'credentials.json';
$config['folder_id'] = [
	'database' => '< YOUR-FOLDER-ID >',
	'system' => '< YOUR-FOLDER-ID >',
];
