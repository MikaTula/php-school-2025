<?php

    declare(strict_types=1);

    namespace App\DataAccess\Repository;

    use App\DataAccess\Interfaces\AuthTokenRepositoryInterface;
    use App\Domain\Auth\AuthToken;
    use Carbon\Carbon;
    use PDO;

    class AuthTokenRepositoryPdo implements AuthTokenRepositoryInterface
    {
        public function __construct(private readonly PDO $pdo)
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
            $sql = "insert into auth_tokens (`user_id`, `token_hash`, `token`, `expired_at`) values (:userId, :tokenHash, :token, :expireAt);";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
        }

        public function getByHash(string $tokenHash): ?AuthToken
        {
            $data = ['tokenHash' => $tokenHash];
            $sql = "select * from auth_tokens where token_hash=:tokenHash and expired_at > NOW()";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);

            $mapper = new \JsonMapper();

            if ($stmt->rowCount() > 0) {
                return $mapper->map($stmt->fetch(), new AuthToken());
            }
        }

        public function exists(string $token, string $tokenHash): bool
        {
            $data = ['tokenHash' => $tokenHash, 'token' => $token];
            $sql = "select 1 from auth_tokens where token_hash=:tokenHash and token=:token 
                              and expired_at > NOW()";
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

    }
