<?php
session_start();
require_once 'classes/Organization.php';


// Check if user is logged in


value: try {
    // Create database connection
    $host = 'localhost';
    $dbname = 'pms1';
    $username = 'root';
    $password = '';
    
    $conn = new mysqli($host, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Get user details
    $stmt = $conn->prepare("SELECT first_name, last_name FROM account WHERE StudentID = ?");
    $stmt->bind_param("i", $_SESSION['StudentID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Fetch organizations from database
    $sql = "SELECT OrganizationID as org_id, OrgName as name FROM organizations";
    $result = $conn->query($sql);
    $organizations = [];

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $organizations[] = [
                'id' => $row['org_id'],
                'org_id' => $row['org_id'],
                'name' => $row['name']
            ];
        }
    }

} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
    die("An error occurred. Please try again later.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orgName = $_POST['org_name'];
    $orgID = $_POST['org_id'];
    $_SESSION['orgName'] = $orgName;


} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organizations - PayThon</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="organizations.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="logo">
                <img src="assets/images/logo.png" alt="logo">
                <span class="logo_name">PayThon</span>
            </div>
            <ul class="sidebar_list">
                <li>
                    <a href="admin.php">
                        <i class='bx bx-grid-alt'></i>
                        <span class="list_name">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="studentlist.php">
                        <i class='bx bx-user-pin'></i>
                        <span class="list_name">Student List</span>
                    </a>
                </li>
                <li>
                    <a href="admin_organizations.php" class="active">
                        <i class='bx bx-group'></i>
                        <span class="list_name">Organization</span>
                    </a>
                </li>
                <li>
                    <a href="payment_status.php">
                        <i class='bx bx-money'></i>
                        <span class="list_name">Payment Status</span>
                    </a>
                </li>
                <li>
                    <a href="login_logs.php">
                        <i class='bx bx-history'></i>
                        <span class="list_name">Login Logs</span>
                    </a>
                </li>
                <li class="log_out">
                    <a href="login.php">
                        <i class='bx bx-log-out'></i>
                        <span class="list_name">Log out</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="topbar">
                <div class="sidebar-button">
                    <i class='bx bx-menu sidebarBtn'></i>
                    <span class="dashboard">Organizations</span>
                </div>
                <div class="profile-details">
                    <img src="assets/images/profile.png" alt="">
                    <span class="admin_name"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span>
                    <i class='bx bx-chevron-down'></i>
                </div>
            </div>

            <!-- Copy the content-wrapper and modals from admin_organizations.php -->
            <div class="content-wrapper">
                <div class="content-header">
                    <div class="header-actions">
                        <button id="add-org-btn" class="btn">
                            <i class="fas fa-plus"></i> Add Organization
                        </button>
                        <div class="search-container">
                            <input type="text" id="searchInput" placeholder="Search organizations...">
                            <i class="fas fa-search search-icon"></i>
                        </div>
                    </div>
                </div>

                <table id="org-table">
                    <thead>
                        <tr>
                            <th>Organization ID</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($organizations as $org): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($org['org_id']); ?></td>
                            <td><?php echo htmlspecialchars($org['name']); ?></td>
                            <td class="actions">
                                <button class="btn view-members-btn" data-id="<?php echo $org['id']; ?>">
                                    <i class="fas fa-users"></i> View Members
                                </button>
                                <button class="btn view-payments-btn" data-id="<?php echo $org['id']; ?>">
                                    <i class="fas fa-money-bill"></i> View Payments
                                </button>
                                <button class="btn add-member-btn" data-id="<?php echo $org['id']; ?>" data-name="<?php echo htmlspecialchars($org['name']); ?>">
                                    <i class="fas fa-user-plus"></i> Add Member
                                </button>
                                <button class="btn add-payment-btn" data-id="<?php echo $org['id']; ?>" data-name="<?php echo htmlspecialchars($org['name']); ?>">
                                    <i class="fas fa-plus-circle"></i> Add Payment
                                </button>
                                <button class="btn btn-danger delete-org-btn" data-id="<?php echo htmlspecialchars($org['org_id']); ?>">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Modals -->
            <div id="org-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3 id="modal-title">Add Organization</h3>
                    <form action="add_organization_handler.php"  method="post">
                        <div class="form-group">
                            <label for="org_name">Organization Name</label>
                            <input type="text" id="org_name" name="org_name" required>
                        </div>
                        <div class="form-group">
                            <label for="org_id">Organization ID</label>
                            <input type="text" id="org_id" name="org_id" required>
                        </div>
                        <button type="submit" name="add_org" class="btn" id="form-group" >
                            <i class="fas fa-plus"></i> Add Organization
                        </button>
                    </form>
                </div>
            </div>

            <div id="members-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>Organization Members</h3>
                    <div id="members-list"></div>
                </div>
            </div>

            <div id="payments-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>Organization Payments</h3>
                    <div id="payments-list"></div>
                </div>
            </div>

            <!-- Add Member Modal -->
            <div id="add-member-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>Add Member to <span id="org-name-display"></span></h3>
                    <form id="add-member-form" method="post">
                        <input type="hidden" name="org_id" id="member-org-id">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="student_id">Student ID</label>
                                <input type="text" id="student_id" name="student_id" required>
                            </div>
                            <div class="form-group">
                                <label for="student_name">Student Name</label>
                                <input type="text" id="student_name" name="student_name" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="course">Course</label>
                                <select id="course" name="course" required>
                                    <option value="">Select Course</option>
                                    <option value="CS">Computer Science</option>
                                    <option value="IT">Information Technology</option>
                                    <option value="ACT-APPDEV">ACT - Application Development</option>
                                    <option value="ACT-NETWORK">ACT - Networking</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="year">Year</label>
                                <select id="year" name="year" required>
                                    <option value="">Select Year</option>
                                    <option value="1">1st Year</option>
                                    <option value="2">2nd Year</option>
                                    <option value="3">3rd Year</option>
                                    <option value="4">4th Year</option>
                                    <option value="5">Over 4 Years</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="position">Position</label>
                            <input type="text" id="position" name="position" required placeholder="Enter position">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="add_member" class="btn">
                                <i class="fas fa-plus"></i> Add Member
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Add Payment Modal -->
            <div id="add-payment-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>Add Payment for <span id="payment-org-name"></span></h3>
                    <form id="add-payment-form" method="post">
                        <input type="hidden" name="org_id" id="payment-org-id">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="fee_id">Fee ID</label>
                                <input type="text" id="fee_id" name="fee_id" required>
                            </div>
                            <div class="form-group">
                                <label for="fee_name">Fee Name</label>
                                <input type="text" id="fee_name" name="fee_name" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="amount">Amount (₱)</label>
                                <input type="number" id="amount" name="amount" step="0.01" min="0" required>
                            </div>
                            <div class="form-group">
                                <label for="due_date">Due Date</label>
                                <input type="date" id="due_date" name="due_date" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="3"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="add_payment" class="btn">
                                <i class="fas fa-plus"></i> Add Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            

                

        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="organizations.js"></script>
    <script>
        // Add the sidebar toggle functionality
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function() {
            sidebar.classList.toggle("active");
        }
    </script>
</body>
</html>