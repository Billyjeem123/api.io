<?php

declare(strict_types=1);
require('v0.1/assets/config.php');

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$parts = explode("/", $path);

$resource = $parts[2];

$id = $parts[3] ?? null;

$utility = new Utility();
if ($utility->validUrl($resource)) {
  exit();
}
//2348183339007 

$controller = new controller;
$Auth = new Auth();
// echo $_SERVER['HTTP_AUTHORIZATION'];
// $header = apache_request_headers();
// echo $header['Authorization'];
if ($Auth->authenticateAccessToken($_SERVER['HTTP_AUTHORIZATION'])) {
  exit();
}

return false;
// $header = apache_request_headers();
print_r($header);
// echo ($_SERVER['HTTP_X_API_KEY']);
$authUser = $Auth->validateKey($_SERVER['HTTP_X_API_KEY']);
$userid = $Auth->extractUserId($authUser[0]['id']);
$controller->processRequest($_SERVER['REQUEST_METHOD'], $id, $userid);
