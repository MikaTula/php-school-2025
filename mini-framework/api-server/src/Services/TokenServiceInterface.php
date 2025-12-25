<?php

declare(strict_types=1);

namespace App\Services;

use App\Api\Models\AuthInfoModel;

interface TokenServiceInterface
{
    public function makeToken(AuthInfoModel $authInfo): string;

    public function validateToken($token): AuthInfoModel;
}
