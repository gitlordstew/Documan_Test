<?php
    session_start();
    date_default_timezone_set('Asia/Manila');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" type="text/css" href="./include/styles/styles.css">
    <link rel="icon" type="image/png" href="./include/styles/images/Logo.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
</head>

<?php
    require('.\include\connect\dbcon.php');

    if (!isset($_SESSION["username"])) {
        header("location:index.php");
    }

    if (!empty($_POST["update_profile"])) {
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
    if (isset($_GET["id"])) {
        $pdoQuery->execute(array(':id' => $_GET["id"]));
    }
    $pdoResult = $pdoQuery->fetchAll();
    
?>

<body class="cli-body">
    <div class="cli-main--content">  
    <header class="client-header">
        <div class="client-container">
            <div class="client-logo">
                <a href=""><img class="cli-logo" src="./include/styles/images/Logo.png" alt=""><span class="cli-logo-title">Files</span></a>
            </div>
            <div class="profile" >
                <img id="menu-btn" src="./include/styles/images/profile.jpg" alt=""/>
            </div>
            <div id="menu" class="cli-profile_menu hide">
                    <?php echo '<h3>'.$_SESSION["username"].'<br><span>Welcome</span></h3>' ?>
                        <ul>
                            <li><i class="fa-solid fa-user"><a href="profile.php">Profile</a></i></li>
                            <li><i class="fa-solid fa-cog"><a href="#">Settings</a></i></li>
                            <li><i class="fa-solid fa-sign-out-alt"><a href="logout.php">Logout</a></i></li>
                        </ul>
                    </div>
        </div>
<div class="profile-tabular--wrapper">
    <h3 class="main--title uline">Profile</h3>
    <div class="tab-container">
        <div class="list-group">
            <h3 class="tab-active">General</h3>
            <h3>Information</h3>
            <h3>Password</h3>
        </div>
    <div class="tab-content">
        <div class="tabs tab-active">
            <div class="general-content">
                <div class="general-align">
                    <h1>General</h1>
                    <div class="general-col">
                <img src="./include/styles/images/profile.jpg" alt="">
                    <div class="general-info">
                        <span>Username</span>
                            <h2><?php $_SESSION['username']?></h2>
                        <span>Email</span>
                            <h2><?php $_SESSION['email']?></h2>
                        <span>Name</span>
                            <h2><?php $_SESSION['name']?></h2>
                </div>
                </div>
                </div>
            </div>
        </div>
    <div class="tabs">
        <div class="info-content">
            <div class="general-align">
                <h1>Information</h1>
            <div class="update-profile">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="flex">
                    <div class="info-input">
                        <span>Username</span>
                        <input type="text" name="user" value="<?= isset($pdoResult[0]['username']) ? htmlspecialchars($pdoResult[0]['username']) : ''; ?>" required placeholder="Username" class="info-inputbox">
                        <span>Email</span>
                        <input type="email" name="email" value="<?= isset($pdoResult[0]['email']) ? htmlspecialchars($pdoResult[0]['email']) : ''; ?>" required placeholder="Email" class="info-inputbox">
                        <span>Full name</span>
                        <input type="text" name="fname" value="<?= isset($pdoResult[0]['fullname']) ? htmlspecialchars($pdoResult[0]['fullname']) : ''; ?>" required placeholder="Fullname" class="info-inputbox">
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tabs">
        <div class="pass-content">
            <div class="general-align">
                <h1>Password</h1>
                <div class="pass-input">
                    <span>old password</span>
                    <input type="password" name="update_pass" placeholder="Enter previous password" class="info-inputbox">
                    <span>new password</span>
                    <input type="password" name="new_pass" placeholder="Enter new password" class="info-inputbox">
                    <span>confirm password</span>
                    <input type="password" name="confirm_pass" placeholder="Confirm new password" class="info-inputbox">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="update-btn">
    <input type="submit" value="Save Changes" name="update_profile" class="profile-btn">
    <a href="clienthome.php" class="profile-btn">Back</a>
    </div>
    </form>
    </div>
    </div>
</div>
</div>
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

            let tabs = document.querySelectorAll(".list-group h3");
            let tabContents = document.querySelectorAll(".tab-content .tabs");

            tabs.forEach((tab, index) => {
                tab.addEventListener("click", () => {
                    tabContents.forEach((content) => {
                        content.classList.remove("tab-active")
                    });
                    tabs.forEach((tab) => {
                        tab.classList.remove("tab-active");
                    });
                    tabContents[index].classList.add("tab-active");
                    tabs[index].classList.add("tab-active");
                });
            });
    </script>
</body>
</html>
