<?php
header('Content-Type: application/json charset=utf-8');
header("Access-Control-Allow-Methods: PUT, GET, POST");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {

    http_response_code(405);
    header("Allow: POST");
    exit;
}

require('v0.1/assets/config.php');
$Utility = new Utility();
$data = file_get_contents('php://input');
$data =  json_decode($data);
if ($Utility->validateObj($data)) {
    exit();
}

isset($data->mail, $data->pword) ?: $Utility->validateResponse();
$user = new Users();
$login = $user->tryLogin($data->mail, $data->pword);

$access_token = base64_encode(json_encode($login));

if ($login !== false) {
    // code...
    echo $Utility->outputData(true, "Login successful..", $access_token);
    exit();
} else {
    http_response_code(401);
    echo $Utility->outputData(false, $_SESSION['err'], null);
    exit();
}
