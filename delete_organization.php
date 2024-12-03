<?php
require_once 'classes/Organization.php';
require_once 'database.class.php';

header('Content-Type: application/json');

try {
    if (!isset($_POST['org_id'])) {
        throw new Exception('Organization ID is required');
    }

    $database = new Database();
    $pdo = $database->connect();
    $org = new Organization($pdo);

    $orgId = trim($_POST['org_id']);
    
    if (empty($orgId)) {
        throw new Exception('Invalid Organization ID');
    }

    $result = $org->deleteOrganization($orgId);

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Organization deleted successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Organization not found or could not be deleted'
        ]);
    }

} catch (Exception $e) {
    error_log("Error deleting organization: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
} 