<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Dashboard - Student List</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="staff_student.css">
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <?php include 'sidebar.php'; ?>

        <div class="content-wrapper">
            <?php include 'navbar.php'; ?>

            <div class="student-list-container">
                <div class="filter-section">
                    <div class="filter-group">
                        <select class="filter-dropdown" id="courseFilter">
                            <option value="">All Courses</option>
                            <option value="BSCS">BSCS</option>
                            <option value="BSIT">BSIT</option>
                            <option value="BSIS">BSIS</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select class="filter-dropdown" id="yearFilter">
                            <option value="">All Years</option>
                            <option value="1st Year">1st Year</option>
                            <option value="2nd Year">2nd Year</option>
                            <option value="3rd Year">3rd Year</option>
                            <option value="4th Year">4th Year</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <select class="filter-dropdown" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="Paid">Paid</option>
                            <option value="Unpaid">Unpaid</option>
                        </select>
                    </div>
                </div>

                <table class="student-table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Year</th>
                            <th>Section</th>
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
                            <td>Paid</td>
                            <td><span class="paid-status">Paid</span></td>
                        </tr>
                        <tr>
                            <td>2021-00003</td>
                            <td>John Smith</td>
                            <td>BSIT</td>
                            <td>1st Year</td>
                            <td>A</td>
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
            </div>
        </div>
    </div>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".bx-menu");
        
        sidebarBtn.addEventListener("click", () => {
            sidebar.classList.toggle("active");
            if(sidebar.classList.contains("active")){
                document.querySelector(".content-wrapper").style.marginLeft = "60px";
                document.querySelector("nav").style.width = "calc(100% - 60px)";
            } else {
                document.querySelector(".content-wrapper").style.marginLeft = "240px";
                document.querySelector("nav").style.width = "calc(100% - 240px)";
            }
        });

        // Status update functionality
        function updateStatus(checkbox, studentId) {
            if(checkbox.checked) {
                const row = checkbox.closest('tr');
                row.querySelector('td:nth-child(6)').textContent = 'Paid';
                checkbox.parentElement.innerHTML = '<span class="paid-status">Paid</span>';
                alert(`Payment status updated for Student ID: ${studentId}`);
            }
        }
    </script>
</body>
</html>

