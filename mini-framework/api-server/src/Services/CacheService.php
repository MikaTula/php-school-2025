<?php

declare(strict_types=1);

namespace App\Services;

use Predis\Client;

readonly class CacheService
{
    public function __construct(
        private Client $cacheClient
    ) {
    }

    public function delAllUserAuth(int $userId): void
    {
        $list = $this->cacheClient->keys("user:$userId:auth-token:*");
        if (count($list)) {
            $this->cacheClient->del($list);
        }
    }

    public function delUserAuth(int $userId, string $tokenHash): void
    {
        $this->cacheClient->del($this->makeAuthKey($userId, $tokenHash));
    }

    public function flushAll(): void
    {
        $list = $this->cacheClient->keys('*');
        if (count($list)) {
            $this->cacheClient->del($list);
        }
    }

    public function get(string $key): ?string
    {
        return $this->cacheClient->get($key);
    }

    public function getOrSave(string $key, callable $getData)
    {
        $data = $this->cacheClient->get($key);

        if ($data !== null) {
            $data = json_decode($data);
        } else {
            $data = $getData();
            if (!empty($data)) {
                $this->cacheClient->set($key, json_encode($data), 'EX', 600);
            }
        }
        return $data;
    }

    public function makeAlbumKey(int $key): string
    {
        return "music:album:$key";
    }

    public function makeAuthKey(int $userId, string $tokenHash): string
    {
        return "user:$userId:auth-token:$tokenHash";
    }
}
