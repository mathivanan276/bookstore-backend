<?php
    require_once '../header.php';
    
    require_once '../../config/Database.php';
    require_once '../../models/Stock.php';
    
    $db = new Database;
    $conn = $db->connect();

    $stock = new Stock($conn);
    $result = $stock->read();

    $num = $result->rowCount();

    if($num > 0){
        $stock_arr = array();
        $stock_arr['data'] = array();
        $stock_items = array();

        while($row = $result->fetch()){
            $stock_items = array(
                'stockId' => $row->stockId,
                'title' => $row->title,
                'value' => $row->value
            );
            array_push($stock_arr['data'],$stock_items);
        }
        echo json_encode($stock_arr);
    }
    else {
        echo json_encode(array(
            'message' => 'No Match Found'
        ));
    }