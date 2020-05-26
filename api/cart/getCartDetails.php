<?php
require_once '../header.php';
require_once '../../config/Database.php';
require_once '../../models/Cart.php';

$db = new Database;
$conn = $db->connect();
$cart = new Cart($conn);

$data = json_decode(file_get_contents('php://input'));
$cart->userId = $data->userId;

$result = $cart->getCartDetails();
$num = $result->rowCount();
if($num > 0){
    $cart_details = array();
    $cart_details['data'] = array();
    $cart_details['address'] = array();
    $cart_detail = array();

    while($row = $result->fetch()){
        $cart_detail = array(
            'url' => $server_url.$row->url,
            'title' => $row->title,
            'price' => $row->price,
            'quantity' => $row->quantity,
            'itemPrice' => $row->itemPrice,
            'itemId' =>$row->itemId
        );
        $cart_address = array(
            'address' => $row->streetAddress.' '.$row->city.' '.$row->state.' Pin:'.$row->pin.' company:'.$row->companyName.' Recipient:'.$row->recipientName,
            'addressId' => $row->addressId
        );
        array_push($cart_details['data'],$cart_detail);
    }
    array_push($cart_details['address'],$cart_address);
    echo json_encode($cart_details);
}
else{
    echo json_encode(array(
        'message' => 'No Match Found'
    ));
}