<?php //json header
header('Content-Type: application/json; charset=utf-8');
// header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header("Access-Control-Allow-Origin: * ");
header('Access-Control-Allow-Credentials: false');
header('Access-Control-Allow-Method: GET');
header('Access-Control-Max-Age: 86400');



// header("Cache-Control: public, must-revalidate"); //HTTP 1.1
// header("Pragma: public"); //HTTP 1.0
// // header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

// //or, if you DO want a file to cache, use:
// header("Cache-Control: max-age=2 592000");
