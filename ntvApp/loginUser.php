<?php
 include 'config.php';
 $Obj = new UserMain();        

//print_r($_REQUEST);
$callback = isset($_REQUEST['callback']) ? $_REQUEST['callback'] : '';

if(isset($_REQUEST['password']) && isset($_REQUEST['email'])) {
    $Obj->login_user($_REQUEST['email'], $_REQUEST['password']);
    $id = $Obj->user_id;
    $email = $Obj->user_email;
    jsonEcho(json_encode(array('results' => array('id' => $id, 'email' => $email))));    
} else {
    echo '-1';//echo json_encode(array('user_id'=>'-1'));  
}

?>