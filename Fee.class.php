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

/*************  ✨ Codeium Command 🌟  *************/
  // In Fee.class.php
function getFeeStatus($student_id) {
    try {
        $sql = "SELECT 
                f.FeeID,
                f.FeeName,
                f.Amount,
                o.OrgName as organization,
                COALESCE(sf.paymentStatus, 'Not Paid') as paymentStatus
            FROM 
                Fees f
                JOIN organizations o ON f.OrgID = o.OrganizationID
                LEFT JOIN student_fees sf ON f.FeeID = sf.feeID 
                    AND sf.studentID = :student_id
            ORDER BY 
                o.OrgName, f.FeeName";

        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':student_id', $student_id, PDO::PARAM_STR);
        $qry->execute();

        return $qry->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error in getFeeStatus: " . $e->getMessage());
        return [];
    }
}

// Add this new function to automatically create student fee records
function initializeStudentFees($student_id) {
    try {
        // First, get all existing fees
        $sql = "SELECT FeeID FROM Fees";
        $qry = $this->db->connect()->prepare($sql);
        $qry->execute();
        $fees = $qry->fetchAll(PDO::FETCH_COLUMN);

        // Insert records for each fee
        $insertSql = "INSERT IGNORE INTO student_fees (studentID, feeID, paymentStatus) 
                      VALUES (:student_id, :fee_id, 'Not Paid')";
        $insertQry = $this->db->connect()->prepare($insertSql);

        foreach ($fees as $fee_id) {
            $insertQry->bindParam(':student_id', $student_id, PDO::PARAM_STR);
            $insertQry->bindParam(':fee_id', $fee_id, PDO::PARAM_INT);
            if (!$insertQry->execute()) {
                throw new Exception("Failed to insert fee record");
            }
        }

        return true;
    } catch (PDOException $e) {
        error_log("Error in initializeStudentFees: " . $e->getMessage());
        return false;
    }
}
/******  ea9d0024-6bfb-4a80-b69a-3da38a6a6650  *******/

public function getOrganizationPayments($orgId) {
  try {
      $sql = "SELECT 
                  FeeID as fee_id,
                  FeeName as fee_name,
                  Amount as amount,
                  DATE_FORMAT(DueDate, '%Y-%m-%d') as due_date,
                  Description as description
              FROM fees
              WHERE OrgID = :orgId
              ORDER BY DueDate DESC";

      $stmt = $this->db->connect()->prepare($sql);
      $stmt->bindParam(':orgId', $orgId, PDO::PARAM_INT);
      $stmt->execute();

      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
      error_log("Query result: " . json_encode($result)); // Debug log
      
      return $result;

  } catch (PDOException $e) {
      error_log("Database error in getOrganizationPayments: " . $e->getMessage());
      throw new Exception("Database error: " . $e->getMessage());
  }
}





}

