<?php
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/User.php';

    $db = new Database;
    $conn = $db->connect();
    $user = new User($conn);

    $data = json_decode(file_get_contents("php://input"));
    $user->username = $data->username;
    $user->password = $data->password;
    $user->email = $data->email;
    $user->role = 1;
    
    $result = $user->login();
    $num = $result->rowCount();
    if($num >0){
        $row = $result->fetch();
        $user_item = array(
            'id' => $row->id,
            'username' => $row->username,
            'email' => $row->email,
            'role' => 'user',
            'message' => 'Matched'
        );

        echo json_encode($user_item);
    }
    else {
        echo json_encode(array(
            'message' => 'No Match Found'
        ));
    }
    
