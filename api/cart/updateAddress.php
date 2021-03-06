<?php
require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Cart.php';

$db = new Database;
$conn = $db->connect();
$cart = new Cart($conn);

$data = json_decode(file_get_contents('php://input'));
$cart->userId = $data->userId;
$cart->addressId = $data->addressId;

if($cart->updateAddress()){
    echo json_encode(array(
        'message' => 'Address Added'
    ));
}
else{
    echo json_encode(array(
        'message' => 'Address Not Added'
    ));
}
