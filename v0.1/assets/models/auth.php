<?php

/**
 * 
 */
class Auth extends db
{
    public function validateKey($apikey)
    {

        $output = new Utility();
        if (empty($apikey)) {
            # code...
            http_response_code(400);
            $output->outputData(false, 'missing api key', null);
            exit();
        }
        $sql = " SELECT * FROM tasks_user  WHERE api_key  = '$apikey' ";
        $stmt = $this->connect()->prepare($sql);
        if (!$stmt->execute()) {
            http_response_code(500);
            return false;
        } else {

            if ($stmt->rowCount() == 0) {

                $output->outputData(false, 'Invalid token', null);
                http_response_code(401);
                exit();
            } else {
                $null =  $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $null;
                // return $null[0]['id'];

            }
        }
    }

    public function extractUserId($userid)
    {

        return $userid;
    }

    public  function validateUserid($userid, $dataid)
    {

            if ($userid === $dataid) {

                return true;
            } else {

                return false;
            }
        }
    
    // authenticate apptoken
    public function CheckToken($apptoken = '')
    {
        // code...
        try {
            $sql = "SELECT * FROM apptoken WHERE token = '$apptoken'";
            $stmt = $this->connect()->prepare($sql);
            if (!$stmt->execute()) {
                $stmt = null;
                $_SESSION['err'] = "Something went wrong, please try again..";
                return false;
            } else {
                if ($stmt->rowCount() == 0) {

                    $stmt = null;
                    $_SESSION['err'] = "No app found.";
                    return false;
                    // code...
                } else {

                    if ($biz = $stmt->fetchAll(PDO::FETCH_ASSOC)) {

                        $posts_arr = array();

                        return true;
                    } else {
                        return false;
                    }
                }
            }
        } catch (PDOException $e) {
            $_SESSION['err'] = $e->getMessage();
            return false;
        }
    }
    public function authenticateAccessToken($server)
    {

        if (!preg_match("/^Bearer\s+(.*)$/", $server, $matches)) {
            http_response_code(400);
            echo json_encode(["message" => "incomplete authorization header"]);
            return false;
        }

        $plain_text = base64_decode($matches[1], true);

        if ($plain_text === false) {

            http_response_code(400);
            echo json_encode(["message" => "invalid authorization header"]);
            return false;
        }

        $data = json_decode($plain_text, true);

        if ($data === null) {

            http_response_code(400);
            echo json_encode(["message" => "invalid JSON"]);
            return false;
        }
        return $data;
        exit;
    }
}
