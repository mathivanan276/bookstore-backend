<?php
class States {
    public $country_id;
    public $state_id;
    public $state;

    public function __construct($db){
        $this->conn = $db;
    }

    public function read_states(){
        $sql = 'SELECT * FROM states 
                WHERE
                    country_id = :country_id';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':country_id',$this->country_id);
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

    public function read_cities(){
        $sql = 'SELECT * FROM cities WHERE state_id = :state_id';
        $stat = $this->conn->prepare($sql);
        $stat->bindparam(':state_id',$this->state_id);
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

    public function read_state_id(){
        $sql = 'SELECT * FROM states
                    WHERE 
                        name = :state &&
                        country_id = :country_id';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':state',$this->state);
        $stat->bindParam(':country_id',$this->country_id);
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