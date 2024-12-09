<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - PayThon</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/staffbar.css">
    <link rel="stylesheet" href="css/staff_dashboard.css">
</head>
<body>
    <?php include 'staffbar.php'; ?>

    <div class="content-wrapper">
        <!-- Bento Grid Layout -->
        <div class="bento-grid">
            <!-- Statistics Cards -->
            <div class="bento-card payments">
                <i class='bx bx-money-withdraw'></i>
                <div class="card-info">
                    <h3>Total Payments</h3>
                    <p class="number">152</p>
                    <span class="trend positive">+12% from last month</span>
                </div>
            </div>

            <div class="bento-card pending">
                <i class='bx bx-time'></i>
                <div class="card-info">
                    <h3>Pending Requests</h3>
                    <p class="number">28</p>
                    <span class="trend negative">+5 new requests</span>
                </div>
            </div>

            <div class="bento-card total">
                <i class='bx bx-wallet'></i>
                <div class="card-info">
                    <h3>Total Collection</h3>
                    <p class="number">₱45,250</p>
                    <span class="trend positive">+₱12,500 this month</span>
                </div>
            </div>

            <!-- Recent Payments Table -->
            <div class="bento-card recent-payments">
                <div class="card-header">
                    <h3>Recent Payments</h3>
                    <a href="receive_receipt.php" class="view-more-btn">
                        <span>View More</span>
                        <i class='bx bx-right-arrow-alt'></i>
                    </a>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Fee Name</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Juan Dela Cruz</td>
                                <td>CSC Membership</td>
                                <td>₱500</td>
                                <td>Mar 15, 2024</td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>Maria Santos</td>
                                <td>Publication Fee</td>
                                <td>₱750</td>
                                <td>Mar 14, 2024</td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>John Smith</td>
                                <td>Organization Fee</td>
                                <td>₱300</td>
                                <td>Mar 13, 2024</td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Fees -->
            <div class="bento-card recent-fees">
                <div class="card-header">
                    <h3>Recent Fees Created</h3>
                    <a href="staff_organizations.php" class="view-more-btn">
                        <span>View More</span>
                        <i class='bx bx-right-arrow-alt'></i>
                    </a>
                </div>
                <div class="fees-list">
                    <div class="fee-item">
                        <div class="fee-info">
                            <h4>CSC Membership Fee</h4>
                            <p>Created on March 15, 2024</p>
                            <span class="staff-name">Created by: John Doe</span>
                        </div>
                        <span class="fee-amount">₱500</span>
                    </div>
                    <div class="fee-item">
                        <div class="fee-info">
                            <h4>Publication Fee</h4>
                            <p>Created on March 14, 2024</p>
                            <span class="staff-name">Created by: Jane Smith</span>
                        </div>
                        <span class="fee-amount">₱750</span>
                    </div>
                    <div class="fee-item">
                        <div class="fee-info">
                            <h4>Organization Fee</h4>
                            <p>Created on March 13, 2024</p>
                            <span class="staff-name">Created by: Mike Johnson</span>
                        </div>
                        <span class="fee-amount">₱300</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 