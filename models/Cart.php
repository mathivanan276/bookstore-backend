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
    public $paymentMethod;

    public function __construct($db){
        $this->conn = $db;
    }

    public function updateAddress(){                            // TO UPDATE ADDRESS INTO THE CART
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

    public function getBookPrice(){                         //TO GET BOOK PRICE TO INSERT INTO THE ITEM PRICE
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

    public function getBookPriceWithItemId(){           //TO GET BOOK PRICE WITH ITEM ID
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

    public function createCartForUser(){                    //CREATE CART FOR USER IT IS CHECKED EVER TIME USER INSERT A ITEM
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

    public function getCartId(){                            //GET CART ID USING USER ID HENCE THE FRONTEND DEALS WITH ONLY USER ID NOT WITH CART ID
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
    
    public function insertItems(){                          //INSERT ITEM INTO ITEMS TABLE
        $check = 'SELECT * FROM items
                    WHERE
                        bookId = :bookId &&
                        cartId = :cartId &&
                        removed != 1';
        $check = $this->conn->prepare($check);              //BEFORE INSERT CHECK FOR THE PRESENCE
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

    public function removeItems(){                      //REMOVE ITEM FROM CART 
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

    public function checkForStock(){                // CHECK FOR THE STOCK BEFORE UPDATING THE CART
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

    public function updateItemQty(){                //UPDATE ITEM QTY
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
    

    public function getCartItems(){                     //GET CART ITEMS
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
                    s.value as stock,
                    i.outofstock as stockalert
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

    public function getOrderSummary(){              //ORDER SUMMARRY
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
                    a.id as addressId,
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

    public function updateDeliveryNote(){
        $sql = 'UPDATE cart
                SET
                    deliveryNote = :deliveryNote
                WHERE
                    userId = :userId &&
                    checkout != 1 &&
                    row_deleted != 1';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':deliveryNote',$this->deliveryNote);
        $stat->bindParam(':userId',$this->userId);

        try{
            if($stat->execute()){
                return true;
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                'message' => 'error in adding Deliverynote to cart',
                'error' => $e->getMessage()
            ));
        }
    }
    public function updateTotalPrice($totalPrice){
        $sql = 'UPDATE cart
                SET
                    totalPrice = :totalPrice
                WHERE
                    userId = :userId &&
                    checkout != 1 &&
                    row_deleted != 1';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':totalPrice',$totalPrice);
        $stat->bindParam(':userId',$this->userId);

        try{
            if($stat->execute()){
                return true;
            }
        } catch (PDOException $e) {
            echo json_encode(array(
                'message' => 'error in adding totalPrice to cart',
                'error' => $e->getMessage()
            ));
        }
    }
    public function readPaymentMethod(){                //GETTING THE PAYMENT METHODS
        $sql = 'SELECT * FROM paymentMethod';
        $stat = $this->conn->prepare($sql);
        try{
            $stat->execute();
            return $stat;
        } catch(PDOException $e){
            echo json_encode(array(
                'message' => 'error in reading payment method',
                'error' =>$e->getMessage()
            ));
        }
    }
    public function updatePaymentMethod(){
        $sql = 'UPDATE cart
                SET
                    paymentMethod = :paymentMethod,
                    checkout = 1
                WHERE
                    userId = :userId &&
                    checkout != 1 &&
                    row_deleted != 1';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':userId',$this->userId);
        $stat->bindParam(':paymentMethod',$this->paymentMethod);
        $this->getCartId();
        try{
            if($stat->execute()){
                if($this->updatestock()){
                    return true;
                }
                else{
                    return false;
                }
            }
        } catch(PDOException $e){
            echo json_encode(array(
                'message' => 'Error inupdating payment method',
                'error' => $e->getMessage()
            ));
        }
    }

    public function updatestock(){                  // FOR STOCK UPDATE
        $sql = 'SELECT 
                    bookId,
                    (s.value-i.qty) as newValue
                FROM items i
                LEFT JOIN cart c ON c.id = i.cartId
                LEFT JOIN stock s ON i.bookId = s.book_id
                WHERE
                c.id = :cartId &&
                checkout = 1';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':cartId',$this->cartId);
        try {
        if($stat->execute()){
            while($row = $stat->fetch()){
                $sql2 = 'CALL updatestock(:bookId,:newvalue)';
                $stat2 = $this->conn->prepare($sql2);
                $stat2->bindParam(':bookId',$row->bookId);
                $stat2->bindParam(':newvalue',$row->newValue);
                try{
                    $stat2->execute();
                }catch(PDOException $e){
                    echo json_encode(array(
                        'message' => 'error in update stock',
                        'error' => $e->getMessage()
                    ));
                    return false;
                }
            }
            return true;
        }
    }catch(PDOException $e){    
        echo json_encode(array(
            'message' => 'error in update stock',
            'error' => $e->getMessage()
        ));
        return false;
    }
    }

    public function createOrder(){                  // ONCE THE PAYMENT IS UPDATED THE CART IS MOVED TO ORDERS TABLE
        $sql1 = 'SELECT * FROM orders
                    WHERE
                        cartId = :cartId &&
                        row_deleted != 1';
        $stat1 = $this->conn->prepare($sql1);
        $stat1->bindParam(':cartId',$this->cartId);
        try{
            if($stat1->execute()){
                $num = $stat1->rowCount();
                if($num <= 0){
                    $sql = 'INSERT INTO orders
                            SET
                                cartId = :cartId,
                                shippingStatus = 1,
                                createdOn = current_timestamp,
                                updated_on = current_timestamp';
                    $stat = $this->conn->prepare($sql);
                    $stat->bindParam(':cartId',$this->cartId);
                    try{
                        if($stat->execute()){
                            return true;
                        }
                    }  catch(PDOException $e){
                        echo json_encode(array(
                            'message' => 'error in create order',
                            'error' =>$e->getMessage()
                        ));
                        exit();
                    }
                } else{
                    // echo json_encode(array(
                    //     'message' => 'Already present an order row for this cart'
                    // ));
                    return true;
                }
            }
        } catch(PDOException $e){
            echo json_encode(array(
                'message' => 'error in checking for order table for presence',
                'error' => $e->getMessage()
            ));
            exit();
        }
    }
    public function seeForOutOfStock(){
        $sql = 'SELECT id FROM items
                WHERE
                    cartId = :cartId &&
                    outofstock = 1 ';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':cartId',$this->cartId);

        try{
            if($stat->execute()){
                return $stat;
            }
        } catch(PDOException $e) {
            echo json_encode(array(
                'message' => 'error in getting outof stock in items',
                'error' => $e->getMessage()
            ));
        }
    }
    public function clearNotification($idItem){
        $sql = 'call clearOutOfStockNotifiaction(:idCart,:idItems)';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':idCart',$this->cartId);
        $stat->bindParam(':idItems',$idItem);
        try{
            if($stat->execute()){
                return true;
            }
        }catch(PDOException $e){
            echo json_encode(array(
                'message' => 'error in clearing notification',
                'error' =>$e->getMessage()
            ));
        }
    }

}