<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use Carbon\Carbon;

class AuthToken
{
    public int $id;
    public string $tokenHash;
    public string $token;
    public Carbon $expiredAt;
    public Carbon $createdAt;
}
