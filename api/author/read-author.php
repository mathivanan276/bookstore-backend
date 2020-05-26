<?php
    
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Author.php';

    $db = new Database;
    $conn = $db->connect();
    $author = new Author($conn);

    $author->authorName = $_GET['authorName'];

    $result = $author->read_author();

    $num = $result->rowCount();

    if($num > 0){
        $author_arr = array();
        $row = $result->fetch();
        $author_arr['data'] = array(
            'authorId' => $row->id,
            'authorName' => $row->authorName,
            'authorDescription' => $row->description
        );
        echo json_encode($author_arr);
    }
    else {
        echo json_encode(array(
            'message' => 'No Match Found'
        ));
    }