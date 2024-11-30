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

public function register($firstName, $lastName, $email, $password) {
    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Check if email already exists
    $stmt = $this->conn->prepare("SELECT email FROM accounts WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return false; // Email already exists
    }
    
    // Insert new user
    $stmt = $this->conn->prepare("INSERT INTO accounts (firstName, lastName, email, password, isstaff, isadmin) VALUES (?, ?, ?, ?, false, false)");
    $stmt->bind_param("ssss", $firstName, $lastName, $email, $hashedPassword);
    
    return $stmt->execute();
}
}


// $obj = new Account;

// 