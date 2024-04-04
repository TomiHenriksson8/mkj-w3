<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

global $DBH;
require_once __DIR__ . '/../db/dbConnect.php';

require_once __DIR__ . '/../MediaProject/MediaItemDatabaseOps.class.php';

$mediaItemDatabaseOps = new MediaProject\MediaItemDatabaseOps($DBH);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['title']) && isset($_POST['description'])) {
        $data = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'media_id' => $_POST['media_id'],
            'user_id' => $_SESSION['user']['user_id'],
        ];


        if ($mediaItemDatabaseOps->updateMediaItem($data)) {
            header('Location: ../home.php?success=Item modified');
        } else {
            header('Location: ../home.php?success=Item not modified');
        }
    }
}