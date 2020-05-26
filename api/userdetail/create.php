<?php
    header('Access-Contorl-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods
    ,Authorization, X-Requested-With');

    require_once '../../config/Database.php';
    require_once '../../models/Personaldetails.php';
    
    $db = new Database;
    $conn = $db->connect();
    $personal = new Personaldetails($conn);

    $data = json_decode(file_get_contents("php://input"));
    $personal->firstName = $data->firstname;
    $personal->lastName = $data->lastname;
    $personal->phone = $data->phone;
    $personal->userId = $data->userid;

    // $personal->firstName = 'mahri';
    // $personal->lastName = 'vanan';
    // $personal->phone = '8055548558';
    // $personal->userId = 3;

    if($personal->create()) {
        echo json_encode(array(
            'message'=>'Added Successfully'
        ));
    }
    else {
        echo json_encode(array(
            'message'=>'Not Added'
        ));
    }