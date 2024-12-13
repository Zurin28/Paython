<?php
require_once 'classes/academicperiod.class.php';

header('Content-Type: application/json');

try {
    $academicPeriod = new AcademicPeriod();
    $currentPeriod = $academicPeriod->getCurrentAcademicPeriod();
    
    if ($currentPeriod) {
        echo json_encode($currentPeriod);
    } else {
        echo json_encode(['error' => 'No active academic period found']);
    }
} catch (Exception $e) {
    error_log("Error in get_current_period.php: " . $e->getMessage());
    echo json_encode(['error' => 'Error checking academic period']);
} 