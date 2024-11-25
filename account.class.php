<?php 
require_once "database.class.php";
class Account{

   
    public $email;
    public $password;

    public $role;
    protected $db;

    function __construct() {
        $this->db = new Database();
    }

    function viewAll() {
        $sql = "SELECT * from Account";
        $qry = $this->db->connect()->prepare($sql);
        if ($qry -> execute()){
            $data = $qry->fetchAll();
        }
        return $data;
      }

      function viewRole() {
        $sql = "SELECT role from Account";
        $qry = $this->db->connect()->prepare($sql);
        if ($qry -> execute()){
            $data = $qry->fetchAll();
        }
        return $data;
      }

      function fetch($email, $password) {
        $sql = "SELECT * from Account where WmsuEmail = :email and Password = :password ";
        $qry = $this->db->connect()->prepare($sql);
        $result = $qry->execute([
            ':email' => $email,
            ':password' => $password
        ]);
    
        // If you need to fetch the results:
        if ($result) {
            $rows = $qry->fetchAll(PDO::FETCH_ASSOC);
            return $rows;
        } else {
            return false; // Or handle the error appropriately
        }
    }
}


// $obj = new Account;

// 