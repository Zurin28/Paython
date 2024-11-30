<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>PAYTHON</title>
  <link rel="stylesheet" href="style.css">
  <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<div class="sidebar">
            <div class="logo">
                <img src="assets/images/logo.png" alt="logo">
                <span class="logo_name">PayThon</span>
            </div>
            <ul class="sidebar_list">
                <li>
                    <a href="admin.php" class="active">
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
                    <a href="admin_organizations.php" >
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


  <section class="navbar">
    <nav>
      <div class="sidebar-button">
        <i class='bx bx-menu sidebarBtn'></i>
        <span class="dashboard">Dashboard</span>
      </div>
      <div class="search-box">
        <input type="text" placeholder="Search...">
        <i class='bx bx-search'></i>
      </div>
      <div class="profile-details">
        <img src="" alt="">
        <span class="admin_name">NAME</span>
        <i class='bx bx-chevron-down'></i>
      </div>
    </nav>

  </section>


  <script src="script.js"></script>
</body>
</html>
