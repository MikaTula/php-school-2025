<?php

declare(strict_types=1);

namespace App\Services;

use App\Api\Models\AuthInfoModel;
use App\DataAccess\Interfaces\AuthTokenRepositoryInterface;
use App\DataAccess\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use RuntimeException;

readonly class AuthService
{
    public function __construct(
        private AuthTokenRepositoryInterface $authTokenRepository,
        private UserRepositoryInterface $userRepository,
        private TokenServiceInterface $tokenService,
        private CacheService $cacheService
    ) {
    }

    /**
     * @throws RuntimeException
     */
    public function login(string $login, string $password): string
    {
        $user = $this->userRepository->getByLogin($login);

        if ($user === null) {
            throw new RuntimeException('Incorrect Login or Password');
        }

        if (!password_verify($password, $user->passwordHash)) {
            throw new RuntimeException('Incorrect Login or Password');
        }

        $expireAt = Carbon::now()->addHours(12);

        $token = $this->encodeToken($user->id, $login, $expireAt);
        $tokenHash = hash('sha256', $token);

        $this->authTokenRepository->create($user->id, $token, $tokenHash, $expireAt);

        $this->cacheService->getOrSave($this->cacheService->makeAuthKey($user->id, $tokenHash), fn() => $token);

        return $token;
    }

    public function logout(int $userId, string $token): void
    {
        $tokenHash = $this->tokenService->makeHash($token);
        $this->cacheService->delUserAuth($userId, $tokenHash);
        $this->authTokenRepository->remove($userId, $tokenHash);
    }

    public function logoutAllDevices(int $userId): void
    {
        $this->cacheService->delAllUserAuth($userId);
        $this->authTokenRepository->removeAll($userId);
    }

    public function validateToken(string|null $token): AuthInfoModel|null
    {
        if ($token === null || trim($token) === '') {
            return null;
        }

        $authInfo = $this->tokenService->validateToken($token);
        $tokenHash = $this->tokenService->makeHash($token);

        if (!$this->cacheService->get($this->cacheService->makeAuthKey($authInfo->userId, $tokenHash))) {
            if (!$this->authTokenRepository->exists($tokenHash)) {
                return null;
            }
        }

        return $authInfo;
    }

    private function decodeToken(string $token): AuthInfoModel
    {
        return $this->tokenService->validateToken($token);
    }

    private function encodeToken(int $userId, string $login, Carbon $expireAt): string
    {
        $authInfo = AuthInfoModel::create(userId: $userId, login: $login, expireAt: $expireAt);
        return $this->tokenService->makeToken($authInfo);
    }
}
