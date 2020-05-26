<?php

require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Cart.php';

$db = new Database;
$conn = $db->connect();
$cart = new Cart($conn);

$data = json_decode(file_get_contents('php://input'));
$cart->itemId = $data->itemId;
$cart->qty = $data->quantity;
if($cart->getBookPriceWithItemId() && $cart->checkForStock()){
    if($cart->stock >= $cart->qty){
        $cart->itemPrice = $cart->qty * $cart->price;
    }
    else{
        echo json_encode(array(
            'message' =>'Insufficient Stock'
        ));
        exit();
    }
}
else{
    echo json_encode(array(
        'message' => 'error in getting book price'
    ));
    exit();
}


if($cart->updateItemQty()){
    echo json_encode(array(
        'message' => 'Item updated'
    ));
}
