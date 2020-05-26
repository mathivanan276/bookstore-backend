<?php
require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/User.php';

$db = new Database;
$conn = $db->connect();

$user = new User($conn);
$user->username = $_GET['username'];
$user->password = $_GET['password'];
$result = $user->read();

$num = $result->rowCount();

if($num > 0){
    $post_arr = array();
    $row = $result->fetch();
    $post_arr['data'] = array(
        'id' => $row->id,
        'username' => $row->username,
        'email' => $row->email,
        'role' => $row->role
    );
    echo json_encode($post_arr);
}
else {
    echo json_encode(array(
        'message' => 'No Match Found'
    ));
}