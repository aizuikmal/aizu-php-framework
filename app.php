<?php

function payload_show(){
global $xuser, $db;

    if(uri(1) == 'record'){
        if(uri(2) == 'item'){
            include '';
        }else{
            include '';
        }
    }else{
        include 'template-dashboard.php';
    }

}

?>