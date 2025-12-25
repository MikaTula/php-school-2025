<?php

    declare(strict_types=1);

    namespace App\Http\Middleware;

    use App\Http\Request;
    use App\Http\Response;
    use App\Http\ResponseCode;

    class ValidatorMiddleware implements IMiddleware
    {
        public function handle(Request $request, callable $next): Response
        {
            if (!$request->validate()) {
                return new Response(
                        ResponseCode::InvalidRequest->value, $request->validationMessages
                );
            }

            return $next($request);
        }
    }
