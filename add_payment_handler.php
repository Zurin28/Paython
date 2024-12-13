<?php

require_once('database.class.php');  
require_once('classes/Organization.php');
require_once 'classes/academicperiod.class.php';
require_once 'Fee.class.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Debug logging
error_log("POST data received: " . print_r($_POST, true));

// Ensure proper content type
header('Content-Type: application/json');

try {
    // Get current academic period
    $academicPeriod = new AcademicPeriod();
    $currentPeriod = $academicPeriod->getCurrentAcademicPeriod();

    if (!$currentPeriod) {
        throw new Exception("No active academic period found");
    }

    // Get POST data
    $orgId = $_POST['org_id'];
    $feeId = $_POST['fee_id'];
    $feeName = $_POST['fee_name'];
    $amount = $_POST['amount'];
    $dueDate = $_POST['due_date'];
    $description = $_POST['description'];

    // Debug log
    error_log("Adding payment with data: " . print_r($_POST, true));
    error_log("Current period: " . print_r($currentPeriod, true));

    // Create Fee instance and use its method to add payment
    $fee = new Fee();
    $result = $fee->addPayment(
        $orgId,
        $feeId,
        $feeName,
        $amount,
        $dueDate,
        $description,
        $currentPeriod['school_year'],
        $currentPeriod['semester']
    );

    if ($result['status'] === 'success') {
        echo json_encode(['status' => 'success', 'message' => 'Payment added successfully']);
    } else {
        throw new Exception($result['message']);
    }

} catch (Exception $e) {
    error_log("Error in add_payment_handler.php: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
