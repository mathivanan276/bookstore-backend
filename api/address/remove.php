<?php

require_once '../header.php';
require_once '../../models/Address.php';
require_once '../../config/Database.php';

$db = new Database;
$conn = $db->connect();
$address = new Address($conn);

$address->addressId = $_GET['address_id'];

if($address->remove()){
    echo json_encode(array(
        'message' => 'Address Removed'
    ));
}
else{
    echo json_encode(array(
        'message' => 'Address Not Removed'
    ));
}