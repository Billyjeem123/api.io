<?php
// Start with PHPMailer class
use PHPMailer\PHPMailer\PHPMailer;

$timestamp = time();

# DECODE THE HTMLSPECIAL STRING IN TO STRING
# -----------------------------------------------------------------------*/

class Utility extends db
{
  public function escape_data($data)
  {
    //$con=mysqli_connect(DB_SERVER,DBASE_USER,DBASE_PASS,DBASE_NAME);
    $mysqli = mysqli_connect(DB_SERVER, DBASE_USER, DBASE_PASS, DBASE_NAME);
    if (function_exists('mysql_real_escape_string')) {
      $data = mysqli_real_escape_string($mysqli, $data);
      $data = strip_tags($data);
    } else {
      $data = trim($data);
      $data = mysqli_escape_string($mysqli, $data);
      $data = strip_tags($data);
    }
    return $data;
  }
  # ---------------------------------------------------------------------


  function get_ip_info()
  {
    $indicesServer = array(
      'PHP_SELF',
      'argv',
      'argc',
      'GATEWAY_INTERFACE',
      'SERVER_ADDR',
      'SERVER_NAME',
      'SERVER_SOFTWARE',
      'SERVER_PROTOCOL',
      'REQUEST_METHOD',
      'REQUEST_TIME',
      'REQUEST_TIME_FLOAT',
      'QUERY_STRING',
      'DOCUMENT_ROOT',
      'HTTP_ACCEPT',
      'HTTP_ACCEPT_CHARSET',
      'HTTP_ACCEPT_ENCODING',
      'HTTP_ACCEPT_LANGUAGE',
      'HTTP_CONNECTION',
      'HTTP_HOST',
      'HTTP_REFERER',
      'HTTP_USER_AGENT',
      'HTTPS',
      'REMOTE_ADDR',
      'REMOTE_HOST',
      'REMOTE_PORT',
      'REMOTE_USER',
      'REDIRECT_REMOTE_USER',
      'SCRIPT_FILENAME',
      'SERVER_ADMIN',
      'SERVER_PORT',
      'SERVER_SIGNATURE',
      'PATH_TRANSLATED',
      'SCRIPT_NAME',
      'REQUEST_URI',
      'PHP_AUTH_DIGEST',
      'PHP_AUTH_USER',
      'PHP_AUTH_PW',
      'AUTH_TYPE',
      'PATH_INFO',
      'ORIG_PATH_INFO'
    );
    $result = "";
    $result = $result . '<table cellpadding="10">';
    foreach ($indicesServer as $arg) {
      if (isset($_SERVER[$arg])) {
        $result = $result . '<tr><td>' . $arg . '</td><td> __ ' . $_SERVER[$arg] . ' </td> </tr>';
      } else {
        $result = $result . '<tr><td>' . $arg . '</td><td>__</td> </tr>';
      }
    }
    $result = $result . '</table>';
    return $result;
  }
  // 0069948573

  public function sql_detect($data)
  {
    $sql = array(
      "DROP DATABASE",
      "ALTER TABLE",
      "TRUNCATE TABLE",
      "DELETE FROM",
      "INSERT INTO",
      "DROP TABLE",
      "CREATE TABLE"
    );

    $str  = strtoupper($data);
    $count = count($sql);
    $b = "";

    for ($i = 0; $i < $count; $i++) {
      $pos = strpos($str, $sql[$i]);
      if ($pos === false) {
        $b = FALSE;
      } else {
        $b = TRUE;
        break;
      }
    }

    if ($b == false) {
      return FALSE;
    } else {
      return TRUE;
    }
  }


  public function get_client_ip()
  {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
      $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
      $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
      $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
      $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
      $ipaddress = getenv('REMOTE_ADDR');
    else
      $ipaddress = 'UNKNOWN';

    $ipaddress = $_SERVER['SERVER_ADDR'];
    return $ipaddress;
  }


