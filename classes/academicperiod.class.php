<?php 
require_once($_SERVER['DOCUMENT_ROOT'] . '/Paython/classes/database.class.php');

class AcademicPeriod {
    public $school_year;
    public $semester;
    public $start_date;
    public $end_date;
    public $is_current;
    protected $db;

    function __construct() {
        $this->db = new Database();
    }

    // Get all academic periods
    function getAllPeriods($searchQuery = '') {
        $sql = "SELECT * FROM academic_periods 
                WHERE (school_year LIKE :search OR semester LIKE :search OR :search = '')
                ORDER BY is_current DESC, 
                school_year DESC, 
                CASE semester 
                    WHEN '1st' THEN 1 
                    WHEN '2nd' THEN 2 
                    WHEN 'Summer' THEN 3 
                END";
                
        $query = $this->db->connect()->prepare($sql);
        $searchTerm = "%$searchQuery%";
        $query->bindParam(':search', $searchTerm, PDO::PARAM_STR);
        
        if ($query->execute()) {
            return $query->fetchAll();
        }
        return false;
    }

    // Get current academic period
    function getCurrentPeriod() {
        $sql = "SELECT * FROM academic_periods WHERE is_current = 1 LIMIT 1";
        $query = $this->db->connect()->prepare($sql);
        if ($query->execute()) {
            return $query->fetch();
        }
        return false;
    }

    // Add new academic period
    function addPeriod($school_year, $semester, $start_date, $end_date) {
        $sql = "INSERT INTO academic_periods (school_year, semester, start_date, end_date) 
                VALUES (?, ?, ?, ?)";
        $query = $this->db->connect()->prepare($sql);
        return $query->execute([$school_year, $semester, $start_date, $end_date]);
    }

    // Set current academic period
    function setCurrentPeriod($school_year, $semester) {
        try {
            $connection = $this->db->connect();
            $connection->beginTransaction();

            // Reset all periods to not current
            $sql1 = "UPDATE academic_periods SET is_current = 0";
            $query1 = $connection->prepare($sql1);
            $query1->execute();

            // Set the selected period as current
            $sql2 = "UPDATE academic_periods SET is_current = 1 
                     WHERE school_year = :school_year AND semester = :semester";
            $query2 = $connection->prepare($sql2);
            $query2->bindParam(':school_year', $school_year, PDO::PARAM_STR);
            $query2->bindParam(':semester', $semester, PDO::PARAM_STR);
            
            // Add debug output
            error_log("Updating period: School Year: $school_year, Semester: $semester");
            
            $result = $query2->execute();
            
            if ($result) {
                $connection->commit();
                error_log("Update successful");
                return true;
            } else {
                $connection->rollBack();
                error_log("Update failed");
                return false;
            }
        } catch (Exception $e) {
            error_log("Error in setCurrentPeriod: " . $e->getMessage());
            if ($connection && $connection->inTransaction()) {
                $connection->rollBack();
            }
            return false;
        }
    }

    // Get period by ID
    function getPeriodById($id) {
        $sql = "SELECT * FROM academic_periods WHERE id = ?";
        $query = $this->db->connect()->prepare($sql);
        if ($query->execute([$id])) {
            return $query->fetch();
        }
        return false;
    }

    // Check if period exists
    function periodExists($school_year, $semester) {
        $sql = "SELECT COUNT(*) FROM academic_periods WHERE school_year = ? AND semester = ?";
        $query = $this->db->connect()->prepare($sql);
        $query->execute([$school_year, $semester]);
        return $query->fetchColumn() > 0;
    }

    // Get active period (current period that's not ended)
    function getActivePeriod() {
        $sql = "SELECT * FROM academic_periods 
                WHERE is_current = 1 
                AND CURDATE() BETWEEN start_date AND end_date 
                LIMIT 1";
        $query = $this->db->connect()->prepare($sql);
        if ($query->execute()) {
            return $query->fetch();
        }
        return false;
    }

    function getCurrentAcademicPeriod() {
        $sql = "SELECT school_year, semester FROM academic_periods WHERE is_current = 1 LIMIT 1";
        $query = $this->db->connect()->prepare($sql);
        if ($query->execute()) {
            return $query->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }
} 