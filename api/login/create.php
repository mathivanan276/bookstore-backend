<?php
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/User.php';
    require_once '../../models/Personaldetails.php';
    
    $db = new Database;
    $conn = $db->connect();
    $user = new User($conn);
    $pd = new Personaldetails($conn);

    $data = json_decode(file_get_contents("php://input"));
    $user->username = $data->username;
    $user->password = $data->password;
    $user->email = $data->email;
    $user->role = $data->role;

    $pd->firstName = $data->username;
    $pd->email = $data->email;

    if($user->create()) {
        $result=$user->read();
        $row = $result->fetch();
        $pd->userId = $row->id;
        if($pd->create()){
            echo json_encode(array( 
                'message'=>'Added Successfully'
            ));
        }
    }
    else {
        echo json_encode(array(
            'message'=>'Not Added'
        ));
    }