  public function datediff($olddate, $newdate)
  {

    // Declare and define two dates 
    // $date1 = strtotime("2016-06-01 22:45:00"); 
    // $date2 = strtotime("2018-09-21 10:44:01"); 

    // Formulate the Difference between two dates 
    $newdate = intval($newdate);
    $olddate = intval($olddate);
    $diff = abs($newdate - $olddate);


    // To get the year divide the resultant date into 
    // total seconds in a year (365*60*60*24) 
    $years = floor($diff / (365 * 60 * 60 * 24));


    // To get the month, subtract it with years and 
    // divide the resultant date into 
    // total seconds in a month (30*60*60*24) 
    $months = floor(($diff - $years * 365 * 60 * 60 * 24)
      / (30 * 60 * 60 * 24));


    // To get the day, subtract it with years and 
    // months and divide the resultant date into 
    // total seconds in a days (60*60*24) 
    $days = floor(($diff - $years * 365 * 60 * 60 * 24 -
      $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));


    // To get the hour, subtract it with years, 
    // months & seconds and divide the resultant 
    // date into total seconds in a hours (60*60) 
    $hours = floor(($diff - $years * 365 * 60 * 60 * 24
      - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24)
      / (60 * 60));


    // To get the minutes, subtract it with years, 
    // months, seconds and hours and divide the 
    // resultant date into total seconds i.e. 60 
    $minutes = floor(($diff - $years * 365 * 60 * 60 * 24
      - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
      - $hours * 60 * 60) / 60);


    // To get the minutes, subtract it with years, 
    // months, seconds, hours and minutes 
    $seconds = floor(($diff - $years * 365 * 60 * 60 * 24
      - $months * 30 * 60 * 60 * 24 - $days * 60 * 60 * 24
      - $hours * 60 * 60 - $minutes * 60));

    // Print the result 
    // printf("%d years, %d months, %d days, %d hours, "
    //   . "%d minutes, %d seconds", $years, $months, 
    //       $days, $hours, $minutes, $seconds); 

    if ($years != '') {
      # code...
      // $value = "$years years, $months months, $days days, $hours hours, $minutes minutes, $seconds seconds";
      $value = "$years year(s)";
      return $value;
    } elseif ($months != '') {
      # code...
      // $value = "$months months, $days days, $hours hours, $minutes minutes, $seconds seconds";
      $value = "$months month(s)";
      return $value;
    } elseif ($days != '') {
      # code...
      // $value = "$days days, $hours hours, $minutes minutes, $seconds seconds";
      $value = "$days day(s)";
      return $value;
    } elseif ($hours != '') {
      # code...
      // $value = "$hours hours, $minutes minutes, $seconds seconds";
      $value = "$hours hour(s)";
      return $value;
    } elseif ($minutes != '') {
      # code...
      // $value = "$minutes minutes, $seconds seconds";
      $value = "$minutes minute(s)";
      return $value;
    } elseif ($seconds != '') {
      # code...
      $value = "$seconds second(s)";
      return $value;
    }
  }

  // Function to generate OTP 
  public function generateNumericOTP($n)
  {

    // Take a generator string which consist of 
    // all numeric digits 
    $generator = "1357902468";

    // Iterate for n-times and pick a single character 
    // from generator and append it to $result 

    // Login for generating a random character from generator 
    //     ---generate a random number 
    //     ---take modulus of same with length of generator (say i) 
    //     ---append the character at place (i) from generator to result 

    $result = "";

    for ($i = 1; $i <= $n; $i++) {
      $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }

    // Return result 
    return $result;
  }

  public function generateAlphaNumericOTP($n)
  {

    // Take a generator string which consist of 
    // all numeric digits 
    $generator = "1357902468ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    // Iterate for n-times and pick a single character 
    // from generator and append it to $result 

    // Login for generating a random character from generator 
    //     ---generate a random number 
    //     ---take modulus of same with length of generator (say i) 
    //     ---append the character at place (i) from generator to result 

    $result = "";

    for ($i = 1; $i <= $n; $i++) {
      $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }

    // Return result 
    return $result;
  }


  public function generateAlphaNumericOTP_case($n)
  {

    // Take a generator string which consist of 
    // all numeric digits 
    $generator = "1357902468ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

    // Iterate for n-times and pick a single character 
    // from generator and append it to $result 

    // Login for generating a random character from generator 
    //     ---generate a random number 
    //     ---take modulus of same with length of generator (say i) 
    //     ---append the character at place (i) from generator to result 

    $result = "";

    for ($i = 1; $i <= $n; $i++) {
      $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }

    // Return result 
    return $result;
  }



  public function generateAlphaNumericOTP_symbol($n)
  {

    // Take a generator string which consist of 
    // all numeric digits 
    $generator = "1357902468ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_@!";

    // Iterate for n-times and pick a single character 
    // from generator and append it to $result 

    // Login for generating a random character from generator 
    //     ---generate a random number 
    //     ---take modulus of same with length of generator (say i) 
    //     ---append the character at place (i) from generator to result 

    $result = "";

    for ($i = 1; $i <= $n; $i++) {
      $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }

    // Return result 
    return $result;
  }



