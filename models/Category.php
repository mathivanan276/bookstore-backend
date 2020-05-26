<?php
    class Category{
        public $categoryId;
        public $category;
        private $conn;

        public function __construct($db){
            $this->conn = $db;
        }

        public function read(){
            $sql = 'SELECT * FROM category';
            $stat = $this->conn->prepare($sql);
            $stat->execute();
            return $stat;
        }

        public function create(){
            $sql = 'INSERT INTO category 
                        SET 
                            category = :category';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':category',$this->category);

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
            $sql = 'UPDATE category 
                        SET
                            category = :category
                        WHERE
                            id = :categoryId';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':categoryId',$this->categoryId);
            $stat->bindParam(':category',$this->category);

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
        public function read_category(){
            $sql = 'SELECT * FROM category
                    wHERE 
                        category=:categoryName';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':categoryName',$this->category);

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