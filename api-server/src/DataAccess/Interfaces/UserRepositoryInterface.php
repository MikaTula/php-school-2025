<?php

    declare(strict_types=1);

    namespace App\DataAccess\Interfaces;

    use App\Domain\User\User;


    interface UserRepositoryInterface
    {
        public function getByLogin(string $login): User|null;

    }
