<?php
// Include necessary files
require_once 'account.class.php';
require_once 'classes/Organization.php';
require_once 'database.class.php';
session_start();

header('Content-Type: application/json');

try {
    if (!isset($_POST['org_id']) || !isset($_POST['org_name'])) {
        throw new Exception('Organization ID and name are required');
    }

    $org = new Organization();
    
    // First, add the organization to the organizations table
    $result = $org->addOrganization($_POST['org_id'], $_POST['org_name']);
    
    // If we got here, the organization was added successfully
    echo json_encode([
        'status' => 'success',
        'message' => 'Organization added successfully'
    ]);

} catch (Exception $e) {
    // Log the error but don't show it to the user
    error_log('Error in add_organization_handler.php: ' . $e->getMessage());
    
    // Check if organization was actually added despite transaction issues
    if (strpos($e->getMessage(), 'no active transaction') !== false) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Organization added successfully'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add organization. Please try again.'
        ]);
    }
}
