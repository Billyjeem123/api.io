<?php

/**
 *
 */
class Users extends db
{

    public function createAccount($mail, $pword)

    {

        $apikey = bin2hex(openssl_random_pseudo_bytes(12));
        $passhash = password_hash(trim($pword), PASSWORD_BCRYPT, [12]);

        $sql = " INSERT INTO tasks_user (email, password, api_key) ";
        $sql .= " VALUES(:name, :password, :apikey)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':name', $mail);
        $stmt->bindParam(':password', $passhash);
        $stmt->bindParam(':apikey', $apikey);

        // return "New records created successfully $name";
        if (!$stmt->execute()) {
            // code...
            return false;
        } else {
            return true;
        }
    }
    // check if user email exist
    public function EmailExist($mail = '')
    {
        // code...
        try {
            $sql = "SELECT * from tasks_user where email = '$mail'";
            $stmt = $this->connect()
                ->prepare($sql);
            if (!$stmt->execute()) {
                $stmt = null;
                $_SESSION['err'] = "Something went wrong, please try again..";
                return false;
            } else {
                if ($stmt->rowCount() == 0) {

                    $stmt = null;
                    $_SESSION['err'] = "No user found..";
                    return false;
                    // code...

                } else {

                    if ($user = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                        return true;
                    }
                }
            }
        } catch (PDOException $e) {
            $_SESSION['err'] = $e->getMessage();
            return false;
        }
    }
    // <!--


    public function updateUsersInfo($fname, $lname, $email, $pword, $phone, $id)
    {

        try {
            $sql = " UPDATE users SET ";
            $sql .= "fname  =  :fname, ";
            $sql .= "lname = :lname, ";
            $sql .= "mail =   :mail, ";
            $sql .= "pword = :pword, ";
            $sql .= " phone   = :phone ";
            $sql .= "WHERE id = :id ";

            $stmt = $this->connect()
                ->prepare($sql);

            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':mail', $email);
            $stmt->bindParam(':pword', $pword);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':id', $id);
            // return "New records updated successfully $name";
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

    /*
    @param mixed $mail
    @param mixed $pword
    @return false|array|void

    */
    public function tryLogin($mail, $pword)
    {
        // code...
        try {
            $sql = "SELECT * from tasks_user where email = '$mail'";
            $stmt = $this->connect()
                ->prepare($sql);
            if (!$stmt->execute()) {
                $stmt = null;
                $_SESSION['err'] = "Something went wrong, please try again..";
                return false;
            } else {
                if ($stmt->rowCount() == 0) {

                    $stmt = null;
                    $_SESSION['err'] = "$mail not found..";
                    return false;
                    // code...

                } else {

                    if ($user = $stmt->fetchAll(PDO::FETCH_ASSOC)) {
                        if (password_verify($pword, $user[0]['password'])) {
                            $user = $user[0];
                            $post_item = array(
                                'userid' => $user['id'],
                                'mail' => $user['email']
                                // 'pword' => $user['password'],
                                // 'apikey' => ($user['api_key'])
                            );
                            return $post_item;
                        } else {
                            $_SESSION['err'] = "Incorrect password for $mail";
                            return false;
                        }
                        // return true;

                    }
                }
            }
        } catch (PDOException $e) {
            return  false . $e->getMessage();
        }
    }
}
