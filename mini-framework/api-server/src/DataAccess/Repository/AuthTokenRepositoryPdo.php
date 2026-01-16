<?php

declare(strict_types=1);

namespace App\DataAccess\Repository;

use App\DataAccess\Interfaces\AuthTokenRepositoryInterface;
use App\Domain\Auth\AuthToken;
use Carbon\Carbon;
use Exception;
use JsonMapper;
use JsonMapper_Exception;
use PDO;

readonly class AuthTokenRepositoryPdo implements AuthTokenRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }


    public function create(int $userId, string $token, string $tokenHash, Carbon $expireAt): void
    {
        $data = [
            'userId' => $userId,
            'token' => $token,
            'tokenHash' => $tokenHash,
            'expireAt' => $expireAt,
        ];
        $sql = "insert into auth_tokens (`user_id`, `token_hash`, `token`, `expired_at`)
              values (:userId, :tokenHash, :token, :expireAt);";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    /**
     * @throws JsonMapper_Exception
     * @throws Exception
     */
    public function getByHash(string $tokenHash): ?AuthToken
    {
        $data = ['tokenHash' => $tokenHash];
        $sql = "select * from `auth_tokens` where `token_hash`=:tokenHash and `expired_at` > NOW()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);

        $mapper = new JsonMapper();

        if ($stmt->rowCount() > 0) {
            return $mapper->map($stmt->fetch(), new AuthToken());
        }
        throw new Exception("Token not found");
    }

    public function exists(string $tokenHash): bool
    {
        sleep(5);
        $data = ['tokenHash' => $tokenHash];
        $sql = "select 1 from `auth_tokens` where `token_hash`=:tokenHash and `expired_at` > NOW()";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
        return $stmt->rowCount() > 0;
    }


    public function remove(int $userId, string $tokenHash): void
    {
        $data = ['userId' => $userId, 'tokenHash' => $tokenHash];
        $statement = $this->pdo->prepare(
            'delete from auth_tokens where user_id = :userId and token_hash = :tokenHash'
        );
        $statement->execute($data);
    }

    public function removeAll(int $userId): void
    {
        $data = ['userId' => $userId];
        $statement = $this->pdo->prepare(
            'delete from auth_tokens where user_id = :userId'
        );
        $statement->execute($data);
    }
}
