<?php
    class Author{

        public $authorName;
        public $authorId;
        public $description;
        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function create(){
            $sql='INSERT INTO author
                    SET 
                        authorName=:name,
                        description=:description';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':name',$this->authorName);
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
            $sql = 'SELECT * FROM author';
            $stat = $this->conn->prepare($sql);
            $stat->execute();
            return $stat;
        }

        public function update(){
            $sql = 'UPDATE author 
                        SET 
                            authorName=:name,
                            description=:description
                        WHERE
                            id=:authorid';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':authorid',$this->authorId);
            $stat->bindParam(':name',$this->authorName);
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

        public function read_author(){
            $sql = 'SELECT * FROM author
                    wHERE 
                        authorName=:authorName';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':authorName',$this->authorName);

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