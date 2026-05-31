<?php
// Connessione PDO
$host = $_ENV['MYSQL_HOST'];
$database = $_ENV['MYSQL_DATABASE'];
$user = $_ENV['MYSQL_ROOT_USER'];
$password = $_ENV['MYSQL_PASSWORD'];

$pdo = new \PDO(
    "mysql:host=$host;dbname=$database;charset=utf8mb4",
    $user,
    $password
);
?>
