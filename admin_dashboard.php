<?php
session_start();
require_once 'classes/Organization.php';
// Add other required classes/connections
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - PayThon</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <section class="home-section">
        <div class="home-content">
            <!-- Bento Grid Stats -->
            <div class="bento-grid">
                <div class="bento-card students">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="fas fa-user-graduate"></i>
                        </div>
                        <div class="card-info">
                            <h3>Total Students</h3>
                            <p class="number">2,150</p>
                            <p class="trend positive">
                                <i class="fas fa-arrow-up"></i> 3.5% from last month
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bento-card organizations">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-info">
                            <h3>Organizations</h3>
                            <p class="number">24</p>
                            <p class="trend positive">
                                <i class="fas fa-arrow-up"></i> 2 new this month
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bento-card pending">
                    <div class="card-content">
                        <div class="card-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="card-info">
                            <h3>Pending Requests</h3>
                            <p class="number">18</p>
                            <p class="trend neutral">
                                <i class="fas fa-minus"></i> No change
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="recent-activity">
                <div class="activity-header">
                    <h2>Recent Activity</h2>
                    <a href="activity_logs.php" class="view-more">
                        View All
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="activity-list">
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="activity-details">
                            <p class="activity-text">
                                <span class="staff-name">John Doe</span> approved payment request from <span class="highlight">CS Organization</span>
                            </p>
                            <p class="activity-time">2 hours ago</p>
                        </div>
                    </div>

                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="activity-details">
                            <p class="activity-text">
                                <span class="staff-name">Jane Smith</span> added new member to <span class="highlight">IEEE</span>
                            </p>
                            <p class="activity-time">5 hours ago</p>
                        </div>
                    </div>

                    <!-- Add more activity items as needed -->
                </div>
            </div>
        </div>
    </section>
</body>
</html> 