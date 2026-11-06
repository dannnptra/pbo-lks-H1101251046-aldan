<?php
<<<<<<< HEAD
/**
 * CORS Headers — wajib untuk frontend terpisah.
 * Izinkan request dari origin manapun (development).
 * Untuk production, ganti '*' dengan domain spesifik.
 */
=======
>>>>>>> 9a87f184151463d4992e7a992fb61a3e258f90bb
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept');
header('Access-Control-Max-Age: 86400');

<<<<<<< HEAD
// Handle preflight request (OPTIONS)
=======
>>>>>>> 9a87f184151463d4992e7a992fb61a3e258f90bb
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Env;
use App\Controllers\BookController;
<<<<<<< HEAD
use App\Controllers\CategoryController;
use App\Contracts\BookRepositoryInterface;
use App\Contracts\CategoryRepositoryInterface;
=======
use App\Contracts\BookRepositoryInterface;
>>>>>>> 9a87f184151463d4992e7a992fb61a3e258f90bb
use App\Core\Container;
use App\Core\Request;
use App\Core\Response;
use App\Core\Router;
use App\Database\Connection;
use App\Repositories\MySQLBookRepository;
<<<<<<< HEAD
use App\Repositories\MySQLCategoryRepository;
use App\Services\BookService;
use App\Services\CategoryService;
=======
use App\Services\BookService;
>>>>>>> 9a87f184151463d4992e7a992fb61a3e258f90bb

// ============ 1. Load .env ============
Env::load(__DIR__ . '/../.env');

// ============ 2. Container ============
$container = new Container();

$container->singleton(PDO::class, fn() => Connection::get());

<<<<<<< HEAD
// Binding Book
=======
>>>>>>> 9a87f184151463d4992e7a992fb61a3e258f90bb
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

<<<<<<< HEAD
// Binding Category
$container->bind(
    CategoryRepositoryInterface::class,
    fn(Container $c) => new MySQLCategoryRepository($c->make(PDO::class))
);
$container->bind(
    CategoryService::class,
    fn(Container $c) => new CategoryService(
        $c->make(CategoryRepositoryInterface::class)
    )
);
$container->bind(
    CategoryController::class,
    fn(Container $c) => new CategoryController(
        $c->make(CategoryService::class)
    )
);

// ============ 3. Routes ============
$router       = new Router();
$bookCtrl     = $container->make(BookController::class);
$categoryCtrl = $container->make(CategoryController::class);

// Route Book
=======
// ============ 3. Routes ============
$router   = new Router();
$bookCtrl = $container->make(BookController::class);

>>>>>>> 9a87f184151463d4992e7a992fb61a3e258f90bb
$router->get('/api/books',         [$bookCtrl, 'index']);
$router->get('/api/books/{id}',    [$bookCtrl, 'show']);
$router->post('/api/books',        [$bookCtrl, 'store']);
$router->put('/api/books/{id}',    [$bookCtrl, 'update']);
$router->delete('/api/books/{id}', [$bookCtrl, 'destroy']);

<<<<<<< HEAD
// Route Category
$router->get('/api/categories',         [$categoryCtrl, 'index']);
$router->get('/api/categories/{id}',    [$categoryCtrl, 'show']);
$router->post('/api/categories',        [$categoryCtrl, 'store']);
$router->put('/api/categories/{id}',    [$categoryCtrl, 'update']);
$router->delete('/api/categories/{id}', [$categoryCtrl, 'destroy']);

=======
>>>>>>> 9a87f184151463d4992e7a992fb61a3e258f90bb
// ============ 4. Dispatch ============
try {
    $router->dispatch(new Request());
} catch (\Throwable $e) {
    Response::error('Server Error: ' . $e->getMessage(), 500);
}