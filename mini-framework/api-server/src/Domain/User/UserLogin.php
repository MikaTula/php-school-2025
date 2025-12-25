<?php

declare(strict_types=1);

namespace App\Domain\User;

class UserLogin
{
    public int $id;

    public string $login;
    public string $password;
}
