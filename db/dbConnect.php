<?php
// dbConnect.php

global $host, $dbname, $username, $password, $port;

require __DIR__ . '/../config/config.php';

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8;port=$port";

try {
    $DBH = new PDO($dsn, $username, $password);
    $DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
} catch(PDOException $e) {
    echo "Could not connect to database: ". $e->getMessage();
    file_put_contents('PDOErrors.txt', 'dbConnect.php - ' . $e->getMessage(), FILE_APPEND);
}