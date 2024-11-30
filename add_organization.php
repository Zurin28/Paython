<?php
session_start();
require_once 'database.class.php';
require_once 'classes/Organization.php';

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['StudentID'])) {
        throw new Exception('Unauthorized access');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Validate and sanitize input
    $org_id = trim($_POST['org_id'] ?? '');
    $org_name = trim($_POST['org_name'] ?? '');

    if (empty($org_id) || empty($org_name)) {
        throw new Exception('Organization ID and Name are required');
    }

    $organization = new Organization($conn);
    $organization->addOrganization($org_id, $org_name);

    echo json_encode([
        'status' => 'success',
        'message' => 'Organization added successfully'
    ]);

} catch (Exception $e) {
    error_log("Error adding organization: " . $e->getMessage());
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

