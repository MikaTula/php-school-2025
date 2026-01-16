<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Request;
use App\Http\Response;
use App\Http\ResponseCode;
use App\Services\AuthService;
use Exception;

readonly class AuthMiddleware implements IMiddleware
{
    public function __construct(private AuthService $authService)
    {
    }

    /**
     * @throws Exception
     */
    public function handle(Request $request, callable $next): Response
    {
        if (in_array($request->path, ['/api/login', '/api/get-token'])) {
            return $next($request);
        }

        $token = isset($request->data?->auth_token) ? $request->data->auth_token : null;
        if ($token === null) {
            throw new Exception("Token not set", ResponseCode::InvalidToken->value);
        }

        $authInfo = $this->authService->validateToken($token);

        if ($authInfo === null) {
            throw new Exception("Token expired", ResponseCode::InvalidToken->value);
            // return new Response(ResponseCode::InvalidToken->value, null, message: "Invalid Token");
        }

        $request->authInfo = $authInfo;
        $request->authToken = $token;

        return $next($request);
    }
}
