<?php
header ("Content-type: application/json");
include '../config/conn.php';

function register_user($conn){
    extract($_POST);
    $data = array();
    $new_id = generate($conn);
    $error_array = array();

    $file_name = $_FILES['image']['name'];
    $file_type = $_FILES['image']['type'];
    $file_size = $_FILES['image']['size'];

    $save_name = $new_id . ".png";

    //allowed images
    $allowedImages = ["image/jpg", "image/jpeg", "image/png"];
    $max_size = 5 * 1024 * 1024;

    if(in_array($file_type,$allowedImages)){
        if($file_size > $max_size){
            $error_array[] = "File size must be less than ".$max_size;
        }

    }else{
        $error_array[] = "This file is not allowed";
    }

    //build the query
    if (count($error_array) <= 0){
        //$query = `INSERT INTO users('id', 'username', 'password', 'image') VALUES (
          //  '$new_id', '$username', MD5('$password'), '$save_name')`;
        $query = "INSERT INTO users (id, username, password, image) VALUES ('$new_id', '$username', MD5('$password'), '$save_name')";

    
        //execution
        $result = $conn->query($query);
    
        if ($result){
            move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$save_name);
            $data = array('status' => true, 'data' => "successfully registered");

        }else{
            $data = array('status' => false, 'data' => $conn->error);
        }
    }else{
        $data = array('status' => false, 'data' => $error_array);
    }

    echo json_encode($data);
}

function generate($conn){
    $new_id = '';
    $data = array();
    $array_data = array();
    $query = "SELECT * FROM `users` order by users.id DESC limit 1";
    $result = $conn->query($query);
    if($result){
        $num_rows = $result->num_rows;
        if($num_rows > 0){
            $row =  $result->fetch_assoc();
            $new_id = ++$row['id'];
        }else{
            $new_id = "USR001";
        }
 
    }else{
        $data = array('status' => false, 'data' => $conn->error);
    }
    return $new_id;
}


function get_users_list($conn){
    $data = array();
    $array_data = array();
    $query = "SELECT * FROM users";
    $result = $conn->query($query);

    if ($result){
        while ($row = $result->fetch_assoc()){
            $array_data[] = $row;
        }
        $data = array("status" => true, "data" => $array_data);
    }else{
        $data = array("status" => false, "data" => $conn->error);
    }
    echo json_encode ($data);
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