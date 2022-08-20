<?php //json header
header('Content-Type: application/json; charset=utf-8');
// header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header("Access-Control-Allow-Origin: * ");
header('Access-Control-Allow-Credentials: false');
header('Access-Control-Allow-Method: GET,POST,OPTIONS');
header('Access-Control-Max-Age: 86400');

?>