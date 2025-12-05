<?php


declare(strict_types=1);


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

session_start();


require_once __DIR__ . '/../config/config.php';


spl_autoload_register(function (string $class) {

    $class = ltrim($class, '\\');


    $baseDir = __DIR__ . '/../src/';


    $file = $baseDir . str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});


use Core\Router;
use Controller\AuthController;
use Controller\PostController;



$router = new Router();


$router->get('/', [PostController::class, 'index']);      
$router->get('/post', [PostController::class, 'show']);   


$router->get('/login', [AuthController::class, 'showLoginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

$router->get('/register', [AuthController::class, 'showRegisterForm']);
$router->post('/register', [AuthController::class, 'register']);


$router->get('/post/create', [PostController::class, 'createForm']);
$router->post('/post/store', [PostController::class, 'store']);

$router->get('/post/edit', [PostController::class, 'editForm']);
$router->post('/post/update', [PostController::class, 'update']);
$router->post('/post/delete', [PostController::class, 'destroy']);



$method = $_SERVER['REQUEST_METHOD'];


$uri = $_SERVER['REQUEST_URI'];


$scriptName = dirname($_SERVER['SCRIPT_NAME']); 
if ($scriptName !== '/' && str_starts_with($uri, $scriptName)) {
    $uri = substr($uri, strlen($scriptName));
}

$router->dispatch($method, $uri);
