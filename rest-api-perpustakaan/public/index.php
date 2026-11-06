<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Access-Control-Max-Age: 86400');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Env;
use App\Controllers\BookController;
use App\Contracts\BookRepositoryInterface;
use App\Core\Container;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Database\Connection;
use App\Repositories\MySQLBookRepository;
use App\Services\BookService;

// ============ 1. Load .env ============
Env::load(__DIR__ . '/../.env');

// ============ 2. Container ============
$container = new Container();

$container->singleton(PDO::class, fn() => Connection::get());

$container->bind(
    BookRepositoryInterface::class,
    fn(Container $c) => new MySQLBookRepository($c->make(PDO::class))
);
$container->bind(
    BookService::class,
    fn(Container $c) => new BookService(
        $c->make(BookRepositoryInterface::class)
    )
);
$container->bind(
    BookController::class,
    fn(Container $c) => new BookController(
        $c->make(BookService::class)
    )
);

// ============ 3. Routes ============
$router   = new Router();
$bookCtrl = $container->make(BookController::class);

$router->get('/api/books',         [$bookCtrl, 'index']);
$router->get('/api/books/{id}',    [$bookCtrl, 'show']);
$router->post('/api/books',        [$bookCtrl, 'store']);
$router->put('/api/books/{id}',    [$bookCtrl, 'update']);
$router->delete('/api/books/{id}', [$bookCtrl, 'destroy']);

// ============ 4. Dispatch ============
try {
    $router->dispatch(new Request());
} catch (\Throwable $e) {
    Response::error('Server Error: ' . $e->getMessage(), 500);
}