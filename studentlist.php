<?php require_once "account.class.php"; ?>

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
        <i class="bx bx-menu sidebarBtn"></i>
        <span class="dashboard">Dashboard</span>
      </div>
      <div class="search-box">
        <input type="text" placeholder="Search...">
        <i class="bx bx-search"></i>
      </div>
      <div class="profile-details">
        <img src="assets/images/profile.png" alt="">
        <span class="admin_name">NAME</span>
        <i class="bx bx-chevron-down"></i>
      </div>
    </nav>
    <!-- Student Table Section -->
    <section class="main">
      <div class="main-box">
        <h2>Student Management</h2>

        <div class="search-filter">
            <input type="text" id="searchBar" placeholder="Search..." class="search-bar">
            
            <select id="categoryDropdown" class="category-dropdown">
              <option value="all">All</option>

            </select>
          </div>

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
              <th rowspan="2">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr>
            <?php 
            $account = new Account;
            $accInfo = $account->viewAccounts();
?> <pre>
            <?php echo $accInfo[0]["StudentID"];?>
   </pre>
<?php



            if (empty($accInfo)) {
        ?>
        <tr>
            <td colspan="6">
                <p class="search">No Student Information </p>
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
            <td><?= htmlspecialchars($arr['first_name']), " ", htmlspecialchars($arr['MI']), " ",htmlspecialchars($arr['last_name'])  ?></td>
            <td><?= htmlspecialchars($arr['WmsuEmail']) ?></td>
            <td><?= htmlspecialchars($arr['Course']) ?></td>
            <td><?= htmlspecialchars($arr['Year']) ?></td>
            <td><?= htmlspecialchars($arr['Section']) ?></td>
            <td>
                  <button id="openModalButton" class="btn btn-danger delete-org-btn" data-id="<?php echo htmlspecialchars($arr['StudentID']); ?>">
                                    <i class="fas fa-trash"></i> View Status
                  </button>
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
            </tr>
          </tbody>
        </table>
      </div>
    </section>
  </section>

  <!-- The Modal -->
  <div id="dataModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Data List</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Table to display data -->
                        <table class="table" id="dataTable">
                        <thead>
                            <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <!-- Add more columns as needed -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be inserted here via AJAX -->
                        </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
                </div>
  
  <script src="student.js"></script>
  <script src="script.js"></script>
</body>
</html>
