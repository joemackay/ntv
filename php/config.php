<?php
    define('HOST',  'localhost');    
    define('USER',  'root');    
    define('PASS',  '');    
    define('DBASE',    'ntvreviews');    
    define('URL',   'http://localhost/Notes/');
    require ("define.php");
    require ("classes/class.main.php");
    require ("classes/class.user.php");
    require ("classes/class.password.php"); 
    require ("classes/class.content.php"); 
    
    $rootDir=$_SERVER['DOCUMENT_ROOT'].'/';
    //print_r($_SESSION);
    
function jsonEcho($json) {
    $cb = $_REQUEST['callback'];
    if ($cb) {
        header('Content-Type: application/javascript');
        echo $cb . '(' . $json . ');';
    } else {
        header('Content-Type: application/json');
        echo $json;
    }
}

function doHttpRequest($urlreq) {
    $ch = curl_init();

    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, $urlreq);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    // grab URL and pass it to the browser
    $request_result = curl_exec($ch);

    // close cURL resource, and free up system resources
    curl_close($ch);

    return $request_result;
}

function _http_request($url, $header, $body = NULL, $method = 'GET') {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_COOKIESESSION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array($header));
    curl_setopt($ch, CURLOPT_URL, $url);

    if ($body) {
        curl_setopt($ch, CURLOPT_POST, 1);
        if ($body == 'token_request') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, '');
        } elseif ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS,$body);
        }
    }

    if($method == "DELETE"){
         curl_setopt($ch, CURLOPT_DELETE, 1);
    }

    $output = curl_exec($ch);
    curl_close($ch);

    return $output;
}


function readGetParam($param, $default){
    return isset($_GET[$param]) && $_GET[$param] !== '' ? $_GET[$param] : $default;
}