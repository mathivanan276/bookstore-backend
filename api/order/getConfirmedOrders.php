<?php
require_once '../header.php';
require_once '../../config/Database.php';
require_once '../../models/Orders.php';

$db = new Database;
$conn = $db->connect();
$order = new Orders($conn);

$result = $order->getConfirmedOrders();
$num = $result->rowCount();
if($num > 0){
    $order_arr = array();
    $order_arr['data'] = array();
    $order_items = array();
    while($row = $result->fetch()){
        $order_items = array(
            'orderId' => $row->orderId,
            'ordered_on' => $row->ordered_on,
            'username' =>$row->username,
            'cartId' => $row->cartId,
            'paymentMethod' => $row->paymentMethod,
            'userId' => $row->userId
        );
        array_push($order_arr['data'],$order_items);
    }
    echo json_encode($order_arr);
}
else {
    echo json_encode(array(
        'message' => 'No orders'
    ));
}