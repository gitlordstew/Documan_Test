<?php
require_once '.\include\connect\dbcon.php';
session_start();

$response = array('success' => false);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $filename = $_POST['filename'];

    // Fetch existing access users from the database
    $pdoQuery = $pdoConnect->prepare("SELECT access_users FROM file_uploads WHERE file_name = :filename");
    $pdoQuery->bindParam(':filename', $filename);
    $pdoQuery->execute();
    $accessUsers = $pdoQuery->fetchColumn();

    if ($accessUsers !== false) {
        $response['success'] = true;
        $response['accessUsers'] = $accessUsers;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
