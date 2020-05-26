<?php
    require_once '../header.php';
    
    require_once '../../config/Database.php';
    require_once '../../models/Publisher.php';
    
    $db = new Database;
    $conn = $db->connect();

    $publisher = new Publisher($conn);
    $result = $publisher->read();

    $num = $result->rowCount();

    if($num > 0){
        $publisher_arr = array();
        $publisher_arr['data'] = array();
        $publisher_items = array();

        while($row = $result->fetch()){
            $publisher_items = array(
                'publisherId' => $row->id,
                'publisherName' => $row->publisher,
                'description' => $row->description
            );
            array_push($publisher_arr['data'],$publisher_items);
        }
        echo json_encode($publisher_arr);
    }
    else {
        echo json_encode(array(
            'message' => 'No Match Found'
        ));
    }