  public function generateAlphaOTP($n)
  {

    // Take a generator string which consist of 
    // all numeric digits 
    $generator = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    // Iterate for n-times and pick a single character 
    // from generator and append it to $result 

    // Login for generating a random character from generator 
    //     ---generate a random number 
    //     ---take modulus of same with length of generator (say i) 
    //     ---append the character at place (i) from generator to result 

    $result = "";

    for ($i = 1; $i <= $n; $i++) {
      $result .= substr($generator, (rand() % (strlen($generator))), 1);
    }

    // Return result 
    return $result;
  }
  # end of custom functions


  public function CheckMailExist($mysqli, $mail)
  {
    $sql = "SELECT * FROM users where mail='$mail'";
    if ($res = $mysqli->query($sql)) {
      if ($res->num_rows > 0) {
        // while ($row = $res->fetch_array())  
        if ($row = $res->fetch_array()) {
          // $_SESSION['usertoken'] = $row['token'];
          return true;
        }
        // echo "</table>"; 
        $res->free();
      } else {
        return false;
        //  echo "Incorrect Code. Try again. <br> <b>Reset</b> and try again biko"; 
      }
    } else {
      return false;
      // echo "ERROR: Could not able to execute command.. ";
      $mysqli->error;
    }
    $mysqli->close();
  }


  public function mail_tb1($mysqli, $email, $subject, $body, $timestamp)
  {
    $body = addslashes($body);
    $sql = "INSERT INTO mail_tb (mail, subject, body, timestamp, sent) 
              VALUES('$email', '$subject','$body','$timestamp','0') ";
    if ($mysqli->query($sql) == true) {
      return true;
    } else {
      // echo "ERROR: Could not able to execute $sql. ";
      return false;
      $mysqli->error;
    }

    // Close connection 
    $mysqli->close();
  }



  public function sendMail($email, $subject, $body, $ReplyTo = "0")
  {

    // require_once '../../../.././vendor/autoload.php';
    require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

    if ($ReplyTo == "0") {
      // code...
      $ReplyTo = APP_MAIL;
    }

    // create a new object
    $mail = new PHPMailer();

    // configure an SMTP
    $mail->isSMTP();
    $mail->Host = 'fulazo.io';
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@fulazo.io';
    $mail->Password = 'MO=p{6p2Nv*(';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->setFrom('no-replys@fulazo.io', '' . APPNAME . '');
    $dbname = "fulazo";
    $mail2 = clone $mail;
    $mail2->addAddress($email, $dbname);
    $mail2->addReplyTo($ReplyTo, '' . APPNAME . '');
    $mail2->Subject = $subject;

    $body = "
        $body<br /><br />
        Stay safe.<br /><b>FULAZO</b>
        ";

    // Set HTML 
    $mail2->isHTML(TRUE);
    $mail2->Body = $body;
    $mail2->AltBody = strip_tags($body);

    if ($mail2->send()) {
      $body = addslashes($body);
      $this->recordEmail($email, $subject, $body, 1, time());
    } else {
      $body = addslashes($body);
      $this->recordEmail($email, $subject, $body, 0, 0);
    }
  }


