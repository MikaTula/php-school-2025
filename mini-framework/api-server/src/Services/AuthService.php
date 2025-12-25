<?php

declare(strict_types=1);

namespace App\Services;

use App\Api\Models\AuthInfoModel;
use App\DataAccess\Interfaces\AuthTokenRepositoryInterface;
use App\DataAccess\Interfaces\UserRepositoryInterface;
use Carbon\Carbon;
use RuntimeException;

class AuthService
{
    public function __construct(
        private readonly AuthTokenRepositoryInterface $authTokenRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly TokenServiceInterface $tokenService,
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

        return $token;
    }

    private function encodeToken(int $userId, string $login, Carbon $expireAt): string
    {
        $authInfo = AuthInfoModel::create(userId: $userId, login: $login, expireAt: $expireAt);
        return $this->tokenService->makeToken($authInfo);
    }

    public function logout(int $userId, string $token): void
    {
        $tokenHash = hash('sha256', $token);

        $this->authTokenRepository->remove($userId, $tokenHash);
    }

    private function decodeToken(string $token): AuthInfoModel
    {
        return $this->tokenService->validateToken($token);
    }

    public function validateToken(string|null $token): AuthInfoModel|null
    {
        if ($token === null || trim($token) === '') {
            return null;
        }

        $authInfo = $this->tokenService->validateToken($token);

        $tokenHash = hash('sha256', $token);

        if (!$this->authTokenRepository->exists($token, $tokenHash)) {
            return null;
        }

        return $authInfo;
    }
}
