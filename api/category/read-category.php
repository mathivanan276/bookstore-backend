<?php
    
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Category.php';

    $db = new Database;
    $conn = $db->connect();
    $category = new Category($conn);

    $category->category = $_GET['categoryName'];

    $result = $category->read_category();

    $num = $result->rowCount();

    if($num > 0){
        $category_arr = array();
        $row = $result->fetch();
        $category_arr['data'] = array(
            'categoryId' => $row->id,
            'categoryName' => $row->category,
        );
        echo json_encode($category_arr);
    }
    else {
        echo json_encode(array(
            'message' => 'No Match Found'
        ));
    }