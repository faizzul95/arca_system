<?php
function redirect_ssl()
{
	$CI = &get_instance();
	$class = $CI->router->fetch_class();
	$exclude =  array(' ');

	if (env('ENVIRONMENT') == 'production' || env('ENVIRONMENT') == 'staging') {
		if (!in_array($class, $exclude)) {
			// redirect ke SSL (HTTP ke HTTPS)
			$CI->config->config['base_url'] = str_replace('http://', 'https://', $CI->config->config['base_url']);
			if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 443) redirect($CI->uri->uri_string());
		} else {
			// redirect tanpa SSL (HTTPS ke HTTP)
			$CI->config->config['base_url'] = str_replace('https://', 'http://', $CI->config->config['base_url']);
			if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443) redirect($CI->uri->uri_string());
		}
	}
}
