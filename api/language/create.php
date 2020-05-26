<?php
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Language.php';

    $db = new Database;
    $conn = $db->connect();
    $lang = new Language($conn);

    $data = json_decode( file_get_contents("php://input"));
    $lang->language = $data->language;
    $lang->languageId = $data->languageId;

    if($lang->create()){
        echo json_encode(array(
            'message'=>'Language Created'
        ));
    }
    else{
        echo json_encode(array(
            'message'=>'Not Created'
        ));
    }