  public function sendPassword($email, $subject, $body, $ReplyTo = "0")
  {

    // require_once '../../../.././vendor/autoload.php';
    require_once($_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php');

    if ($ReplyTo == "0") {
      // code...
      $ReplyTo = APP_MAIL;
    }

    // create a new object
    $mail = new PHPMailer();

    // configure an SMTP
    $mail->isSMTP();
    $mail->Host = 'fulazo.io';
    $mail->SMTPAuth = true;
    $mail->Username = 'no-reply@fulazo.io';
    $mail->Password = 'MO=p{6p2Nv*(';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;
    $mail->setFrom('no-replys@fulazo.io', '' . APPNAME . '');
    $dbname = "fulazo";
    $mail2 = clone $mail;
    $mail2->addAddress($email, $dbname);
    $mail2->addReplyTo($ReplyTo, '' . APPNAME . '');
    $mail2->Subject = $subject;

    $body = "
          $body<br /><br />
          Stay safe.<br /><b>FULAZO</b>
          ";

    // Set HTML 
    $mail2->isHTML(TRUE);
    $mail2->Body = $body;
    $mail2->AltBody = strip_tags($body);

    if ($mail2->send()) {
      $body = addslashes($body);
      $this->recordEmail($email, $subject, $body, 1, time());
    } else {
      $body = addslashes($body);
      $this->recordEmail($email, $subject, $body, 0, 0);
    }
  }

  public function recordEmail($mail, $subject, $message, $sent = 0, $sent_time)
  {
    try {

      $time = time();
      $ip = $_SERVER['REMOTE_ADDR'];
      $sql = "INSERT INTO mail_tb (mail, subject, message, time, sent, sent_time, ip)
  VALUES (:mail, :subject, :message, :time, :sent, :sent_time, :ip)";
      $stmt = $this->connect()->prepare($sql);
      $stmt->bindParam(':mail', $mail);
      $stmt->bindParam(':subject', $subject);
      $stmt->bindParam(':message', $message);
      $stmt->bindParam(':time', $time);
      $stmt->bindParam(':sent', $sent);
      $stmt->bindParam(':sent_time', $sent_time);
      $stmt->bindParam(':ip', $ip);

      // return "New records created successfully $name";
      if (!$stmt->execute()) {
        // code...
        return false;
      } else {
        return true;
      }
    } catch (PDOException $e) {
      $_SESSION['err'] = $e->getMessage();
      return false;
    }
  }

  public  function validateObj($data)
  {

    if (!isset($data)) {

      $array = ['success' => false, 'message' => "Payload not found.", 'data' => null];
      $return = json_encode($array);
      http_response_code(500);
      echo "$return";
      exit();
    }
  }

  public function authToken($apikey)
  {
    if (empty($apikey)) {
      # code...
      http_response_code(400);
      $output = new Utility();
      $output->outputData(false, 'missing api key', null);
      exit();
    }
  }

  public function verifyImageType($imageType)
  {

    if ($imageType == "mp4" || $imageType == "mov" || $imageType == "mp3") {
      return  false;
      exit;
    } else {
      return  true;
      exit;
    }
  }

  // validate email
  public function validateEmail($mail = '')
  {
    // code...
    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
      return true;
    } else {
      return false;
    }
  }

  // validate phone number
  public function validatePhone($value = '')
  {
    // code...
    if (preg_match('/^[0-9]{13}+$/', $value)) {
      return true;
    } else {
      return false;
    }
  }


  // output data
  public function outputData($success = null, $message = null, $data = null)
  {
    $arr_output = array(
      'success' => $success,
      'message' => $message,
      'data' => $data,
    );
    echo json_encode($arr_output);
  }


  public function encryptPassword()
  {

    $defaultPassword = mt_rand(100000, 999999);
    return $defaultPassword;
  }


  public function validateCard($number)
  {

    global $type;

    $cardtype = array(
      "visa"       => "/^4[0-9]{12}(?:[0-9]{3})?$/",
      "mastercard" => "/^5[1-5][0-9]{14}$/",
      "amex"       => "/^3[47][0-9]{13}$/",
      "discover"   => "/^6(?:011|5[0-9]{2})[0-9]{12}$/",
    );

    if (preg_match($cardtype['visa'], $number)) {
      $type = "visa";
      return 'visa';
    } else if (preg_match($cardtype['mastercard'], $number)) {
      $type = "mastercard";
      return 'mastercard';
    } else if (preg_match($cardtype['amex'], $number)) {
      $type = "amex";
      return 'amex';
    } else if (preg_match($cardtype['discover'], $number)) {
      $type = "discover";
      return 'discover';
    } else {
      return false;
    }
  }

  public function validateDate($date)
  {

    $dateType = "/^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/";

    if (preg_match($dateType, $date)) {
      return  true;
    } else {

      return false;
    }
  }

  public function validUrl($resources)
  {
    if ($resources != "tasks") {
      http_response_code(404);
      exit;
    }
  }

  public function validUrlstatus($resources)
  {
    if ($resources != "status") {
      http_response_code(404);
      exit;
    }
  }

  public function validUrlStstus($resources)
  {
    if ($resources === "status") {
      http_response_code(404);
      exit;
    }
  }

  public function validateParams(){

   $this->outputData(false, 'Invalid credentials', null);
   exit;

  }

  public function validateCvv($cvv)
  {

    $Cvv = "/^[0-9]{3,4}$/";

    if (preg_match($Cvv, $cvv)) {
      return  true;
    } else {

      return false;
    }
  }
}
