<?php
// Connessione PDO

$host = $_ENV['MYSQL_HOST'];
$database = $_ENV['MYSQL_DATABASE'];
$user = $_ENV['MYSQL_ROOT_USER'];
$password = $_ENV['MYSQL_PASSWORD'];

try {
    $pdo = new \PDO(
        "mysql:host=$host;dbname=$database;charset=utf8mb4",
        $user,
        $password
    );
} catch (PDOException $e) {
        die("Errore connessione DB: " . $e->getMessage());
    // finche non si aggiunge il file error.phpnonono
    //  $output = "Errore di connessione: " . $e->getMessage();
    // include __DIR__ . "/../Views/error.php";
    // exit;
}
?>
