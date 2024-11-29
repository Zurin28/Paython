<?php
session_start();
require_once 'db_connection.php';

// Check if $pdo exists
if (!isset($pdo)) {
    die("Database connection not established");
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $studentId = $_POST['StudentID'] ?? '';
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $mi = $_POST['MI'] ?? '';
        $wmsuEmail = $_POST['WmsuEmail'] ?? '';
        $password = $_POST['Password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? '';
        $course = $_POST['Course'] ?? '';
        $year = $_POST['Year'] ?? '';
        $section = $_POST['Section'] ?? '';

        // Set isstaff and isadmin based on role
        $isstaff = ($role === 'staff' || $role === 'admin') ? 1 : 0;
        $isadmin = ($role === 'admin') ? 1 : 0;

        if (empty($studentId) || empty($first_name) || empty($last_name) || empty($mi) || 
            empty($wmsuEmail) || empty($password) || empty($role) || empty($course) || 
            empty($year) || empty($section)) {
            $error = "All fields are required.";
        } elseif ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            $stmt = $pdo->prepare("SELECT StudentID FROM account WHERE StudentID = ? OR WmsuEmail = ?");
            $stmt->execute([$studentId, $wmsuEmail]);
            if ($stmt->rowCount() > 0) {
                $error = "Student ID or WMSU Email already exists.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO account (StudentID, first_name, last_name, MI, WmsuEmail, 
                                    Password, role, Course, Year, Section, isstaff, isadmin) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                if ($stmt->execute([$studentId, $first_name, $last_name, $mi, $wmsuEmail, 
                                  $password, $role, $course, $year, $section, $isstaff, $isadmin])) {
                    $_SESSION['success_message'] = "Account created successfully. You can now log in.";
                    header("Location: login.php");
                    exit();
                } else {
                    $error = "An error occurred. Please try again.";
                }
            }
        }
    } catch (PDOException $e) {
        $error = "Database error: " . $e->getMessage();
    }
}
?>

<!-- Rest of your HTML remains the same -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - PayThon</title>
    <link rel="stylesheet" href="css/signstyle.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Create Account</h2>
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form action="" method="post">
                <div class="form-row">
                    <div class="form-group">
                        <label for="StudentID">Student ID</label>
                        <input type="text" id="StudentID" name="StudentID" required>
                    </div>
                    <div class="form-group">
                        <label for="MI">Middle Initial</label>
                        <input type="text" id="MI" name="MI" maxlength="1" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="first_name">First Name</label>
                        <input type="text" id="first_name" name="first_name" required>
                    </div>
                    <div class="form-group">
                        <label for="last_name">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="WmsuEmail">WMSU Email</label>
                    <input type="email" id="WmsuEmail" name="WmsuEmail" 
                           pattern="hz[0-9]+@wmsu\.edu\.ph" 
                           placeholder="hz123456789@wmsu.edu.ph" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="Password">Password</label>
                        <input type="password" id="Password" name="Password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="student">Student</option>
                            <option value="staff">Staff</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Course">Course</label>
                        <select id="Course" name="Course" required>
                            <option value="">Select Course</option>
                            <option value="Computer Science">Computer Science</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Associate in Computer Technology">Associate in Computer Technology</option>
                            <option value="Application Development">Application Development</option>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="Year">Year Level</label>
                        <select id="Year" name="Year" required>
                            <option value="">Select Year</option>
                            <option value="1st">1st Year</option>
                            <option value="2nd">2nd Year</option>
                            <option value="3rd">3rd Year</option>
                            <option value="4th">4th Year</option>
                            <option value="Over 4 years">Over 4 Years</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Section">Section</label>
                        <input type="text" id="Section" name="Section" required>
                    </div>
                </div>
                <button type="submit" class="btn">Create Account</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>

