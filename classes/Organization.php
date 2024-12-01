<?php
class Organization {
    private $db;

     function __construct($db) {
        $this->db = $db;
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
        // Check if organization exists
        $sqlCheck = "SELECT COUNT(*) as count FROM organizations WHERE OrganizationID = ?";
        $stmtCheck = $this->db->connect()->prepare($sqlCheck);
        $stmtCheck->bind_param("s", $orgId);
        $stmtCheck->execute();
        $result = $stmtCheck->get_result();
        $row = $result->fetch_assoc();
        $stmtCheck->close();
    
        if ($row['count'] > 0) {
            return false; // Organization ID already exists
        }
    
        // Add new organization
        $sqlInsert = "INSERT INTO organizations (OrganizationID, OrgName) VALUES (?, ?)";
        $stmtInsert = $this->db->connect()->prepare($sqlInsert);
        $stmtInsert->bind_param("ss", $orgId, $orgName);
    
        $success = $stmtInsert->execute();
        $stmtInsert->close();
    
        return $success;
    }
    

     function deleteOrganization($orgId) {
        try {
            // Get the database connection
            $conn = $this->db->getConnection();
            
            // Prepare the statement
            $stmt = $conn->prepare("DELETE FROM organizations WHERE OrganizationID = ?");
            if (!$stmt) {
                throw new Exception("Failed to prepare delete statement: " . $conn->error);
            }

            // Bind the parameter
            $stmt->bind_param("s", $orgId);
            
            // Execute the statement
            if (!$stmt->execute()) {
                throw new Exception("Failed to execute delete statement: " . $stmt->error);
            }

            // Check if any rows were affected
            if ($stmt->affected_rows === 0) {
                throw new Exception("No organization found with ID: " . $orgId);
            }

            $stmt->close();
            return true;

        } catch (Exception $e) {
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