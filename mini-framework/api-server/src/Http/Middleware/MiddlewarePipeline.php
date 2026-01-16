<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;

class MiddlewarePipeline
{
        /** @var callable */
    private $action;

        /**
         * @param  list<IMiddleware>  $middlewares
         */
    public function __construct(
        private array $middlewares
    ) {
    }

    public function handle(Request $request, callable $next): Response
    {
        $this->action = $next;
        return (count($this->middlewares) !== 0) ? $this->nextWrapper($request) : $next($request);
    }

    private function nextWrapper(Request $request): Response
    {
        if (count($this->middlewares) !== 0) {
            $middleware = array_shift($this->middlewares);
            return $middleware->handle($request, $this->nextWrapper(...));
        }

        return ($this->action)($request);
    }
}
