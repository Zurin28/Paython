<?php 
session_start();
require_once "account.class.php";
require_once 'fee.class.php';
require_once 'classes/Organization.php'
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="UTF-8">
  <title>PAYTHON</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="addstyles.css">
  <link href="https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
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
            <a href="admin.php">
                <i class='bx bx-grid-alt'></i>
                <span class="list_name">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="studentlist.php" class="active">
                <i class='bx bx-user-pin'></i>
                <span class="list_name">Student List</span>
            </a>
        </li>
        <li>
            <a href="admin_organizations.php">
                <i class='bx bx-group'></i>
                <span class="list_name">Organization</span>
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
            <i class="bx bx-menu sidebarBtn"></i>
            <span class="dashboard">Dashboard</span>
        </div>
        <div class="search-box">
            <input type="text" placeholder="Search...">
            <i class="bx bx-search"></i>
        </div>
        <div class="profile-details">
            <img src="assets/images/profile.png" alt="">
            <span class="admin_name"><?php echo $_SESSION['Name'] ?></span>
            <i class="bx bx-chevron-down"></i>
        </div>
    </nav>
    <!-- Student Table Section -->
    <section class="main">
        <div class="main-box">
            <h2>Student Management</h2>

            <!-- Search Form -->
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

            <!-- Student Table -->
            <table id="studentTable">
                <thead>
                    <tr>
                        <th>NO.</th>
                        <th>StudentID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Section</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $account = new Account;

                    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['searchQuery'])) {
                        $searchQuery = trim($_POST['searchQuery']);
                        $accInfo = $account->searchAccounts($searchQuery);
                    } else {
                        $accInfo = $account->viewAccounts();
                    }

                    if (empty($accInfo)) {
                    ?>
                    <tr>
                        <td colspan="8">
                            <p class="search">No Student Information</p>
                        </td>
                    </tr>
                    <?php 
                    } else {
                        $i = 1;
                        foreach ($accInfo as $arr) {
                    ?>
                    <tr>
                        <td><?= $i ?></td>
                        <td><?= htmlspecialchars($arr['StudentID']) ?></td>
                        <td><?= htmlspecialchars($arr['first_name']) . " " . htmlspecialchars($arr['MI']) . " " . htmlspecialchars($arr['last_name']) ?></td>
                        <td><?= htmlspecialchars($arr['WmsuEmail']) ?></td>
                        <td><?= htmlspecialchars($arr['Course']) ?></td>
                        <td><?= htmlspecialchars($arr['Year']) ?></td>
                        <td><?= htmlspecialchars($arr['Section']) ?></td>
                        <td><button class="view-status-btn" data-student-id="<?=$arr['StudentID']?>">View Status</button>
                            <form method="POST" action="delete_account.php">
                                <input type="hidden" name="studentId" value="<?= htmlspecialchars($arr['StudentID']) ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php 
                            $i++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>
</section>



<!-- Fee Status Modal -->
<div id="show-fees-modal" style="display: none;">
    <table id="feesTable">
        <thead>
            <tr>
                <th>NO.</th>
                <th>Organization</th>
                <th>Fee Name</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <!-- Fee rows will be dynamically added here -->
        </tbody>
    </table>
    <button id="closeFeesModalButton">Close</button>
</div>
<style>
    #show-fees-modal {
    display: none; /* Ensures modal is hidden by default */
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 1050; /* Ensure it is above other elements */
    background: white;
    border: 1px solid #ccc;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

</style>

<script src="student.js"></script>
<script src="script.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</body>
</html>