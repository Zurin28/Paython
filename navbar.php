<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/navbar.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Fixed Header Section -->
        <div class="sidebar-header">
            <div class="logo">
                <img src="img/logoccs.png" alt="logo">
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
                <a href="admin_dashboard.php" class="<?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>">
                    <i class='bx bx-grid-alt'></i>
                    <span class="list_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="studentlist.php" class="<?php echo ($current_page == 'studentlist.php') ? 'active' : ''; ?>">
                    <i class='bx bx-user-pin'></i>
                    <span class="list_name">Student List</span>
                </a>
            </li>
            <li>
                <a href="admin_organizations.php" class="<?php echo ($current_page == 'admin_organizations.php') ? 'active' : ''; ?>">
                    <i class='bx bx-group'></i>
                    <span class="list_name">Organization</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bx-user'></i>
                    <span class="list_name">login_logs</span>
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

    <!-- Top Navigation Bar -->
    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="dashboard">Dashboard</span>
            </div>
        </nav>
    </section>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function() {
            sidebar.classList.toggle("active");
        }
    </script>
</body>
</html> 