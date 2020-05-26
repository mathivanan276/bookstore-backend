<?php
    class Personaldetails {
        public $userId;
        public $firstName;
        public $lastName;
        public $phone;
        public $email;
        private $conn;
    
        public function __construct($db) {
            $this->conn = $db;
        }

        public function read(){
            $sql='SELECT 
                    p.id as id,
                    firstname,
                    lastname,
                    phone,
                    u.email as email
                FROM 
                    personaldetails p
                left JOIN user u 
                        ON u.id = p.userid
                    WHERE p.userid=:userid';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':userid',$this->userId);
            $stat->execute();
            return $stat;
        }

        public function update(){
            $sql='UPDATE personaldetails 
                    SET
                        firstname=:firstname,
                        lastname=:lastname,
                        phone=:phone
                    WHERE
                        userid=:userid';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':firstname',$this->firstName);
            $stat->bindParam(':lastname',$this->lastName);
            $stat->bindParam(':phone',$this->phone);
            $stat->bindParam(':userid',$this->userId);
            
            try{
                if($stat->execute()){
                return true;
            }
            return false;
            }catch(PDOException $e){
                echo json_encode(array(
                    'error'=>$e->getMessage()
                ));
            }
        }

        public function create(){
            $sql='INSERT INTO  personaldetails
                    SET
                        firstname=:firstname,
                        lastname=:lastname,
                        phone=:phone,
                        userid=:userid, 
                        email=:email';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':firstname',$this->firstName);
            $stat->bindParam(':lastname',$this->lastName);
            $stat->bindParam(':phone',$this->phone);
            $stat->bindParam(':userid',$this->userId);
            $stat->bindParam(':email',$this->email);

            try{
                if($stat->execute()){
                    return true;
                }
            } catch(PDOException $e){
                echo json_encode( array(
                    'error'=> $e->getMessage()
                ));
                return false;
            }
        }
        
    }