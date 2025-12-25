<?php

declare(strict_types=1);

namespace App\DataAccess\Interfaces;

use App\Domain\Auth\AuthToken;
use Carbon\Carbon;

interface AuthTokenRepositoryInterface
{
    public function remove(int $userId, string $tokenHash): void;

    public function exists(string $token, string $tokenHash): bool;

    public function getByHash(string $tokenHash): ?AuthToken;

    public function create(int $userId, string $token, string $tokenHash, Carbon $expireAt): void;
}
