<?php

require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Book.php';

$db = new Database;
$conn = $db->connect();
$book = new Book($conn);

$data = json_decode( file_get_contents("php://input"));
$book->bookId = $data->bookId;
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
$book->lang = $data->languageId;

if($book->update()){
    echo json_encode(array(
        'status' => true,
        'message'=>'book update'
    ));
}
else{
    echo json_encode(array(
        'status' => false,
        'message'=>'not updated'
    ));
}