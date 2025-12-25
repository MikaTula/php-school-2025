<?php

    declare(strict_types=1);

    namespace App\DataAccess\Repository;

    use App\Api\Models\AlbumCreateUpdateModel;
    use App\Api\Models\AlbumModel;
    use App\Api\Models\SingerCreateUpdateModel;
    use App\Api\Models\SingerModel;
    use App\DataAccess\Interfaces\AlbumsRepositoryInterface;
    use App\DataAccess\Interfaces\SingerRepositoryInterface;
    use App\Domain\Album;
    use JsonMapper;
    use PDO;

    class SingerRepositoryPdo implements SingerRepositoryInterface
    {
        public function __construct(private readonly PDO $pdo)
        {
        }


        /**
         * @return SingerModel[]
         * @throws \JsonMapper_Exception
         */
        public function getAll(): array
        {
            $statement = $this->pdo->query("select * from singers");
            $mapper = new JsonMapper();

            return array_map(
                static fn (object $row): SingerModel => $mapper->map($row, new SingerModel()),
                $statement->fetchAll()
            );
        }

        public function getById(int $id): SingerModel
        {
            $statement = $this->pdo->prepare('select * from singers where id = :id');
            $statement->execute(['id' => $id]);

            $mapper = new JsonMapper();

            $res = ($statement->rowCount() === 0)
                ? null
                : $mapper->map($statement->fetch(), new SingerModel());

            if ($res !== null) {
                return $res;
            }
            throw new \Exception("Singer not found");
        }

        public function removeById(int $id):void
        {
            $sql = "delete from singers where id=:id;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
        }

        public function update(SingerCreateUpdateModel $model):void
        {
            $updates = ['id' => $model->id];
            $fields = [];
            foreach ($model as $param => $value) {
                if ($param !== 'id' && $value !== null) {
                    $updates[$param] = $value;
                    $fields[] = $param  . '=:' . $param;
                }
            }

            $fields = join (',',$fields);
            $sql = "UPDATE singers SET " . $fields . " WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($updates);
        }

        public function create(SingerCreateUpdateModel $model):void
        {
            $data = [
                'name' => $model->name,
            ];
            $sql = "insert into singers (`name`) values (:name);";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
        }
    }
