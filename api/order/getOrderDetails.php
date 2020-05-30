<?php
require_once '../header.php';
require_once '../../config/Database.php';
require_once '../../models/Orders.php';

$db = new Database;
$conn = $db->connect();
$order = new Orders($conn);

$data = json_decode(file_get_contents('php://input'));
$order->orderId = $data->orderId;

$result = $order->getOrder();
$num = $result->rowCount();
if($num >= 0){
    $row = $result->fetch();
        $order_items = array(
            'orderId' => $row->orderId,
            'ordered_on' => $row->ordered_on,
            'username' =>$row->username,
            'cartId' => $row->cartId,
            'paymentMethod' => $row->paymentMethod,
            'address' => $row->street.' '.$row->city.' '.$row->state.' Pin:'.$row->pin.' company:'.$row->companyName.' Recipient:'.$row->recipientName,
            'userId' => $row->userId
        );
    echo json_encode($order_items);
}
else {
    echo json_encode(array(
        'message' => 'No orders'
    ));
}