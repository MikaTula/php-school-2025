<?php

    declare(strict_types=1);

    namespace App\Http\Routing;

    class Router
    {
        /**
         * @var Route[]
         */
        private array $routes;

        /**
         * @param  Route[]  $routes
         */
        public function __construct(array $routes)
        {
            $this->routes = $routes;
        }

        public function findRequestHandler(string $method, string $path): ?callable
        {
            foreach ($this->routes as $route) {
                if ($route->canHandle($method, $path)) {
                    return $route->getHandler();
                }
            }

            return null;
        }
    }
