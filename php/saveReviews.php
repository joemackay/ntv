<?php
require_once ("config.php"); 
$Obj = new CmsMain();    
//print_r($_REQUEST);
if(isset($_REQUEST['video_id']) && isset($_REQUEST['comments'])) {
    $res = $Obj->saveReview($_REQUEST['video_id'], $_REQUEST['comments'], $_REQUEST['user_id']);
    jsonEcho(json_encode(array('results' => array('id' => $res))));      
} else {
    jsonEcho(json_encode(array('results' => array('id' => -1))));  
}