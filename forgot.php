<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password 🞄 DocuMan</title>
    <link rel="stylesheet" type="text/css" href="./include/styles/styles.css">
    <link rel="icon" type="image/png" href="./include/styles/images/Logo.png">
</head>
<body>
    <div class="main_bg"></div>
    <br>
    <div class="center">
    <?php
    if (isset($message))
    {
        echo '<label>' . $message . '</label>';
    }
    ?>
    <h1>Forgot Password</h1>
    <p class="fpar">Enter your email below to send instructions on how to reset your password</p>
    <form method="post">
        <div class="txt_field">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <a class="fpass" href="./index.php">Back to Login</a>
        <input type="submit" name="request" value="Send Request">
    </form>
    </div>
    <br>    
</body>
</html>

<?php
    session_start();

    require_once '.\include\connect\dbcon.php';
?>