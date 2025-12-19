<?php

    declare(strict_types=1);

    use App\Api\Controllers\AlbumController;
    use App\Api\Controllers\SingerController;
    use App\DataAccess\PdoFactory;
    use App\DataAccess\Repository\AlbumsRepositoryPdo;
    use App\DataAccess\Repository\AlbumsRepositoryTxt;
    use App\DataAccess\Repository\SingerRepositoryPdo;
    use App\Http\Middleware\MiddlewarePipeline;
    use App\Http\Middleware\ValidatorMiddleware;
    use App\Http\Response;
    use App\Http\ResponseCode;
    use App\Http\Routing\Route;
    use App\Http\Routing\Router;
    use App\Utils\RequestUtils;

    set_exception_handler(function (Throwable $e) {
        header('Content-Type: application/json');
        $response = new Response(ResponseCode::Error->value, null, $e->getMessage());
        echo json_encode($response);
    });

    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    $pdo = PdoFactory::instance();

    // $albumsRepository = new AlbumsRepositoryTxt(dirname(__DIR__).'/resources/input/albums.txt');
    $albumsRepository = new AlbumsRepositoryPdo($pdo);
    $albumController = new AlbumController($albumsRepository);

    $singerRepository = new SingerRepositoryPdo($pdo);
    $singerController = new SingerController($singerRepository);

    $routes = [
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
    ];

    $router = new Router($routes);
    $handler = $router->findRequestHandler(RequestUtils::getRequestMethod(), RequestUtils::getRequestPath());

    if ($handler===null) {
        echo "<h3>Page not found</h3>";
        return;
    }

    $middlewares = [new ValidatorMiddleware()];

    $middlewaresPipeline = new MiddlewarePipeline($middlewares);

    $requestType = RequestUtils::getRequestType($handler);

    $request = new $requestType(
            RequestUtils::getRequestMethod(),
            RequestUtils::getRequestPath(),
            RequestUtils::getRequestData()
    );

    $response = $middlewaresPipeline->handle($request, $handler);

    header('Content-Type: application/json');
    echo json_encode($response);
