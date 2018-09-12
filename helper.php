<?php

//small helper function to return $uri. So the $uri always false if no value. Don't have to put isset app code logic
function uri($pos){
global $uri;
    return (isset($uri[$pos])) ? $uri[$pos] : false ;
}

//another small helper, to avoid 'Undefined variable' error.
function show($arr,$key){
    $return = false;
    if(isset($$arr)){
        $return = (isset($$arr[$key])) ? $a : false;
    }
    return $return;
}

function cache_get($key){
global $cache_dir;
    $filename = $cache_dir.'/'.base64_encode($key).'.cache';
    if(is_file($filename)){
        $payload = implode('', file($filename));
        return $payload;
    }else{
        return false;
    }
}

function cache_set($key,$content){
global $cache_dir;
    $filename = $cache_dir.'/'.base64_encode($key).'.cache';
    $fwrite = fopen($filename, 'w') or die("can't open file");
    fwrite($fwrite, $content);
    fclose($fwrite);
}


function nice_number($n) {
    // first strip any formatting;
    $n = (0+str_replace(",","",$n));
    
    // is this a number?
    if(!is_numeric($n)) return false;
    
    // now filter it;
    if($n>1000000000000) return round(($n/1000000000000),1).' T';
    else if($n>1000000000) return round(($n/1000000000),1).' B';
    else if($n>1000000) return round(($n/1000000),1).' M';
    else if($n>1000) return round(($n/1000),1).' k';
    
    return number_format($n);
}


function get_param($paramiter_name){
  $paramiter_name = str_replace('$', '', $paramiter_name);
  $paramiter_value = "";
  if(isset($_POST[$paramiter_name]))
    $paramiter_value = $_POST[$paramiter_name];
  else if(isset($_GET[$paramiter_name]))
    $paramiter_value = $_GET[$paramiter_name];

  if (!get_magic_quotes_gpc()){$paramiter_value = addslashes($paramiter_value);}else{$paramiter_value = $paramiter_value;}
  return trim($paramiter_value);
}

function post_param($paramiter_name){
  $paramiter_name = str_replace('$', '', $paramiter_name);
  $paramiter_value = "";
  if(isset($_POST[$paramiter_name]))
    $paramiter_value = $_POST[$paramiter_name];

  //if (!get_magic_quotes_gpc()){$paramiter_value = addslashes($paramiter_value);}else{$paramiter_value = $paramiter_value;}
  return trim($paramiter_value);
}

function dmy2time($the_raw_time){
/* time format accept ---> dd/mm/yyyy */

    $the_raw_time_arr = explode('/',$the_raw_time);
    /*
    $the_raw_time_arr[0] - dd
    $the_raw_time_arr[1] - mm
    $the_raw_time_arr[2] - yyyy
    */

    return(@mktime(0,0,0,$the_raw_time_arr[1],$the_raw_time_arr[0],$the_raw_time_arr[2]));
}

function pre($string_to_pre = '',$height=false){

	if($string_to_pre){
	
		// if($ob){
		
		// 	ob_start();
		// 		echo '<pre>';
		//         print_r($string_to_pre);
		//         echo '</pre>';
		// 	$the_return = ob_get_contents();
		// 	ob_end_clean();
		// 	return $the_return;
		
		// }else{
            if(is_numeric($height)){
                echo '<pre style="border:1px solid #333; padding:10px; height:'.$height.'px; overflow:auto;">';
            }else{
                echo '<pre>';
            }
			print_r($string_to_pre);
			echo '</pre>';
		//}
	
	}
}

function prec($sting_to_pre = ''){

    if($sting_to_pre){
    
        print_r($sting_to_pre);
        echo "\n";
    
    }
}

function short_title($a,$char_limit=43){

	if(strlen($a) >= $char_limit){
		$a = substr($a,0,$char_limit) . '...';
	}

	return $a;

}

