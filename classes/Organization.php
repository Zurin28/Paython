<?php
require_once "database.class.php";
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
    

    function addOrganization($orgId, $orgName) {
    try {
        // Check if the organization already exists
        $sqlCheck = "SELECT COUNT(*) as count FROM organizations WHERE OrganizationID = :orgId";
        $qryCheck = $this->db->connect()->prepare($sqlCheck);
    
        // Bind parameters
        $qryCheck->bindParam(':orgId', $orgId, PDO::PARAM_STR);
        $qryCheck->execute();
        $row = $qryCheck->fetch(PDO::FETCH_ASSOC);
    
        if ($row['count'] > 0) {
            // Organization already exists
            return false;
        }

        // Insert the new organization
        $sqlInsert = "INSERT INTO organizations (OrganizationID, OrgName) VALUES (:orgId, :orgName)";
        $qryInsert = $this->db->connect()->prepare($sqlInsert);
    
        // Bind parameters
        $qryInsert->bindParam(':orgId', $orgId, PDO::PARAM_STR);
        $qryInsert->bindParam(':orgName', $orgName, PDO::PARAM_STR);
    
        // Execute the insert query
        if ($qryInsert->execute()) {
            return true;
        } else {
            error_log("Error inserting organization: " . $qryInsert->errorInfo()[2]);
            return false;
        }
    } catch (PDOException $e) {
        // Log the exception
        error_log("Error in addOrganization: " . $e->getMessage());
        return false;
    }
}

    
    

    function deleteOrganization($orgId) {
        $sql = "DELETE FROM organizations WHERE OrganizationID = :orgId";
        $qry = $this->db->connect()->prepare($sql);
    
        try {
            // Bind the parameter
            $qry->bindParam(':orgId', $orgId, PDO::PARAM_STR);
    
            // Execute the statement
            $qry->execute();
    
            // Check if any rows were affected
            if ($qry->rowCount() === 0) {
                throw new Exception("No organization found with ID: " . $orgId);
            }
    
            return true; // Deletion successful
        } catch (PDOException $e) {
            // Log the error
            error_log("Error in deleteOrganization: " . $e->getMessage());
            return false; // Return false if an error occurs
        }
    }
    
    

    function getOrganizationById($orgId) {
        $sql = "SELECT OrganizationID as org_id, OrgName as name FROM organizations WHERE OrganizationID = :orgId";
        $qry = $this->db->connect()->prepare($sql);
    
        try {
            // Bind the parameter
            $qry->bindParam(':orgId', $orgId, PDO::PARAM_STR);
    
            // Execute the query
            $qry->execute();
    
            // Fetch the result
            $organization = $qry->fetch(PDO::FETCH_ASSOC);
            return $organization ?: null; // Return the organization or null if not found
        } catch (PDOException $e) {
            // Log the error
            error_log("Error in getOrganizationById: " . $e->getMessage());
            return null; // Return null if an error occurs
        }
    }
    
    function createOrg($orgName) {
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
    

} 