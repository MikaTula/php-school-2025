<?php

    declare(strict_types=1);

    use App\Api\Controllers\AlbumController;
    use App\DataAccess\AlbumsRepository;
    use App\Http\Middleware\MiddlewarePipeline;
    use App\Http\Middleware\ValidatorMiddleware;
    use App\Http\Routing\Route;
    use App\Http\Routing\Router;
    use App\Utils\RequestUtils;

    $albumsRepository = new AlbumsRepository(dirname(__DIR__).'/resources/input/albums.txt');
    $albumController = new AlbumController($albumsRepository);

    $routes = [
            Route::createGet('/api/albums', [$albumController, 'getAlbum']),
            Route::createGet('/api/albums/all', [$albumController, 'getAlbums']),
            Route::createDelete('/api/albums', [$albumController, 'deleteAlbum']),
            Route::createPut('/api/albums', [$albumController, 'putAlbum']), // update
            Route::createPost('/api/albums', [$albumController, 'postAlbum'])
    ];

    $router = new Router($routes);
    $handler = $router->findRequestHandler(RequestUtils::getRequestMethod(), RequestUtils::getRequestPath());

    if ($handler===null) {
        echo "<h1>Page not found</h1>";
        return;
    }

    $middlewares = [new ValidatorMiddleware()];

    $middlewaresPipeline = new MiddlewarePipeline($middlewares);

    $requestType = RequestUtils::getRequestType($handler);

    $request = new $requestType(RequestUtils::getRequestMethod(), RequestUtils::getRequestPath(),
            RequestUtils::getRequestData());

    $response = $middlewaresPipeline->handle($request, $handler);

    header('Content-Type: application/json');
    echo json_encode($response);
