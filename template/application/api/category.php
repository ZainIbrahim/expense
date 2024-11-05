<?php
header ("Content-type: application/json");
include '../config/conn.php';

function register_category($conn){
    extract($_POST);
    $data = array();

    //build the query
    $query = "INSERT INTO `category`(`name`, `icon`, `role`) VALUES('$name','$icon','$role') ";

    //execution
    $result = $conn->query($query);

    if ($result){
            $data = array('status' => true, 'data' => 'Registered successfully');
        
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}

function read_all_category($conn){
    $data = array();
    $array_data = array();
    $query = "SELECT * FROM `category` WHERE 1";
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

function get_category_info($conn){

    $data = array();
    $array_data = array();
    extract($_POST);

    $query = "SELECT * FROM `category` WHERE id='$id'";
    $result = $conn->query($query);

    if ($result){
        $row = $result->fetch_assoc();
        $data = array('status' => true, 'data' => $row);
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}

function update_category($conn){
    extract($_POST);
    $data = array();

    //build the query
    $query = "UPDATE category set name = '$name', icon ='$icon', role = '$role' where id= '$id'";

    //execution
    $result = $conn->query($query);

    if ($result){
        $data = array('status' => true, 'data' => 'Updated Successfully');
        
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}

function delete_category_info($conn){

    $data = array();
    $array_data = array();
    extract($_POST);

    $query = "DELETE FROM `category` WHERE id='$id'";
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