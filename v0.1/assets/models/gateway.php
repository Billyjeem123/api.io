<?php

use Gateway as GlobalGateway;

/**
 *
 */
class Gateway extends db
{

    public function fetchTaskbyUserid($userid)
    {

        $dataArray = array();
        $sql = " SELECT * FROM tasks  ";
        $sql .= " WHERE userid = :userid ";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':userid',  $userid);

        if (!$stmt->execute()) {
            // code...
            return false;
        } else {
            if ($stmt->rowCount() > 0) {
                $task = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($task as $value) {

                    $array = array(
                        'taskid' => ($value['id']),
                        'taskname' => ($value['name']),
                        'taskpriority' => ($value['priority']),
                        'status' =>   $this->getStatus($value['is_completed']),
                        'created' => date("D d M, Y: H", $value['time'])

                    );
                    array_push($dataArray, $array);
                }

                return $dataArray;
            } else {

                $this->outputData(false,  'No taskfound', null);
                exit();
            }
        }
    }

    public function fetchTaskbyId($taskid, $userid)
    {

        $dataArray = array();
        $sql = " SELECT * FROM tasks  ";
        $sql .= " WHERE id = :taskid  AND userid = :userid";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':taskid', $taskid);
        $stmt->bindParam(':userid', $userid);
        if (!$stmt->execute()) {
            // code...
            return false;
        } else {
            $task = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($task as $value) {

                $array = array(
                    'taskid' => ($value['id']),
                    'taskname' => ($value['name']),
                    'taskpriority' => ($value['priority']),
                    'status' =>   $this->getStatus($value['is_completed']),
                    'created' => date("D d M, Y: H", $value['time'])

                );
                array_push($dataArray, $array);
            }

            return $dataArray;
        }
    }

    private function getStatus($boolean)
    {
        if ($boolean == 1) {

            return true;
        } else {

            return false;
        }
    }

    public function  createTask($name, $priority, $is_completed, $userid)
    {

        $time = time();

        if (!$this->validateTask($name, $priority, $is_completed)) {
            exit;
        }

        $sql = " INSERT INTO tasks(name, priority, is_completed, userid,  time)";
        $sql .= "VALUES(:name, :priority, :is_completed, :userid, :timestamp)";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':priority', $priority);
        $stmt->bindParam(':is_completed', $is_completed);
        $stmt->bindParam(':userid', $userid);
        $stmt->bindParam(':timestamp', $time);
        if (!$stmt->execute()) {

            return false;
        } else {

            return true;
        }
    }

    public function  updateTask($name, $priority, $is_completed, $id, $userid)
    {


        if (!$this->validateTask($name, $priority, $is_completed)) {
            exit;
        }

        $sql = " UPDATE tasks SET ";
        $sql .= "name  = :name, ";
        $sql .= "priority = :priority, ";
        $sql .= "is_completed = :is_completed ";
        $sql .= " WHERE id = :id AND userid = :userid ";
        $stmt = $this->connect()
            ->prepare($sql);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':priority', $priority);
        $stmt->bindParam(':is_completed', $is_completed);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':userid', $userid);
        if (!$stmt->execute()) {
            return false;
        } else {
            return $stmt->rowCount();
        }
    }

    public  function validateTask($name, $priority, $is_completed)
    {
        $db = new Utility();

        if (empty($name)) {
            $db->outputData(false, 'taskname must not be empty', null);
            http_response_code(422);
            exit;
        }
        if (!is_int($priority)) {
            $db->outputData(false, 'priority fmust be an integer', null);
            http_response_code(422);
            exit;
        }
        if (empty($priority)) {
            $db->outputData(false, 'priority filed  must not be empty', null);
            http_response_code(422);
            exit;
        }
        if (empty($is_completed)) {
            $db->outputData(false, 'is_completed field must not be empty', null);
            http_response_code(422);
            exit;
        }
        if (!is_int($is_completed)) {
            $db->outputData(false, 'is_completed fmust be an integer', null);
            http_response_code(422);
            exit;
        } {

            return true;
        }
    }
    public function deleteTask(string $id, $userid): int
    {
        $sql = "DELETE FROM tasks
                WHERE id = :id
                And userid = :userid ";

        $stmt = $this->connect()
            ->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->bindValue(":userid", $userid, PDO::PARAM_INT);


        $stmt->execute();

        return $stmt->rowCount();
    }

    public function deletesTask(string $id): int
    {
        $sql = "DELETE FROM tasks
                WHERE id = :id ";

        $stmt = $this->connect()
            ->prepare($sql);

        $stmt->bindValue(":id", $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->rowCount();
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
}
