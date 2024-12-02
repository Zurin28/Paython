<?php
require_once "database.class.php";
class Organization {
    private $db;

     function __construct($db) {
        $this->db = new Database;
    }

    public function getAllOrganizations() {
        try {
            $sql = "SELECT OrganizationID as org_id, OrgName as name FROM organizations";
            $result = $this->db->query($sql);
            $organizations = [];

            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $organizations[] = [
                        'id' => $row['org_id'],
                        'org_id' => $row['org_id'],
                        'name' => $row['name']
                    ];
                }
            }
            return $organizations;
        } catch (Exception $e) {
            error_log("Error fetching organizations: " . $e->getMessage());
            return [];
        }
    }

    function addOrganization($orgId, $orgName) {
        // Check if the organization exists
        $sqlCheck = "SELECT COUNT(*) as count FROM organizations WHERE OrganizationID = :orgId";
        $qryCheck = $this->db->connect()->prepare($sqlCheck);
    
        // Bind parameters
        $qryCheck->bindParam(':orgId', $orgId, PDO::PARAM_STR);
    
        // Execute and fetch the result
        $qryCheck->execute();
        $row = $qryCheck->fetch(PDO::FETCH_ASSOC);
    
        if ($row['count'] > 0) {
            return false; // Organization ID already exists
        }
    
        // Add a new organization
        $sqlInsert = "INSERT INTO organizations (OrganizationID, OrgName) VALUES (:orgId, :orgName)";
        $qryInsert = $this->db->connect()->prepare($sqlInsert);
    
        // Bind parameters
        $qryInsert->bindParam(':orgId', $orgId, PDO::PARAM_STR);
        $qryInsert->bindParam(':orgName', $orgName, PDO::PARAM_STR);
    
        // Execute the insert query and return the result
        return $qryInsert->execute();
    }
    
    

    function deleteOrganization($orgId) {
        try {
            // Prepare the delete statement
            $sqlDelete = "DELETE FROM organizations WHERE OrganizationID = :orgId";
            $stmt = $this->db->connect()->prepare($sqlDelete);
    
            // Bind the parameter
            $stmt->bindParam(':orgId', $orgId, PDO::PARAM_STR);
    
            // Execute the statement
            $stmt->execute();
    
            // Check if any rows were affected
            if ($stmt->rowCount() === 0) {
                throw new Exception("No organization found with ID: " . $orgId);
            }
    
            return true;
    
        } catch (PDOException $e) {
            // Log the error and rethrow it
            error_log("Error in deleteOrganization: " . $e->getMessage());
            throw $e;
        }
    }
    

    public function getOrganizationById($orgId) {
        try {
            $stmt = $this->db->prepare("SELECT OrganizationID as org_id, OrgName as name FROM organizations WHERE OrganizationID = ?");
            $stmt->bind_param("s", $orgId);
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_assoc();
        } catch (Exception $e) {
            error_log("Error in getOrganizationById: " . $e->getMessage());
            return null;
        }
    }
} 