<?php
require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Cart.php';

$db = new Database;
$conn = $db->connect();
$cart = new Cart($conn);

$data = json_decode(file_get_contents('php://input'));

$cart->bookId = $data->bookId;
if($cart->getBookPrice()){
    if($data->quantity != null){
        $cart->qty = $data->quantity;
    } else {
        $cart->qty = 1;
    }      
}
else {
    echo json_encode(array(
        'message' => 'error in getting price'
    ));
    exit();
}
$cart->itemPrice = $cart->price * $cart->qty;
$cart->userId = $data->userId;


if($cart->createCartForUser()){
    if($cart->getCartId()){
        if($cart->insertItems()){
            echo json_encode(array(
                'message' => 'Item Added'
            ));
        } 
    }
}