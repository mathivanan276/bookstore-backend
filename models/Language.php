<?php

class Language {
    public $languageId;
    public $language;
    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create(){
        $sql = 'INSERT INTO lang
                    SET 
                        languageId = :languageId,
                        language = :language';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':language',$this->language);
        $stat->bindParam(':languageId',$this->languageId);

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
        $sql = 'SELECT * FROM lang';
        $stat = $this->conn->prepare($sql);
        
        $stat->execute();
        return $stat;
    }

    public function read_lang(){
        $sql = 'SELECT * FROM lang
                WHERE 
                    language = :language';
        $stat = $this->conn->prepare($sql);
        $stat->bindParam(':language',$this->language);

        $stat->execute();
        return $stat;
    }
    
}