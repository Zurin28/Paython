<?php
require_once __DIR__ . "/../database.class.php";
class Organization {
    private $db;

     function __construct(){
        $this->db = new Database;
    }

    public function getAllOrganizations() {
        try {
            $sql = "SELECT OrganizationID as org_id, OrgName as name FROM organizations";
            $qry = $this->db->connect()->prepare($sql);
            $qry->execute();
            $organizations = $qry->fetchAll(PDO::FETCH_ASSOC); // Fetch all rows as an array of associative arrays
    
            // Format organizations data
            foreach ($organizations as &$org) {
                $org = [
                    'id' => $org['org_id'],
                    'org_id' => $org['org_id'],
                    'name' => $org['name'],
                ];
            }
    
            return $organizations;
    
        } catch (PDOException $e) {
            error_log("Error fetching organizations: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Adds a new organization and creates its corresponding members table
     * 
     * @param string $orgId The organization ID
     * @param string $orgName The organization name
     * @return array Associative array containing status and message
     * @throws Exception When validation fails or database operations fail
     */
    public function addOrganization($orgId, $orgName) {
        $conn = null;
        $transactionStarted = false;

        // Get current academic period
        $academicPeriod = new AcademicPeriod();
        $currentPeriod = $academicPeriod->getCurrentAcademicPeriod();
        if (!$currentPeriod) {
            throw new Exception("No current academic period set.");
        }

        // Input validation
        if (empty($orgId) || empty($orgName)) {
            throw new Exception("Organization ID and name are required");
        }

        if (!preg_match('/^[a-zA-Z0-9\s_-]+$/', $orgName)) {
            throw new Exception("Organization name contains invalid characters");
        }

        try {
            $conn = $this->db->connect();
            
            // Check for existing organization
            $checkSql = "SELECT COUNT(*) FROM organizations WHERE OrganizationID = :orgId OR OrgName = :orgName";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bindParam(':orgId', $orgId, PDO::PARAM_STR);
            $checkStmt->bindParam(':orgName', $orgName, PDO::PARAM_STR);
            $checkStmt->execute();
            
            if ($checkStmt->fetchColumn() > 0) {
                throw new Exception("Organization with this ID or name already exists");
            }

            // Start transaction
            $conn->beginTransaction();
            $transactionStarted = true;

            // Insert into organizations table
            $insertSql = "INSERT INTO organizations (OrganizationID, OrgName, school_year, semester) 
                          VALUES (:orgId, :orgName, :school_year, :semester)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bindParam(':orgId', $orgId, PDO::PARAM_STR);
            $insertStmt->bindParam(':orgName', $orgName, PDO::PARAM_STR);
            $insertStmt->bindParam(':school_year', $currentPeriod['school_year'], PDO::PARAM_STR);
            $insertStmt->bindParam(':semester', $currentPeriod['semester'], PDO::PARAM_STR);
            
            if (!$insertStmt->execute()) {
                throw new Exception("Failed to insert organization record");
            }

            // Create sanitized table name
            $tableName = 'pms1_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $orgName);
            
            // SQL to create the members table
            $sqlCreateTable = "CREATE TABLE IF NOT EXISTS `$tableName` (
                `id` INT PRIMARY KEY AUTO_INCREMENT,
                `OrganizationID` VARCHAR(100) COLLATE utf8mb4_general_ci,
                `StudentID` INT(11),
                `first_name` VARCHAR(255),
                `last_name` VARCHAR(255),
                `WmsuEmail` VARCHAR(255),
                `Position` VARCHAR(50),
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (`OrganizationID`) REFERENCES `organizations`(`OrganizationID`) ON DELETE CASCADE,
                FOREIGN KEY (`StudentID`) REFERENCES `account`(`StudentID`) ON DELETE CASCADE,
                UNIQUE KEY `unique_student` (`OrganizationID`, `StudentID`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

            // Create the members table
            if ($conn->exec($sqlCreateTable) === false) {
                throw new Exception("Failed to create members table");
            }

            // Log successful creation
            error_log("Organization created successfully: $orgName ($orgId)");
            
            // Commit transaction
            $conn->commit();
            $transactionStarted = false;

            return [
                'status' => 'success',
                'message' => 'Organization added successfully',
                'data' => [
                    'org_id' => $orgId,
                    'org_name' => $orgName,
                    'table_name' => $tableName
                ]
            ];

        } catch (PDOException $e) {
            // Database-specific error handling
            if ($transactionStarted && $conn) {
                $conn->rollBack();
            }
            error_log("Database error in addOrganization: " . $e->getMessage());
            throw new Exception("Database error: " . $e->getMessage());
            
        } catch (Exception $e) {
            // General error handling
            if ($transactionStarted && $conn) {
                $conn->rollBack();
            }
            error_log("Error in addOrganization: " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteOrganization($orgId) {
        $db = null;
        try {
            $db = $this->db->connect();
            
            // First get the organization details to know the table name
            $orgDetails = $this->getOrganizationById($orgId);
            if (!$orgDetails) {
                throw new Exception("Organization not found");
            }

            // Start transaction after getting org details
            $db->beginTransaction();

            // Generate the table name
            $tableName = 'pms1_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $orgDetails['name']);
            
            // Drop the members table first
            $sqlDropTable = "DROP TABLE IF EXISTS `$tableName`";
            $db->exec($sqlDropTable);

            // Then delete from organizations table
            $sql = "DELETE FROM organizations WHERE OrganizationID = :orgId";
            $query = $db->prepare($sql);
            $query->bindParam(':orgId', $orgId);
            $query->execute();

            // Check if transaction is active before committing
            if ($db->inTransaction()) {
                $db->commit();
            }
            
            return true;
        } catch (PDOException $e) {
            if ($db && $db->inTransaction()) {
                $db->rollBack();
            }
            error_log("Error in deleteOrganization: " . $e->getMessage());
            throw new Exception("Failed to delete organization");  // Simplified error message
        } catch (Exception $e) {
            if ($db && $db->inTransaction()) {
                $db->rollBack();
            }
            error_log("Error in deleteOrganization: " . $e->getMessage());
            throw new Exception("Failed to delete organization");  // Simplified error message
        }
    }

    function getOrganizationById($orgId) {
        try {
            $sql = "SELECT OrganizationID as org_id, OrgName as name FROM organizations WHERE OrganizationID = :orgId";
            $qry = $this->db->connect()->prepare($sql);
            
            // Bind the parameter
            $qry->bindParam(':orgId', $orgId, PDO::PARAM_STR);
            
            // Execute the query
            $qry->execute();
            
            // Fetch the result
            $organization = $qry->fetch(PDO::FETCH_ASSOC);
            
            // Debug log
            error_log("Organization details for ID $orgId: " . print_r($organization, true));
            
            return $organization ?: null; // Return the organization or null if not found
        } catch (PDOException $e) {
            // Log the error
            error_log("Error in getOrganizationById: " . $e->getMessage());
            return null;
        }
    }
    


    function createOrgMember($orgName) {
        // Ensure the table name is safe by sanitizing the organization name
        $safeOrgName = preg_replace('/[^a-zA-Z0-9_]/', '_', $orgName); // Remove any non-alphanumeric characters
    
        $sql = "CREATE TABLE `pms1_{$safeOrgName}` (
            `OrganizationID` VARCHAR(100) NOT NULL, 
            `StudentID` INT(11) NOT NULL, 
            `first_name` VARCHAR(255) NOT NULL, 
            `last_name` VARCHAR(255) NOT NULL, 
            `WmsuEmail` VARCHAR(255) NOT NULL, 
            `Position` VARCHAR(255) NOT NULL,
            CONSTRAINT fk_OrgID FOREIGN KEY (OrganizationID) REFERENCES organizations(OrganizationID),
            CONSTRAINT fk_StudID FOREIGN KEY (StudentID) REFERENCES account(StudentID)
        ) ENGINE = InnoDB;";
    
        $qry = $this->db->connect()->prepare($sql);
        
        // Execute the query and return whether the table was created successfully
        return $qry->execute();
    }

    public function addPayment($org_id, $fee_id, $fee_name, $amount, $due_date, $description) {
        // Validate the inputs
        if (empty($org_id) || empty($fee_id) || empty($fee_name) || empty($amount) || empty($due_date)) {
            return ['status' => 'error', 'message' => 'Please fill in all required fields.'];
        }

        // SQL query to insert payment details
        $sql = "INSERT INTO fees (OrgID, FeeID, FeeName, Amount, DueDate, Description) 
                VALUES (:org_id, :fee_id, :fee_name, :amount, :due_date, :description)";
        
        try {
            // Prepare the query
            $query = $this->db->connect()->prepare($sql);
            
            // Bind the parameters
            $query->bindParam(':org_id', $org_id);
            $query->bindParam(':fee_id', $fee_id);
            $query->bindParam(':fee_name', $fee_name);
            $query->bindParam(':amount', $amount);
            $query->bindParam(':due_date', $due_date);
            $query->bindParam(':description', $description);

            // Execute the query
            if ($query->execute()) {
                return ['status' => 'success', 'message' => 'Payment added successfully'];
            } else {
                return ['status' => 'error', 'message' => 'Failed to add payment: ' . implode(", ", $query->errorInfo())];
            }
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }
    
    

    public function addMember($orgId, $studentId, $firstName, $lastName, $email, $position, $schoolYear, $semester) {
        try {
            $sql = "INSERT INTO organization_members 
                    (org_id, StudentID, first_name, last_name, WmsuEmail, Position, school_year, semester) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->connect()->prepare($sql);
            return $stmt->execute([
                $orgId, 
                $studentId, 
                $firstName, 
                $lastName, 
                $email, 
                $position,
                $schoolYear,
                $semester
            ]);
        } catch (PDOException $e) {
            error_log("Error adding member: " . $e->getMessage());
            return false;
        }
    }

    public function getOrganizationMembers($orgId, $schoolYear, $semester) {
        try {
            $sql = "SELECT * FROM organization_members 
                    WHERE org_id = ? 
                    AND school_year = ? 
                    AND semester = ?
                    ORDER BY Position";
            
            $stmt = $this->db->connect()->prepare($sql);
            $stmt->execute([$orgId, $schoolYear, $semester]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error getting members: " . $e->getMessage());
            return [];
        }
    }

    public function removeMember($studentId, $orgId, $orgName) {
        try {
            $conn = $this->db->connect();
            
            // Create table name
            $tableName = 'pms1_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $orgName);
            
            // Delete the member from the organization's members table
            $sql = "DELETE FROM `$tableName` WHERE StudentID = :studentId AND OrganizationID = :orgId";
            $stmt = $conn->prepare($sql);
            
            $stmt->bindParam(':studentId', $studentId, PDO::PARAM_INT);
            $stmt->bindParam(':orgId', $orgId, PDO::PARAM_STR);
            
            return $stmt->execute();
    
        } catch (PDOException $e) {
            error_log("Error in removeMember: " . $e->getMessage());
            throw new Exception("Failed to remove member: " . $e->getMessage());
        }
    }


} 