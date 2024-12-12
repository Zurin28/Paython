<?php
require_once 'classes/Organization.php';
header('Content-Type: application/json');

try {
    if (!isset($_POST['org_name'])) {
        throw new Exception('Organization name is required');
    }

    $org = new Organization();
    $result = $org->createOrgMember($_POST['org_name']);

    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Member table created successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create member table']);
    }
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'already exists') !== false) {
        echo json_encode(['status' => 'success', 'message' => 'Table already exists']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
} 