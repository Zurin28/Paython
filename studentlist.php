<?php 
session_start();
require_once "account.class.php";
require_once 'Fee.class.php';
require_once 'classes/Organization.php';
require_once 'classes/academicperiod.class.php';

// Get current academic period
$academicPeriod = new AcademicPeriod();
$currentPeriod = $academicPeriod->getCurrentAcademicPeriod();

if (!$currentPeriod) {
    echo "<p style='color: red;'>No active academic period set. Please contact the administrator.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List - PayThon</title>
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/studentlist.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <section class="home-section">
        <div class="home-content">
            <!-- Student Table Section -->
            <div class="table-container">
                <div class="table-header">
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
                </div>

                <!-- Student Table -->
                <table id="studentTable" class="custom-table">
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
                            $accInfo = $account->searchAccounts($searchQuery, $currentPeriod['school_year'], $currentPeriod['semester']);
                        } else {
                            $accInfo = $account->viewAccounts($currentPeriod['school_year'], $currentPeriod['semester']);
                        }

                        if (empty($accInfo)) {
                        ?>
                        <tr>
                            <td colspan="8">
                                <p class="search">No Student Information for <?= htmlspecialchars($currentPeriod['school_year']) ?> - <?= htmlspecialchars($currentPeriod['semester']) ?> Semester</p>
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
                            <td class="table-actions">
                                <button class="table-btn btn-view view-status-btn" data-student-id="<?=$arr['StudentID']?>">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <form method="POST" action="delete_account.php" style="display: inline;">
                                    <input type="hidden" name="studentId" value="<?= htmlspecialchars($arr['StudentID']) ?>">
                                    <button type="submit" class="table-btn btn-delete">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
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
        </div>
    </section>

   <!-- Fee Status Modal -->
<div id="show-fees-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Student Fees Status</h3>
        <div id="loading-indicator" style="display: none;">Loading...</div>
        <table id="feesTable" class="custom-table">
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
    </div>
</div>

    <script src="js/modal.js"></script>
    <script src="js/student.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>