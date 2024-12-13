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
    // Get current academic period
    $academicPeriod = new AcademicPeriod();
    $currentPeriod = $academicPeriod->getCurrentAcademicPeriod();
    if (!$currentPeriod) {
        return [];
    }

    $sql = "SELECT * FROM Fees WHERE school_year = :school_year AND semester = :semester";
    $qry = $this->db->connect()->prepare($sql);
    $qry->bindParam(':school_year', $currentPeriod['school_year']);
    $qry->bindParam(':semester', $currentPeriod['semester']);
    if ($qry->execute()) {
        return $qry->fetchAll();
    }
    return [];
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
        // Get current period
        $academicPeriod = new AcademicPeriod();
        $currentPeriod = $academicPeriod->getCurrentAcademicPeriod();

        // Get all fees for current period
        $sql = "SELECT FeeID 
                FROM Fees 
                WHERE school_year = :school_year 
                AND semester = :semester";
                
        $qry = $this->db->connect()->prepare($sql);
        $qry->bindParam(':school_year', $currentPeriod['school_year'], PDO::PARAM_STR);
        $qry->bindParam(':semester', $currentPeriod['semester'], PDO::PARAM_STR);
        $qry->execute();
        
        $fees = $qry->fetchAll(PDO::FETCH_ASSOC);

        if (empty($fees)) {
            return true;
        }

        // Insert student fees one by one
        $insertSql = "INSERT INTO student_fees 
                     (studentID, school_year, semester, feeID, paymentStatus) 
                     VALUES 
                     (:student_id, :school_year, :semester, :fee_id, 'Not Paid')";
        
        $insertQry = $this->db->connect()->prepare($insertSql);
        
        foreach ($fees as $fee) {
            $insertQry->bindParam(':student_id', $student_id, PDO::PARAM_STR);
            $insertQry->bindParam(':school_year', $currentPeriod['school_year'], PDO::PARAM_STR);
            $insertQry->bindParam(':semester', $currentPeriod['semester'], PDO::PARAM_STR);
            $insertQry->bindParam(':fee_id', $fee['FeeID'], PDO::PARAM_INT);
            $insertQry->execute();
        }

        return true;

    } catch (PDOException $e) {
        return false;
    }
}
/******  ea9d0024-6bfb-4a80-b69a-3da38a6a6650  *******/

public function getOrganizationPayments($orgId, $schoolYear, $semester) {
    $sql = "SELECT * FROM payments WHERE org_id = ? AND school_year = ? AND semester = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iss", $orgId, $schoolYear, $semester);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

public function addPayment($org_id, $fee_id, $fee_name, $amount, $due_date, $description, $school_year, $semester) {
    try {
        $conn = $this->db->connect();
        $conn->beginTransaction();

        error_log("Organization ID: " . $org_id);

        // First, verify if the organization exists for the current period
        $checkSql = "SELECT COUNT(*) FROM organizations 
                    WHERE OrganizationID = :org_id 
                    AND school_year = :school_year 
                    AND semester = :semester";
        
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->execute([
            ':org_id' => $org_id,
            ':school_year' => $school_year,
            ':semester' => $semester
        ]);
        
        $exists = $checkStmt->fetchColumn();
        error_log("Organization exists check: " . ($exists ? "Yes" : "No"));
        
        if (!$exists) {
            // Get the organization details from any period
            $getOrgSql = "SELECT OrgName FROM organizations WHERE OrganizationID = :org_id LIMIT 1";
            $getOrgStmt = $conn->prepare($getOrgSql);
            $getOrgStmt->execute([':org_id' => $org_id]);
            $orgDetails = $getOrgStmt->fetch(PDO::FETCH_ASSOC);

            if (!$orgDetails) {
                throw new Exception("Organization not found");
            }

            // Create organization entry for current period
            $createOrgSql = "INSERT INTO organizations (OrganizationID, OrgName, school_year, semester) 
                            VALUES (:org_id, :org_name, :school_year, :semester)";
            
            $createOrgStmt = $conn->prepare($createOrgSql);
            $createResult = $createOrgStmt->execute([
                ':org_id' => $org_id,
                ':org_name' => $orgDetails['OrgName'],
                ':school_year' => $school_year,
                ':semester' => $semester
            ]);

            if (!$createResult) {
                throw new Exception("Failed to create organization for current period");
            }

            error_log("Created organization for current period");
        }

        // Now proceed with adding the fee
        $sql = "INSERT INTO fees (FeeID, FeeName, Amount, DueDate, Description, OrgID, school_year, semester) 
                VALUES (:fee_id, :fee_name, :amount, :due_date, :description, :org_id, :school_year, :semester)";
        
        $stmt = $conn->prepare($sql);
        $params = [
            ':fee_id' => $fee_id,
            ':fee_name' => $fee_name,
            ':amount' => $amount,
            ':due_date' => $due_date,
            ':description' => $description,
            ':org_id' => $org_id,
            ':school_year' => $school_year,
            ':semester' => $semester
        ];
        
        error_log("Adding fee with params: " . print_r($params, true));
        
        if ($stmt->execute($params)) {
            $conn->commit();
            error_log("Successfully added fee");
            return ['status' => 'success', 'message' => 'Payment added successfully'];
        } else {
            throw new Exception("Failed to add payment");
        }
        
    } catch (Exception $e) {
        if ($conn && $conn->inTransaction()) {
            $conn->rollBack();
        }
        error_log("Database error in addPayment: " . $e->getMessage());
        error_log("Stack trace: " . $e->getTraceAsString());
        return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
    }
}





}

