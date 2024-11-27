<?php 
require_once "database.class.php";
class Account{

   
    public $email;
    public $password;

    public $role;
    public $isstaff = null;
    public $isadmin = null;
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

      function login($email, $password) {
        $sql = "SELECT * from Account where WmsuEmail = :email and Password = :password LIMIT 1;";
        $qry = $this->db->connect()->prepare($sql);
        $result = $qry->execute([
            ':email' => $email,
            ':password' => $password
        ]);

        if ($result) {
            return true;
        } else {
            return false; // Or handle the error appropriately
        }
      }


      function fetch($email, $password) {
        $sql = "SELECT * from Account where WmsuEmail = :email and Password = :password LIMIT 1;";
        $qry = $this->db->connect()->prepare($sql);
        $result = $qry->execute([
            ':email' => $email,
            ':password' => $password
        ]);
        
        $data = '';

        // If you need to fetch the results:
        if ($result) {
            $data = $qry->fetch();  
        }
        return $data;
    }
}


// $obj = new Account;

// 