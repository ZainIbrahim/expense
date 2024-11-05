<?php
header ("Content-type: application/json");
include '../config/conn.php';

function readAllLinks(){
    $data = array();
    $data_array = array();
    $search_result = glob('../views/*.php');
    foreach($search_result as $sr){
        $pure_link = explode("/", $sr);
        $data_array[] = $pure_link[2];
    }
    if (count ($search_result)>0){
        $data = array("status" => true, "data" => $data_array);
    }else{
        $data = array("status" => false, "data" => 'Not found');
    }
    echo json_encode ($data);
}

function register_link($conn){
    extract($_POST);
    $data = array();

    //build the query
    $query = "INSERT INTO system_links (name, link, category_id) values ('$name', '$link', '$category')";

    //execution
    $result = $conn->query($query);

    if ($result){
            $data = array('status' => true, 'data' => 'Successfully Registered');
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}

function read_all_db_links($conn){
    $data = array();
    $array_data = array();
    $query = "SELECT * from system_links";
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

function get_link_info($conn){

    $data = array();
    $array_data = array();
    extract($_POST);

    $query = "SELECT * FROM `system_links` WHERE id='$id'";
    $result = $conn->query($query);

    if ($result){
        $row = $result->fetch_assoc();
        $data = array('status' => true, 'data' => $row);
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}

function update_link($conn){
    extract($_POST);
    $data = array();

    //build the query
    $query = "UPDATE system_links set name = '$name', link ='$link', category_id = '$category' where id= '$id'";

    //execution
    $result = $conn->query($query);

    if ($result){
        $data = array('status' => true, 'data' => 'Updated Successfully');
        
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}

function delete_link_info($conn){

    $data = array();
    $array_data = array();
    extract($_POST);

    $query = "DELETE FROM `system_links` WHERE id='$id'";
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