<?php
require_once '../header.php';
require_once '../../models/Address.php';
require_once '../../config/Database.php';

$db = new Database;
$conn = $db->connect();
$address = new Address($conn);

$address->addressId = $_GET['address_id'];

$result = $address->read_address();

$num = $result->rowCount();

if($num>0){
    $address_arr = array();
    $address_arr['data'] = array();
    $address_items = array();

    while($row = $result->fetch()){
        $address_items = array(
            'addressId' => $row->id,
            'recipientName'=> $row->recipientName,
            'companyName'=> $row->companyName,
            'streetAddress'=> $row->streetAddress,
            'country'=> $row->country,
            'state'=> $row->state,
            'city'=> $row->city,
            'pin'=> $row->pin,
            'row_delete' => $row->row_deleted
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