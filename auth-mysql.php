<?php

global $xuser;

if(isset($_GET['logout'])){
    clear_cookies();
    header('Location: '.$app_dir.'?msg=logout');
}

if( post_param('submit') ){ do_login(); exit(); }

$cook_user = (isset($_COOKIE['rtga_user'])) ? make_slurp(base64_decode($_COOKIE['rtga_user'])) : false;
$cook_hash = (isset($_COOKIE['rtga_hash'])) ? make_slurp(base64_decode($_COOKIE['rtga_hash'])) : false;
$cook_login = (isset($_COOKIE['rtga_login'])) ? make_slurp(base64_decode($_COOKIE['rtga_login'])) : false;

if(!$cook_user || !$cook_hash || !$cook_login){ show_login(); exit(); }

$check_db_auth = $db->get_row("SELECT `id`,`password`,`email` FROM `users` WHERE `username` = '$cook_user';",ARRAY_A);
if(md5($cook_user.$check_db_auth['id'].$check_db_auth['password'].$salt) != $cook_hash){ show_login(); exit(); }

//update the time, so we won't logged out if keep refreshing
$time_ttl = time()+86400;
setcookie('rtga_user', $_COOKIE['rtga_user'], $time_ttl, '/', false);
setcookie('rtga_hash', $_COOKIE['rtga_hash'], $time_ttl, '/', false);
setcookie('rtga_login', $_COOKIE['rtga_login'], $time_ttl, '/', false);

$xuser = array(
	'id' => $check_db_auth['id'],
	'username' => $cook_user,
	'email' => $check_db_auth['email']
);

function do_login(){
global $app_dir, $db, $salt;

	$form_username = post_param('username');
	$form_password = md5(post_param('password'));
	
	$db_auth = $db->get_row("SELECT `id` FROM `users` WHERE `username` = '$form_username';",ARRAY_A);
	$allow = false;
	if(is_array($db_auth) && is_numeric($db_auth['id'])){
		$db_auth_id = $db_auth['id'];
        $db_auth_pass = $db->get_row("SELECT `password` FROM `users` WHERE `id` = '$db_auth_id';",ARRAY_A);
		if($db_auth_pass['password'] == $form_password){
			$allow = true;
		}
	}

	if($allow){

		$cook_set_user = $form_username;
		$cook_set_hash = md5($form_username.$db_auth_id.$db_auth_pass['password'].$salt);
		$cook_set_login = time();

		$time_ttl = time()+86400;

		setcookie('rtga_user', base64_encode($cook_set_user), $time_ttl, '/', false);
		setcookie('rtga_hash', base64_encode($cook_set_hash), $time_ttl, '/', false);
		setcookie('rtga_login', base64_encode($cook_set_login), $time_ttl, '/', false);

		header('Location: '.$app_dir);
		exit();

	}else{
	    header('Location: '.$app_dir.'?msg=forbidden_login');
	}

}

function clear_cookies(){
    unset($_COOKIE['rtga_user']);
	unset($_COOKIE['rtga_hash']);
	unset($_COOKIE['rtga_login']);
	setcookie('rtga_user', false, time()-86400, '/', false);
	setcookie('rtga_hash', false, time()-86400, '/', false);
	setcookie('rtga_login', false, time()-86400, '/', false);
}

function show_login(){
    
    clear_cookies();

    include 'template-login.php';

    /*
    ?>
	<form method="POST">
		Username: <input type="text" name="username" value="aizu"><br />
		Password: <input type="text" name="password" value="ipo"><br />
		<input type="submit" name="submit">
	</form>
	<?
    */

}

