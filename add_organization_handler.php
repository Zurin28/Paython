<?php
// Include the necessary files and initialize the connection
include_once 'account.class.php';
$account = new Account();

// Check if the required parameters are received from the AJAX request
if (isset($_POST['orgName']) && isset($_POST['orgID'])) {
    $orgName = $_POST['orgName'];
    $orgID = $_POST['orgID'];

    // Call the addOrganization function to insert the data into the database
    $result = $account->addOrganization($orgName, $orgID);

    // Return success or failure response
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Organization added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add organization']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}
?>
