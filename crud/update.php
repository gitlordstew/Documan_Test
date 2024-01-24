<?php
    session_start();
    ob_start(); // Start output buffering
    date_default_timezone_set('Asia/Manila');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="../include/styles/styles.css">
        <link rel="icon" type="image/png" href="../include/styles/images/Logo.png">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    </head>

    <?php
        require_once 'connect.php';

        if (isset($_SESSION["username"]))
        {

        } else {
            header("location:index.php");
        }
            echo '<div class="sidebar">
            <div class="logo"></div>
                <ul class="menu">
                    <li>
                        <a href="..\home.php">
                        <i class="fas fa-square"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                <a href="..\upload.php">
                    <i class="fas fa-upload"></i>
                    <span>Uploads</span>
                </a>
                </li>
                <li class="active">
                    <a href=".\read.php">
                        <i class="fas fa-user-lock"></i>
                        <span>Users & Access</span>
                    </a>
                </li>
                <li>
                    <a href="..\auditlog.php">
                        <i class="fas fa-file-lines"></i>
                        <span>Logs</span>
                    </a>
                </li>
                <li>
                <a href="#">
                    <i class="fas fa-user"></i>
                    <span>Requests</span>
                </a>
                </li>
            </ul>
        </div>
    <div class="main--content">
        <div class="header--wrapper">
            <div class="header--title">
                <h2>Users & Access</h2>
            </div>
            <div class="user--info">
                <div class="nav--icons">
                    <ul>
                        <li>
                            <a href="">
                            <i class="fa-solid fa-message"></i>
                            </a>
                        </li>
                        <li>
                            <a href="" class="notification">
                            <i class="fa-solid fa-bell"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="profile" >
                <img id="menu-btn" src="../include/styles/images/profile.jpg" alt=""/>
                </div>
                    <div id="menu" class="profile_menu hide">
                    <h3>'.$_SESSION["username"].'<br><span>Super Admin</span></h3>
                        <ul>
                            <li><i class="fa-solid fa-user"><a href="#">Profile</a></i></li>
                            <li><i class="fa-solid fa-cog"><a href="#">Settings</a></i></li>
                            <li><i class="fa-solid fa-sign-out-alt"><a href="../logout.php">Logout</a></i></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card--container">
            <h3 class="main--title">Users</h3>
';
?>

        <div class="container-bar">
            <button id="add_btn" class="add_user_btn">Add User</button>
            <div class="search_add">
                <form action="search.php" method="post">
                    <input class="txt-inputs" type="text" name="id" placeholder="Search">
                    <input class="tbl_btn" type="submit" name="Find" value="Search">
                </form>
            </div>
        </div>

    <?php

        $pdoQuery = 'SELECT * FROM tbuser';
        $pdoResult = $pdoConnect->prepare($pdoQuery);
        $pdoResult->execute();
        echo '<table border="2" cellpadding="7">
        <thead>
        <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Password</th>
        <th>Full name</th>
        <th>Role</th>
        <th>Action</th>
        </tr>
        </thead>';

        while($row = $pdoResult->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);
            echo "<tr>";
            echo "<td>$id</td>";
            echo "<td>$username</td>";
            echo "<td>$email</td>";
            echo "<td>$password</td>";
            echo "<td>$fullname</td>";
            echo "<td>$role</td>";
            echo "<td>
                <a class='tbl_btn' id='update_btn' href='#';?>Update</a>
                <a class='tbl_btn' href='delete.php?id=$id';?>Delete</a>
                </td>";
            echo "</tr>";
        }

        if (!empty($_POST["modify"])) {
            // Update user details
            $username = htmlspecialchars($_POST['user']);
            $email = htmlspecialchars($_POST['email']);
            $password = htmlspecialchars($_POST['pass']);
            $fullname = htmlspecialchars($_POST['fname']);
            $role = htmlspecialchars($_POST['role']);

            $pdoQuery = $pdoConnect->prepare("UPDATE tbuser SET username = :username, email = :email, password = :password, fullname = :fullname, role = :role WHERE id=:id");
            $pdoResult = $pdoQuery->execute(array(
                'username' => $username,
                'email' => $email,
                'password' => $password,
                'fullname' => $fullname,
                'role' => $role,
                'id' => $_GET['id']
            ));

            // Check if the update was successful
            if ($pdoResult) {
                // Audit log entry for updating a user
                $iolog = "Updated user details.";
                $stmt = $pdoConnect->prepare('INSERT INTO auditlog (time_log, username, iolog) VALUES (NOW(), :username, :iolog)');
                $stmt->bindParam(':username', $_SESSION['username']);
                $stmt->bindParam(':iolog', $iolog);
                $stmt->execute();
        
                if ($stmt) {
                    echo "Audit log entry added successfully!";
                } else {
                    echo "Error adding audit log entry";
                }
        
                // Redirect to read.php
                header("Location: read.php");
                exit();
            } else {
                echo 'Error updating user details';
            }
        }
        
        $pdoQuery = $pdoConnect->prepare("SELECT * FROM tbuser WHERE id = :id");
        if (isset($_GET["id"]))
        {
            $pdoQuery->execute(array(':id' => $_GET["id"]));
        }
        $pdoResult = $pdoQuery->fetchAll();
        $pdoConnect= null;

    ?>
            <div class="update_center">
                <div id="menu_update" class="update_menu show_update">
                    <h2>UPDATE USER</h2>
                        <div id="updateclosebtn" class="updateclose_btn">
                            <a href=".\read.php">&times;</a>
                        </div>
                    <form action="update.php?id=<?php if(isset($_GET["id"])){echo $_GET["id"];}?>" method="post">
                        <input type="hidden" name="id">
                    <div class="update_form_element">
                        <label for="user">Username</label>
                        <input type="text" name="user" value="<?php if(isset($pdoResult[0]['username'])){echo $pdoResult[0]['username'];} ?>" required placeholder="Username">
                    </div>
                    <div class="update_form_element">
                        <label for="pass">Password</label>
                        <input type="password" name="pass" value="<?php if(isset($pdoResult[0]['password'])){echo $pdoResult[0]['password'];} ?>" required placeholder="Password">
                    </div>
                    <div class="update_form_element">
                        <label for="fname">Full Name</label>
                        <input type="text" name="fname" value="<?php if(isset($pdoResult[0]['fullname'])){echo $pdoResult[0]['fullname'];} ?>" required placeholder="Fullname">
                    </div>
                    <div class="update_form_element">
                        <label for="email">Email</label>
                        <input type="email" name="email" value="<?php if(isset($pdoResult[0]['email'])){echo $pdoResult[0]['email'];} ?>" required placeholder="Email">
                    </div>
                    <div class="update_form_element">
                        <label for="role">Role</label>
                        <select name="role" required>
                            <option value="superadmin" <?php if (isset($pdoResult[0]['role']) && $pdoResult[0]['role'] == 'superadmin') {echo 'selected';} ?>>Super Admin</option>
                            <option value="admin" <?php if (isset($pdoResult[0]['role']) && $pdoResult[0]['role'] == 'admin') {echo 'selected';} ?>>Admin</option>
                            <option value="client" <?php if (isset($pdoResult[0]['role']) && $pdoResult[0]['role'] == 'client') {echo 'selected';} ?>>Client</option>
                        </select>
                    </div>
                    <div id="updatesavebtn" class="update_save_btn">
                        <input class="update_user_btn" type="submit" name="modify" value="Update"> 
                    </div>
                    </form>
                </div>
            </div>
    </body>
</html>