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
        $sql = "SELECT * from account";
        $qry = $this->db->connect()->prepare($sql);
        if ($qry -> execute()){
            $data = $qry->fetchAll();
        }
        return $data;
      }

      function viewRole() {
        $sql = "SELECT role from account";
        $qry = $this->db->connect()->prepare($sql);
        if ($qry -> execute()){
            $data = $qry->fetchAll();
        }
        return $data;
      }

      function login($email, $password) {
    $sql = "SELECT * from account where WmsuEmail = :email and Password = :password LIMIT 1;";
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
    $sql = "SELECT * from account where WmsuEmail = :email and Password = :password LIMIT 1;";
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

function createOrg() {
    $sql = "CREATE TABLE `pms1`.`SESSION` (
    `OrganizationID` VARCHAR(100) NOT NULL , 
    `StudentID` INT(11) NOT NULL , 
    `first_name` VARCHAR(255) NOT NULL , 
    `last_name` VARCHAR(255) NOT NULL , 
    `WmsuEmail` VARCHAR(255) NOT NULL , 
    `Position` VARCHAR(255) NOT NULL,
    
    CONSTRAINT fk_OrgID FOREIGN KEY OrganizationID REFERENCES organizations(OrganizationID),
    CONSTRAINT fk_StudID FOREIGN KEY StudentID REFERENCES account(StudentID)) ENGINE = InnoDB;";
    $qry = $this->db->connect()->prepare($sql);
    if ($qry -> execute()){
        $data = $qry->fetchAll();
    }
    return $data;
  }
}


// $obj = new Account;

// 