<?php
    class Database {
        private $host='localhost';
        private $dbName='bookstore1';
        private $username='root';
        private $password='password';
        private $conn;
        
        public function connect(){
            $this->conn=null;
            
            try{
                $this->conn=new PDO('mysql:host=' . $this->host .';dbname='.$this->dbName,
                $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            } catch(PDOException $e) {
                echo 'Connection Error'.$e->getMessage();
            }
            return $this->conn;
        }
    }