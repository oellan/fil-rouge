<?php

use BadBox\Exceptions\BadBoxException;
use BadBox\Modules\Router;
use Dotenv\Dotenv;

require_once __DIR__ . '/../../vendor/autoload.php';
define("__BADBOX_ROOT_DIR__", realpath(dirname(__DIR__)));

(static function () {
    $dotenv = Dotenv::createMutable(__DIR__ . '/../..');
    $dotenv->load();
    $dotenv->required(
        ['DB_DSN',
         'DB_USER',
         'DB_PASS']
    )
           ->notEmpty();
    $dotenv->ifPresent(['APP_DEBUG'])
           ->isBoolean();
    $debug = $_ENV['APP_DEBUG'] = (array_key_exists('APP_DEBUG', $_ENV) && strtolower($_ENV['APP_DEBUG']) === 'true');
    ini_set('error_reporting', $debug ? E_ALL : E_ERROR | E_CORE_ERROR | E_COMPILE_ERROR);
    ini_set('display_errors', $debug);
})();

$requestedPage = array_key_exists('__PAGE__', $_GET) && !empty($_GET['__PAGE__']) ? $_GET['__PAGE__'] : '/';
if (!str_starts_with('/', $requestedPage)) {
    $requestedPage = '/' . $requestedPage;
}

$router = Router::getInstance();
(require dirname(__BADBOX_ROOT_DIR__) . '/App/config/routes.php')($router);
try {
    $router->callRoute($requestedPage);
} catch (BadBoxException $exception) {
    $router->callErrorRoute($exception->getHttpErrorCode(), $exception->getMessage());
}
