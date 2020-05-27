<?php
class Orders {

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getConfirmedOrders(){
        $sql = 'SELECT ';
    }

}