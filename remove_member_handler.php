<?php
require_once 'classes/Organization.php';
header('Content-Type: application/json');

try {
    // Get POST data
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['student_id']) || !isset($data['org_id']) || !isset($data['org_name'])) {
        throw new Exception('Missing required parameters');
    }

    $org = new Organization();
    $result = $org->removeMember($data['student_id'], $data['org_id'], $data['org_name']);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Member removed successfully'
        ]);
    } else {
        throw new Exception('Failed to remove member');
    }

} catch (Exception $e) {
    error_log('Error in remove_member_handler.php: ' . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 