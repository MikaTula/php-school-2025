<?php

declare(strict_types=1);

namespace App\Services;

use App\Api\Models\AuthInfoModel;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use JsonMapper;
use JsonMapper_Exception;

class JwtTokenService implements TokenServiceInterface
{
    private string $secretKey = "super-secret-key-super-secret-key-super-secret-key";
    private string $algorithm = 'HS256';
    private int|float $expireTime = 10 * 3600; // seconds, 10 hour

    public function makeToken(AuthInfoModel $authInfo): string
    {
        $issuedAt = time();
        $expireAt = $issuedAt + $this->expireTime;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expireAt,
            'data' => json_encode($authInfo)
        ];

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function makeHash(string $token): string
    {
        return hash('sha256', $token);
    }

    /**
     * @throws JsonMapper_Exception
     * @throws Exception
     */
    public function validateToken($token): AuthInfoModel
    {
        $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
        if (isset($decoded->exp) && $decoded->exp < time()) {
            throw new Exception("Token expired");
        }
        return new JsonMapper()->map(json_decode($decoded->data), new AuthInfoModel());
    }
}
