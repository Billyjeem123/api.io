<?php

declare(strict_types=1);
require('v0.1/assets/config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    http_response_code(405);
    header("Allow: GET");
    exit;
}

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);

$parts = explode("/", $path);

 $resources = $parts[2];

if ($resources !== "status") {
    http_response_code(404);
    exit;
  }else{

     $array = ['success'=>true, 'message'=>'Ok', 'data'=>null];
     $result = json_encode($array);
     echo "$result";
  }

