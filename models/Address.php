<?php
class Address {
    public $addressId;
    public $userId;
    public $recipientName;
    public $companyNmae;
    public $streetAddress;
    public $country;
    public $state;
    public $city;
    public $pin;
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $sql = 'INSERT INTO address
                SET
                    userid = :userId,
                    recipientName = :recipientName,
                    companyName = :companyName,
                    streetAddress = :streetAddress,
                    country = :country,
                    state = :state,
                    city = :city,
                    pin = :pin';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam('userId',$this->userId);
        $stat->bindParam('recipientName',$this->recipientName);
        $stat->bindParam('companyName',$this->companyName);
        $stat->bindParam('streetAddress',$this->streetAddress);
        $stat->bindParam('country',$this->country);
        $stat->bindParam('state',$this->state);
        $stat->bindParam('city',$this->city);
        $stat->bindParam('pin',$this->pin);

        try{
            if($stat->execute()){
                return true;
            }
            return false;
        } catch(PDOException $e){
            echo json_encode(array(
                'error'=>$e->getMessage()
            ));
        }
    }
     
    public function read(){
        $sql = 'SELECT * FROM address
                    WHERE
                        userid = :userId && row_deleted != 1';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':userId',$this->userId);

        try{
            if($stat->execute()){
                return $stat;
            }
        } catch(PDOException $e){
            echo json_encode(array(
                'error' => $e->getMessage()
            ));
        }
    }

    public function read_address(){
        $sql = 'SELECT * FROM address
                    WHERE
                        id = :addressId && row_deleted != 1';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':addressId',$this->addressId);

        try{
            if($stat->execute()){
                return $stat;
            }
        } catch(PDOException $e){
            echo json_encode(array(
                'error' => $e->getMessage()
            ));
        }
    }

    public function update(){
        $sql = 'UPDATE address 
                SET 
                    recipientName = :recipientName,
                    companyName = :companyName,
                    streetAddress = :streetAddress,
                    city = :city,
                    pin = :pin,
                    state = :state,
                    country = :country
                WHERE
                    id = :addressId && userid = :userId';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':recipientName',$this->recipientName);
        $stat->bindParam(':companyName',$this->companyName);
        $stat->bindParam(':streetAddress',$this->streetAddress);
        $stat->bindParam(':city',$this->city);
        $stat->bindParam(':state',$this->state);
        $stat->bindParam(':pin',$this->pin);
        $stat->bindParam(':country',$this->country);
        $stat->bindParam(':addressId',$this->addressId);
        $stat->bindParam(':userId',$this->userId);

        try{
            if($stat->execute()){
                return true;
            }
            else{
                return false;
            }
        } catch(PDOException $e){
            echo json_encode(array(
                'error' => $e->getMessage()
            ));
        }
    }

    public function remove(){
        $sql = 'UPDATE address
                SET 
                    row_deleted = 1
                WHERE
                    id = :addressId';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':addressId',$this->addressId);

        try{
            if($stat->execute()){
                return true;
            } else{
                return false;
            }
        } catch(PDOException $e){
            echo json_encode(array(
                'error' => $e->getMessage()
            ));
        }
    }
    public function getAddressId(){
        $sql = 'SELECT id as addressId 
                FROM address
                WHERE
                    userid = :userId && 
                    row_deleted != 1
                order by
                    created_on desc
                limit 1';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam('userId',$this->userId);

        try{
            if($stat->execute()){
                return $stat;
            }
        } catch(PDOException $e){
            echo json_encode(array(
                'error' => $e->getMessage()
            ));
        }
    }

}