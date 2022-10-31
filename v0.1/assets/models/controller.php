<?php

class controller
{

    public $userid;
    public function processRequest(string $method, ?string $id, int $userid): void
    {
        $get = new Gateway();
        $auth = new Auth();
        $utility = new Utility();

        if ($id === null) {

            if ($method == "GET") {

                $tasks =  $get->fetchTaskbyUserid($userid);

                $this->outputData(true, 'Fetched tasks', $tasks);
            } elseif ($method == "POST") {

                $data = file_get_contents('php://input');
                $data = json_decode($data);
                if ($utility->validateObj($data)) {
                    exit();
                }
                isset(
                    $data->taskname,
                    $data->priority,
                    $data->completed,
                    $data->userid
                ) ? : $utility ->validateResponse();

                if ($auth->validateUserid($userid, $data->userid) == false) {

                    $utility->outputData(false, 'Invalid userid', null);
                    exit;
                } else {

                    if ($get->createTask($data->taskname, $data->priority, $data->completed, $data->userid) == true) {

                        $this->outputData(true, 'task created', null);
                        http_response_code(201);
                        exit;
                    } else {

                        $this->outputData(false, 'Unable to process', null);
                        http_response_code(500);
                        exit;
                    }
                }
            } else {

                $this->respondMethodNotAllowed("GET, POST");
            }
        } else {

            $task =  $get->fetchTaskbyId($id, $userid);

            if ($task == false) {

                $this->invalidId($id);
                return;
            }

            switch ($method) {

                case "GET":
                    isset($id) ?: $utility->validateParams();
                    $this->outputData(true, 'Fetched tasks', $task);

                    break;

                case "PATCH":

                    $data = file_get_contents('php://input');
                    $data = json_decode($data);
                    if ($utility->validateObj($data)) {
                        exit();
                    }
                  

                    isset($id, $data->taskname,$data->priority, 
                    $data->completed, $data->userid ) ? : $utility->validateParams();
                    
                    if ($auth->validateUserid($userid, $data->userid) == false) {

                        $utility->outputData(false, 'Invalid userid', null);
                        exit;
                    } else {

                        $uptodate = $get->updateTask(
                            $data->taskname,
                            $data->priority,
                            $data->completed,
                            $id,
                            $data->userid

                        );
                        $this->outputData(true, 'Task updated', $uptodate);
                        http_response_code(200);
                    }
                    break;

                case "DELETE":
                    // isset($id) ?: $utility->validateResponse();
                    $rows = $get->deleteTask($id, $userid);
                    echo json_encode(["message" => "Task deleted", "rows" => $rows]);
                    break;

                default:
                    $this->respondMethodNotAllowed("GET, PATCH, DELETE");
            }
        }
    }

    private function respondMethodNotAllowed(string $allowed_methods): void
    {
        http_response_code(405);
        header("Allow: $allowed_methods");
    }

    private function invalidId(string $id): void
    {
        $this->outputData(false, 'Task with ID ' . $id . ' not found', null);
        http_response_code(404);
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
