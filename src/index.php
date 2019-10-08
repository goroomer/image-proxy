<?php
require 'config.php';
require 'Classes/Autoloader.php';
require '../vendor/autoload.php';

function exception_error_handler($severity, $message, $file, $line) {
    $fatal_codes = array(E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR);
    if(!in_array($severity, $fatal_codes)){
        return;
    }
    $response = new ErrorResponse($severity, $message);
}

set_error_handler("exception_error_handler");

$url_parts = explode("/http",$_SERVER["REQUEST_URI"]);

if(sizeof($url_parts) < 2){
	die('');
}

$params_parts = explode('/', $url_parts[0]);

$GLOBALS['params'] = $params_parts[sizeof($params_parts)-1];
$GLOBALS['source_url'] = "http".$url_parts[1];

if(!isset($GLOBALS['params']) && !isset($GLOBALS['source_url'])){
	die('');
}

$response = new Response();

