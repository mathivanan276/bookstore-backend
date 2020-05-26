<?php

require_once '../header.php';
require_once '../../models/Address.php';
require_once '../../config/Database.php';

$db = new Database;
$conn = $db->connect();
$address = new Address($conn);

$data = json_decode(file_get_contents('php://input'));
$address->addressId = $data->addressId;
$address->recipientName = $data->recipientName;
$address->companyName = $data->companyName;
$address->streetAddress = $data->streetAddress;
$address->city = $data->city;
$address->country = $data->country;
$address->state = $data->state;
$address->pin = $data->pin;
$address->userId = $data->userId;

if($address->update()){
    echo json_encode(array(
        'message' => 'Address Updated'
    ));
}
else{
    echo json_encode(array(
        'message' => 'Address Not Updated'
    ));
}