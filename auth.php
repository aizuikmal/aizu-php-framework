<?php

if(isset($_GET['code'])){ login_google(); exit(); }
if(isset($_GET['logout'])){
	if(isset($_SESSION['access_token'])){ unset($_SESSION['access_token']); }
	setcookie('rtga_user', false, time()-86400, '/', false);
	setcookie('rtga_hash', false, time()-86400, '/', false);
	setcookie('rtga_login', false, time()-86400, '/', false);
	header('Location: '.$app_dir.'?msg=logout');
}

$cook_user = false;
$cook_hash = false;
$cook_login = false;

//check local cookie.
if(isset($_COOKIE['rtga_user'])){ $cook_user = base64_decode($_COOKIE['rtga_user']); }
if(isset($_COOKIE['rtga_hash'])){ $cook_hash = base64_decode($_COOKIE['rtga_hash']); }
if(isset($_COOKIE['rtga_login'])){ $cook_login = base64_decode($_COOKIE['rtga_login']); }

if(!$cook_user || !$cook_hash || !$cook_login){ login_google(); exit(); }

if(md5($cook_user.'4837723928753') != $cook_hash){ login_google(); exit(); }

//echo '<pre>';
$cook_user_data = unserialize($cook_user);
$cook_user_data = casttoclass('stdClass', $cook_user_data);
$cook_user_data =  (array) $cook_user_data;
$xuser = $cook_user_data;
//print_r($cook_user_data);
//echo '</pre>';


function casttoclass($class, $object){
	return unserialize(preg_replace('/^O:\d+:"[^"]++"/', 'O:' . strlen($class) . ':"' . $class . '"', serialize($object)));
}

