<?php
session_start();
header ("Content-type: application/json");
include '../config/conn.php';


function login($conn){
    extract($_POST);
    $data = array();
    $array_data = array();
    $query = "Call login_sp ('$username', '$password')";
    $result = $conn->query($query);

    if ($result){
        $row = $result->fetch_assoc();
        if(isset($row['msg'])){

            if($row['msg'] == 'Deny'){
                $data = array("status" => false, "data" => "Username or password is incorrect");
            }else{
                $data = array("status" => false, "data" => "User locked by admin");
            }
        }else{
            foreach($row as $key => $value){
                $_SESSION[$key] = $value;
            }
            $data = array("status" => true, "data" => 'Success');
        }
    }else{
        $data = array("status" => false, "data" => $conn->error);
    }
    echo json_encode ($data);
}





if(isset($_POST['action'])){
    $action = $_POST['action'];
    $action($conn);
}else{
    echo json_encode(array('status' => false, 'data' => 'Action required'));
}

?>