<?php
require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Personaldetails.php';

$db = new Database;
$conn = $db->connect();

$personal = new Personaldetails($conn);
$data = json_decode(file_get_contents("php://input"));
$personal->phone = $data->phone;
$personal->firstName = $data->firstname;
$personal->lastName = $data->lastname;
$personal->userId = $_GET['userid'];

if($personal->update()) {
    echo json_encode(array(
        'message'=>'Updated Successfully'
    ));
}
else {
    echo json_encode(array(
        'message'=>'Not Updated'
    ));
}