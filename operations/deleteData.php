<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../index.php');
    exit;
}

global $DBH;
require_once __DIR__ . '/../db/dbConnect.php';

if(isset($_GET['id'])) {
    $DBH->beginTransaction();
    $data = [
        'media_id' => $_GET['id'],
    ];

    // delete likes
    $sql = 'DELETE FROM Likes WHERE media_id = :media_id';
    try {
        $STH = $DBH->prepare($sql);
        $STH->execute($data);
    } catch (PDOException $e) {
        echo "Could not delete likes from the database.";
        file_put_contents('PDOErrors.txt', 'deleteData.php - ' . $e->getMessage(), FILE_APPEND);
        $DBH->rollBack();
    }

    // delete comments
    $sql = 'DELETE FROM Comments WHERE media_id = :media_id';

    try {
        $STH = $DBH->prepare($sql);
        $STH->execute($data);
    } catch (PDOException $e) {
        echo "Could not delete comments from the database.";
        file_put_contents('PDOErrors.txt', 'deleteData.php - ' . $e->getMessage(), FILE_APPEND);
        $DBH->rollBack();
        exit;
    }

    // delete ratings
    $sql = 'DELETE FROM Ratings WHERE media_id = :media_id';

    try {
        $STH = $DBH->prepare($sql);
        $STH->execute($data);
    } catch (PDOException $e) {
        echo "Could not delete ratings from the database.";
        file_put_contents('PDOErrors.txt', 'deleteData.php - ' . $e->getMessage(), FILE_APPEND);
        $DBH->rollBack();
        exit;
    }

    // delete MediaItemTags
    $sql = 'DELETE FROM MediaItemTags WHERE media_id = :media_id';

    try {
        $STH = $DBH->prepare($sql);
        $STH->execute($data);
    } catch (PDOException $e) {
        echo "Could not delete MediaItemTags from the database.";
        file_put_contents('PDOErrors.txt', 'deleteData.php - ' . $e->getMessage(), FILE_APPEND);
        $DBH->rollBack();
        exit;
    }

    $data['user_id'] = $_SESSION['user']['user_id'];

    // delete file from server
    $sql = 'SELECT filename FROM MediaItems WHERE media_id = :media_id AND user_id = :user_id';
    try {
        $STH = $DBH->prepare($sql);
        $STH->execute($data);
        $STH->setFetchMode(PDO::FETCH_ASSOC);
        $row = $STH->fetch();
        $filename = $row['filename'];
        $destination = __DIR__ . '/../uploads/' . $filename;
        if (!unlink($destination)) {
            $DBH->rollBack();
            header('Location: ../home.php?success=File delete failed');
            exit;
        }
    } catch (PDOException $e) {
        echo "Could not get filename from the database.";
        file_put_contents('PDOErrors.txt', 'deleteData.php - ' . $e->getMessage(), FILE_APPEND);
        $DBH->rollBack();
        exit;
    }

    // delete MediaItems
    $sql = 'DELETE FROM MediaItems WHERE media_id = :media_id AND user_id = :user_id';

    try {
        $STH = $DBH->prepare($sql);
        $STH->execute($data);
        $DBH->commit();
        if($STH->rowCount() > 0) {
            header('Location: ../home.php?success=Item deleted');
        } else {
            header('Location: ../home.php?success=Not your item.');
        }
    } catch (PDOException $e) {
        echo "Could not delete data from the database.";
        file_put_contents('PDOErrors.txt', 'deleteData.php - ' . $e->getMessage(), FILE_APPEND);
        $DBH->rollBack();
        exit;
    }
} else {
    header('Location: ../home.php?success=No hacking allowed.');
}