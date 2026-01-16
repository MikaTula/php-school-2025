<?php

declare(strict_types=1);

namespace App\DataAccess;

use Predis\Client;

class RedisFactory
{
    private static ?Client $client = null;

    public static function instance(): Client
    {
        if (self::$client !== null) {
            return self::$client;
        }

        $client = new Client([
                                 'scheme' => 'tcp',
                                 'host' => $_ENV['REDIS_HOST'],
                                 'port' => $_ENV['REDIS_PORT'],
                                 'database' => $_ENV['REDIS_BASE_NUMBER']
                             ]);

        self::$client = $client;
        return self::$client;
    }
}
