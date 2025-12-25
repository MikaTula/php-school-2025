<?php

    namespace App\Domain\User;

    use Carbon\Carbon;

    class User
    {
        public int $id;
        public string $login;
        public string $passwordHash;
        public string|null $firstName;
        public string|null $lastName;
        public Carbon $birthday;
        public Carbon $createdAt;
    }
