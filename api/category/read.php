<?php
    require_once '../header.php';
    
    require_once '../../config/Database.php';
    require_once '../../models/Category.php';
    
    $db = new Database;
    $conn = $db->connect();

    $category = new Category($conn);
    $result = $category->read();

    $num = $result->rowCount();

    if($num > 0){
        $category_arr = array();
        $category_arr['data'] = array();
        $category_items = array();

        while($row = $result->fetch()){
            $category_items = array(
                'categoryid' => $row->id,
                'category' => $row->category,
            );
            array_push($category_arr['data'],$category_items);
        }
        echo json_encode($category_arr);
    }
    else {
        echo json_encode(array(
            'message' => 'No Match Found'
        ));
    }