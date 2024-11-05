<?php
header ("Content-type: application/json");
include '../config/conn.php';



function register_system_action($conn){
    extract($_POST);
    $data = array();

    //build the query
    $query = "INSERT INTO system_actions (name, action,link_id) values ('$name','$system_action','$link_id')";

    //execution
    $result = $conn->query($query);

    if ($result){
            $data = array('status' => true, 'data' => 'Successfully Registered');
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}

function update_system_action($conn){
    extract($_POST);
    $data = array();

    //build the query
    $query = "UPDATE  system_actions set name = '$name', action = '$system_action', link_id = '$link_id' where id = '$id'";

    //execution
    $result = $conn->query($query);

    if ($result){
            $data = array('status' => true, 'data' => 'Successfully Updated');
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}

function read_all_system_actions($conn){
    $data = array();
    $array_data = array();
    $query = "SELECT * from system_actions";
    $result = $conn->query($query);
    if($result){
        while($row =  $result->fetch_assoc()){
            $array_data[] = $row;
        }
        $data = array('status' => true, 'data' => $array_data);
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}

function get_action_info($conn){

    $data = array();
    $array_data = array();
    extract($_POST);

    $query = "SELECT * FROM `system_actions` WHERE id='$id'";
    $result = $conn->query($query);

    if ($result){
        $row = $result->fetch_assoc();
        $data = array('status' => true, 'data' => $row);
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}



function delete_action_info($conn){

    $data = array();
    $array_data = array();
    extract($_POST);

    $query = "DELETE FROM `system_actions` WHERE id='$id'";
    $result = $conn->query($query);

    if ($result){
        $data = array('status' => true, 'data' => "Deleted Successfully");
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}

if(isset($_POST['action'])){
    $action = $_POST['action'];
    $action($conn);
}else{
    echo json_encode(array('status' => false, 'data' => 'Action required'));
}

?>