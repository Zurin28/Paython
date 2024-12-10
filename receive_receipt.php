<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipts</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/staffbar.css">
    <link rel="stylesheet" href="css/receive_receipt.css">
    
</head>
<body>
    <div class="container">
        <?php include 'staffbar.php'; ?>

        <div class="content-wrapper">
            <div class="table-container">
                <!-- Updated filter section -->
                <div class="filter-section">
                    <div class="search-group">
                        <div class="search-box">
                            <i class='bx bx-search'></i>
                            <input type="text" id="searchInput" placeholder="Search student name or ID...">
                        </div>
                    </div>
                    <div class="filter-group">
                        <button class="filter-date-btn" id="dateFilter">
                            <i class='bx bx-sort'></i>
                            Sort by Date
                        </button>
                    </div>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Fee Name</th>
                            <th>Payment Type</th>
                            <th>Date Paid</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>2021-00001</td>
                            <td>Juan Dela Cruz</td>
                            <td>CSC Palaro Fee</td>
                            <td>GCash</td>
                            <td>2024-03-15</td>
                            <td class='action-buttons'>
                                <button onclick="viewReceipt('sample_receipt.jpg')" class="btn view">View</button>
                                <button onclick="acceptPayment('2021-00001')" class="btn accept">Accept</button>
                                <button onclick="rejectPayment('2021-00001')" class="btn reject">Reject</button>
                            </td>
                        </tr>
                        <tr>
                            <td>2021-00002</td>
                            <td>Maria Santos</td>
                            <td>Publication Fee</td>
                            <td>GCash</td>
                            <td>2024-03-14</td>
                            <td class='action-buttons'>
                                <button onclick="viewReceipt('img/pic.jpg')" class="btn view">View</button>
                                <button onclick="acceptPayment('2021-00002')" class="btn accept">Accept</button>
                                <button onclick="rejectPayment('2021-00002')" class="btn reject">Reject</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div id="receiptModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img id="receiptImage" src="" alt="Receipt">
        </div>
    </div>

    <script>
        // Sidebar toggle functionality
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

        // Modal functionality
        function viewReceipt(imageSrc) {
            var modal = document.getElementById("receiptModal");
            var img = document.getElementById("receiptImage");
            img.src = imageSrc;
            modal.style.display = "flex";
        }

        function acceptPayment(studentID) {
            alert("Payment accepted for Student ID: " + studentID);
        }

        function rejectPayment(studentID) {
            alert("Payment rejected for Student ID: " + studentID);
        }

        // Close modal when clicking X or outside
        var modal = document.getElementById("receiptModal");
        var span = document.getElementsByClassName("close")[0];
        
        span.onclick = function() {
            modal.style.display = "none";
        }
        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        // Add filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const courseFilter = document.getElementById('courseFilter');
            const yearFilter = document.getElementById('yearFilter');
            const dateFilter = document.getElementById('dateFilter');
            let isDateAscending = true;

            function filterTable() {
                const rows = Array.from(document.querySelectorAll('table tbody tr'));
                const course = courseFilter.value.toLowerCase();
                const year = yearFilter.value;

                rows.forEach(row => {
                    const courseMatch = !course || row.cells[0].textContent.toLowerCase().includes(course);
                    const yearMatch = !year || row.cells[0].textContent.includes(year);
                    row.style.display = courseMatch && yearMatch ? '' : 'none';
                });
            }

            function sortByDate() {
                const tbody = document.querySelector('table tbody');
                const rows = Array.from(tbody.querySelectorAll('tr'));

                rows.sort((a, b) => {
                    const dateA = new Date(a.cells[4].textContent);
                    const dateB = new Date(b.cells[4].textContent);
                    return isDateAscending ? dateA - dateB : dateB - dateA;
                });

                // Clear the table body
                tbody.innerHTML = '';
                
                // Add sorted rows back
                rows.forEach(row => tbody.appendChild(row));
                
                // Toggle sort direction
                isDateAscending = !isDateAscending;
                
                // Update button icon
                const icon = dateFilter.querySelector('i');
                icon.className = isDateAscending ? 'bx bx-sort-up' : 'bx bx-sort-down';
            }

            // Add event listeners
            courseFilter.addEventListener('change', filterTable);
            yearFilter.addEventListener('change', filterTable);
            dateFilter.addEventListener('click', sortByDate);
        });
    </script>
</body>
</html>

