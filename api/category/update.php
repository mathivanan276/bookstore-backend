<?php
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Category.php';

    $db = new Database;
    $conn = $db->connect();
    $category = new Category($conn);

    $data = json_decode(file_get_contents("php://input"));
    $category->categoryId = $data->categoryId;
    $category->category = $data->category;

    if($category->update()){
        echo json_encode(array(
            'message'=>'category Updated'
        ));
    }
    else {
        echo json_encode(array(
            'message'=>'Not Updated'
        ));
    }