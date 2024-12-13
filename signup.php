<?php
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/debug.log');
error_reporting(E_ALL);

error_log("Starting signup process...");

require_once "account.class.php";
require_once "Fee.class.php";
require_once "classes/academicperiod.class.php";
require_once "studentfees.class.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("Form submitted. Processing POST request...");
    
    // Get current academic period
    $academicPeriod = new AcademicPeriod();
    $currentPeriod = $academicPeriod->getCurrentAcademicPeriod();
    
    error_log("Current Period: " . print_r($currentPeriod, true));
    
    if (!$currentPeriod) {
        error_log("No active academic period found");
        echo "<p style='color: red;'>No active academic period set. Please contact the administrator.</p>";
        exit;
    }

    // Collect user input from the form
    $studentId = $_POST['studentId'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $mi = $_POST['mi'];
    $wmsuEmail = $_POST['wmsuEmail'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $role = 'student';
    $course = $_POST['course'];
    $year = $_POST['year'];
    $section = $_POST['section'];

    error_log("Form data collected: " . print_r($_POST, true));

    // Check if passwords match
    if ($password !== $confirmPassword) {
        error_log("Password mismatch");
        echo "<p style='color: red;'>Passwords do not match. Please try again.</p>";
    } else {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Create an Account object
        $accObj = new Account();

        // Check if the account already exists
        if ($accObj->accountExists($studentId, $wmsuEmail)) {
            error_log("Account already exists for studentId: $studentId or email: $wmsuEmail");
            echo "<p style='color: red;'>Student ID or WMSU Email already exists. Please try again with different credentials.</p>";
        } else {
            error_log("Attempting to create new account...");
            
            // Create the account with the hashed password and academic period
            $isCreated = $accObj->createStudAcc(
                $studentId, 
                $first_name, 
                $last_name, 
                $mi, 
                $wmsuEmail, 
                $hashedPassword, 
                $role, 
                $course, 
                $year, 
                $section,
                $currentPeriod['school_year'],
                $currentPeriod['semester']
            );

            if ($isCreated) {
                error_log("Account created successfully");
                echo "<p style='color: green;'>Account created successfully. You can now log in.</p>";
                echo "<meta http-equiv='refresh' content='3;url=login.php'>";
                exit;
            } else {
                error_log("Failed to create account");
                echo "<p style='color: red;'>Failed to create account. Please try again later.</p>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>
    <h1 style="text-align: center;">Signup Page</h1>
    <form method="POST" action="">
        <label for="studentId">Student ID</label>
        <input type="text" id="studentId" name="studentId" required>

        <label for="first_name">First Name</label>
        <input type="text" id="first_name" name="first_name" required>

        <label for="last_name">Last Name</label>
        <input type="text" id="last_name" name="last_name" required>

        <label for="mi">Middle Initial</label>
        <input type="text" id="mi" name="mi" maxlength="1" required>

        <label for="wmsuEmail">WMSU Email</label>
        <input type="email" id="wmsuEmail" name="wmsuEmail" required>

        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>

        <label for="course">Course</label>
        <select id="course" name="course" required>
            <option value="Computer Science">Computer Science</option>
            <option value="Information Technology">Information Technology</option>
            <option value="Associate in Computer Technology">Associate in Computer Technology</option>
            <option value="Application Development">Application Development</option>
        </select>

        <label for="year">Year</label>
        <select id="year" name="year" required>
            <option value="1">1st Year</option>
            <option value="2">2nd Year</option>
            <option value="3">3rd Year</option>
            <option value="4">4th Year</option>
            <option value="5">Over 4 Years</option>
        </select>

        <label for="section">Section</label>
        <input type="text" id="section" name="section" required>

        <button type="submit">Signup</button>
    </form>
</body>
</html>
