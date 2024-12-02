<?php
// Include necessary files and initialize the connection
require_once 'account.class.php';
require_once 'classes/Organization.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



header('Content-Type: application/json');

// Database connection setup
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pms1"; // Change this to your actual database name

// Create the database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize the Organization class, passing the database connection
$org = new Organization($conn);

// Check if the required parameters are received from the AJAX request
if (isset($_POST['org_name']) && isset($_POST['org_id'])) {
    $orgName = $_POST['org_name'];
    $orgID = $_POST['org_id'];

    // Call the addOrganization function to insert the data into the database
    $result = $org->addOrganization($orgName, $orgID);
    echo $result;

    // Return success or failure response
    if ($result) {
        echo json_encode(['status' => 'success', 'message' => 'Organization added successfully']);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to add organization: ' . $conn->error
        ]);
    }
} else {
    // Handle missing parameters
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
}

$conn->close();
?>