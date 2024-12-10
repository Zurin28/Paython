<?php
// Include necessary files
require_once 'account.class.php';
require_once 'classes/Organization.php';
require_once 'database.class.php'; // Include the Database class
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

try {
    // Use the Database class to get a PDO connection
    $database = new Database(); // Create an instance of Database
    $pdo = $database->connect(); // Call the connect method to get the PDO object

    // Initialize the Organization class, passing the PDO connection
    $org = new Organization($pdo);

    // Check if the required parameters are received from the AJAX request
    if (isset($_POST['org_name']) && isset($_POST['org_id'])) {
        $orgName = $_POST['org_name'];
        $orgID = $_POST['org_id'];
        $_SESSION['orgName'] = $orgName;

        // Call the addOrganization function to insert the data into the database
        $result = $org->addOrganization($orgName, $orgID);

        // Return success or failure response
        if ($result) {

            header("Location: admin_organizations");
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Failed to add organization'
            ]);
        }
    } else {
        // Handle missing parameters
        echo json_encode(['status' => 'error', 'message' => 'Missing required parameters']);
    }
} catch (PDOException $e) {
    // Handle connection or execution errors
    echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    exit;
}