function make_slurp($a){

	$b = strip_tags($a);
	$b = strtolower($b);
	$b = str_replace(' ','-',$b);
	$b = str_replace('/','',$b);
	$b = str_replace('!','',$b);
	$b = str_replace('~','',$b);
	$b = str_replace('`','',$b);
	$b = str_replace('@','',$b);
	$b = str_replace('#','',$b);
	$b = str_replace('$','',$b);
	$b = str_replace('%','',$b);
	$b = str_replace('^','',$b);
	$b = str_replace('&','',$b);
	$b = str_replace('*','',$b);
	$b = str_replace('(','',$b);
	$b = str_replace(')','',$b);
	//$b = str_replace('_','',$b);
	$b = str_replace('+','',$b);
	$b = str_replace('=','',$b);
	$b = str_replace('{','',$b);
	$b = str_replace('[','',$b);
	$b = str_replace('}','',$b);
	$b = str_replace(']','',$b);
	$b = str_replace('\\','',$b);
	$b = str_replace('|','',$b);
	$b = str_replace('?','',$b);
	$b = str_replace('.','',$b);
	$b = str_replace(',','',$b);
	$b = str_replace('<','',$b);
	$b = str_replace('>','',$b);
	$b = str_replace(':','',$b);
	$b = str_replace(';','',$b);
	$b = str_replace('"','',$b);
	$b = str_replace("'",'',$b);
	$b = str_replace('','',$b);

	return $b;

}


function month_text2num($a){

	switch($a){
		case 'January' : 	$b = 1; 	break;
		case 'February' :	$b = 2; 	break;
		case 'March' :		$b = 3;		break;
		case 'April' :		$b = 4; 	break;
		case 'May' :		$b = 5; 	break;
		case 'June' :		$b = 6; 	break;
		case 'July' :		$b = 7; 	break;
		case 'August' :		$b = 8;		break;
		case 'September' :	$b = 9;		break;
		case 'October' :	$b = 10;	break;
		case 'November' :	$b = 11;	break;
		case 'December' :	$b = 12;	break;
		default :			$b = 0;		break;
	}

	return $b;

}

function snip_between($start, $end, $string){

	$one = explode($start,$string);
	if(isset($one[1])){
		$two = explode($end,$one[1]);
		$output = $two[0];
	}

	if(isset($two[0])){
		return $output;
	}else{
		return false;
	}

}

function is_odd($number) {
   return $number & 1; // 0 = even, 1 = odd
}

function db_insert($table, $arr, $debug=false){
global $db;

	$total_arr = count($arr);

	$query = 'INSERT INTO `'.$table.'` (';

	$i = 1;
	foreach($arr as $key => $val){
		$query .= '`'.$key.'`';
		if($total_arr != $i){ $query .= ','; }
	$i++;
	}
	
	$query .= ') VALUES (';
	
	$j = 1;
	foreach($arr as $key => $val){
		//$val = mysqli_real_escape_string(urldecode($val));
        $val = addslashes(urldecode($val));
		$query .= "'$val'";
		if($total_arr != $j){ $query .= ','; }
	$j++;
	}
	
	$query .= ');';	
	
	
	$db->query($query);
	
	if($debug){
		$db->debug();
	}

}

function db_update($table, $arr, $where=false, $auto_insert=true, $debug=false){
global $db;

	if($where){

		$total_arr = count($arr);

		//check if the data already in db or not. if not, auto insert
		if($auto_insert){
            $check_data = $db->get_row("SELECT * FROM $table WHERE $where;",ARRAY_A);
    		if($debug){ $db->debug(); }
        }else{
            $check_data = false;
        }
		if(is_array($check_data)){

			$query = 'UPDATE `'.$table.'` SET ';
	
			$i = 1;
			foreach($arr as $key => $val){
				//$query .= '`'.$key.'` = \''.mysqli_real_escape_string(urldecode($val)).'\' ';
                //$query .= '`'.$key.'` = \''.addslashes(urldecode($val)).'\' '; //modified by aizu 20170321 - somehow, if the character "plus" ( + symbol ) wanted to insert, the urldecode seem strip the + symbol out.
                $query .= '`'.$key.'` = \''.addslashes($val).'\' ';
				if($total_arr != $i){ $query .= ', '; }
			$i++;
			}
			
			$query .= ' WHERE '.$where.';';
			
			$db->query($query);
			
			if($debug){
                echo "\n\n";
                echo $query;
                echo "\n\n";
				$db->debug();
			}
		
		
		}else{
		
			if($auto_insert){
				//auto insert it.
				db_insert($table,$arr);
				if($debug){
					$db->debug();
				}
			}
			
		}
		
		
		
	
	}

}


function character_limiter($str, $n = 500, $end_char = '&#8230;'){

	if (strlen($str) < $n)
	{
		return $str;
	}
	
	$str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

	if (strlen($str) <= $n)
	{
		return $str;
	}
								
	$out = "";
	foreach (explode(' ', trim($str)) as $val)
	{
		$out .= $val.' ';			
		if (strlen($out) >= $n)
		{
			return trim($out).$end_char;
		}		
	}
}

