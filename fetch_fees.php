<?php
include 'database.class.php';  // Include your database connection file
include 'Fee.class.php';  // Include the fee class file

// Get the student_id from the query string
$student_id = isset($_GET['student_id']) ? $_GET['student_id'] : die('Student ID not provided');

// Create an instance of the Fee class
$fee = new Fee();  // No need to pass the PDO connection here

// Call the function to get fee status
$fees = $fee->getFeeStatus($student_id);

// Return the data as a JSON response
echo json_encode($fees);
?>
