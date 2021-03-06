<?php
    require_once '../header.php';
    require_once '../../models/States.php';
    require_once '../../config/Database.php';

    $db = new Database;
    $conn = $db->connect();
    $states = new States($conn);

    $data = json_decode(file_get_contents('php://input'));
    $states->country_id = 105;
    $states->state = $_GET['state_name'];
    $result = $states->read_state_id();

    $num = $result->rowCount();

    if($num > 0){
        $state_arr = array();
        $state_arr['data'] = array();
        $state_items = array();

        while($row = $result->fetch()){
            $state_items = array(
                'stateId' => $row->id,
                'name' => $row->name
            );
            array_push($state_arr['data'],$state_items);
        }   
        echo json_encode($state_arr);
    }
    else{
        echo json_encode(array(
            'message' => 'No Match Found'
        ));
    }