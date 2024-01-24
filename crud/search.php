<!DOCTYPE html>
<html>
    <head>
        <title>Search Page</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" type="text/css" href="../include/styles/styles.css">
    </head>
    <body>
        <div class="container">
            <h1>Welcome to Seach Page</h1>
            <p>This is the Search page for your project.</p>
        </div>
    </body>
</html>

<?php
    session_start();

    if (isset($_SESSION["username"])) {
        echo '<h3>Here is the list of users you are checking, ' . $_SESSION["username"] . '</h3>';
        echo '<br> <a href="/prj/home.php">Home</a>';
        echo '<br> <a href="/prj/dropdown.php">Sample Drop-down Menu</a>';
        echo '<br> <a href="/prj/logout.php">Logout</a>';
    } else {
        header("location:index.php");
    }
    $id = "";
    $username = "";
    $password = "";
    $fullname = "";

    if (isset($_POST['Find'])) {
        try {
            $pdoConnect = new PDO("mysql:host=localhost;dbname=dbtest", "root", "");
        } catch (PDOException $exc) {
            echo $exc->getMessage();
            exit();
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($id !== false && $id !== null) {
            $pdoQuery = "SELECT * FROM tbuser WHERE id = :id";
            $pdoResult = $pdoConnect->prepare($pdoQuery);
            $pdoExec = $pdoResult->execute(array(":id" => $id));

            echo "<table border='2' cellpadding='7'>";
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Username</th>";
            echo "<th>Password</th>";
            echo "<th>Fullname</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            if ($pdoExec) {
                if ($pdoResult->rowCount() > 0) {
                    while ($row = $pdoResult->fetch(PDO::FETCH_ASSOC)) {
                        extract($row);
                        echo "<tr>";
                        echo "<td>$id</td>";
                        echo "<td>$username</td>";
                        echo "<td>$password</td>";
                        echo "<td>$fullname</td>";
                        echo "<td><a href='update.php?id=$id';>Edit</a> <a href='delete.php?id=$id';?>Delete</a></td>";
                        echo "</tr>";
                    }
                } else {
                    echo '<br><br><br><br><br>';
                    echo 'No Data';
                }
            }
        } else {
            echo '<br><br><br><br><br>';
            echo 'No Data';
        }

        $pdoConnect = null;
    }
?>