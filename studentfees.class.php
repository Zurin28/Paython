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
        $sql = "INSERT INTO student_fees (studentID, feeID, status) VALUES (:studentID, :feeID, :status)";
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':studentID', $studentID);
        $qry->bindParam(':feeID', $feeID);
        $qry->bindParam(':status', $status);
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
}
?>
