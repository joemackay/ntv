<?php
require_once ("config.php");
if($_COOKIE['NtvVidzAppUserId'] && $_COOKIE['NtvVidzAppUserEmail']){
    jsonEcho(json_encode(array('results' => array('id' => $_COOKIE['NtvVidzAppUserId'], 'email' => $_COOKIE['NtvVidzAppUserEmail']))));
}else{
    jsonEcho(json_encode(array('results' => array('id' => -1))));
}
/*
echo  "\n";

echo 'NtvVidzAppUserId=>' . $_COOKIE["NtvVidzAppUserId"] ."\n";  
echo 'NtvVidzAppUserEmail=>' . $_COOKIE["NtvVidzAppUserEmail"] ."\n";
echo 'ntvVideoAppUserId=>' . $_COOKIE["ntvVideoAppUserId"]; */