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




$controller = new controller;
$Auth = new Auth();

$authToken = $Auth->authenticateAccessToken($_SERVER['HTTP_AUTHORIZATION']);
if (!$authToken) {
  exit();
}


$userid = $Auth->extractUserId($authToken['userid']);
$controller->processRequest($_SERVER['REQUEST_METHOD'], $id, $userid);
