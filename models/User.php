<?php   
    class User {
        public $username;
        public $password;
        public $email;
        public $id;
        public $role;
        public $table = 'user';
        public $conn;

        public function __construct($db) {
            $this->conn = $db;
        }

        public function read() {
            $sql='SELECT * FROM '.$this->table.' 
                    WHERE username=:username && password=:password';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':username',$this->username);
            $stat->bindParam(':password',$this->password);
            $stat->execute();
            return $stat;
        }
        public function create(){
            $sql='INSERT INTO '.$this->table.'
                    SET 
                        role = :role,
                        username = :username,
                        password = :password,
                        email = :email';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':username',$this->username);
            $stat->bindParam(':email',$this->email);
            $stat->bindParam(':password',$this->password);
            $stat->bindParam(':role',$this->role);
            
            try{
                if($stat->execute()){
                    return true;
                }
                else{
                    return false;
                }
            }
            catch (PDOException $e){
                echo json_encode(array(
                    'error' => $e
                ));
                return false;
            }
            
        }
        public function delete(){
            $sql='DELETE FROM '.$this->table.' WHERE id=:id';

            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':id',$this->id);

            if($stat->execute()){
                return true;
            }
            return false;
        }

        public function login(){
            $sql='SELECT id,username,email FROM user
                    WHERE username=:username && password=:password && email=:email && role=:role';
            $stat = $this->conn->prepare($sql);
            $stat->bindParam(':username',$this->username);
            $stat->bindParam(':password',$this->password);
            $stat->bindParam(':email',$this->email);
            $stat->bindParam(':role',$this->role);

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