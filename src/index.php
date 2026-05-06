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
        echo '404 - Metodo non trovato';
    }
}
else {
    http_response_code(404);
    echo '404 - Pagina non trovata';
}
