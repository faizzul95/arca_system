
<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Throttle Status Enable
|--------------------------------------------------------------------------
|
| Set [TRUE/FALSE] to set Throttle.
|
*/
$config['throttle_enable'] = [
	'rate_limiting' => TRUE,
	'connection_limiting' => FALSE,
	'bandwidth_limiting' => FALSE,
];

/*
|--------------------------------------------------------------------------
| Throttle Override Settings
|--------------------------------------------------------------------------
|
| Set TRUE to use override the throttle settings below or set FALSE to use default
|
*/
$config['throttle_override'] = [
	'rate_config_override' => TRUE,
	'connection_config_override' => FALSE,
	'bandwidth_config_override' => FALSE,
];

/*
|--------------------------------------------------------------------------
| Throttle Custom Configuration
|--------------------------------------------------------------------------
|
| Define all Custom Limitting configuration
*/
$config['throttle_settings'] = [

	'rate_custom' => [
		'directory' => 'rate', // set path directory to store cache
		'request' => 100, // set default limit request for client
		'interval' => 60, // set the interval before request count will be reset in seconds (eg : 5 minute = 300)
		'warning' => 20, // set maximum message "Too many requests" before temporary blocked the IP
		'blocked' => 15, // set maximum "Temporary blocked" count before permanently block IP
		'blocked_temporary_time' => [
			30, // 30 seconds for 1st temporary blocked
			30, // 30 seconds for 2nd temporary blocked
			45, // 45 seconds for 3rd temporary blocked
			60, // 1 minute for 4th temporary blocked
			120, // 2 minute for 5th temporary blocked
			180, // 3 minute for 6th temporary blocked
			300, // 5 minute for 7rd temporary blocked
			600, // 10 minute for 8rd temporary blocked
			900, // 15 minute for 9th temporary blocked
			1800, // 30 minute for 10th temporary blocked
			3600, // 1 hour for 11th temporary blocked
			86400, // 1 day for 12th temporary blocked
			604800, // 1 week for 13th temporary blocked
			2592000, // 1 month for 14th temporary blocked
			31536000, // 1 year for 15th temporary blocked
		], // set increase limit time each time, default is 30 seconds
		'limit_increase' => 0, // set the increase request (will extend then default) per temporary blocked. Formula : request + (limit_increase x blocked)
	],

	'connection_custom' => [
		'directory' => 'connection', // set path directory to store cache,
		'limit' => 30, // set total connection to 30 users
		'interval' => 60, // set the interval before request count will be reset in seconds (eg : 5 minute = 300)
	],

	'bandwidth_custom' => [
		'directory' => 'bandwidth', // set path directory to store cache,
		'size' => 5, // set max total download size in MB
		'interval' => 60, // set the interval before request count will be reset in seconds (eg : 5 minute = 300)
	]
];

/*
|--------------------------------------------------------------------------
| Throttle URL Exclusions
|--------------------------------------------------------------------------
|
| Define all route to exclude for limiting (use for all limiting)
*/
$config['throttle_exclude_url'] = [
	// 'auth/sign-in',
	'auth/logout',
];

/*
|--------------------------------------------------------------------------
| Throttle IP Exclusions
|--------------------------------------------------------------------------
|
| Define all IP to exclude for limiting (use for all limiting)
*/
$config['throttle_exclude_ips'] = [
	// '127.0.0.1',
	// '::1'
];
