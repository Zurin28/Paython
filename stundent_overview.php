

<?php 
require_once 'fee.class.php';
require_once 'account.class.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayThon - Payment Management System</title>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="student_overview.css">
    <link rel="stylesheet" href="header.css">
</head>
<body>
    
<?php include 'header.php'; ?>
<?php 
$feeObj = new Fee;
$feeInfo = $feeObj->viewFees();

?>




    <main class="main-content">
        <h1 class="welcome-text">Welcome, <span class="welcome-name"><?= $_SESSION['Name']?></span></h1>
        <div class="main-logo">PayThon</div>
        <div class="subtitle">CSC-CCS Payment Management System</div>
        
        <?php foreach ($feeInfo as $fee){?>
        <div class="payment-container">
            <div class="payment-item">
                <div class="payment-icon"></div>
                <div class="payment-details">
                    <h3><?=$fee['FeeName']?></h3>
                    <p><?=$fee['OrgID']?></p>
                </div>
            </div>
        <?php } ?>

            <button class="view-all"><a href="student_payment.php">View All</a></button>
        </div>
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