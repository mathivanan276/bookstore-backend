<?php
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Stock.php';

    $db = new Database;
    $conn = $db->connect();
    $stock = new Stock($conn);

    $data = json_decode(file_get_contents("php://input"));
    $stock->stockId = $data->stockId;
    $stock->value = $data->value;

    if($stock->update()){
        echo json_encode(array(
            'message'=>'Stock Updated'
        ));
    }
    else {
        echo json_encode(array(
            'message'=>'Not Updated'
        ));
    }