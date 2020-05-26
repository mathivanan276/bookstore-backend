<?php

class Cart {
    private $conn;
    public $userId;
    public $cartId;
    public $bookId;
    public $qty;
    public $itemPrice;
    public $itemId;
    public $price;
    public $stock;
    public $addressId;
    public $deliveryNote;

    public function __construct($db){
        $this->conn = $db;
    }

    public function updateAddress(){
        $sql = 'UPDATE cart
                SET
                    addressId = :addressId,
                    updated_on = current_timestamp
                WHERE
                    userId = :userId &&
                    checkout != 1 &&
                    row_deleted != 1';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':addressId',$this->addressId);
        $stat->bindParam(':userId',$this->userId);

        try{
            if($stat->execute()){
                return true;
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                'message' => 'error in adding Address to cart',
                'error' => $e->getMessage()
            ));
        }

    }

    public function getBookPrice(){
        $sql = 'SELECT price FROM book
                WHERE
                    id = :bookId';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':bookId',$this->bookId);

        try{
            if($stat->execute()){
                $result = $stat->fetch();
                $this->price = $result->price;
                return true;
            }
        } catch(PDOException $e) {
            echo json_encode(array(
                'message' => 'error in getting book price',
                'error' => $e->getMessage()
            ));
            return false;
        }
    }

    public function getBookPriceWithItemId(){
        $sql = 'SELECT price FROM items
                WHERE
                    id = :itemId';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':itemId',$this->itemId);

        try{
            if($stat->execute()){
                $result = $stat->fetch();
                $this->price = $result->price;
                return true;
            }
        } catch(PDOException $e){
            echo json_encode(array(
                'message' => 'error in getting book price',
                'error' => $e->getMessage()
            ));
            return false;
        }
    }

    public function createCartForUser(){
        $sql = 'SELECT * FROM cart
                WHERE userId = :userId &&
                        checkout = 0 &&
                        row_deleted = 0';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':userId',$this->userId);
        try{
            $stat->execute();
            $num = $stat->rowCount();
            if($num <= 0){
                $sql2 = 'INSERT INTO cart
                            SET 
                                userId = :userId';
                $stat2 = $this->conn->prepare($sql2);
                $stat2->bindParam(':userId',$this->userId);

                try{
                    if($stat2->execute()){
                        return true;
                    }
                } catch(PDOException $e) {
                    echo json_encode(array(
                        'message' =>'error in adding user into cart',
                        'error' => $e->getMessage()
                    ));
                }
            }
            else{
                return true;
            }
        } catch(PDOException $e) {
            echo json_encode(array(
                'message' => 'error in checking cart',
                'error' => $e->getMessage()
            ));
        }                
    }

    public function getCartId(){
        $sql = 'SELECT * FROM cart
                    WHERE
                        userId = :userId &&
                        checkout != true &&
                        row_deleted != 1';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':userId',$this->userId);
        
        try{
            if($stat->execute()){
                if($stat->rowCount() > 0){
                    $row = $stat->fetch();
                    $this->cartId = $row->id;
                    return true;
                }
                else{
                    return 'No Cart Found';
                }
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                'message' => 'error in get cart Id',
                'error' => $e->getMessage()
            ));
        }
    }
    
    public function insertItems(){
        $check = 'SELECT * FROM items
                    WHERE
                        bookId = :bookId &&
                        cartId = :cartId &&
                        removed != 1';
        $check = $this->conn->prepare($check);
        $check->bindParam(':bookId',$this->bookId);
        $check->bindParam(':cartId',$this->cartId);

        try{
            $check->execute();

            $num = $check->rowCount();
            if($num > 0){
                echo json_encode(array(
                    'message' => 'item already present'
                ));
                return true;
            } else{
                        $sql = 'INSERT INTO items
                            SET
                                cartId = :cartId,
                                bookId = :bookId,
                                qty = :qty,
                                itemPrice = :itemPrice,
                                price = :price';
                $stat = $this->conn->prepare($sql);
                $stat->bindParam(':cartId',$this->cartId);
                $stat->bindParam(':bookId',$this->bookId);
                $stat->bindParam(':qty',$this->qty);
                $stat->bindParam(':itemPrice',$this->itemPrice);
                $stat->bindParam(':price',$this->price);

                try{
                    if($stat->execute()){
                        return true;
                    }
                } catch (PDOException $e) {
                    echo json_encode(array(
                        'message' => 'error in inserting Item',
                        'error' => $e->getMessage()
                    ));
                }
            }
        } catch(PDOException $e) {
            echo json_encode(array(
                'message' =>'error in checking the existance of item',
                'error' =>$e->getMessage()
            ));
        }
        
        
    }

    public function removeItems(){
        $sql = 'UPDATE items
                    SET
                        removed = true
                    WHERE   
                        id = :itemId';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':itemId',$this->itemId);

        try{
            if($stat->execute()){
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo json_encode(array(
                'message' => 'error in removing Item',
                'error' => $e->getMessage()
            ));
        }
    }

    public function checkForStock(){
        $sql = 'SELECT 
                    value as stock  
                FROM stock s
                LEFT JOIN items i ON i.bookId = s.book_id
                WHERE 
                    i.id = :itemId';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':itemId',$this->itemId);
        try{
            if($stat->execute()){
                $result = $stat->fetch();
                $this->stock = $result->stock;
                return true;
            }
        } catch(PDOException $e){
            echo json_encode(array(
                'message' => 'error in getting stock',
                'error' => $e->getMessage()
            ));
        }
    }

    public function updateItemQty(){
        $sql = 'UPDATE items
                    SET
                        qty = :qty,
                        itemPrice = :itemPrice
                    WHERE 
                        id = :itemId';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':qty',$this->qty);
        $stat->bindParam(':itemPrice',$this->itemPrice);
        $stat->bindParam(':itemId',$this->itemId);

        try{
            if($stat->execute()){
                return true;
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                'message' => 'error in updating Item',
                'error' => $e->getMessage()
            ));
        }
    }

    public function getCartItems(){
        $sql = 'SELECT 
                    i.id as itemId,
                    qty as quantity,
                    i.itemPrice as itemPrice,
                    i.bookId as bookId,
                    b.title as title,
                    a.authorName as authorName,
                    p.publisher as publisher,
                    i.price as price,
                    b.url as url,
                    s.value as stock
                FROM items i
                LEFT JOIN book b ON b.id = i.bookId
                LEFT JOIN author a ON a.id = b.author_id
                LEFT JOIN publisher p ON p.id = b.publisher_id
                LEFT JOIN stock s ON s.book_id = b.id
                WHERE i.cartId = :cartId && i.removed = false && i.row_deleted=0';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':cartId',$this->cartId);
        
        try{
            if($stat->execute()){
                return $stat;
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                'message' => 'error in getting Item',
                'error' => $e->getMessage()
            ));
        }
    }

    public function getOrderSummary(){
        $sql = 'SELECT 
                    sum(qty) as quantity,
                    sum(itemPrice) as totalPrice
                FROM items
                WHERE
                    cartId = :cartId &&
                    removed = 0
                GROUP BY
                    cartId';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':cartId',$this->cartId);

        try{
            if($stat->execute()){
                return $stat;
            }
        } catch(PDOException $e) {
            echo json_encode(array(
                'message' => 'error in getting order summary',
                'error' =>$e->getMessage()
            ));
        }
    }

    public function getCartDetails(){
        $sql = 'SELECT 
                    a.streetAddress as streetAddress,
                    a.companyName as companyName,
                    a.recipientName as recipientName,
                    a.state as state,
                    a.city as city,
                    a.country as country,
                    a.pin as pin,
                    b.url as url,
                    b.title as title,
                    b.price as price,
                    i.qty as quantity,
                    i.itemPrice as itemPrice,
                    i.id as itemId
                FROM cart c
                    LEFT JOIN address a ON a.id = c.addressId
                    LEFT JOIN items i ON i.cartId = c.id
                    LEFT JOIN book b ON b.id = i.bookId 
                WHERE 
                    c.userId = :userId &&
                    c.checkout != 1 &&
                    c.row_deleted != 1  &&
                    i.removed != 1';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':userId',$this->userId);
        try{
            if($stat->execute()){
                return $stat;
            }
        } catch(PDOException $e){
            echo json_encode(array(
                'message' => 'error in getting cart details',
                'error' =>$e->getMessage()
            ));
        }
    }

}