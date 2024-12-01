<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayThon - Payment Section</title>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="student_payment.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <i class='bx bx-wallet'></i>
            PayThon
        </div>
        <nav class="nav">
            <a href="stundent_overview.php">Overview</a>
            <a href="#" class="active">Payments</a>
            
        </nav>
        <div style="display: flex; align-items: center; gap: 20px;">
            <div class="search-container">
                <i class='bx bx-search search-icon'></i>
                <input type="text" class="search-input" placeholder="Search payments...">
            </div>
            <div class="user-icon-container">
                <div class="user-icon" onclick="toggleDropdown()">
                    <i class='bx bx-user'></i>
                </div>
                <div class="dropdown-menu" id="dropdownMenu">
                    <a href="#" class="dropdown-item">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="title-section">
            <h1 class="title">Student Payments</h1>
            <div class="filter-container">
                <select class="filter-dropdown">
                    <option value="all">Status</option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                </select>
            </div>
        </div>
        
        <table class="payment-table">
            <thead>
                <tr>
                    <th>Organization</th>
                    <th>Fee</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>CSC</td>
                    <td>Palaro Fee</td>
                    <td>Not paid yet</td>
                    <td>150 php</td>
                    <td><button class="action-button pay-now">Pay Now</button></td>
                </tr>
                <tr>
                    <td>VENOM Fee</td>
                    <td>Publication Fee</td>
                    <td>Paid</td>
                    <td>50 php</td>
                    <td><button class="action-button paid">Paid</button></td>
                </tr>
            </tbody>
        </table>
    </main>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.user-icon') && !event.target.matches('.bx-user')) {
                const dropdowns = document.getElementsByClassName('dropdown-menu');
                for (let dropdown of dropdowns) {
                    if (dropdown.classList.contains('show')) {
                        dropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>