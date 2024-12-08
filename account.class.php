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
      function searchAccounts($query) {
        $sql = "SELECT * FROM account WHERE 
                StudentID LIKE ? OR 
                first_name LIKE ? OR 
                last_name LIKE ? OR 
                WmsuEmail LIKE ? OR 
                Course LIKE ?";
        
        $qry = $this->db->connect()->prepare($sql);
        $searchTerm = '%' . $query . '%';
    
        if ($qry->execute([$searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm])) {
            $data = $qry->fetchAll();
        }
        
        return $data ?? [];
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
        try {
            $sql = "SELECT Password FROM account WHERE WmsuEmail = :email LIMIT 1;";
            $qry = $this->db->connect()->prepare($sql);
    
            // Use bindParam for parameter binding
            $qry->bindParam(':email', $email, PDO::PARAM_STR);
            $qry->execute();
    
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $row['Password'])) {
                    return true; // Password matches
                }
            }
            return false; // No match or user not found
        } catch (PDOException $e) {
            // Handle errors
            error_log("Login error: " . $e->getMessage());
            return false;
        }
    }

    function fetch($email, $password) {
        try {
            $sql = "SELECT * FROM account WHERE WmsuEmail = :email LIMIT 1;";
            $qry = $this->db->connect()->prepare($sql);
    
            // Use bindParam for parameter binding
            $qry->bindParam(':email', $email, PDO::PARAM_STR);
            $qry->execute();
    
            if ($qry->rowCount() > 0) {
                $row = $qry->fetch(PDO::FETCH_ASSOC); // Fetch account details
                if (password_verify($password, $row['Password'])) {
                    return $row; // Return account details if password matches
                }
            }
            return false; // No match or user not found
        } catch (PDOException $e) {
            // Handle errors
            error_log("Fetch error: " . $e->getMessage());
            return false;
        }
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
function createStudAcc($studentId, $first_name, $last_name, $mi, $wmsuEmail, $password, $role, $course, $year, $section) {
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
 
function createAdminAcc($studentId, $first_name, $last_name, $mi, $wmsuEmail, $password, $role, $course, $year, $section) {
    try {
        // Determine role flags
        

        $sql = "INSERT INTO account 
                (StudentID, first_name, last_name, MI, WmsuEmail, Password, role, Course, Year, Section, isstaff, isadmin)
                VALUES 
                (:studentId, :first_name, :last_name, :mi, :wmsuEmail, :password, :role, :course, :year, :section, '1' , '1')";
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
function createStaffAcc($studentId, $first_name, $last_name, $mi, $wmsuEmail, $password, $role, $course, $year, $section) {
    try {
        // Determine role flags
        

        $sql = "INSERT INTO account 
                (StudentID, first_name, last_name, MI, WmsuEmail, Password, role, Course, Year, Section, isstaff, isadmin)
                VALUES 
                (:studentId, :first_name, :last_name, :mi, :wmsuEmail, :password, :role, :course, :year, :section, '1' , '0')";
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
 function deleteAccount($studentId) {
    $sql = "DELETE FROM account WHERE StudentID = :studentId"; // SQL statement
    $qry = $this->db->connect()->prepare($sql); // Prepare the statement
    $qry->bindParam(':studentId', $studentId, PDO::PARAM_STR); // Bind the parameter
    if ($qry->execute()) { // Execute the statement
        return true; // Return true if successful
    }
    return false; // Return false if unsuccessful
}

function getUserDetails($studentID) {
    try {
        $sql = "SELECT first_name, last_name FROM account WHERE StudentID = :studentID";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':studentID', $studentID, PDO::PARAM_INT);
        $qry->execute();
        return $qry->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching user details: " . $e->getMessage());
        return null;
    }
}

// Fetch organizations
function getOrganizations() {
    try {
        $sql = "SELECT OrganizationID AS org_id, OrgName AS name FROM organizations";
        $qry = $this->db->connect()->prepare($sql);
        $qry->execute();
        return $qry->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error fetching organizations: " . $e->getMessage());
        return [];
    }
}

function fetchData($query, $params = []) {
    // Prepare the query
    $qry = $this->db->connect()->prepare($query);

    // Bind parameters dynamically
    foreach ($params as $key => $value) {
        $qry->bindValue($key, $value);
    }

    // Execute the query
    $qry->execute();

    // Return the results
    return $qry->fetchAll(PDO::FETCH_ASSOC);
}

function getStudentFeesByOrganization($organizationId) {
    // Define the SQL query
    $sql = "SELECT 
                a.student_id AS 'Student ID',
                CONCAT(a.first_name, ' ', a.last_name) AS 'Name',
                a.course AS 'Course',
                a.year_level AS 'Year',
                a.section AS 'Section',
                f.fee_name AS 'Fee Name',
                f.amount AS 'Amount',
                sf.status AS 'Status'
            FROM 
                Accounts a
            INNER JOIN 
                Student_Fees sf ON a.student_id = sf.student_id
            INNER JOIN 
                Fees f ON sf.fee_id = f.fee_id
            INNER JOIN 
                Organizations o ON sf.org_id = o.org_id
            WHERE 
                o.org_id = :organization_id
            ORDER BY 
                a.student_id";
    
    // Prepare the SQL statement
    $qry = $this->db->connect()->prepare($sql);

    // Bind parameters
    $qry->bindParam(':organization_id', $organizationId, PDO::PARAM_INT);

    // Execute the query
    $qry->execute();

    // Fetch all results and return them
    return $qry->fetchAll(PDO::FETCH_ASSOC); // Returns an associative array of the results
}

function getOrganizationId($studentId, $feeId) {
    // SQL query to get the organization ID
    $sql = "SELECT 
                sf.org_id AS OrganizationID
            FROM 
                Student_Fees sf
            INNER JOIN 
                Organizations o ON sf.org_id = o.org_id
            WHERE 
                sf.student_id = :studentId 
                AND sf.fee_id = :feeId
            LIMIT 1";
    
    // Prepare the query
    $qry = $this->db->connect()->prepare($sql);
    
    // Bind parameters
    $qry->bindParam(':studentId', $studentId, PDO::PARAM_STR);
    $qry->bindParam(':feeId', $feeId, PDO::PARAM_INT);
    
    // Execute the query
    $qry->execute();
    
    // Fetch the result
    $result = $qry->fetch(PDO::FETCH_ASSOC);
    
    // Return the organization ID, or null if not found
    return $result['OrganizationID'] ?? null;
}







    // Function to insert organization dat
}


// $obj = new Account;

// 