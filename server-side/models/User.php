<?php
declare(strict_types=1);

class User{
    private $conn;
    private string $userEmail;
    private string $userName;
    private string $pwd;
    
    public function __construct(string $userEmail,string $userName, string $pwd,Database $db){
      $this->conn = $db->getConnection();
      $this->userEmail = $userEmail;
      $this->userName = $userName;
      $this->pwd = $pwd;
    }
    public function createUser():void{
        $db=$this->conn;
        $query = "INSERT INTO users(userEmail,userName,password) VALUES('".$this->userEmail."','".$this->userName."','". $this->pwd ."');";
        $stmt =$db->prepare($query);

        try{
            $stmt->execute();
            echo json_encode(["msg"=>"success"]);
        }catch(PDOException $e){
            echo json_encode($e->getMessage());
        }

    
    }
    public function fetchUser(string $userEmail, string $pwd):void{
        $db=$this->conn;
        $query = "SELECT userName,userEmail FROM users WHERE userEmail='".$userEmail."' AND password='".$pwd."';";
        $stmt =$db->prepare($query);
       

        try{
          $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_OBJ);
            echo json_encode($data);
        }catch(PDOException $e){
            echo json_encode(["msg"=>"Error:". $e->getMessage()]);
        }

        
    }
    
    public function getUserEmail ():string{
        return $this->userEmail;
    }

    public function UserExist():bool
    {
        $query = "SELECT userName,userEmail FROM users WHERE userEmail ='" . $this->userEmail . "'; ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if (!$result) {
            return false;
            
        }else{
            return true;
        }
        
    }


}