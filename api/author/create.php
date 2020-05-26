<?php
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Author.php';
    
    $db = new Database;
    $conn = $db->connect();
    $author = new Author($conn);

    $data = json_decode(file_get_contents("php://input"));
    $author->authorName = $data->authorName;
    $author->description = $data->description; 

    if($author->create()){
        echo json_encode(array(
            'message'=>'Author Added'
        ));
    }
    else {
        echo json_encode(array(
            'message'=>'Not Added'
        ));
    }