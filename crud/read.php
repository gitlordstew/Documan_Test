<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="../include/styles/styles.css">
        <link rel="icon" type="image/png" href="./include/styles/images/Logo.png">
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
                            <li><i class="fa-solid fa-user"><a href="superadminprofile.php">Profile</a></i></li>
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
            <form action="" method="post">
                <input class="txt-inputs" type="text" name="id" placeholder="Search">
                <input class="tbl_btn" type="submit" name="Find" value="Search">
            </form>
        </div>
        </div>
        <div class="add_center">
            <div id="menu_add" class="add_menu show_add">
                <h2>ADD USER</h2>
            <div id="closebtn" class="close_btn">&times</div>
                <form action="create.php" method="post">
                    <input type="hidden" name = "id">
            <div class="add_form_element">
                <label for="user">Username</label>
                <input type="text" name="user" required>
            </div>
            <div class="add_form_element">
                <label for="pass">Password</label>
                <input type="password" name="pass" required>
            </div>
            <div class="add_form_element">
                <label for="fname">Full Name</label>
                <input type="text" name="fname" required>
            </div>
            <div class="add_form_element">
                <label for="email">Email</label>
                <input type="text" name="email" required>
            </div>
            <div class="add_form_element">
                <label for="role">Role</label>
                <select name="role" required>
                    <option value="superadmin">Super Admin</option>
                    <option value="admin">Admin</option>
                    <option value="client">Client</option>
                </select>
            </div>
            <div class="add_save_btn">
                <input class="add_user_btn" type="submit" name="insert" value="Save">
            </div>
                </form>
            </div>
        </div>

    <?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If the form is submitted, retrieve the entered username
    $searchUsername = isset($_POST['id']) ? $_POST['id'] : '';

    if (!empty($searchUsername)) {
        // If a username is provided, filter the results based on the username
        $pdoQuery = 'SELECT * FROM tbuser WHERE username = :searchUsername';
        $pdoResult = $pdoConnect->prepare($pdoQuery);
        $pdoResult->bindParam(':searchUsername', $searchUsername, PDO::PARAM_STR);
    }else {
        // If no username is provided, fetch all values
        $pdoQuery = 'SELECT * FROM tbuser';
        $pdoResult = $pdoConnect->prepare($pdoQuery);
    }
    // Execute the query
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

    while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        echo "<tr>";
        echo "<td>$id</td>";
        echo "<td>$username</td>";
        echo "<td>$email</td>";
        echo "<td>$password</td>";
        echo "<td>$fullname</td>";
        echo "<td>$role</td>";
        echo "<td>
                <a class='tbl_btn' id='update_btn' href='update.php?id=$id'>Update</a>
                <a class='tbl_btn' href='delete.php?id=$id'>Delete</a>
                </td>";
        echo "</tr>";
    }
} else {
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
                <a class='tbl_btn' id='update_btn' href='update.php?id=$id'>Update</a>
                <a class='tbl_btn' href='delete.php?id=$id';?>Delete</a>
                </td>";
            echo "</tr>";
        }
}
    ?>
        <script>
            const menuBtn = document.querySelector('#menu-btn')
            const menu = document.querySelector('#menu')

            menuBtn.addEventListener('click', () => {
                menu.classList.toggle('hide')
            })

            document.addEventListener('click', e =>{
                if(!menu.contains(e.target) && e.target !== menuBtn) {
                    menu.classList.add('hide')
                }
            })

            const addbtn = document.querySelector('#add_btn')
            const closebtn = document.querySelector('#closebtn')
            const adduser = document.querySelector('#menu_add')

            addbtn.addEventListener('click', () => {
                adduser.classList.toggle('show_add')
            })
            closebtn.addEventListener('click', () => {
                adduser.classList.toggle('show_add')
            })
            document.addEventListener('click', e =>{
                if(!adduser.contains(e.target) && e.target !== addbtn) {
                    adduser.classList.add('show_add')
                }
            })
        </script>
    </body>
</html>