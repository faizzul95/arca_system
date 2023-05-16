<?php

use voku\helper\AntiXSS;

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

if (!function_exists('input')) {
	function input($fieldName, $typeInput = 'post')
	{
		$ci = get_instance();
		return $ci->input->$typeInput($fieldName, TRUE); // with XSS Clean
	}
}

if (!function_exists('xssClean')) {
	function xssClean($data)
	{
		$ci = get_instance();
		return $ci->security->xss_clean($data);
	}
}

if (!function_exists('purify')) {
	function purify($post)
	{
		// reff : https://github.com/voku/anti-xss
		$antiXss = new AntiXSS();
		$antiXss->removeEvilAttributes(array('style')); // allow style-attributes
		return $antiXss->xss_clean($post);
	}
}

if (!function_exists('antiXss')) {
	function antiXss($data)
	{
		$antiXss = new AntiXSS();
		$antiXss->removeEvilAttributes(array('style')); // allow style-attributes

		$xssFound = false;
		if (isArray($data)) {
			foreach ($data as $post) {
				$antiXss->xss_clean($post);
				if ($antiXss->isXssFound()) {
					$xssFound = true;
				}
			}
		} else {
			$antiXss->xss_clean($data);
			if ($antiXss->isXssFound()) {
				$xssFound = true;
			}
		}

		return $xssFound;
	}
}

if (!function_exists('recaptchav2')) {
	function recaptchav2()
	{
		if (filter_var(env('RECAPTCHA_ENABLE'), FILTER_VALIDATE_BOOLEAN)) {
			library('recaptcha');
			return ci()->recaptcha->is_valid();
		} else {
			return ['success' => TRUE, 'error_message' => 'reCAPTCHA is currently disabled'];
		}
	}
}
