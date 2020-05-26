<?php
    class Stock {
        public $stockId;
        public $bookId;
        public $value;
        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function create() {
            $sql = 'INSERT INTO stock  
                        SET
                            book_id = :bookId ,
                            value = :value';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':bookId',$this->bookId);
            $stat->bindParam(':value',$this->value);

            try{
                if($stat->execute()){
                    return true;
                }
                return false;
            } catch(PDOException $e) {
                echo json_encode(array(
                    'error'=>$e->getMessage()
                ));
                return false;
            }
        }

        public function update(){
            $sql = 'UPDATE stock 
                        SET 
                            value = :value
                        WHERE 
                            id = :stockId';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam('stockId',$this->stockId);
            $stat->bindParam('value',$this->value);

            try{
                if($stat->execute()){
                    return true;
                }
                return false;
            } catch(PDOException $e) {
                echo json_encode(array(
                    'error'=>$e->getMessage()
                ));
                return false;
            }
        }

        public function read(){
            $sql = 'SELECT s.id as stockId,value,title,b.id as bookId FROM stock s
                    LEFT JOIN book b ON s.book_id = b.id 
                    ORDER BY s.value asc';
            $stat = $this->conn->prepare($sql);
            try{
                if($stat->execute()){
                    return $stat;
                }
                return false;
            } catch(PDOException $e) {
                echo json_encode(array(
                    'error'=>$e->getMessage()
                ));
                return false;
            }
        }
    }