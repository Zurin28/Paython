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
    $qry->execute([
        ':email' => $email,
        ':password' => $password
    ]);

    // Check if any rows were returned
    if ($qry->rowCount() > 0) {
        return true;  // User found with the matching credentials
    } else {
        return false;  // No matching user
    }
}



      function fetch($email, $password) {
    $sql = "SELECT * from Account where WmsuEmail = :email and Password = :password LIMIT 1;";
    $qry = $this->db->connect()->prepare($sql);
    $qry->execute([
        ':email' => $email,
        ':password' => $password
    ]);
    
    // Check if any rows were returned and fetch the data
    if ($qry->rowCount() > 0) {
        return $qry->fetch();
    } else {
        return null;  // No matching record found
    }
}
}


// $obj = new Account;

// 