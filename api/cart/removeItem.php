<?php

require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Cart.php';

$db = new Database;
$conn = $db->connect();
$cart = new Cart($conn);

$data = json_decode(file_get_contents('php://input'));
$cart->itemId = $data->itemId;

if($cart->removeItems()){
    echo json_encode(array(
        'message' => 'Item removed'
    ));
}