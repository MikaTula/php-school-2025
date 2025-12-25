<?php

declare(strict_types=1);

namespace App\Http;

enum ResponseCode: int
{
    case Ok = 0;

    case InvalidRequest = 100;

    case InvalidToken = 401;

    case NotFound = 404;

    case Error = 500;
}
