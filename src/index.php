<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>O5 - Inserisci Competenza</title>
</head>
<body>
    <h2>Aggiungi una nuova competenza (Revisore ESG)</h2>
    <form action ="" method="POST">

        <label>Username Revisore:</label><br>
        <input type="text" name="username_revisore" required><br><br>

        <label>Nome Competenza:</label><br>
        <input type="text" name="nome_competenza" placeholder="Es. Sostenibilità" required><br><br>

        <label>Livello (0-5):</label><br>
        <!-- Applichiamo il vincolo 1 direttamente nell'interfaccia -->
        <input type="number" name="livello" min="0" max="5" required><br><br>

        <!-- Il 'name' di questo bottone è il segnale che il PHP aspetta -->
        <button type="submit" name="salva_competenza">Salva Competenza</button>
        
    </form> 
</body>
</html>    

<?php
define('BASE_PATH', '/var/www/html');

spl_autoload_register(function ($class) {
    $file = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$dbModel = new Models\DatabaseModel();
session_start();

$uri = trim($_SERVER['REQUEST_URI'], '/');
$parts = explode('/', $uri);

$controllerName = isset($parts[0]) && $parts[0] !== '' ? ucfirst($parts[0]) . 'Controller' : null;
$methodName = isset($parts[1]) && $parts[1] !== '' ? $parts[1] : 'index';
$controllerClass = 'Controllers\\' . $controllerName;

if ($controllerName && class_exists($controllerClass)) {
    $controller = new $controllerClass($dbModel);
    if (method_exists($controller, $methodName)) {
        $controller->$methodName();
    }
    else {
        http_response_code(404);
        echo '404 - Metodo non trovato';
    }
}
else {
    http_response_code(404);
    echo '404 - Pagina non trovata';
}
