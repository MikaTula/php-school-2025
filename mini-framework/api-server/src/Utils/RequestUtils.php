<?php

    declare(strict_types=1);

    namespace App\Utils;

    use App\Http\Requests\EmptyRequest;
    use ReflectionException;
    use ReflectionMethod;
    use stdClass;

    class RequestUtils
    {
        public static function getRequestMethod(): string
        {
            return $_SERVER['REQUEST_METHOD'];
        }

        public static function getRequestPath(): string
        {
            return explode('?', $_SERVER['REQUEST_URI'] ?? '/')[0];
        }

        public static function getRequestData(): object
        {
            // Создаем объект Request
            $request = new stdClass();
            if (self::getRequestMethod() === 'PUT') {
                 parse_str(file_get_contents("php://input"), $_PUT);
                $request->params = $_PUT;
            } else {
                $request->params = self::getRequestMethod() === 'POST' ? $_POST : $_GET;
            }

            $headers = getallheaders();
            if (!empty($_COOKIE['auth_token'])) {
                $request->auth_token = $_COOKIE['auth_token'];
            }

            if (!empty($headers['Authorization'])) {
                $request->auth_token = str_replace('Bearer ', '', $headers['Authorization']);
            }

            return $request;
        }

        /**
         * @throws ReflectionException
         */
        public static function getRequestType(callable $handler): string
        {
            $reflectionMethod = new ReflectionMethod($handler[0], $handler[1]);
            $parameters = $reflectionMethod->getParameters();

            if (count($parameters) === 0) {
                return EmptyRequest::class;
            }

            return $parameters[0]->getType()->getName();
        }
    }
