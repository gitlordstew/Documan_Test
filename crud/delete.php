<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="../include/styles/styles.css"> 
</head>
<body>
    <div class="container">
        <h1>Welcome to Delete Page</h1>
        <p>This is the Delete Page for your projects.</p>
    </div>
</body>
</html>

<?php
    require_once 'connect.php';
    session_start();
    if(isset($_GET['id']))
    {
        // Get user details before deleting
        $getUserQuery = "SELECT * FROM tbuser WHERE id=:id";
        $getUserResult = $pdoConnect->prepare($getUserQuery);
        $getUserResult->execute(array(':id' => $_GET['id']));
        $userDetails = $getUserResult->fetch(PDO::FETCH_ASSOC);

        // Delete user
        $pdoQuery = "DELETE FROM tbuser WHERE id=:id";
        $pdoResult = $pdoConnect->prepare($pdoQuery);
        $pdoResult->execute(array(':id' => $_GET['id']));

        // Check if the deletion was successful
        if ($pdoResult) {
            // Audit log entry for deleting a user
            $iolog = "Deleted user with ID: " . $_GET['id'] . " (Username: " . $userDetails['username'] . ")";
            $stmt = $pdoConnect->prepare('INSERT INTO auditlog (time_log, username, iolog) VALUES (NOW(), :username, :iolog)');
            $stmt->bindParam(':username', $_SESSION['username']);
            $stmt->bindParam(':iolog', $iolog);
            $stmt->execute();

            if ($stmt) {
                echo "Audit log entry added successfully!";
            } else {
                echo "Error adding audit log entry";
            }

            header('location:read.php');
        } else {
            echo 'Error deleting user';
        }
    } else {
        echo "Invalid request. Please provide a valid ID.";
    }

    $pdoConnect = null;
?>