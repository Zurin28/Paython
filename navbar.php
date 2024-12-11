<?php
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Define page titles
$page_titles = [
    'admin_dashboard.php' => 'Dashboard',
    'studentlist.php' => 'Student List',
    'admin_organizations.php' => 'Organizations',
    'admin_login_logs.php' => 'LogIn Logs',
    // Add more pages as needed
];

// Get the current page title
$current_title = isset($page_titles[$current_page]) ? $page_titles[$current_page] : 'Dashboard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/staffbar.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Fixed Header Section -->
        <div class="sidebar-header">
            <div class="logo">
                <img src="img/ccs.png" alt="logo">
                <span class="logo_name">PayThon</span>
            </div>

            <div class="profile-section">
        <img src="img/prof.jpg" alt="profile">
        <div class="profile-info">
            <span class="admin_name">NAME</span>
            <span class="admin_role">Administrator</span>
        </div>
    </div>
        </div>

        <!-- Scrollable Menu Section -->
        <ul class="sidebar_list">
            <li>
                <a href="admin_dashboard.php" data-name="Dashboard" class="menu-item">
                    <i class='bx bx-grid-alt'></i>
                    <span class="list_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="studentlist.php" data-name="Student List" class="menu-item">
                    <i class='bx bx-user-pin'></i>
                    <span class="list_name">Student List</span>
                </a>
            </li>
            <li>
                <a href="admin_organizations.php" data-name="Organizations" class="menu-item">
                    <i class='bx bx-group'></i>
                    <span class="list_name">Organizations</span>
                </a>
            </li>
            <li>
                <a href="admin_login_logs.php" data-name="LogIn Logs" class="menu-item">
                    <i class='bx bx-group'></i>
                    <span class="list_name">LogIn Logs</span>
                </a>
            </li>
        </ul>

        <!-- Fixed Logout Button -->
        <li class="log_out">
            <a href="login.php">
                <i class='bx bx-log-out'></i>
                <span class="list_name">Log out</span>
            </a>
        </li>
    </div>

    <!-- Modify this section -->
    <nav class="home-section-nav">
    <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard" id="topbar-title"><?php echo $current_title; ?></span>
    </div>
</nav>

    <script>
        document.querySelectorAll('.menu-item').forEach(item => {
            item.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent default link behavior
                const name = this.getAttribute('data-name');
                document.getElementById('topbar-title').textContent = name;
                window.location.href = this.getAttribute('href'); // Navigate to the link
            });
        });
    </script>
</body>
</html> 