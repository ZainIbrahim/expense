<?php
header ("Content-type: application/json");
include '../config/conn.php';

function register_expense($conn){
    extract($_POST);
    $data = array();

    //build the query
    $query = "CALL register_expense_sp('1','$amount','$type','$description','USR001')";

    //execution
    $result = $conn->query($query);

    if ($result){
        $row = $result->fetch_assoc();
        if($row['Message'] == 'Deny'){
            $data = array('status' => false, 'data' => 'Insuficient balance');
        }elseif($row['Message'] == 'Registered'){
            $data = array('status' => true, 'data' => 'Registered Successfully');
        }
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}

function get_user_transaction($conn){
    $data = array();
    $array_data = array();
    $query = "SELECT `id`, `amount`, `type`, `description` FROM `expense` WHERE 1";
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

function get_expense_info($conn){

    $data = array();
    $array_data = array();
    extract($_POST);

    $query = "SELECT * FROM `expense` WHERE expense.id='$id'";
    $result = $conn->query($query);

    if ($result){
        $row = $result->fetch_assoc();
        $data = array('status' => true, 'data' => $row);
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}

function update_expense($conn){
    extract($_POST);
    $data = array();

    //build the query
    $query = "UPDATE expense set amount = '$amount', type ='$type', description = '$description' where id= '$id'";

    //execution
    $result = $conn->query($query);

    if ($result){
        $data = array('status' => true, 'data' => 'Updated Successfully');
        
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    echo json_encode($data);
}

function delete_expense_info($conn){

    $data = array();
    $array_data = array();
    extract($_POST);

    $query = "DELETE FROM `expense` WHERE expense.id='$id'";
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