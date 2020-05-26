<?php

require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Cart.php';

$db = new Database;
$conn = $db->connect();
$cart = new Cart($conn);

$data = json_decode(file_get_contents('php://input'));
$cart->userId = $data->userId;
$cart->getCartId();

$result = $cart->getOrderSummary();
$num = $result->rowCount();
if($num > 0 ){
    $summary_item = array();

    $row = $result->fetch();
    $cart->updateTotalPrice($row->totalPrice);
    $summary_item = array(
        'quantity' => $row->quantity,
        'totalPrice' => $row->totalPrice
    );

    echo json_encode($summary_item);

} else {
    echo json_encode(array(
        'message'=> 'No Results'
    ));
}