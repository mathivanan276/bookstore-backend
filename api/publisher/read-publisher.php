<?php
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Publisher.php';

    $db = new Database;
    $conn = $db->connect();
    $publisher = new Publisher($conn);

    $publisher->publisherName = $_GET['publisherName'];

    $result = $publisher->read_publisher();

    $num = $result->rowCount();

    if($num > 0){
        $publisher_arr = array();
        $row = $result->fetch();
        $publisher_arr['data'] = array(
            'publisherId' => $row->id,
            'publisherName' => $row->publisher,
            'description' => $row->description
        );
        echo json_encode($publisher_arr);
    }
    else {
        echo json_encode(array(
            'message' => 'No Match Found'
        ));
    }