function login_google(){
global $app_dir, $oauth_clientid, $oauth_secretkey, $oauth_redirect, $db;

	require_once ('library/Google/autoload.php');

	$client_id = $oauth_clientid; 
	$client_secret = $oauth_secretkey;
	$redirect_uri = $oauth_redirect;

	$client = new Google_Client();
	$client->setClientId($client_id);
	$client->setClientSecret($client_secret);
	$client->setRedirectUri($redirect_uri);
	$client->setApprovalPrompt('auto');
	$client->addScope("email");
	$client->addScope("profile");

	$service = new Google_Service_Oauth2($client);

	$uri = $_SERVER['REQUEST_URI'];
	$uri_arr = ($uri) ? explode('/', $uri) : false;

	//pre($uri_arr);

	//if( substr($uri_arr[1],0,6) == '?code=' AND isset($_GET['code'])){
	if( isset($_GET['goog_auth_login']) AND isset($_GET['code']) ){

		$client->authenticate($_GET['code']);
		$access_token = $client->getAccessToken();
		$_SESSION['access_token'] = $access_token;
		$access_token_json = json_decode($access_token);
		
		/*
		echo '<pre>'; print_r(json_decode($access_token));
		[access_token] => ya29.CjMEA0brxBe65c2xl5hr0feZVYhfAJ4m9HpGgEXzfhub-l-jpQxAV7FhX4anRNebKxPpvQ4
		[token_type] => Bearer
		[expires_in] => 3600
		[id_token] => eyJhbGciOiJSUzI1NiIsImtpZCI6ImQyM2YwYTEzZjY1NWQxMjc3ZWJiZTViODE3YmIyNWYxODViNWVmNjQifQ.eyJpc3MiOiJhY2NvdW50cy5nb29nbGUuY29tIiwiYXRfaGFzaCI6ImctYlRCRUsxc0MzMmozWnhzcDJMM2ciLCJhdWQiOiI2ODI5MzY5NzM4MzktZm5rZmY4NXRqaWluMmlzODQwMTN2aGlkMGNnYjdhN3IuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDY5OTY0OTk3NTcxMDMzMDYxMzAiLCJlbWFpbF92ZXJpZmllZCI6dHJ1ZSwiYXpwIjoiNjgyOTM2OTczODM5LWZua2ZmODV0amlpbjJpczg0MDEzdmhpZDBjZ2I3YTdyLmFwcHMuZ29vZ2xldXNlcmNvbnRlbnQuY29tIiwiZW1haWwiOiJhaXp1LmlrbWFsQGdtYWlsLmNvbSIsImlhdCI6MTQ2NjE2MTU0MCwiZXhwIjoxNDY2MTY1MTQwfQ.gcU2K48o1YzcwVRRJd4vtxKDBO4fzBKv5CItYqAngOhBWi7nrLuYWf3qkZ3tDUy2jSLm1gWWbxmODxO0j5Z3BG_20OV4-do6rOB14PCcULKrY9_AF6VDa6s61rdP4YmHfqIBlS2XMjzm0nv16k9skw1my4XlCripBCPpXwvUG_Kyy7TSkFmSh5ALhBfDBPbcDL1pCE3GVAJbg8T-vBVLHBeh4-IN8MMYwrw4dHs_epClIMJBKXMihJPCbzKL5T4g7uJyn0Q7jSsa-P2r92OklxT0I_BP3zRdT2j9GlLxZGCGX849oJC7gKhJUvUseyPfzmmePfZ2l1dRzv0t97KgPA
		[created] => 1466161540
		*/


		$allow = false;
		$allow_from_domain = false;

		if($access_token_json->expires_in >= 100){
			$xuser = $service->userinfo->get();

			$domain = substr(strrchr($xuser['email'], "@"), 1);

			if($domain == 'malaysiakini.com'){ $allow = true; $allow_from_domain = $domain; }
			//if($xuser['email'] == 'aizu@malaysiakini.com'){ $allow = true; }

			$email_to_check = $xuser['email'];
			if(filter_var($email_to_check, FILTER_VALIDATE_EMAIL)){
				$db_auth = $db->get_row("SELECT * FROM `users` WHERE `email` = '$email_to_check';",ARRAY_A);

				if($db_auth['email'] == $email_to_check){

					$xuser->type = $db_auth['type'];
					//$xuser->channel = $db_auth['channel'];
					$xuser->domain = $domain;
					$xuser->id = $db_auth['id'];
					$xuser_id = $db_auth['id'];

					$allow = true;

					//update last login for tracking
					db_update('users',array('last_login' => time())," `id` = $xuser_id");

				}else{
					//not in db

					if($allow AND $allow_from_domain){
						//allowed from domain

						//create user
						$user_insert_sql = array(
							'email' => $xuser['email'],
							'name' => $xuser['name'],
							'type' => 'user_by_domain',
							'last_login' => time()
						);
						db_insert('users',$user_insert_sql);
						//$db->debug();

						sleep(2);
						$email_to_check = $xuser['email'];
						$db_auth = $db->get_row("SELECT * FROM `users` WHERE `email` = '$email_to_check';",ARRAY_A);

						$xuser->type = $db_auth['type'];
						//$xuser->channel = $db_auth['channel'];
						$xuser->domain = $domain;
						$xuser->id = $db_auth['id'];

					}else{
						
						unset($_SESSION['access_token']);
						$client->revokeToken();
						header('Location: '.$app_dir.'?msg=invalid_email_address');

						exit();
						//exit('Error login - 4738');
					}

				}
			}

			// $db->debug();
			// exit();

			//if($domain == 'malaysiakini.com' || $domain == 'gmail.com'){
			if($allow){ //allow to login

				$cook_set_user = serialize($xuser);
				$cook_set_hash = md5($cook_set_user.'4837723928753');
				$cook_set_login = time();

				$time_ttl = time()+86400;

				if($xuser['email'] == 'aizu@malaysiakini.com'){ $time_ttl = time()+8640000; } // 100 days time

				setcookie('rtga_user', base64_encode($cook_set_user), $time_ttl, '/', false);
				setcookie('rtga_hash', base64_encode($cook_set_hash), $time_ttl, '/', false);
				setcookie('rtga_login', base64_encode($cook_set_login), $time_ttl, '/', false);

				header('Location: '.$app_dir);
				exit();			

			}else{
				unset($_SESSION['access_token']);
				$client->revokeToken();
				//header('Location: /?msg=invalid_login_domain');
				header('Location: '.$app_dir.'?msg=forbidden_login');
			}
		}else{
			unset($_SESSION['access_token']);
			$client->revokeToken();
    		header('Location: '.$app_dir.'?msg=invalid_auth_token');
		}


	}else{

		$authUrl = $client->createAuthUrl();

		if(isset($authUrl)){
			include 'template-login.php';
	  		exit();
	  	}

  	}

}



