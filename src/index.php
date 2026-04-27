<?php
define('BASE_PATH', '/var/www/html');

spl_autoload_register(function ($class) {
    $file = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

$uri = trim($_SERVER['REQUEST_URI'], '/');

switch ($uri) {
    case '': 
        http_response_code(404);
        break;
    case 'stats':
        $controller = new Controllers\StatsController();
        $controller->index();
        break;
    default:
        http_response_code(404);
        break;
}
