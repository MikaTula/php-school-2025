<?php

    declare(strict_types=1);

    namespace App\Api\Controllers;

    use App\Http\Response;
    use App\Http\ResponseCode;

    abstract class BaseController
    {
        public function successResponse(mixed $data = null): Response
        {
            return new Response(ResponseCode::Ok->value, $data);
        }

        public function failResponse(ResponseCode $code, string $message, mixed $data): Response
        {
            return new Response($code->value, $data, $message);
        }
    }
