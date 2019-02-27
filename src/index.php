<?php
require 'config.php';
require 'Classes/Autoloader.php';
require '../vendor/autoload.php';

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

