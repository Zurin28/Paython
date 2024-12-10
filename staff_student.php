<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student List - PayThon</title>
    <link rel="stylesheet" href="css/staffbar.css">
    <link rel="stylesheet" href="css/staff_table.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <?php include 'staffbar.php'; ?>

    <!-- Remove any extra home-section wrappers -->
    <div class="content-wrapper">
        <div class="table-container">
            <div class="table-header">
                <h2>Student Management</h2>
                <div class="search-filter">
                    <form method="POST">
                        <input type="text" name="searchQuery" id="searchBar" placeholder="Search..." class="search-bar">
                        <button type="submit" class="search-button">Search</button>
                    </form>
                </div>
            </div>
            
            <div class="table-wrapper">
                <table class="custom-table">
                    <!-- Your table content -->
                    <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Course</th>
                                <th>Year</th>
                                <th>Section</th>
                                <th>Fee Name</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2021-00001</td>
                                <td>Juan Dela Cruz</td>
                                <td>BSCS</td>
                                <td>3rd Year</td>
                                <td>A</td>
                                <td>Membership Fee</td>
                                <td>₱500.00</td>
                                <td>Unpaid</td>
                                <td>
                                    <label class="checkbox-container">
                                        <input type="checkbox" onchange="updateStatus(this, '2021-00001')">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                            </tr>
                            <tr>
                                <td>2021-00002</td>
                                <td>Maria Santos</td>
                                <td>BSCS</td>
                                <td>2nd Year</td>
                                <td>B</td>
                                <td>Tuition Fee</td>
                                <td>₱1000.00</td>
                                <td>Paid</td>
                                <td><span class="paid-status">Paid</span></td>
                            </tr>
                            <tr>
                                <td>2021-00003</td>
                                <td>John Smith</td>
                                <td>BSIT</td>
                                <td>1st Year</td>
                                <td>A</td>
                                <td>Library Fee</td>
                                <td>₱200.00</td>
                                <td>Unpaid</td>
                                <td>
                                    <label class="checkbox-container">
                                        <input type="checkbox" onchange="updateStatus(this, '2021-00003')">
                                        <span class="checkmark"></span>
                                    </label>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </table>
            </div>
        </div>
    </div>

    <script>
          let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function() {
            sidebar.classList.toggle("active");
        }
           // Status update functionality
           function updateStatus(checkbox, studentId) {
            if(checkbox.checked) {
                const row = checkbox.closest('tr');
                row.querySelector('td:nth-child(8)').textContent = 'Paid';
                checkbox.parentElement.innerHTML = '<span class="paid-status">Paid</span>';
                alert(`Payment status updated for Student ID: ${studentId}`);
            }
        }
    </script>
</body>
</html>

