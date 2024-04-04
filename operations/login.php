<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
global $DBH;
require_once __DIR__ . '/../db/dbConnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $sql = "SELECT * FROM Users WHERE username = :username";
        $data = [
            'username' => $_POST['username'],
        ];
        $STH = $DBH->prepare($sql);
        $STH->execute($data);
        $user = $STH->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($_POST['password'], $user['password'])) {
            $_SESSION['user'] = $user;
            // print_r($_SESSION['user']);
            // redirect to secret page
            header('Location: ../home.php');
            exit;
        } else {
           header('Location: ../index.php?success=Invalid username or password');
        }
    }
}
?>