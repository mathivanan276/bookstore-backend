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
$result = $cart->getCartItems();
$num = $result->rowCount();
if($num > 0 ){
    $cart_arr = array();
    $cart_arr['data'] = array();
    $cart_items= array();

    while($row = $result->fetch()){
        $cart_items = array(
            'itemId' => $row->itemId,
            'quantity' => $row->quantity,
            'itemPrice' => $row->itemPrice,
            'price' => $row->price,
            'bookId' => $row->bookId,
            'title' => $row->title,
            'authorName' => $row->authorName,
            'publisherName' => $row->publisher,
            'url' => $server_url . $row->url,
            'stock' => $row->stock
        );
        array_push($cart_arr['data'],$cart_items);
    }
    echo json_encode($cart_arr);
}

else{
    echo json_encode(array(
        'message' => 'No Result Found'
    ));
}