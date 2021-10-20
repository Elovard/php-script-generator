<?php

$host = 'localhost';
$db = 'participants';
$user = 'root';
$password = getenv('MYSQL_ROOT_PASSWORD');

try {
    $pdo = new PDO("mysql:host=$host; dbname=$db", $user, $password);
} catch (PDOException $e) {
    echo 'Database error ' . $e->getMessage();
}