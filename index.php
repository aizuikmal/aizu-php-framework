<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(empty($_SERVER['HTTP_X_FORWARDED_PROTO']) || $_SERVER['HTTP_X_FORWARDED_PROTO'] == "off"){
    $redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirect);
    exit();
}

header('Access-Control-Allow-Origin: *');  

date_default_timezone_set('UTC');

include 'config.php';
include 'db.php';
include 'functions.php';
include 'auth.php';

$req = $_SERVER['REQUEST_URI'];
$req = explode('?',$req);

if(isset($req[1])){
	$req = $req[1];
	$req = explode('/',$req);
}else{
	$req = array();
}

if( isset($req[0]) && $req[0] == 'api'){
	include 'api.php';
}else{
	include 'template.php';
}

?>