<?php 
require_once "account.class.php";
session_start() ?>

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
            <img src="" alt="Logo">
             <h2>Welcome to PayThon</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            <form action="" method="post">
                <label for="email">Enter your WMSU email:</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <a href="">Forgot Password?</a>

                <button type="loginbtn">Log In</button>

            </form>

            <?php if ($_SERVER["REQUEST_METHOD"]=="POST"){
                $_SESSION["email"] = htmlspecialchars($_POST["email"]);
                $_SESSION["password"] = htmlspecialchars($_POST['password']);

                $accObj = new Account;
                $accInfo = $accObj -> fetch($_SESSION["email"],  $_SESSION["password"]);
                $accRole = $accObj -> viewRole();
          

                if (!empty($accInfo) && $accRole == "student"){
                    header("Location: dashboard.php");
                    exit();
                }
                else if (!empty($accInfo) && $accRole == "staff"){
                    header("Location: staff.php");
                    exit();
                }
                else if (!empty($accInfo) && $accRole == "Admin"){
                    header("Location: admin.page.php");
                    exit();
                }
                else{
                    ?><p class="errorMsg"><?php echo("*Wrong Email or Password");?></p><?php
                }
            } 
            
            session_destroy();?>
        </div>
        <div class="design">
            <img src="img/Screenshot 2024-11-02 034438.png" alt="basta design kay d ko alam paano ilagay yung mga border chuchu kaya yung img na lng yung ano sa figma niglagay ko dito yung sa side d ko alm paano yun"> </div>
</section>
</body>
</html>