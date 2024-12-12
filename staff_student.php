<?php 
session_start();
require_once 'classes/Organization.php';
require_once 'studentfees.class.php';

// Test database connection
try {
    $db = new Database();
    $conn = $db->connect();
    error_log("Database connection successful");
} catch (Exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
}

// Initialize classes
$organizationObj = new Organization();
$studentFeeObj = new StudentFee();

// Get selected organization from URL parameter
$selectedOrgId = isset($_GET['org']) ? $_GET['org'] : null;

// Get organizations for dropdown
$organizations = $organizationObj->getAllOrganizations();

// Get student fees data filtered by organization if selected
$studentFees = $studentFeeObj->getStudentFeesWithDetails($selectedOrgId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List - PayThon</title>
    <link rel="stylesheet" href="css/staffbar.css">
    <link rel="stylesheet" href="css/staff_table.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <?php include 'staffbar.php'; ?>

    <!-- Remove any extra home-section wrappers -->
    <div class="content-wrapper">
        <div class="table-container">
            <div class="table-header">
             
                <div class="filter-section">
                    <!-- Filter Group -->
                    <div class="filter-group">
                        <select id="statusFilter" class="filter-select">
                            <option value="all">All Status</option>
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                        <select id="courseFilter" class="filter-select">
                            <option value="all">All Courses</option>
                            <option value="BSCS">BSCS</option>
                            <option value="BSIT">BSIT</option>
                            <option value="BSIS">BSIS</option>
                        </select>
                    </div>
                    
                    <!-- Search Filter -->
                    <div class="search-filter">
                        <i class='bx bx-search'></i>
                        <input type="text" id="searchBar" class="search-bar" 
                            placeholder="Search student name or ID...">
                        <button class="search-btn">
                            <i class='bx bx-search'></i>
                            Search
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="table-wrapper">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th>Section</th>
                            <th>Fee Name</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($studentFees as $fee): 
                            $status = $studentFeeObj->getStudentFeeStatus($fee['studentID'], $fee['FeeID']);
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fee['studentID']); ?></td>
                                <td><?php echo htmlspecialchars($fee['first_name'] . ' ' . $fee['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($fee['Course']); ?></td>
                                <td><?php echo htmlspecialchars($fee['Year']); ?></td>
                                <td><?php echo htmlspecialchars($fee['Section']); ?></td>
                                <td><?php echo htmlspecialchars($fee['FeeName']); ?></td>
                                <td>₱<?php echo number_format($fee['Amount'], 2); ?></td>
                                <td><?php echo htmlspecialchars($status); ?></td>
                                <td>
                                    <?php if ($status !== 'Paid'): ?>
                                        <label class="checkbox-container">
                                            <input type="checkbox" onchange="updateStatus(this, '<?php echo $fee['studentID']; ?>')">
                                            <span class="checkmark"></span>
                                        </label>
                                    <?php else: ?>
                                        <span class="paid-status">Paid</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function() {
            sidebar.classList.toggle("active");
        }
           // Status update functionality
           function updateStatus(checkbox, studentId) {
            if(checkbox.checked) {
                const row = checkbox.closest('tr');
                row.querySelector('td:nth-child(8)').textContent = 'Paid';
                checkbox.parentElement.innerHTML = '<span class="paid-status">Paid</span>';
                alert(`Payment status updated for Student ID: ${studentId}`);
            }
        }

        document.getElementById('organizationSelect').addEventListener('change', function() {
            const orgId = this.value;
            if (orgId) {
                window.location.href = 'staff_student.php?org=' + orgId;
            } else {
                window.location.href = 'staff_student.php';
            }
        });
    </script>
</body>
</html>

