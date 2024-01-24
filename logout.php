<?php
require('.\include\connect\dbcon.php');

session_start();
$pdoResult=$pdoConnect->prepare("SELECT * FROM tbuser WHERE id=?");
if (isset($_SESSION['user']))
{
    $pdoResult->execute(array(
        $_SESSION['user'])
        );
}
$row=$pdoResult->fetch(PDO::FETCH_ASSOC);
$count=$pdoResult->rowCount();
if (isset($_POST["username"]))
{
    $_SESSION["username"] = $_POST["username"];
}
$iolog="Logged Out.";
$stmt=$pdoConnect->prepare('INSERT INTO auditlog (time_log,username,iolog) VALUES (NOW(),:username,:iolog)');
$stmt->bindParam(':username',$_SESSION['username']);
$stmt->bindParam(':iolog',$iolog);
$stmt->execute();		
unset($_SESSION['user']);
session_destroy();
header('location:landhome.php');
?>