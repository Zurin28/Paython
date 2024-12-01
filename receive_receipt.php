<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipts</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="receive_receipt.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php 
        include 'sidebar.php';
    
        ?>

        <div class="content-wrapper">
            <?php include 'navbar.php'; ?>

            <div class="table-container">
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
    </script>
</body>
</html>

