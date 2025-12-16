<?php
    // run in docker
    // docker exec -it cm-api-server sh -c 'php /var/www/api-server/databases/db-init.php'

    use App\DataAccess\PdoFactory;

    require __DIR__.'/../vendor/autoload.php';

    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    $pdo = PdoFactory::instance();

    $pdo->exec('DROP TABLE IF EXISTS `albums`');
    $pdo->exec('CREATE TABLE `albums`
            (
                id INT(11) NOT NULL AUTO_INCREMENT,
                title VARCHAR(255) NOT NULL,
                year INT,
                singer_id INT(11),
                PRIMARY KEY (`id`)
            )
    ');

    $pdo->exec('DROP TABLE IF EXISTS `singers`');
    $pdo->exec('CREATE TABLE `singers`
            (
                `id` INT(11) NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(255) NOT NULL,
                PRIMARY KEY (`id`)
            )
    ');

    echo 'data base recreated';


