<?php

if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

const HTTP_OK = 200;
const HTTP_CREATED = 201;
const HTTP_NOT_MODIFIED = 304;
const HTTP_BAD_REQUEST = 400;
const HTTP_UNAUTHORIZED = 401;
const HTTP_FORBIDDEN = 403;
const HTTP_NOT_FOUND = 404;
const HTTP_METHOD_NOT_ALLOWED = 405;
const HTTP_NOT_ACCEPTABLE = 406;
const HTTP_UNPROCESSABLE_ENTITY = 422;
const HTTP_LIMIT_REQUEST = 429;
const HTTP_INTERNAL_ERROR = 500;
const HTTP_SERVICE_UNAVAILABLE = 503;

function response($data, $response_code = HTTP_OK)
{
	http_response_code($response_code);
	header('Content-Type: application/json');
	echo json_encode($data, JSON_PRETTY_PRINT);
	exit;
}
