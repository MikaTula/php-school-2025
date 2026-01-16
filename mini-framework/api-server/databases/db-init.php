<?php

// run in docker
// docker exec -it cm-api-server sh -c 'php /var/www/api-server/databases/db-init.php'

use App\DataAccess\PdoFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$pdo = PdoFactory::instance();

$pdo->exec('DROP TABLE IF EXISTS `albums`');
$pdo->exec(
    'CREATE TABLE `albums`
            (
                id INT(11) NOT NULL AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                year INT,
                singer_id INT(11),
                PRIMARY KEY (`id`)
            )
    '
);

$pdo->exec('DROP TABLE IF EXISTS `singers`');
$pdo->exec(
    'CREATE TABLE `singers`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            )
    '
);

$pdo->exec('DROP TABLE IF EXISTS `auth_tokens`');
$pdo->exec(
    'CREATE TABLE `auth_tokens`
            (
                id INT NOT NULL AUTO_INCREMENT,
                user_id INT NOT NULL,
                token_hash VARCHAR(255) NOT NULL,
                token TEXT NOT NULL,
                expired_at DATETIME NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )
    '
);

$pdo->exec('DROP TABLE IF EXISTS `users`');
$pdo->exec(
    'CREATE TABLE `users`
            (
                id INT NOT NULL AUTO_INCREMENT,
                login VARCHAR(64) NOT NULL,
                password_hash VARCHAR(64) NOT NULL,
                first_name VARCHAR(64),
                last_name VARCHAR(64),
                birthday DATETIME NOT NULL,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            )
    '
);

// pass admin123
$pdo->exec(
    'INSERT INTO `users`
            (
                login,
                password_hash,
                first_name,
                last_name,
                birthday
            ) VALUES (
                "admin",
                "$2y$10$NLf4uc3kJ0vvtVdEzXxBMuAfZT2/f5naV2mbiUXj4rWZ8gAFO7xpi",
                "Джек",
                "Воробей",
                "2001-10-07 15:15:15"
            )
    '
);

$pdo->exec('DROP TABLE IF EXISTS `files`');
$pdo->exec(
    'CREATE TABLE `files` (
                  `id` int NOT NULL,
                  `name` varchar(255) NOT NULL,
                  `path` varchar(255) NOT NULL,
                  `type` varchar(255) NOT NULL,
                  `size` int NOT NULL,
                  `created_by` int DEFAULT NULL,
                  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci'
);
$pdo->exec(
    'ALTER TABLE `files`
                  ADD PRIMARY KEY (`id`),
                  ADD UNIQUE KEY `path` (`path`);'
);
$pdo->exec(
    'ALTER TABLE `files`
                  MODIFY `id` int NOT NULL AUTO_INCREMENT;'
);

echo 'data base recreated';
