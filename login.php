<?php 
require_once "account.class.php";
require_once "tools/functions.php";
$accObj = new Account;

session_start();

$password = $email = '';

?>


<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <link rel="stylesheet" href="css/style.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
</head>
<body>
    <section class="login-container">
        <div class="login">
            <img src="img/logoccs.png" alt="Logo">
            <h2>Welcome to PayThon</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            <form action="" method="post">
                <label for="email">Enter your WMSU email:</label>
                <i class="fas fa-envelope"></i>
                <input type="email" id="email" name="email" placeholder="WMSU Email" required>

                <label for="password">Password:</label>
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>

                <a href="">Forgot Password?</a>

                <button type="loginbtn">Log In</button>
            </form>
            <p class="signup-link">Don't have an account? <a href="signup.php">Create Account</a></p>
            <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = clean_input($_POST["email"]);
    $password = clean_input($_POST['password']);

   

    if ($accObj->login($email, $password)) {
        $data = $accObj->fetch($email, $password);
        $_SESSION['account'] = $data;
        if (isset($_SESSION['account'])) {
            $_SESSION['StudentID'] = $_SESSION['account']['StudentID'];
            $_SESSION['Name'] = $_SESSION['account']['first_name'];
            echo $_SESSION['Name'];
             if ($_SESSION['account']['isstaff'] == false && $_SESSION['account']['isadmin'] == false){
                header("Location: stundent_overview.php");
            }elseif ($_SESSION['account']['isstaff'] == true && $_SESSION['account']['isadmin'] == false) {
                header("Location: student.staff.php");
            }elseif ($_SESSION['account']['isadmin']) {
                header("Location: admin_dashboard.php");
            }
            }

    }else {
        echo '<p class="errorMsg">*Wrong Email or Password</p>';
    }
}?>
        </div>
      
    </section>
</body>
</html>
