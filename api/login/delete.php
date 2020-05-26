<?php
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/User.php';
    
    $db = new Database;
    $conn = $db->connect();
    $user = new User($conn);

    $data = json_decode(file_get_contents("php://input"));
    $user->id = $data->id;

    if($user->delete()) {
        echo json_encode(array(
            'message'=>'Deleted Successfully'
        ));
    }
    else {
        echo json_encode(array(
            'message'=>'Not Deleted'
        ));
    }