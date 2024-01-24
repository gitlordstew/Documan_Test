<?php

session_start();

if (isset($_POST['insert'])) {
    $host = "localhost";
    $dbname = "dbtest";
    $username = "root";
    $password = "";

    try {
        $pdoConnect = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo "Connected successfully!";
    } catch (PDOEXCEPTION $exc){
        echo "Connection Failed: " . $exc->getMessage();
        exit();
    }

    if (isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['fname']) && isset($_POST['email']) && isset($_POST['role'])) {
        $user = $_POST['user'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $fname = $_POST['fname'];
        $role = $_POST['role'];

        $pdoQuery = "INSERT INTO `tbuser` (`role`, `username`, `email`, `password`, `fullname`) VALUES (:role, :user, :email, :pass, :fname)";
        $pdoResult = $pdoConnect->prepare($pdoQuery);
        $pdoResult->bindParam(':role', $role);
        $pdoResult->bindParam(':user', $user);
        $pdoResult->bindParam(':email', $email);
        $pdoResult->bindParam(':pass', $pass);
        $pdoResult->bindParam(':fname', $fname);

        try {
            $pdoExec = $pdoResult->execute();

            if ($pdoExec) {
                // Data inserted successfully
                echo "Data inserted successfully!";

                // Add entry to audit_log
                $iolog = "Added a user.";
                $stmt = $pdoConnect->prepare('INSERT INTO auditlog (time_log, username, iolog) VALUES (NOW(), :username, :iolog)');
                $stmt->bindParam(':username', $_SESSION['username']);
                $stmt->bindParam(':iolog', $iolog);
                $stmtExec = $stmt->execute();

                if ($stmtExec) {
                    echo "Audit log entry added successfully!";
                } else {
                    echo "Error adding audit log entry";
                }

                // Redirect to read.php
                header("Location: read.php");
                exit;
            } else {
                echo 'Data not inserted';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo 'Missing required fields (user, pass, fname, email, role)';
    }

    $pdoConnect = null;
}
?>