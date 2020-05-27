<?php
require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Cart.php';

$db = new Database;
$conn = $db->connect();
$cart = new Cart($conn);

$data = json_decode(file_get_contents('php://input'));
$cart->userId = $data->userId;
$cart->paymentMethod = $data->paymentMethod;

if($cart->updatePaymentMethod()){
    $cart->getCartId();
    if($cart->createOrder()){
        echo json_encode(array(
            'message' => 'Payment Method Added'
        ));
    }
}
else{
    echo json_encode(array(
        'message' => 'Payment Method Not Added'
    ));
}
