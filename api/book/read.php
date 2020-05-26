<?php 
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Book.php';

    $db = new Database;
    $conn = $db->connect();
    $book = new Book($conn);

    $result = $book->read();

    $num = $result->rowCount();

    if($num > 0){
        $book_arr = array();
        $book_arr['data'] = array();
        $book_items = array();

        while($row = $result->fetch()){
            $book_items = array(
                'bookid' => $row->id,
                'title' => $row->title,
                'url' => ($server_url . $row->url),
                'description' => $row->description,
                'authorName' => $row->authorName,
                'publisherName' => $row->publisherName,
                'categoryName' => $row->categoryName,
                'year' => $row->year,
                'price' => $row->price,
                'page' => $row->page,
                'language' => $row->language,
                'languageId' => $row->lang,
                'stock' => $row->stock
            );
            array_push($book_arr['data'],$book_items);
        }
        echo json_encode($book_arr);
    }
    else {
        echo json_encode(array(
            'message' => 'No Match Found'
        ));
    }