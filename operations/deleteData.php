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

if(isset($_GET['id'])) {
    $data = [
        'media_id' => $_GET['id'],
        'user_id' => $_SESSION['user']['user_id'],
    ];
    if ($mediaItemDatabaseOps->deleteMediaItem($data)) {
        header('Location: ../home.php?success=Item deleted');
    } else {
        header('Location: ../home.php?success=Item not deleted');
    }

} else {
    header('Location: ../home.php?success=No hacking allowed.');
}