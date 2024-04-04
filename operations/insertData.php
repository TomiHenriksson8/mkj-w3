<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}
error_reporting(E_ALL);
ini_set('display_errors', 1);
global $DBH;
require_once __DIR__ . '/../db/dbConnect.php';

require_once __DIR__ . '/../MediaProject/MediaItemDatabaseOps.class.php';

$mediaItemDatabaseOps = new MediaProject\MediaItemDatabaseOps($DBH);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['title']) && isset($_POST['description']) && $_FILES['file'] !== null) {
        $filename = $_FILES['file']['name'];
        $filetype = $_FILES['file']['type'];
        $filesize = $_FILES['file']['size'];
        $temp_file = $_FILES['file']['tmp_name'];
        $destination = __DIR__ . '/../uploads/' . $filename;
        if (!move_uploaded_file($temp_file, $destination)) {
            header('Location: home.php?success=File upload failed');
            exit;
        }

        $data = [
            'user_id' => $_SESSION['user']['user_id'],
            'filename' => $filename,
            'media_type' => $filetype,
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'filesize' => $filesize,
        ];

        

        if ($mediaItemDatabaseOps->insertMediaItem($data)) {
            header('Location: ../home.php?success=Item added');
        } else {
            header('Location: ../home.php?success=Item not added');
        }
    }
}