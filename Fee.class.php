<?php 
require_once "database.class.php";
class Fee{

public $FeeID;

public $orgID;
public $FeeName;
public $Amount;
public $Duedate;
public $Description;

public $status;

protected $db;

function __construct() {
    $this->db = new Database();
}

function viewFees() {
    $sql = "SELECT * from Fees";
    $qry = $this->db->connect()->prepare($sql);
    if ($qry -> execute()){
        $data = $qry->fetchAll();
    }
    return $data;
  }

  function getFeeStatus($student_id) {
    // Query to get the fee status for the student
    $sql = "
        SELECT f.FeeName, sf.paymentStatus
        FROM Fees f
        JOIN Student_Fees sf ON f.feeID = sf.feeID
        WHERE sf.studentID = :student_id
    ";

    // Prepare and execute the query
    $qry = $this->db->connect()->prepare($sql);
    $qry->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $qry->execute();

    // Fetch the results
    $data = $qry->fetchAll(PDO::FETCH_ASSOC);
    return $data;
}






}

