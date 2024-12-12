<?php
require_once 'account.class.php';
header('Content-Type: application/json');

try {
    if (!isset($_GET['student_id'])) {
        throw new Exception('Student ID is required');
    }

    $account = new Account();
    $studentDetails = $account->getStudentDetails($_GET['student_id']);

    // Debug log
    error_log('Student Details: ' . print_r($studentDetails, true));

    if ($studentDetails) {
        echo json_encode([
            'status' => 'success',
            'student' => $studentDetails
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Student not found'
        ]);
    }
} catch (Exception $e) {
    error_log('Error in get_student_details.php: ' . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 