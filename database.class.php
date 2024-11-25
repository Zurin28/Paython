<?php

class Database {
    protected $db;

    function connect() {
        try {
            $this->db = new PDO("mysql:host=localhost;dbname=PMS1", "root", "");
            return $this->db;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}