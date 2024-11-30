<?php
require_once "account.class.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect user input from the form
    $studentId = $_POST['studentId'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $mi = $_POST['mi'];
    $wmsuEmail = $_POST['wmsuEmail'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $role = $_POST['role'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $section = $_POST['section'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        echo "<p style='color: red;'>Passwords do not match. Please try again.</p>";
    } else {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Create an Account object
        $accObj = new Account();

        // Check if the account already exists
        if ($accObj->accountExists($studentId, $wmsuEmail)) {
            echo "<p style='color: red;'>Student ID or WMSU Email already exists. Please try again with different credentials.</p>";
        } else {
            // Create the account with the hashed password
            $isCreated = $accObj->create($studentId, $first_name, $last_name, $mi, $wmsuEmail, $hashedPassword, $role, $course, $year, $section);
            if ($isCreated) {
                echo "<p style='color: green;'>Account created successfully. You can now log in.</p>";

                // Delay using sleep and a meta-refresh tag
                echo "<meta http-equiv='refresh' content='3;url=login.php'>";

                exit;
            } else {
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
    <style>
        form {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        input, select, button {
            display: block;
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            font-size: 16px;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
    </style>
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

        <label for="role">Role</label>
        <select id="role" name="role" required>
            <option value="student">Student</option>
        </select>

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