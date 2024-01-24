<!DOCTYPE html>
<html>
<head>
    <title>Login 🞄 DocuMan</title>
    <link rel="stylesheet" type="text/css" href="./include/styles/styles.css">
    <link rel="icon" type="image/png" href="./include/styles/images/Logo.png">
</head>
<body>
    <img href="./images/bgblue.png">
    <div class="main_bg"></div>
    <br>
    <div class="center">
    <?php
    if (isset($message))
    {
        echo '<label>' . $message . '</label>';
    }
    ?>
    <h1> Log In</h1>
    <form method="post">
        <div class="txt_field">
            <label>Username</label>
            <input type="text" name="username" required>
        </div>
        <div class="txt_field">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <a class="fpass" href="./forgot.php">Forgot Password?</a>
        <input class="log-button" type="submit" name="login" value="Login">
    </form>
    </div>
    <br>    
</body>
</html>

<?php
    session_start();

    require_once '.\include\connect\dbcon.php';

    try {
        if (isset($_POST["login"])) {
            if (empty($_POST["username"]) || empty($_POST["password"])) {
                $message = '<label>All fields are required</label>';
            } else {
                $pdoQuery = "SELECT * FROM tbuser WHERE username = :username AND password = :password";
                $pdoResult = $pdoConnect->prepare($pdoQuery);
                $pdoResult->execute(array(
                    'username' => $_POST["username"],
                    'password' => $_POST["password"]
                ));
                
                $user = $pdoResult->fetch(PDO::FETCH_ASSOC); // fetching user's data
                
                if ($user) {
                    $_SESSION["username"] = $user["username"];
                    $userRole = $user["role"]; // checking the role
                    
                    if ($userRole == "superadmin") {
                        header("location: home.php"); // redirect to super admin page
                    } elseif ($userRole == "admin") {
                        header("location: admin.php"); // redirect to admin page
                    } else {
                        header("location: clienthome.php"); // client role add a client role now!
                    }
                    
                    $iolog = "Logged in.";
                    $astatus = "Online";
                    
                    $stmt = $pdoConnect->prepare('INSERT INTO auditlog (time_log, username, iolog) VALUES (NOW(), :username, :iolog)');
                    $stmt->bindParam(':username', $_SESSION['username']);
                    $stmt->bindParam(':iolog', $iolog);
                    $stmt->execute();
                    
                    $stmt = $pdoConnect->prepare('UPDATE tbuser SET a_status = :astatus WHERE username = :username');
                    $stmt->bindParam(':astatus', $astatus);
                    $stmt->bindParam(':username', $_SESSION["username"]);
                    $stmt->execute();
                } else {
                    $message = '<label>Wrong Data</label>';
                }
            }
        }
    } catch (PDOException $error) {
        $message = $error->getMessage();
    }
?>
