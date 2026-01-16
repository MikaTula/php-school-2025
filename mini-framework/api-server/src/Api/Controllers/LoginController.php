<?php

declare(strict_types=1);

namespace App\Api\Controllers;

use App\Http\Requests\EmptyRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Response;
use App\Http\ResponseCode;
use App\Services\AuthService;
use Exception;
use JsonMapper_Exception;

class LoginController extends BaseController
{
    public function __construct(private readonly AuthService $authService)
    {
    }

    public function logout(EmptyRequest $request): Response
    {
        $this->authService->logout($request->authInfo->userId, $request->authToken);

        return $this->successResponse(headers: ['Set-Cookie' => "auth_token=; HttpOnly; MaxAge=0;"]);
    }


    public function logoutAllDevices(EmptyRequest $request): Response
    {
        $this->authService->logoutAllDevices($request->authInfo->userId);

        return $this->successResponse(headers: ['Set-Cookie' => "auth_token=; HttpOnly; MaxAge=0;"]);
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function getToken(LoginRequest $request): Response
    {
        $data = $request->getModel();

        try {
            $token = $this->authService->login($data->login, $data->password);

            return $this->successResponse(data: ['token' => $token]);
        } catch (Exception $exception) {
            return $this->failResponse(ResponseCode::NotFound, $exception->getMessage(), null);
        }
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function login(LoginRequest $request): Response
    {
        $data = $request->getModel();

        try {
            $token = $this->authService->login($data->login, $data->password);

            return $this->successResponse(headers: ['Set-Cookie' => "auth_token=$token; HttpOnly; MaxAge=43200;"]);
        } catch (Exception $exception) {
            return $this->failResponse(ResponseCode::NotFound, $exception->getMessage(), null);
        }
    }
}
