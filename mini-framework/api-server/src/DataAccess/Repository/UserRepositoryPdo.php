<?php

    declare(strict_types=1);

    namespace App\DataAccess\Repository;


    use App\DataAccess\Interfaces\UserRepositoryInterface;
    use App\Domain\User\User;
    use Carbon\Carbon;
    use PDO;

    class UserRepositoryPdo implements UserRepositoryInterface
    {
        public function __construct(private readonly PDO $pdo)
        {
        }

        public function getByLogin(string $login): User|null
        {
            $statement = $this->pdo->prepare('select * from users where login = :login;');
            $statement->execute(['login' => $login]);
            $rawUser = $statement->fetch();

            return $rawUser !== false ? self::mapToModel($rawUser) : null;
        }

        private static function mapToModel(object $data): User
        {
            $user = new User();
            $user->id = $data->id;
            $user->login = $data->login;
            $user->firstName = $data->first_name;
            $user->lastName = $data->last_name;
            $user->birthday = Carbon::parse($data->birthday);
            $user->createdAt = Carbon::parse($data->created_at);
            $user->passwordHash = $data->password_hash;

            return $user;
        }
    }
