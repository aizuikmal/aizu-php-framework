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
include 'helper.php';
include 'auth.php';
include 'app.php';

$uri = $_SERVER['REQUEST_URI'];
$uri = ($uri) ? explode('/', $uri) : false; //$uri starts at [1]

// $content = '';
// $content .= date('j-M-Y H:i:s') . ' | ';
// $content .= $xuser['username'] . ' | ';
// $content .= $_SERVER['HTTP_CF_CONNECTING_IP'] . ' | ';
// $content .= $_SERVER["REQUEST_METHOD"] . ' | ';
// $content .= $_SERVER['REQUEST_URI'] . ' | ';
// $content .= $_SERVER['HTTP_USER_AGENT'] . ' | ';
// $content .= "\n";

// $filename = 'visits.log';
// $fwrite = fopen($filename, 'a') or die("can't open file");
// fwrite($fwrite, $content);
// fclose($fwrite);

if(uri(1) == 'api'){
	include 'api.php';
}else{
	include 'template.php';
}

?>