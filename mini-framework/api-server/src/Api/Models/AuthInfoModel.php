<?php

declare(strict_types=1);

namespace App\Api\Models;

use Carbon\Carbon;

class AuthInfoModel
{
    public int $userId;
    public string $login;
    public Carbon $expireAt;

    public static function create(
        int $userId,
        string $login,
        Carbon $expireAt
    ): self {
        $authInfoModel = new self();
        $authInfoModel->userId = $userId;
        $authInfoModel->login = $login;
        $authInfoModel->expireAt = $expireAt;

        return $authInfoModel;
    }
}
