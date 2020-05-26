<?php
require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Publisher.php';

$db = new Database;
$conn = $db->connect();

$publisher = new Publisher($conn);
$data = json_decode(file_get_contents("php://input"));
$publisher->publisherId = $data->publisherId;
$publisher->publisherName = $data->publisherName;
$publisher->description = $data->description;

if($publisher->update()) {
    echo json_encode(array(
        'message'=>'Updated Successfully'
    ));
}
else {
    echo json_encode(array(
        'message'=>'Not Updated'
    ));
}