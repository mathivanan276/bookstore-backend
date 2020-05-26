<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: PUT,GET,POST');
    header('Content-Type: application/json');

    require_once '../../config/Database.php';
    require_once '../../models/Stock.php';

    $db = new Database;
    $conn = $db->connect();
    $stock = new Stock($conn);

    $data = json_decode(file_get_contents("php://input"));
    $stock->bookId = $data->bookId;
    $stock->value = $data->value;

    if($stock->create()){
        echo json_encode(array(
            'message'=>'Stock Added'
        ));
    }
    else {
        echo json_encode(array(
            'message'=>'Not Added'
        ));
    }