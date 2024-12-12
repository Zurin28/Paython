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
    $organizationName = $_POST['org_name'];
    $orgID = $_POST['org_id'];
    


} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Organizations - PayThon</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/organizations.css">
    <link rel="stylesheet" href="css/table.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <!-- Main Content -->
    <section class="home-section">
        <div class="home-content">
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
                                <button class="btn view-members-btn" data-id="<?php echo htmlspecialchars($org['org_id']); ?>" data-name="<?php echo htmlspecialchars($org['name']); ?>">
                                    <i class="fas fa-users"></i> View Members
                                </button>
                                <button class="btn view-payments-btn" data-id="<?php echo htmlspecialchars($org['org_id']); ?>" data-name="<?php echo htmlspecialchars($org['name']); ?>">
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
                    <form id="add-org-form" method="post">
                        <div class="form-group">
                            <label for="org_name">Organization Name</label>
                            <input type="text" id="org_name" name="org_name" required>
                        </div>
                        <div class="form-group">
                            <label for="org_id">Organization ID</label>
                            <input type="text" id="org_id" name="org_id" required>
                        </div>
                        <button type="submit" class="btn">
                            <i class="fas fa-plus"></i> Add Organization
                        </button>
                    </form>
                </div>
            </div>
            <?php

?>


            <div id="payments-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>Organization Payments</h3>
                    <div id="payments-list"></div>
                </div>
            </div>

            <!-- Add Member Modal -->
            <div id="add-member-modal" class="modal">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3>Add Member to <span id="org-name-display"></span></h3>
                            <span class="close">&times;</span>
                        </div>
                        <div class="modal-body">
                            <form id="add-member-form" method="post">
                                <input type="hidden" name="org_id" id="member-org-id">
                                <div class="form-group">
                                    <label for="student_id">Student ID</label>
                                    <input type="text" id="student_id" name="student_id" required>
                                </div>
                                <div class="student-details">
                                    <div class="form-group">
                                        <label for="student_name">Student Name</label>
                                        <input type="text" id="student_name" name="student_name" readonly>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="course">Course</label>
                                            <input type="text" id="course" name="course" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="year">Year</label>
                                            <input type="text" id="year" name="year" readonly>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="section">Section</label>
                                            <input type="text" id="section" name="section" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="text" id="email" name="email" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="position">Position</label>
                                        <select id="position" name="position" required>
                                            <option value="">Select Position</option>
                                            <option value="President">President</option>
                                            <option value="Vice President">Vice President</option>
                                            <option value="Secretary">Secretary</option>
                                            <option value="Treasurer">Treasurer</option>
                                            <option value="Member">Member</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn" id="add-member-submit">
                                        <i class="fas fa-plus"></i> Add Member
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Payment Modal -->
            <div id="add-payment-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h3>Add Payment for <span id="payment-org-name"></span></h3>
                    <form id="add-payment-form" method="post">
                        <input type="hidden" name="add_payment" value="1">
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
    </section>

    <!-- View Members Modal -->
    <div id="view-members-modal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Members of <span id="org-name-header"></span></h3>
                    <span class="close">&times;</span>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="members-table">
                            <thead>
                                <tr>
                                    <th style="border: 1px solid #ddd;">#</th>
                                    <th style="border: 1px solid #ddd;">Student ID</th>
                                    <th style="border: 1px solid #ddd;">Name</th>
                                    <th style="border: 1px solid #ddd;">Email</th>
                                    <th style="border: 1px solid #ddd;">Position</th>
                                    <th style="border: 1px solid #ddd;">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="members-table-body">
                                <!-- Members will be loaded here dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- View Payments Modal -->
    <div id="view-payments-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Payments for <span id="org-name-payments"></span></h3>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th style="border: 1px solid #ddd;">#</th>
                            <th style="border: 1px solid #ddd;">Fee Name</th>
                            <th style="border: 1px solid #ddd;">Amount</th>
                            <th style="border: 1px solid #ddd;">Due Date</th>
                            <th style="border: 1px solid #ddd;">Description</th>
                            <th style="border: 1px solid #ddd;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="payments-table-body">
                        <!-- Payments will be loaded here -->
                    </tbody>
                </table>
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Modal element exists:', !!document.getElementById('view-payments-modal'));
        console.log('Table body exists:', !!document.getElementById('payments-table-body'));
        console.log('Org name span exists:', !!document.getElementById('org-name-payments'));
    });
    </script>
    <div class="modal" id="payments-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Organization Payments</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="payments-content">
                        <!-- Payments will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>