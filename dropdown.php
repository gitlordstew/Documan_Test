<!DOCTYPE html>
<html lang="en">
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1.0">
        <title>Dashboard</title>
        <link rel="stylesheet" type="text/css" href="./include/styles/styles.css">
    </head>
    <body>
        <div class="container">
            <h1>Welcome to Dropdown Page</h1>
            <p>This is the Dropdown Page for your projects.</p>
        </div>
    </body>
</html>
<?php
session_start();

if (isset($_SESSION["username"]))
{
    echo '<h3>Enjoy browsing, '.$_SESSION["username"].'!</h3>';
    echo '<br /><br /><a href="/prj/home.php">Home</a>';
    echo '<br /><br /><a href="crud/read.php">Add a user</a>';

    echo'<br /><br /><a href="logout.php">Logout</a>';

    echo "<br><br><br><br><br><br><br><br>";
    echo "Select Username";

    require_once './include/connect/dbcon.php';

    $pdoQuery = "SELECT username FROM tbuser";

    $pdoResult = $pdoConnect->query($pdoQuery);

    $dropdown = "<select name='users'>";
    foreach ($pdoResult as $row)
    {
        $dropdown .="\r\n<option value='{$row['username']}'>{$row['username']}</option>";
    }

$dropdown .= "\r\n<select>";
echo $dropdown;
echo '</select>';


echo "<br><br><br><br><br><br><br><br>";
echo "This is to fetch the data in selected dropdown menu";
}
else
{
    header("location:index.php");
}
?>