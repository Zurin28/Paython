<?php
require_once 'classes/Organization.php';
require_once 'classes/academicperiod.class.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['org_id'])) {
        throw new Exception('Organization ID is required');
    }

    // Get current academic period
    $academicPeriod = new AcademicPeriod();
    $currentPeriod = $academicPeriod->getCurrentAcademicPeriod();

    if (!$currentPeriod) {
        throw new Exception("No active academic period found");
    }

    $org = new Organization();
    $members = $org->getOrganizationMembers(
        $_GET['org_id'],
        $currentPeriod['school_year'],
        $currentPeriod['semester']
    );
    
    echo json_encode($members);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
} 