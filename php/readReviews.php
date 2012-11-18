<?php
require_once ("config.php"); 
$Obj = new CmsMain(); 
   
if(isset($_REQUEST['videoid'])) {
    $res = $Obj->getReviews($_REQUEST['videoid']);
    jsonEcho(json_encode(array('reviews' => $res)));    
} else {
    jsonEcho(json_encode(array('reviews' => array('id' => -1))));  
}