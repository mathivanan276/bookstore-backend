<?php
require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Personaldetails.php';

$db = new Database;
$conn = $db->connect();

$personal = new Personaldetails($conn);
$personal->userId = $_GET['userid'];
$result = $personal->read();

$num = $result->rowCount();

if($num > 0){
    $post_arr = array();
    $row = $result->fetch();
    $post_arr['data'] = array(
        'id' => $row->id,
        'firstname' => $row->firstname,
        'lastname' => $row->lastname,
        'email' => $row->email,
        'phone' => $row->phone
    );
    
    echo json_encode($post_arr);
}
else {
    echo json_encode(array(
        'message' => 'No Match Found'
    ));
}