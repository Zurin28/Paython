<?php 
require_once "database.class.php";

class StudentFee {

    // Define the variables
    public $studentID;
    public $feeID;
    public $status;

    protected $db;

    // Constructor to initialize the database connection
    function __construct() {
        $this->db = new Database();
    }

    // Function to view all student fees
    function viewStudentFees() {
        $sql = "SELECT * FROM student_fees"; // Replace `student_fees` with your actual table name
        $qry = $this->db->connect()->prepare($sql);
        if ($qry->execute()) {
            $data = $qry->fetchAll();
        }
        return $data;
    }

    // Function to add a new student fee record
    function addStudentFee($studentID, $feeID, $status) {
        // Get current academic period
        $academicPeriod = new AcademicPeriod();
        $currentPeriod = $academicPeriod->getCurrentAcademicPeriod();
        if (!$currentPeriod) {
            throw new Exception("No current academic period set.");
        }

        $sql = "INSERT INTO student_fees (studentID, feeID, status, school_year, semester) 
                VALUES (:studentID, :feeID, :status, :school_year, :semester)";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':studentID', $studentID);
        $qry->bindParam(':feeID', $feeID);
        $qry->bindParam(':status', $status);
        $qry->bindParam(':school_year', $currentPeriod['school_year']);
        $qry->bindParam(':semester', $currentPeriod['semester']);
        return $qry->execute();
    }

    // Function to update the status of a student fee record
    function updateStatus($studentID, $feeID, $status) {
        $sql = "UPDATE student_fees SET status = :status WHERE studentID = :studentID AND feeID = :feeID";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':studentID', $studentID);
        $qry->bindParam(':feeID', $feeID);
        $qry->bindParam(':status', $status);
        return $qry->execute();
    }

    // Function to delete a student fee record
    function deleteStudentFee($studentID, $feeID) {
        $sql = "DELETE FROM student_fees WHERE studentID = :studentID AND feeID = :feeID";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':studentID', $studentID);
        $qry->bindParam(':feeID', $feeID);
        return $qry->execute();
    }

    public function getStudentFeesWithDetails($organizationId = null) {
        try {
            $sql = "SELECT 
                    a.StudentID as studentID,
                    a.first_name,
                    a.last_name,
                    a.Course,
                    a.Year,
                    a.Section,
                    f.FeeID,
                    f.FeeName,
                    f.Amount
                FROM account a, fees f";
            
            // Add WHERE clause if organizationId is provided
            if ($organizationId) {
                $sql .= " WHERE f.OrgID = :orgId";
                $qry = $this->db->connect()->prepare($sql);
                $qry->execute([':orgId' => $organizationId]);
            } else {
                $qry = $this->db->connect()->prepare($sql);
                $qry->execute();
            }
            
            return $qry->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getStudentFeesWithDetails: " . $e->getMessage());
            return [];
        }
    }

    public function getStudentFeeStatus($studentId, $feeId) {
        try {
            $sql = "SELECT paymentStatus FROM student_fees 
                    WHERE studentID = :studentId 
                    AND feeID = :feeId";
            
            $qry = $this->db->connect()->prepare($sql);
            $qry->execute([
                ':studentId' => $studentId,
                ':feeId' => $feeId
            ]);
            
            $result = $qry->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['paymentStatus'] : 'Unpaid';
        } catch (PDOException $e) {
            error_log("Error getting fee status: " . $e->getMessage());
            return 'Unpaid';
        }
    }
}
?>
