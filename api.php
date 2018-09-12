<?php

if(uri(2) == 'appt'){
    if(uri(3) == 'add'){
        appt_add(uri(4));
    }elseif(uri(3) == 'edit'){
        appt_edit(uri(4));
    }elseif(uri(3) == 'delete'){
        appt_delete(uri(4));
    }elseif(uri(3) == 'get'){
        appt_get(uri(4));
    }else{
        exit('Error API 501');
    }
}elseif(uri(2) == 'profile'){
    if(uri(3) == 'add'){
        profile_add_edit();
    }elseif(uri(3) == 'edit'){
        profile_add_edit(uri(4));
    }elseif(uri(3) == 'delete'){
        profile_delete(uri(4));
    }else{
        exit('Error API 520');
    }
}else{
    exit('Error API 500');
}

function profile_add_edit($pat_id=false){
global $db;

    if(is_numeric($pat_id)){
        $mode = 'edit';
    }else{
        $mode = 'add';
    }

    $form_data_raw = file_get_contents('php://input');
    if(trim($form_data_raw) == ''){ exit('Error API 523'); }

    $form_data_raw = json_decode($form_data_raw);
    if(!is_array($form_data_raw)){ exit('Error API 524'); }

    $form_data = array();
    foreach($form_data_raw as $form_data_raw_item){
        $form_data[$form_data_raw_item->name] = $form_data_raw_item->value;
    }
    if(count($form_data) == 0){ exit('Error API 524'); }

    if($mode == 'edit'){
        $sql_statement = array(
            'date_update' => date('Y-m-d H:i:s'),
            'name' => $form_data['name']
        );
        db_update('table',$sql_statement," `id` = $pat_id");
    }else{
        $sql_statement = array(
            'date_insert' => date('Y-m-d H:i:s'),
            'date_update' => date('Y-m-d H:i:s'),
            'status' => 1
        );
        db_insert('table',$sql_statement);
    }

    exit('OK');


}

function profile_delete($pat_id=false){
global $db;
    
        if(!is_numeric($pat_id)){ exit('Error API 300'); }
    
        $sql_statement = array('status' => 0);
        db_update('table',$sql_statement," `id` = $pat_id");

        exit('OK');
    
    
}

function appt_get($appt_id=false){
global $db;

    if(!is_numeric($appt_id)){ exit('Error API 507'); }
    
    $appt_get_db = $db->get_row("SELECT * FROM table_name WHERE id = $appt_id LIMIT 1;",ARRAY_A);
    $appt_get_db['date'] = date('Y-m-d',strtotime($appt_get_db['date']));
    echo json_encode($appt_get_db);

}

function appt_add($pat_id=false){
global $db;

    if(is_numeric($pat_id)){

        //check if this pat_id is exist
        $check_pat = $db->get_row("SELECT id,name FROM table_name WHERE id = $pat_id LIMIT 0,1;",ARRAY_A);

        if(is_numeric($check_pat['id']) AND $check_pat['id'] == $pat_id){

            $form_data_raw = file_get_contents('php://input');
            if(trim($form_data_raw) == ''){ exit('Error API 504'); }

            $form_data_raw = json_decode($form_data_raw);
            if(!is_array($form_data_raw)){ exit('Error API 505'); }

            $form_data = array();
            foreach($form_data_raw as $form_data_raw_item){
                $form_data[$form_data_raw_item->name] = $form_data_raw_item->value;
            }
            if(count($form_data) == 0){ exit('Error API 506'); }

            $sql_insert = array(
                'date_insert' => date('Y-m-d H:i:s'),
                'date_update' => date('Y-m-d H:i:s'),
                'status' => 1
            );
            db_insert('table_name',$sql_insert);

            exit('OK');

        }else{
            exit('Error API 502');
        }

    }else{
        exit('Error API 503');
    }

}

function appt_edit($appt_id=false){
global $db,$uri;

    if(is_numeric($appt_id)){

        //check if this pat_id is exist
        $check_appt = $db->get_row("SELECT id FROM table_name WHERE id = $appt_id LIMIT 0,1;",ARRAY_A);

        if(is_numeric($check_appt['id']) AND $check_appt['id'] == $appt_id){

            $form_data_raw = file_get_contents('php://input');
            if(trim($form_data_raw) == ''){ exit('Error API 511'); }

            $form_data_raw = json_decode($form_data_raw);
            if(!is_array($form_data_raw)){ exit('Error API 512'); }

            $form_data = array();
            foreach($form_data_raw as $form_data_raw_item){
                $form_data[$form_data_raw_item->name] = $form_data_raw_item->value;
            }
            if(count($form_data) == 0){ exit('Error API 513'); }

            $sql_insert = array(
                'date_update' => date('Y-m-d H:i:s'),
                'date' => $form_data['date']
            );
            db_update('table_name',$sql_insert," `id` = $appt_id ");

            exit('OK');

        }else{
            exit('Error API 509');
        }

    }else{
        exit('Error API 510');
    }

}

function appt_delete($appt_id=false){
global $db,$uri;

    if(!is_numeric($appt_id)){ exit('Error API 700'); }

    $sql_insert = array('status' => 0);
    db_update('table_name',$sql_insert," `id` = $appt_id ");

    exit('OK');

}

?>