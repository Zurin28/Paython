<?php
require_once '../tools/functions.php';
require_once '../classes/account.class.php';
// Change
session_start();

$username = $password = '';
$accountObj = new Account();
$loginErr = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input(($_POST['username']));
    $password = clean_input($_POST['password']);

    if ($accountObj->login($username, $password)) {
        $data = $accountObj->fetch($username);
        $_SESSION['account'] = $data;
        header('location: ../admin/dashboard.php');
    } else {
        $loginErr = 'Invalid username/password';
    }
} else {
    if (isset($_SESSION['account'])) {
        if ($_SESSION['account']['is_staff']) {
            header('location: ../admin/dashboard.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <form action="login.php" method="post">
        <h2>Login</h2>
        <label for="username">Username/Email</label>
        <br>
        <input type="text" name="username" id="username">
        <br>
        <label for="password">Password</label>
        <br>
        <input type="password" name="password" id="password">
        <br>
        <input type="submit" value="Login" name="login">
        <?php
        if (!empty($loginErr)) {
        ?>
            <p class="error"><?= $loginErr ?></p>
        <?php
        }
        ?>
    </form>
</body>

</html>