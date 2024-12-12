<header class="header">
    <div class="logo">
        <i class='bx bx-wallet'></i>
        PayThon
    </div>
    <nav class="nav">
        <a href="student.staff.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'student.staff.php') ? 'class="active"' : ''; ?>>Overview</a>
        <a href="student.staff.payment.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'student.staff.payment.php') ? 'class="active"' : ''; ?>>Payments</a>
    </nav>
    <div style="display: flex; align-items: center; gap: 20px;">
        <?php if (basename($_SERVER['PHP_SELF']) == 'student.staff.payment.php'): ?>
        <div class="search-container">
            <i class='bx bx-search search-icon'></i>
            <input type="text" class="search-input" placeholder="Search payments...">
        </div>
        <?php endif; ?>
        <div class="user-icon-container">
            <div class="user-icon" onclick="toggleDropdown()">
                <i class='bx bx-user'></i>
            </div>
            <div class="dropdown-menu" id="dropdownMenu">
                <a href="staff_student.php" class="dropdown-item">Change to Staff</a>
                <a href="login.php" class="dropdown-item">Logout</a>
            </div>
        </div>
    </div>
</header> 