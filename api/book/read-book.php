<?php
    
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Book.php';

    $server_url = 'http://localhost/bookstore/api/book'; 

    $db = new Database;
    $conn = $db->connect();
    $book = new Book($conn);

    $book->title = $_GET['title'];

    $result = $book->read_book();

    $num = $result->rowCount();

    if($num > 0){
        $book_arr = array();
        $row = $result->fetch();
        $book_arr['data'] = array(
            'bookId' => $row->id,
            'title' => $row->title,
            'isbn' => $row->isbn,
            'authorName' => $row->authorName,
            'authorId' => $row->authorId,
            'categoryId' => $row->categoryId,
            'categoryName' => $row->categoryName,
            'publisherId' => $row->publisherId,
            'publisherName' => $row->publisherName,
            'year' => $row->year,
            'description'=> $row->description,
            'url'=> ($server_url .'/'. $row->url),
            'price' => $row->price,
            'language' => $row->language,
            'languageId' => $row->lang,
            'page' => $row->page,
            'stock' => $row->stock
        );
        echo json_encode($book_arr);
    }
    else {
        echo json_encode(array(
            'message' => 'No Match Found'
        ));
    }