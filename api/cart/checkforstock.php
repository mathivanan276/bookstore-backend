<?php
require_once '../header.php';

require_once '../../config/Database.php';
require_once '../../models/Cart.php';

$db = new Database;
$conn = $db->connect();
$cart = new Cart($conn);

$data = json_decode(file_get_contents('php://input'));
$cart->userId = $data->userId;
if($cart->getCartId()){
$result = $cart->seeForOutOfStock();
$num = $result->rowCount();

if($num > 0){
    $item_arr=array();
    $item_arr['data'] = array();
    $item_items = array();
    while($row = $result->fetch()){
        $item_items = array(
            'itemId' => $row->id,
            'noofitems' => $num
        );
        array_push($item_arr['data'],$item_items);
    }
    
    echo json_encode($item_arr['data']);
    
} else {
    echo json_encode(array(
        'message' => 'No change'
    ));
}
} else {
    echo json_encode('no cart ');
}