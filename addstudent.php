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
          <i class="bx bx-grid-alt"></i>
          <span class="list_name">Dashboard</span>
        </a>
      </li>
      <li>
        <a href="#" class="active">
            <i class='bx bx-face'></i>
          <span class="list_name">Student</span>
        </a>
      </li>
      <li>
        <a href="organization.html">
            <i class='bx bx-group'></i>
          <span class="list_name">Organization</span>
        </a>
      </li>
      <li>
        <a href="#">
          <i class='bx bxl-paypal'></i>
          <span class="list_name">Payment</span>
        </a>
      </li>
      <li class="log_out">
        <a href="login.php">
          <i class="bx bx-log-out"></i>
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
              <th>Action</th>
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
                  <a href="delete">Edit</a><a href="edit"></a>
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

  <!-- Modal for Adding/Editing Students -->
  <!-- <div id="studentModal" class="modal">
    <div class="modal-content">
      <h3 id="modalTitle">Add Student</h3>
      
      <label for="studentID">Student ID:</label>
      <input type="text" id="studentID" placeholder="Enter Student ID">
      
      <label for="studentName">Name:</label>
      <input type="text" id="studentName" placeholder="Enter Name">
      
      <label for="studentEmail">Email:</label>
      <input type="email" id="studentEmail" placeholder="Enter Email">
      
      <label for="studentCourse">Course:</label>
      <select id="studentCourse">
        <option value="CS">CS</option>
        <option value="IT">IT</option>
        <option value="ACT">ACT</option>
      </select>
      
      
      <label for="studentYear">Year:</label>
      <input type="number" id="studentYear" placeholder="Enter Year">
      
      <label for="studentSection">Section:</label>
      <input type="text" id="studentSection" placeholder="Enter Section">
      
      <button id="saveStudentBtn">Save</button>
      <button id="closeModalBtn">Cancel</button>
    </div>
  </div> -->
  
  <script src="student.js"></script>
  <script src="script.js"></script>
</body>
</html>
