<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login Logs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_logs.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <?php
    require_once 'classes/academicperiod.class.php';
    require_once 'tools/functions.php';

    $academicPeriod = new AcademicPeriod();

    // At the top of the file, add error reporting
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Temporary debug code
    error_log("POST data: " . print_r($_POST, true));

    // Handle form submission for adding new semester
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_semester'])) {
        $school_year = clean_input($_POST['school_year']);
        $semester = clean_input($_POST['semester']);
        $start_date = clean_input($_POST['start_date']);
        $end_date = clean_input($_POST['end_date']);
        
        if ($academicPeriod->periodExists($school_year, $semester)) {
            $error_message = "This academic period already exists.";
        } else {
            if ($academicPeriod->addPeriod($school_year, $semester, $start_date, $end_date)) {
                $success_message = "Academic period added successfully!";
            } else {
                $error_message = "Error adding academic period.";
            }
        }
    }

    // Handle setting current semester
    if (isset($_POST['set_current']) && isset($_POST['school_year']) && isset($_POST['semester'])) {
        $school_year = clean_input($_POST['school_year']);
        $semester = clean_input($_POST['semester']);
        
        error_log("Form submitted - School Year: $school_year, Semester: $semester");
        
        if ($school_year && $semester) {
            $result = $academicPeriod->setCurrentPeriod($school_year, $semester);
            
            error_log("SetCurrentPeriod result: " . ($result ? "true" : "false"));
            
            if ($result) {
                $success_message = "Current academic period updated successfully!";
            } else {
                $error_message = "Error updating current academic period.";
            }
        } else {
            $error_message = "Invalid school year or semester.";
        }
    }

    $searchQuery = isset($_POST['searchQuery']) ? clean_input($_POST['searchQuery']) : '';
    $periods = $academicPeriod->getAllPeriods($searchQuery);
    ?>

    <section class="home-section">
        <div class="home-content">
            <div class="content-wrapper">
                <h3 class="page-title" style="font-weight: 700; font-size: 24px; color: #333; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #1a7431;">Semesters</h3>
                
                <div class="search-filter">
                    <form method="POST">
                        <input 
                            type="text" 
                            name="searchQuery" 
                            id="searchBar" 
                            placeholder="Search by school year or semester..." 
                            class="search-bar"
                            value="<?php echo $searchQuery; ?>"
                        >
                        <button type="submit" class="search-button">Search</button>
                    </form>
                </div>

                <!-- Display messages -->
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php endif; ?>
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>

                <!-- Add Academic Period Form -->
                <div class="form-section">
                    <h4>Add New Academic Period</h4>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="semester">Semester:</label>
                            <select name="semester" required>
                                <option value="1st">First</option>
                                <option value="2nd">Second</option>
                                <option value="Summer">Summer</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="school_year">School Year:</label>
                            <input type="text" name="school_year" required placeholder="e.g., 2023-2024" pattern="\d{4}-\d{4}">
                        </div>

                        <div class="form-group">
                            <label for="start_date">Start Date:</label>
                            <input type="date" name="start_date" required>
                        </div>

                        <div class="form-group">
                            <label for="end_date">End Date:</label>
                            <input type="date" name="end_date" required>
                        </div>
                        
                        <button type="submit" name="add_semester" class="btn btn-success">Add Academic Period</button>
                    </form>
                </div>

                <!-- Display Academic Periods -->
                <div class="list-section">
                    <table class="admin-logs-table">
                        <thead>
                            <tr>
                                <th>School Year</th>
                                <th>Semester</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($periods): ?>
                                <?php foreach ($periods as $row): ?>
                                    <tr>
                                        <td><?php echo $row['school_year']; ?></td>
                                        <td><?php echo $row['semester']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['start_date'])); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row['end_date'])); ?></td>
                                        <td>
                                            <?php if ($row['is_current']): ?>
                                                <span class="status-badge status-approved">Current</span>
                                            <?php else: ?>
                                                <span class="status-badge">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="actions">
                                            <?php if (isset($row['is_current']) && !$row['is_current']): ?>
                                                <form method="POST" style="display: inline;">
                                                    <input type="hidden" name="school_year" 
                                                        value="<?php echo isset($row['school_year']) ? htmlspecialchars($row['school_year']) : ''; ?>">
                                                    <input type="hidden" name="semester" 
                                                        value="<?php echo isset($row['semester']) ? htmlspecialchars($row['semester']) : ''; ?>">
                                                    <!-- Add debug output -->
                                                    <?php error_log("Form values - School Year: {$row['school_year']}, Semester: {$row['semester']}"); ?>
                                                    <button type="submit" name="set_current" class="btn btn-custom-green">
                                                        <i class="fas fa-check"></i> Set as Current
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No academic periods found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>