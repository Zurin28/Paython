<?php
require_once 'classes/Organization.php';
require_once 'account.class.php';
require_once 'classes/academicperiod.class.php';

header('Content-Type: application/json');

try {
    // Get current academic period
    $academicPeriod = new AcademicPeriod();
    $currentPeriod = $academicPeriod->getCurrentAcademicPeriod();

    if (!$currentPeriod) {
        throw new Exception("No active academic period found");
    }

    if (!isset($_POST['org_id']) || !isset($_POST['student_id']) || !isset($_POST['position'])) {
        throw new Exception('All required fields must be filled');
    }

    $org = new Organization();
    $account = new Account();
    
    // Get organization details
    $orgDetails = $org->getOrganizationById($_POST['org_id']);
    if (!$orgDetails) {
        throw new Exception('Organization not found');
    }

    // First, try to create the table if it doesn't exist
    try {
        $org->createOrgMember($orgDetails['name']);
    } catch (Exception $e) {
        // Ignore error if table already exists
        if (!strpos($e->getMessage(), 'already exists')) {
            throw $e;
        }
    }

    // Get student details
    $studentDetails = $account->getStudentDetails($_POST['student_id']);
    if (!$studentDetails) {
        throw new Exception('Student not found');
    }

    // Now add the member with academic period
    $result = $org->addMember(
        $_POST['org_id'],
        $_POST['student_id'],
        $studentDetails['first_name'],
        $studentDetails['last_name'],
        $studentDetails['WmsuEmail'],
        $_POST['position'],
        $currentPeriod['school_year'],
        $currentPeriod['semester']
    );

    if ($result) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Member added successfully'
        ]);
    } else {
        throw new Exception('Failed to add member');
    }

} catch (Exception $e) {
    error_log('Error in add_member_handler.php: ' . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to add member: ' . $e->getMessage()
    ]);
}