<?php
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Author.php';

    $db = new Database;
    $conn = $db->connect();
    $author = new Author($conn);

    $result = $author->read();

    $num = $result->rowCount();

    if($num > 0){
        $author_arr = array();
        $author_arr['data'] = array();
        $author_items = array();

        while($row = $result->fetch()){
            $author_items = array(
                'authorid' => $row->id,
                'authorname' => $row->authorName,
                'authordescription' => $row->description
            );
            array_push($author_arr['data'],$author_items);
        }
        echo json_encode($author_arr);
    }
    else {
        echo json_encode(array(
            'message' => 'No Match Found'
        ));
    }