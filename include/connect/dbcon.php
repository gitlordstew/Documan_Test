<?php
$host = "localhost";
$dbname = "dbtest";
$username = "root";
$password = "";

try {
    $pdoConnect = new PDO("mysql:host=$host;dbname=$dbname",$username,$password);
    $pdoConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOEXCEPTION $e){
        echo "Connection Failed : ". $e->getMessage();
}

?>
