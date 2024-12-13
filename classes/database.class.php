<?php
if (!class_exists('Database')) {
    class Database {
        protected $db;

        function connect() {
            try {
                $this->db = new PDO("mysql:host=localhost;dbname=PMS1", "root", "");
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->db;
            } catch(PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
    }
}
?>