function dbug($val,$prepend='',$prepend_color='red'){

	$fp = fopen('_debug/log.txt', 'a');
	fwrite($fp, '<span style="color:'.$prepend_color.';"><b>'.$prepend.'</b></span> '.$val."\n");
	fclose($fp);

}

function download($file_source, $file_target){

      // prepare
      $file_source = str_replace(' ', '%20', html_entity_decode($file_source)); // fix url format
      if (file_exists($file_target)) chmod($file_target, 0777); // add write permission

      // opne files
      if (($rh = fopen($file_source, 'rb')) === FALSE) return false; // fopen() handles
      if (($wh = fopen($file_target, 'wb')) === FALSE) return false; // error messages.

      // read & write
      while (!feof($rh))
      {
            if (fwrite($wh, fread($rh, 1024)) === FALSE)
            {
                  // unable to write to file, possibly
                  // because the harddrive has filled up
                  fclose($rh);
                  fclose($wh);
                  return false;
            }
      }

      // close files
      fclose($rh);
      fclose($wh);
      return true;
}


function http_socket($host, $method, $path, $data){
	
	$method = strtoupper($method);        
	
	if ($method == "GET"){
		$path.= '?'.$data;
	}    
	
	$filePointer = fsockopen($host, 80, $errorNumber, $errorString);
	
	if (!$filePointer) {
		//throw new Exception("Chyba spojeni $errorNumber $errorString");
	}
	
	$requestHeader = $method." ".$path."  HTTP/1.1\r\n";
	$requestHeader.= "Host: ".$host."\r\n";
	$requestHeader.= "User-Agent:      Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1) Gecko/20061010 Firefox/2.0\r\n";
	$requestHeader.= "Content-Type: application/x-www-form-urlencoded\r\n";
	
	if ($method == "POST"){
		$requestHeader.= "Content-Length: ".strlen($data)."\r\n";
	}
	
	$requestHeader.= "Connection: close\r\n\r\n";
	
	if ($method == "POST"){
		$requestHeader.= $data;
	}            
	
	fwrite($filePointer, $requestHeader);
	
	$responseHeader = '';
	$responseContent = '';
	
	do {
		$responseHeader.= fread($filePointer, 1); 
	}
	while (!preg_match('/\\r\\n\\r\\n$/', $responseHeader));
	
	
	if (!strstr($responseHeader, "Transfer-Encoding: chunked")){
		while (!feof($filePointer)){
			$responseContent.= fgets($filePointer, 128);
		}
	}else{
		
		while ($chunk_length = hexdec(fgets($filePointer))) {
			$responseContentChunk = '';
			$read_length = 0;
			while ($read_length < $chunk_length){
				$responseContentChunk .= fread($filePointer, $chunk_length - $read_length);
				$read_length = strlen($responseContentChunk);
			}
			$responseContent.= $responseContentChunk;
			fgets($filePointer);
		}
	
	}
	
	
	
	
	return chop($responseContent);

}



function post_request($url, $data, $optional_headers = null){

	$params = array('http' => array(
		'method' => 'POST',
		'content' => $data
	));
	if ($optional_headers !== null) {
		$params['http']['header'] = $optional_headers;
	}
	$ctx = stream_context_create($params);
	$fp = @fopen($url, 'rb', false, $ctx);
	if (!$fp) {
		throw new Exception("Problem with $url, $php_errormsg");
	}
	$response = @stream_get_contents($fp);
	if ($response === false) {
		throw new Exception("Problem reading data from $url, $php_errormsg");
	}
	return $response;
}

function nice_time($unix_date){

    if(empty($unix_date)) {
        return "No date provided";
    }
   
    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");
   
    $now             = time();
    //$unix_date         = strtotime($date);
   
       // check validity of date
    if(empty($unix_date)) {   
        return "Bad date";
    }

    // is it future date or past date
    if($now > $unix_date) {   
        $difference     = $now - $unix_date;
        $tense         = "ago";
       
    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }
   
    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }
   
    $difference = round($difference);
   
    if($difference != 1) {
        $periods[$j].= "s";
    }
   
    return "$difference $periods[$j] {$tense}";
}

function replace_unicode_escape_sequence($match) {
    return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
}
function unicode_decode($str) {
    return preg_replace_callback('/\\\\u([0-9a-f]{4})/i', 'replace_unicode_escape_sequence', $str);
}

function is_rtl( $string ) {
    $rtl_chars_pattern = '/[\x{0590}-\x{05ff}\x{0600}-\x{06ff}]/u';
    return preg_match($rtl_chars_pattern, $string);
}
?>