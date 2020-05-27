<?php

require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Cart.php';

$db = new Database;
$conn = $db->connect();
$cart = new Cart($conn);

$result = $cart->readPaymentMethod();

$num = $result->rowCount();
if($num > 0){
    $payment_arr = array();
    $payment_arr['data'] = array();
    $payment_items = array();
    
    while($row = $result->fetch()){
        $payment_items = array(
            'method' => $row->method,
            'id' => $row->id
        );
        array_push($payment_arr['data'],$payment_items);
    }
    echo json_encode($payment_arr['data']);
}