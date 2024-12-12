<?php
require_once 'classes/Organization.php';
header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['org_id']) || !isset($data['student_id']) || !isset($data['org_name'])) {
        throw new Exception('Missing required parameters');
    }

    $org = new Organization();
    $result = $org->removeMember($data['org_id'], $data['student_id'], $data['org_name']);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Member removed successfully'
        ]);
    } else {
        throw new Exception('Failed to remove member');
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 