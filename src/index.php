<?php
define('BASE_PATH', '/var/www/html');

// Converte tutti gli errori PHP in eccezioni
set_error_handler(function($severity, $message, $file, $line) {
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

spl_autoload_register(function ($class) {
    $file = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Gestione centralizzata degli errori
try {
    $dbModel = new Models\DatabaseModel();
    session_start();

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = trim($uri, '/');
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
            $messaggio_errore = "404 - Metodo non trovato";
            include BASE_PATH . "/Views/ErrorView.php";
        }
    }
    else {
        http_response_code(404);
        $messaggio_errore = "404 - Pagina non trovata";
        include BASE_PATH . "/Views/ErrorView.php";
    }
}
catch (\PDOException $e) {
    http_response_code(500);
    error_log($e->getMessage());
    $messaggio_errore = "Errore di connessione. Riprova più tardi.";
    include BASE_PATH . "/Views/ErrorView.php";
}
catch (\Exception $e) {
    http_response_code(500);
    error_log($e->getMessage());
    $messaggio_errore = "Si è verificato un errore. Riprova più tardi.";
    include BASE_PATH . "/Views/ErrorView.php";
}
