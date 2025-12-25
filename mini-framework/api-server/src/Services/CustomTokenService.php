<?php

declare(strict_types=1);

namespace App\Services;

use App\Api\Models\AuthInfoModel;
use Exception;
use JsonMapper;
use JsonMapper_Exception;

class CustomTokenService implements TokenServiceInterface
{
    private string $iv;
    private string $key;

    public function __construct()
    {
        $this->key = "secret";
        $this->iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(
            0x0
        ) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
    }

    public function makeToken(AuthInfoModel $authInfo): string
    {
        $authInfoData = json_encode($authInfo);
        return base64_encode(openssl_encrypt($authInfoData, 'aes-256-cbc', $this->key, OPENSSL_RAW_DATA, $this->iv));
    }

    /**
     * @throws JsonMapper_Exception
     * @throws Exception
     */
    public function validateToken($token): AuthInfoModel
    {
        if ($token === null || trim($token) === '') {
            throw new Exception("Token incorrect");
        }

        $authInfoData = openssl_decrypt(base64_decode($token), 'aes-256-cbc', $this->key, OPENSSL_RAW_DATA, $this->iv);
        return (new JsonMapper())->map(json_decode($authInfoData), new AuthInfoModel());
    }
}
