<?php

declare(strict_types=1);

use App\Api\Controllers\AlbumController;
use App\Api\Controllers\FileController;
use App\Api\Controllers\LoginController;
use App\Api\Controllers\SingerController;
use App\DataAccess\PdoFactory;
use App\DataAccess\RedisFactory;
use App\DataAccess\Repository\AlbumsRepositoryPdo;
use App\DataAccess\Repository\AuthTokenRepositoryPdo;
use App\DataAccess\Repository\FilesRepositoryPdo;
use App\DataAccess\Repository\SingerRepositoryPdo;
use App\DataAccess\Repository\UserRepositoryPdo;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\MiddlewarePipeline;
use App\Http\Middleware\ValidatorMiddleware;
use App\Http\Response;
use App\Http\ResponseCode;
use App\Http\Routing\Route;
use App\Http\Routing\Router;
use App\Services\AuthService;
use App\Services\CacheService;
use App\Services\CustomTokenService;
use App\Services\FileService;
use App\Services\JwtTokenService;
use App\Utils\RequestUtils;

set_exception_handler(function (Throwable $e) {
    header('Content-Type: application/json');
    $response = new Response($e->getCode() ?? ResponseCode::Error->value, null, $e->getMessage());
    die(json_encode($response));
});

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$pdo = PdoFactory::instance();

$cacheService = new CacheService(RedisFactory::instance());

// $albumsRepository = new AlbumsRepository(dirname(__DIR__).'/resources/input/albums.txt');
$albumsRepository = new AlbumsRepositoryPdo($pdo);
$albumController = new AlbumController($albumsRepository, $cacheService);

$singerRepository = new SingerRepositoryPdo($pdo);
$singerController = new SingerController($singerRepository);

$authTokenRepository = new AuthTokenRepositoryPdo($pdo);
$usersRepository = new UserRepositoryPdo($pdo);

// need select
$tokenService = new JwtTokenService();
$tokenService = new CustomTokenService();

$authService = new AuthService($authTokenRepository, $usersRepository, $tokenService, $cacheService);
$loginController = new LoginController($authService);

$fileRepository = new FilesRepositoryPdo($pdo);
$fileService = new FileService(dirname(__DIR__) . '/resources/upload/', $fileRepository);
$fileController = new FileController($fileRepository, $fileService);

$routes = [
    // Login
    Route::createPost('/api/login', [$loginController, 'login']),
    Route::createPost('/api/logout', [$loginController, 'logout']),
    Route::createPost('/api/logout-all-devices', [$loginController, 'logoutAllDevices']),

    // For Api
    Route::createPost('/api/get-token', [$loginController, 'getToken']),

    // Albums
    Route::createGet('/api/albums', [$albumController, 'getById']),
    Route::createGet('/api/albums/all', [$albumController, 'getAll']),
    Route::createDelete('/api/albums', [$albumController, 'delete']),
    Route::createPut('/api/albums', [$albumController, 'update']),
    Route::createPost('/api/albums', [$albumController, 'create']),

    // Singer
    Route::createGet('/api/singers', [$singerController, 'getById']),
    Route::createGet('/api/singers/all', [$singerController, 'getAll']),
    Route::createDelete('/api/singers', [$singerController, 'delete']),
    Route::createPut('/api/singers', [$singerController, 'update']),
    Route::createPost('/api/singers', [$singerController, 'create']),

    // Files
    Route::createPost('/api/files', [$fileController, 'create']),
    Route::createGet('/api/files', [$fileController, 'getById']),
    Route::createGet('/api/files/all', [$fileController, 'getAll']),
    Route::createPut('/api/files/rename', [$fileController, 'rename']),
    Route::createDelete('/api/files', [$fileController, 'delete']),
    Route::createGet('/api/files/stream', [$fileController, 'getStream']),
    Route::createGet('/api/files/attachment', [$fileController, 'getDownload']),
];

$router = new Router($routes);
$handler = $router->findRequestHandler(RequestUtils::getRequestMethod(), RequestUtils::getRequestPath());

if ($handler === null) {
    echo "<h1>Page not found</h1>";
    return;
}

$middlewares = [
    new AuthMiddleware($authService),
    new ValidatorMiddleware()
];

$middlewaresPipeline = new MiddlewarePipeline($middlewares);

$requestType = RequestUtils::getRequestType($handler);

$request = new $requestType(
    RequestUtils::getRequestMethod(),
    RequestUtils::getRequestPath(),
    RequestUtils::getRequestData()
);

$response = $middlewaresPipeline->handle($request, $handler);

foreach ($response->headers as $name => $value) {
    header("{$name}: {$value}");
}

header('Content-Type: application/json');
echo json_encode($response);
