<?php
require 'config.php';
require 'Classes/Autoloader.php';
require '../vendor/autoload.php';

if(!isset($_REQUEST['params']) && !isset($_REQUEST['source_url'])){
	die('');
}

if(!preg_match('/^http/',$_REQUEST['source_url']) ){
	die('');
}
$response = new Response();
