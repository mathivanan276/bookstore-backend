<?php
    require_once '../header.php';

    require_once '../../config/Database.php';
    require_once '../../models/Book.php';

    $db = new Database;
    $conn = $db->connect();
    $book = new Book($conn);

    $data = json_decode( file_get_contents("php://input"));
    $book->title = $data->title;
    $book->isbn = $data->isbn;
    $book->authorId = $data->authorId;
    $book->publisherId = $data->publisherId;
    $book->categoryId = $data->categoryId;
    $book->description = $data->description;
    $book->year = $data->year;
    $book->url = $data->url;
    $book->price = $data->price;
    $book->page = $data->page;
    $book->lang = $data->lang;
    // $book->title = $_POST['title'];
    // $book->ISBN = $_POST['ISBN'];
    // $book->authorId = $_POST['authorId'];
    // $book->publisherId = $_POST['publisherId'];
    // $book->categoryId = $_POST['categoryId'];
    // $book->year = $_POST['year'];
    // $book->description = $_POST['description'];
    if($book->create()){
        $stock = $book->create_stock();
        if($stock == true){
            echo json_encode(array(
                'message'=>'book Created'
            ));
        }
    }
    else{
        echo json_encode(array(
            'message'=>'Not Created'
        ));
    }