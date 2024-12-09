<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <div class="profile-dropdown">
                    <div class="profile-info-trigger" style="background: none; padding: 0;">
                        <img src="img/prof.jpg" alt="profile">
                        <div class="profile-info">
                            <span class="admin_name">NAME</span>
                            <span class="admin_role">Staff</span>
                        </div>
                        <i class='bx bx-chevron-down'></i>
                    </div>
                    
                    <div class="profile-dropdown-content">
                        <div class="profile-header">
                            <img src="img/prof.jpg" alt="profile">
                            <div>
                                <span class="full-name">John Doe</span>
                                <span class="email">johndoe@example.com</span>
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="staff_student.php">
                            <i class='bx bx-user-circle'></i>
                            Student Account
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="login.php" class="logout-btn">
                            <i class='bx bx-log-out'></i>
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Scrollable Menu Section -->
        <ul class="sidebar_list">
            <li>
                <a href="staff_dashboard.php" class="<?php echo ($current_page == 'staff_dashboard.php') ? 'active' : ''; ?>">
                    <i class='bx bx-grid-alt'></i>
                    <span class="list_name">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="staff_student.php" class="<?php echo ($current_page == 'staff_studentlist.php') ? 'active' : ''; ?>">
                    <i class='bx bx-user-pin'></i>
                    <span class="list_name">Student List</span>
                </a>
            </li>
            <li>
                <a href="receive_receipt.php" class="<?php echo ($current_page == 'staff_organizations.php') ? 'active' : ''; ?>">
                    <i class='bx bx-group'></i>
                    <span class="list_name">Reciept</span>
                </a>
            </li>
            <li>
                <a href="staff_organizations.php" class="<?php echo ($current_page == 'staff_organizations.php') ? 'active' : ''; ?>">
                    <i class='bx bx-group'></i>
                    <span class="list_name">Fees</span>
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
            <span class="dashboard">Dashboard</span>
        </div>
        
        <div class="org-dropdown">
            <button class="dropbtn">
                Select Organization 
                <i class='bx bx-chevron-down'></i>
            </button>
            <div class="dropdown-content">
                <a href="#">Computer Society</a>
                <a href="#">Junior Philippine Computer Society</a>
                <a href="#">Institute of Computer Engineers</a>
            </div>
        </div>
    </nav>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function() {
            sidebar.classList.toggle("active");
        }
    </script>
</body>
</html> 