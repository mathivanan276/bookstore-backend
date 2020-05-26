<?php
require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Author.php';

$db = new Database;
$conn = $db->connect();

$author = new Author($conn);
$data = json_decode(file_get_contents("php://input"));
$author->authorId = $data->authorId;
$author->authorName = $data->authorName;
$author->description = $data->description;

if($author->update()) {
    echo json_encode(array(
        'message'=>'Updated Successfully'
    ));
}
else {
    echo json_encode(array(
        'message'=>'Not Updated'
    ));
}