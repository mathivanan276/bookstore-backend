<?php

require_once '../header.php';
require_once '../../models/Address.php';
require_once '../../config/Database.php';

$db = new Database;
$conn = $db->connect();
$address = new Address($conn);

$data = json_decode(file_get_contents('php://input'));
$address->userId = $data->userId;
$address->recipientName = $data->recipientName;
$address->companyName = $data->companyName;
$address->streetAddress = $data->streetAddress;
$address->city = $data->city;
$address->country = $data->country;
$address->state = $data->state;
$address->pin = $data->pin;

if($address->create()){
    $result = $address->getAddressId();
    $row = $result->fetch();
    echo json_encode(array(
        'addressId' => $row->addressId
    ));
}
else{
    echo json_encode(array(
        'message' => 'Address Not Added'
    ));
}