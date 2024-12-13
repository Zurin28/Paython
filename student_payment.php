<?php require_once 'fee.class.php';
      require_once 'classes/Organization.php';
      require_once 'studentfees.class.php';
      require_once 'classes/academicperiod.class.php';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayThon - Payment Section</title>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="student_payment.css">
</head>
<body>


    <?php include 'header.php'; ?>


    <main class="main">
        <div class="title-section">
            <h1 class="title">Student Payments</h1>
            <div class="filter-container">
                <select class="filter-dropdown">
                    <option value="all">Status</option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                </select>
            </div>
        </div>
        
        <?php
// Start session to access session variable
session_start();

if (!isset($_SESSION['StudentID'])) {
    die("Unauthorized access. Please log in.");
}

// Use the session key with a capital "S"
$loggedInStudentID = $_SESSION['StudentID'];


?>

<table class="payment-table">
    <thead>
        <tr>
            <th>Organization</th>
            <th>Fee</th>
            <th>Status</th>
            <th>Amount</th>
            <th>Due</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $organization = new Organization;
        $fee = new Fee;
        $studentfee = new StudentFee;

        $studentFeeInfo = $studentfee->viewStudentFees();
        $organizationInfo = $organization->getAllOrganizations();
        $feeInfo = $fee->viewFees();

// Ensure all necessary information was fetched
if (!$studentFeeInfo || !$organizationInfo || !$feeInfo) {
    die("Error fetching data.");
}

// For each fee in the database, check if the student has it
foreach ($feeInfo as $fee) {
    $feeID = $fee['FeeID']; // The ID of the current fee
    $feeName = $fee['FeeName']; // Fee name
    $amount = $fee['Amount']; // Fee amount
    $Due = $fee ['DueDate'];

    
    // Find if this fee exists in the student's fees record
    $studentFee = null;
    foreach ($studentFeeInfo as $studentFeeRecord) {
        
        if ($studentFeeRecord['studentID'] === $loggedInStudentID && $studentFeeRecord['feeID'] === $feeID) {
            $studentFee = $studentFeeRecord;
            break;
        }
    }
    
    // If no student fee record exists, the student has not been assigned this fee
    if ($studentFee === null) {
        $status = 'Not Paid';
    } else {
        // If a student fee record exists, use the stored status
        $status = isset($studentFee['Status']) && $studentFee['Status'] === 'Paid' ? 'Paid' : 'Not Paid';
    }

    // Find the organization name associated with this fee (if exists)
    $organizationName = 'N/A'; // Default if organization is not found


    foreach ($organizationInfo as $org) {
        if (isset($studentFee['OrganizationID']) && $org['org_id'] === $studentFee['OrganizationID']) {
            $organizationName = $org['OrgName'];
            break;
        }
    }
 

    // Output the row with the fee status and data attributes
    echo "<tr data-fee-id='{$feeID}' data-organization-id='" . ($studentFee['OrganizationID'] ?? '') . "'>
            <td>{$organizationName}</td>
            <td>{$feeName}</td>
            <td>{$status}</td>
            <td>{$amount}</td>
            <td>{$Due}</td>
            <td><button class='action-button pay-now' id='payNowBtn' 
                data-fee-id='{$feeID}'
                data-fee-name='{$feeName}'
                data-organization='{$organizationName}'
                data-amount='{$amount}'
                data-due='{$Due}'>Pay Now</button></td>
        </tr>";
}
?>
    </tbody>
</table>


    </main>

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <!-- Content will be loaded here via AJAX -->
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="success-modal">
        <div class="success-modal-content">
            <div class="success-icon">✓</div>
            <h1 class="success-title">Done!</h1>
            <p class="success-message">Your Payment Has Been Processed Successfully</p>
        </div>
    </div>

    <script src="studentside.js"></script>
</body>
</html>

