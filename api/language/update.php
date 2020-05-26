<?php
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Language.php';

    $db = new Database;
    $conn = $db->connect();
    $lang = new Language($conn);

    $data = json_decode(file_get_contents("php://input"));
    $lang->languageId = $data->languageId;
    $lang->language = $data->language;

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