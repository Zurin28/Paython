<?php
require_once "database.class.php";
require_once 'account.class.php';

// Create a Database instance
$account  = new Database;

// Query to fetch data
$query = "SELECT id, name, email FROM your_table";
$data = $dbInstance->fetchData($query);

// Return data as JSON
echo json_encode($data);
?>
