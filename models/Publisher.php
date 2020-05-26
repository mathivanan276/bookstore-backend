<?php
    class Publisher{
        public $publisherName;
        public $description;
        public $publisherId;
        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function create(){
            $sql = 'INSERT INTO publisher 
                        SET 
                            publisher=:publisherName,
                            description=:description';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':publisherName',$this->publisherName);
            $stat->bindParam(':description',$this->description);

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
            $sql = 'SELECT * FROM publisher';
            $stat = $this->conn->prepare($sql);
            $stat->execute();
            return $stat;
        }
        public function update(){
            $sql = 'UPDATE publisher 
                        SET 
                            publisher=:publisher,
                            description=:description
                        WHERE
                            id=:publisherid';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':publisherid',$this->publisherId);
            $stat->bindParam(':publisher',$this->publisherName);
            $stat->bindParam(':description',$this->description);

            try{
                if($stat->execute()){
                    return true;
                }
            } catch(PDOException $e) {
                echo json_encode(array(
                    'error'=>$e->getMessage()
                ));
            }
            return false;
        }
        public function read_publisher(){
            $sql = 'SELECT * FROM publisher
                    wHERE 
                        publisher=:publisherName';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':publisherName',$this->publisherName);

            try{
                if($stat->execute()){
                    return $stat;
                }
            } catch(PDOException $e) {
                echo json_encode(array(
                    'error'=>$e->getMessage()
                ));
            }
            return false;
        }
    }