<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('csrfKey')) {
    function csrfKey()
    {
        $ci = get_instance();
        $ci->security->get_csrf_token_name();

        $csrf = array(
            'name' => $ci->security->get_csrf_token_name(),
            'hash' => $ci->security->get_csrf_hash()
        );
    }
}

if (!function_exists('csrfName')) {
    function csrfName()
    {
        $ci = get_instance();
        return $ci->security->get_csrf_token_name();
    }
}

if (!function_exists('csrfValue')) {
    function csrfValue()
    {
        $ci = get_instance();
        return $ci->security->get_csrf_hash();
    }
}

if (!function_exists('updateCsrf')) {
    function updateCsrf()
    {
        $ci = get_instance();
        return $ci->security->get_csrf_hash();
    }
}
