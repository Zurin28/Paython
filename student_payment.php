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
    <?php include 'header.php'; ?>

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
                    <td><button class="action-button pay-now" id="payNowBtn">Pay Now</button></td>
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

    <!-- Payment Modal -->
    <div id="paymentModal" class="modal">
        <!-- Content will be loaded here via AJAX -->
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="success-modal">
        <div class="success-modal-content">
            <div class="success-icon">✓</div>
            <h1 class="success-title">Done!</h1>
            <p class="success-message">Your Payment Has Been Processed Successfully</p>
        </div>
    </div>

    <script src="studentside.js"></script>
</body>
</html>

