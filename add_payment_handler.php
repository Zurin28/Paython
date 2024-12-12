<?php

include('database.class.php');  
include('classes/Organization.php');

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Debug logging
error_log("POST data received: " . print_r($_POST, true));

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_payment'])) {
    // Get form data
    $org_id = $_POST['org_id'];
    $fee_id = $_POST['fee_id'];
    $fee_name = $_POST['fee_name'];
    $amount = $_POST['amount'];
    $due_date = $_POST['due_date'];
    $description = $_POST['description'];

    // Debug logging
    error_log("Processing payment for org_id: $org_id, fee_id: $fee_id");

    $organization = new Organization();
    $response = $organization->addPayment($org_id, $fee_id, $fee_name, $amount, $due_date, $description);

    // Debug logging
    error_log("Response: " . print_r($response, true));

    echo json_encode($response);
} else {
    // Debug logging
    error_log("Required POST parameters not found");
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method or missing parameters']);
}
?>
