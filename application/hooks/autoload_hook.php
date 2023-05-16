<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

function autoload()
{
    spl_autoload_register(function ($class) {
        // if (strpos($class, 'CI_') !== 0 && file_exists(APPPATH . 'core/' . $class . '.php')) {
        //     require_once APPPATH . 'core/' . $class . '.php';
        // }
        if (substr($class, 0, 3) !== 'CI_') {
            if (file_exists($file = APPPATH . 'core/' . $class . '.php')) {
                require_once $file;
            }
        }
    });
}
