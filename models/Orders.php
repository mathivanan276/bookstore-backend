<?php
class Orders {

    private $conn;
    public $orderId;

    public function __construct($db){
        $this->conn = $db;
    }

    public function getConfirmedOrders(){
        $sql = 'SELECT 
                o.id as orderId,
                o.createdOn as ordered_on,
                u.username as username,
                c.id as cartId,
                c.paymentMethod as paymentMethod,
                u.id as userId
                FROM orders o
                LEFT JOIN cart c ON c.id = o.cartId
                LEFT JOIN orderStatus s ON s.id = o.shippingStatus
                LEFT JOIN user u ON c.userid = u.id
                LEFT JOIN address a ON a.id = c.addressId 
                WHERE
                s.status = "confirmed"
                ORDER BY o.updated_on desc';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':orderId',$this->orderId);
        try {
            if($stat->execute()){
                return $stat;
            }
        }catch(PDOException $e){
            echo json_encode(array(
                'message' => 'error in getting confirmed orders',
                'error' => $e->getMessage()
            ));
        }
    }

    public function getOrder(){
        $sql = 'SELECT 
                o.id as orderId,
                o.createdOn as ordered_on,
                u.username as username,
                a.streetAddress as street,
                a.city as city,
                a.companyName as companyName,
                a.recipientName as recipientName,
                a.state as state,
                a.pin as pin,
                c.id as cartId,
                c.paymentMethod as paymentMethod,
                u.id as userId
                FROM orders o
                LEFT JOIN cart c ON c.id = o.cartId
                LEFT JOIN orderStatus s ON s.id = o.shippingStatus
                LEFT JOIN user u ON c.userid = u.id
                LEFT JOIN address a ON a.id = c.addressId 
                WHERE
                o.id = :orderId ';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':orderId',$this->orderId);
        try {
            if($stat->execute()){
                return $stat;
            }
        }catch(PDOException $e){
            echo json_encode(array(
                'message' => 'error in getting order details',
                'error' => $e->getMessage()
            ));
        }
    }

}