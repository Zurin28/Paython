<?php
require_once 'classes/Organization.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['org_id'])) {
        throw new Exception('Organization ID is required');
    }

    $org = new Organization();
    $members = $org->getOrganizationMembers($_GET['org_id']);
    
    echo json_encode($members);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} 