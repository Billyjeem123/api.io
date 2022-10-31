<?php
header('Content-Type: application/json charset=utf-8');
header("Access-Control-Allow-Methods: PUT, GET, POST");

require('v0.1/assets/config.php');
$Utility = new Utility();
$data = file_get_contents('php://input');
$data = json_decode($data);
if ($Utility->validateObj($data)) {
  exit();
}


if ($Utility->validateEmail($data->mail) == false) {

  echo $Utility->outputData(true, "Invalid email account..", null);
  exit();
}
$user = new Users();

if ($user->EmailExist($data->mail) == true) {
  echo $Utility->outputData(false, "An account already exists with this email..", null);
  exit();
} else {
  if ($user->createAccount($data->mail, $data->pword) == true) {

    echo $Utility->outputData(true, "Registeration successfull..", null);
    exit();
  } else {
    echo $Utility->outputData(false, "Failed to create account..", null);
    exit();
  }
}
// 3118848153
