<div class="sidebar">
    <div class="logo">
        <i class='bx bx-wallet'></i>
        <span class="logo_name">PayThon</span>
    </div>
    <ul class="sidebar_list">
        <li>
            <a href="admin.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'admin.php') ? 'class="active"' : ''; ?>>
                <i class='bx bx-grid-alt'></i>
                <span class="list_name">Gender Club</span>
            </a>
        </li>
        <li>
            <a href="staff_student.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'studentlist.php') ? 'class="active"' : ''; ?>>
                <i class='bx bx-user-pin'></i>
                <span class="list_name">Student List</span>
            </a>
        </li>
        <li>
            <a href="receive_receipt.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'admin_organizations.php') ? 'class="active"' : ''; ?>>
                <i class='bx bx-group'></i>
                <span class="list_name">Receipts</span>
            </a>
        </li>

        <li class="log_out">
            <a href="login.php">
                <i class='bx bx-log-out'></i>
                <span class="list_name">Log out</span>
            </a>
        </li>
    </ul>
</div> 
