<?php

use Ramsey\Uuid\Uuid;
use GO\Scheduler; // reff : https://github.com/peppeocchi/php-cron-scheduler

if (!function_exists('currency_format')) {
	function currency_format($amount)
	{
		return number_format((float)$amount, 2, '.', ',');
	}
}

if (!function_exists('dateDiff')) {
	function dateDiff($d1, $d2)
	{
		return round(abs(strtotime($d1) - strtotime($d2)) / 86400);
	}
}

if (!function_exists('timeDiff')) {
	function timeDiff($t1, $t2)
	{
		return round(abs(strtotime($t1) - strtotime($t2)) / 60);
	}
}

if (!function_exists('encode_base64')) {
	function encode_base64($sData = NULL)
	{
		if (!empty($sData)) {
			$sBase64 = base64_encode($sData);
			return strtr($sBase64, '+/', '-_');
		} else {
			return '';
		}
	}
}

if (!function_exists('decode_base64')) {
	function decode_base64($sData = NULL)
	{
		if (!empty($sData)) {
			$sBase64 = strtr($sData, '-_', '+/');
			return base64_decode($sBase64);
		} else {
			return '';
		}
	}
}

if (!function_exists('encodeID')) {
	function encodeID($id = NULL, $count = 25)
	{
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$uniqueURL = substr(str_shuffle($permitted_chars), 0, $count) . '' . $id . '' . substr(str_shuffle($permitted_chars), 0, $count);
		return encode_base64($uniqueURL);
	}
}

if (!function_exists('decodeID')) {
	function decodeID($id = NULL, $count = 25)
	{
		$id = decode_base64($id);
		return substr($id, $count, -$count);
	}
}

if (!function_exists('isMobileDevice')) {

	function isMobileDevice()
	{
		if (!empty($_SERVER['HTTP_USER_AGENT'])) {
			return preg_match("/(android|webos|avantgo|iphone|ipad|ipod|blackberry|iemobile|bolt|boost|cricket|docomo|fone|hiptop|mini|opera mini|kitkat|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
		};

		return false;
	}
}

if (!function_exists('genCode')) {
	function genCode($name, $codeList = array(), $codeType = 'S', $codeLength = 4, $numLength = 4, $counter = 1)
	{
		$code = '';

		$nameArr = explode(' ', strtoupper($name));
		$wordIdx = array();
		$word = 0;
		while ($codeLength != strlen($code)) {
			if ($word >= count($nameArr)) {
				$word = 0;
			}
			if (!isset($wordIdx[$word])) {
				$wordIdx[$word] = 0;
			}
			if ($wordIdx[$word] >= strlen($nameArr[$word])) {
				$wordIdx[$word] = 0;
			}

			$code .= $nameArr[$word][$wordIdx[$word]];
			$wordIdx[$word]++;
			$word++;
		}

		$found = false;
		while (!$found) {
			$tempcode = $codeType . $code . str_pad($counter, $numLength, '0', STR_PAD_LEFT);

			if (!in_array($tempcode, $codeList)) {
				$code = $tempcode;
				$found = true;
			}
			$counter++;
		}

		return $code;
	}
}

if (!function_exists('deleteFolder')) {
	function deleteFolder($folder, $excludedFiles = [])
	{
		$excFile = array_merge(['index.html', '.htaccess'], $excludedFiles);

		if (is_dir($folder)) {
			$files = scandir($folder);
			foreach ($files as $file) {
				if ($file != '.' && $file != '..' && !in_array($file, $excFile)) {
					$filePath = $folder . DIRECTORY_SEPARATOR . $file;
					if (is_dir($filePath)) {
						deleteFolder($filePath, $excFile);
					} else {
						unlink($filePath);
					}
				}
			}

			// check if folder is empty then remove
			if (count(glob("$folder/*")) === 0) {
				rmdir($folder);
			}
		}
	}
}

if (!function_exists('app')) {
	function app($namespace)
	{
		return new class($namespace)
		{
			private $namespace;

			public function __construct($namespace)
			{
				$this->namespace = $namespace;
			}

			public function __call($method, $args)
			{
				$class = $this->namespace;
				$obj = new $class();

				try {
					if (method_exists($obj, $method)) {
						return call_user_func_array(array($obj, $method), $args);
					} else {
						throw new Exception("Method $method does not exist");
					}
				} catch (Exception $e) {
					// handle the error
					return $e->getMessage();
				}
			}
		};
	}
}

// GENERATE UUIDv4

if (!function_exists('uuid')) {
	function uuid($code = NULL)
	{
		$uuid = Uuid::uuid4();
		return $uuid->toString();
	}
}

// Create scheduler object
if (!function_exists('cronScheduler')) {
	function cronScheduler()
	{
		return new Scheduler(); // Create a new scheduler
	}
}

if (!function_exists('recaptchaDiv')) {
	function recaptchaDiv($size = 'invisible', $callback = 'setResponse')
	{
		if (filter_var(env('RECAPTCHA_ENABLE'), FILTER_VALIDATE_BOOLEAN)) {
			$sitekey = env('RECAPTCHA_KEY');
			return '<div class="g-recaptcha" data-sitekey="' . $sitekey . '" data-size="' . $size . '" data-callback="' . $callback . '"></div>
					<input type="hidden" id="captcha-response" name="g-recaptcha-response" class="form-control" />';
		} else {
			return NULL;
		}
	}
}

if (!function_exists('gapiConfig')) {
	function gapiConfig()
	{
		return json_encode([
			'client_id' => '< YOUR-CLIENT-ID >',
			'cookiepolicy' => 'single_host_origin',
			'fetch_basic_profile' => true,
			'redirect_uri' => '< YOUR-REDIRECT-URL >',
		]);
	}
}

if (!function_exists('truncate')) {
	function truncate($string, $length, $suffix = '...')
	{
		// If the string is shorter than or equal to the maximum length, return the string as is
		if (strlen($string) <= $length) {
			return $string;
		}

		// Truncate the string to the specified length
		$truncated = substr($string, 0, $length);

		// If the truncated string ends with a space, remove the space
		if (substr($truncated, -1) == ' ') {
			$truncated = substr($truncated, 0, -1);
		}

		// Append the suffix to the truncated string
		$truncated .= $suffix;

		return $truncated;
	}
}
