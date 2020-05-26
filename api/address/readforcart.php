<?php
require_once '../header.php';
require_once '../../models/Address.php';
require_once '../../config/Database.php';

$db = new Database;
$conn = $db->connect();
$address = new Address($conn);

$address->userId = $_GET['userId'];

$result = $address->read();

$num = $result->rowCount();

if($num>0){
    $address_arr = array();
    $address_arr['data'] = array();
    $address_items = array();

    while($row = $result->fetch()){
        $address_items = array(
            'address' => $row->streetAddress.' '.$row->city.' '.$row->state.' Pin:'.$row->pin.' company:'.$row->companyName.' Recipient:'.$row->recipientName,
            'addressId' => $row->id
        );
        array_push($address_arr['data'],$address_items);
    }
    echo json_encode($address_arr);
}
else{
    echo json_encode(array(
        'message' => 'No Results Found'
    ));
}