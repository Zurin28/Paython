<?php
require_once 'Fee.class.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['student_id'])) {
        throw new Exception('Student ID not provided');
    }

    $fee = new Fee();
    $fees = $fee->getFeeStatus($_GET['student_id']);

    if (empty($fees)) {
        // Initialize fees if none exist
        $fee->initializeStudentFees($_GET['student_id']);
        $fees = $fee->getFeeStatus($_GET['student_id']);
    }

    echo json_encode($fees);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['error' => $e->getMessage()]);
}