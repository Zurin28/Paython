<?php
require_once "account.class.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['studentId'])) {
        $studentId = $_POST['studentId'];
        $account = new Account();

        if ($account->deleteAccount($studentId)) {
            echo "<p style='color: green;'>Account successfully deleted.</p>";
        } else {
            echo "<p style='color: red;'>Failed to delete the account. Please try again later.</p>";
        }
    } else {
        echo "<p style='color: red;'>Invalid request. No StudentID provided.</p>";
    }
}
