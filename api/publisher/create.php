<?php
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Publisher.php';

    $db = new Database;
    $conn = $db->connect();
    $publisher = new Publisher($conn);

    $data = json_decode(file_get_contents("php://input"));
    $publisher->publisherName = $data->publisherName;
    $publisher->description = $data->description;

    if($publisher->create()){
        echo json_encode(array(
            'message'=>'Publisher Added'
        ));
    }
    else {
        echo json_encode(array(
            'message'=>'Not Added'
        ));
    }