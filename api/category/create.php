<?php
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Category.php';

    $db = new Database;
    $conn = $db->connect();
    $category = new Category($conn);

    $data = json_decode(file_get_contents("php://input"));
    $category->category = $data->category;

    if($category->create()){
        echo json_encode(array(
            'message'=>'category Added'
        ));
    }
    else {
        echo json_encode(array(
            'message'=>'Not Added'
        ));
    }