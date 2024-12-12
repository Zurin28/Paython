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

    <section class="home-section">
        <div class="home-content">
            <div class="content-wrapper">
                <div class="search-filter">
                    <form method="POST">
                        <input 
                            type="text" 
                            name="searchQuery" 
                            id="searchBar" 
                            placeholder="Search..." 
                            class="search-bar"
                            value="<?= isset($_POST['searchQuery']) ? htmlspecialchars($_POST['searchQuery']) : '' ?>"
                        >
                        <button type="submit" class="search-button">Search</button>
                    </form>
                </div>

                <!-- Tab Navigation -->
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#login_history">
                            Staff Login History <span class="tab-count">2</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#fee_requests">
                            Fee Requests <span class="tab-count">2</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#staff_activity">
                            Staff Activity <span class="tab-count">2</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- Staff Login History Tab -->
                    <div class="tab-pane fade show active" id="login_history">
                        <table class="admin-logs-table">
                            <thead>
                                <tr>
                                    <th>StudentID</th>
                                    <th>Staff Name</th>
                                    <th>Organization</th>
                                    <th>Login Date & Time</th>
                                    <th>Logout Date & Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2202301283</td>
                                    <td>Joel Josh Que</td>
                                    <td>CSC</td>
                                    <td>2024-01-11 08:30:00</td>
                                    <td>2024-01-11 17:15:00</td>
                                    <td class="actions">
                                        <button class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                <td>2202301283</td>
                                    <td>Joel Josh Que</td>
                                    <td>CSC</td>
                                    <td>2024-01-11 08:30:00</td>
                                    <td>2024-01-11 17:15:00</td>
                                    <td class="actions">
                                        <button class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Fee Requests Tab -->
                    <div class="tab-pane fade" id="fee_requests">
                        <table class="admin-logs-table">
                            <thead>
                                <tr>
                                    <th>Organization</th>
                                    <th>Fee ID</th>
                                    <th>Fee Name</th>
                                    <th>Amount</th>
                                    <th>Due Date</th>
                                    <th>Description</th>
                                    <th>Created By</th>
                                    <th>Submit Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Gender</td>
                                    <td>Gender123</td>
                                    <td>Gender Fee</td>
                                    <td>₱500.00</td>
                                    <td>2024-12-15</td>
                                    <td>Yearly membership fee</td>
                                    <td>Ethan Wayne</td>
                                    <td>2024-011-10</td>
                                    <td class="actions">
                                        <button class="btn btn-custom-green">
                                            <i class="fas fa-check"></i> Accept
                                        </button>
                                        <button class="btn btn-custom-red">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                <td>Gender</td>
                                    <td>Gender123</td>
                                    <td>Gender Fee</td>
                                    <td>₱500.00</td>
                                    <td>2024-12-15</td>
                                    <td>Yearly membership fee</td>
                                    <td>Ethan Wayne</td>
                                    <td>2024-011-10</td>
                                    <td class="actions">
                                        <button class="btn btn-custom-green">
                                            <i class="fas fa-check"></i> Accept
                                        </button>
                                        <button class="btn btn-custom-red">
                                            <i class="fas fa-times"></i> Reject
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Staff Activity Tab -->
                    <div class="tab-pane fade" id="staff_activity">
                        <table class="admin-logs-table">
                            <thead>
                                <tr>
                                    <th>Organization</th>
                                    <th>Fee ID</th>
                                    <th>Fee Name</th>
                                    <th>Student ID</th>
                                    <th>Student Name</th>
                                    <th>Staff Name</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                    <th>Action Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>CSC</td>
                                    <td>CSC123</td>
                                    <td>CSC Fee</td>
                                    <td>202301283</td>
                                    <td>Joel Josh Que</td>
                                    <td>Ethan Wayne</td>
                                    <td>
                                        <span class="status-badge status-approved">Accepted</span>
                                    </td>
                                    <td>-</td>
                                    <td>2024-01-10 14:30:00</td>
                                    <td class="actions">
                                        <button class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>CSC</td>
                                    <td>CSC123</td>
                                    <td>CSC Fee</td>
                                    <td>202301283</td>
                                    <td>Joel Josh Que</td>
                                    <td>Ethan Wayne</td>
                                    <td>
                                        <span class="status-badge status-rejected">Rejected</span>
                                    </td>
                                    <td>Amount exceeds maximum limit</td>
                                    <td>2024-01-10 15:45:00</td>
                                    <td class="actions">
                                        <button class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>