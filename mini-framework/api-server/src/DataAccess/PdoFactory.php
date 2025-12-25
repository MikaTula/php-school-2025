<?php

declare(strict_types=1);

namespace App\DataAccess;

use PDO;

class PdoFactory
{
    private static ?PDO $pdo = null;

    public static function instance(): PDO
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        self::$pdo = new PDO(
            "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_DATABASE']}",
            $_ENV['DB_USERNAME'],
            $_ENV['DB_PASSWORD'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ]
        );

        return self::$pdo;
    }
}
