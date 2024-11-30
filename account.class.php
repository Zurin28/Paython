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

    function viewAccounts() {
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
        $sql = "SELECT Password FROM account WHERE WmsuEmail = :email LIMIT 1;";
        $qry = $this->db->connect()->prepare($sql);
        $qry->execute([':email' => $email]);
    
        if ($qry->rowCount() > 0) {
            $row = $qry->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['Password'])) {
                return true; // Password matches
            }
        }
        return false; // No match or user not found
    }

    function fetch($wmsuEmail, $password) {
        try {
            // SQL to get the account details by email
            $sql = "SELECT * FROM account WHERE WmsuEmail = :wmsuEmail";
            $qry = $this->db->connect()->prepare($sql);
            $qry->execute([':wmsuEmail' => $wmsuEmail]);

            // Check if account exists
            if ($qry->rowCount() === 1) {
                $account = $qry->fetch(PDO::FETCH_ASSOC); // Fetch account details

                // Verify the hashed password
                if (password_verify($password, $account['Password'])) {
                    // Password matches
                    return [
                        'success' => true,
                        'message' => 'Login successful.',
                        'account' => $account // Return account details if needed
                    ];
                } else {
                    // Password does not match
                    return [
                        'success' => false,
                        'message' => 'Invalid password.'
                    ];
                }
            } else {
                // No account found
                return [
                    'success' => false,
                    'message' => 'Account not found.'
                ];
            }
        } catch (PDOException $e) {
            // Handle errors
            error_log("Fetch error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'An error occurred. Please try again later.'
            ];
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

  function accountExists($studentId, $wmsuEmail) {
    $sql = "SELECT StudentID FROM account WHERE StudentID = :studentId OR WmsuEmail = :wmsuEmail";
    $qry = $this->db->connect()->prepare($sql);

    // Bind parameters
    $qry->bindParam(':studentId', $studentId, PDO::PARAM_STR);
    $qry->bindParam(':wmsuEmail', $wmsuEmail, PDO::PARAM_STR);

    $qry->execute();
    return $qry->rowCount() > 0; // Returns true if record exists
}

/**
 * Function to create a new account
 */
function create($studentId, $first_name, $last_name, $mi, $wmsuEmail, $password, $role, $course, $year, $section) {
    try {
        // Determine role flags
        

        $sql = "INSERT INTO account 
                (StudentID, first_name, last_name, MI, WmsuEmail, Password, role, Course, Year, Section, isstaff, isadmin)
                VALUES 
                (:studentId, :first_name, :last_name, :mi, :wmsuEmail, :password, :role, :course, :year, :section, '0' , '0')";
        $qry = $this->db->connect()->prepare($sql);

        // Bind parameters
        $qry->bindParam(':studentId', $studentId, PDO::PARAM_STR);
        $qry->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $qry->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $qry->bindParam(':mi', $mi, PDO::PARAM_STR);
        $qry->bindParam(':wmsuEmail', $wmsuEmail, PDO::PARAM_STR);
        $qry->bindParam(':password', $password, PDO::PARAM_STR);
        $qry->bindParam(':role', $role, PDO::PARAM_STR);
        $qry->bindParam(':course', $course, PDO::PARAM_STR);
        $qry->bindParam(':year', $year, PDO::PARAM_INT);
        $qry->bindParam(':section', $section, PDO::PARAM_STR);

        $qry->execute();
        return true; // Successfully created account
    } catch (PDOException $e) {
        return false; // Error occurred
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