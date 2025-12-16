<?php

    declare(strict_types=1);

    namespace App\DataAccess\Repository;

    use App\Api\Models\AlbumCreateUpdateModel;
    use App\Api\Models\AlbumModel;
    use App\DataAccess\Interfaces\AlbumsRepositoryInterface;
    use Exception;
    use JsonMapper;
    use JsonMapper_Exception;
    use PDO;

    class AlbumsRepositoryPdo implements AlbumsRepositoryInterface
    {
        public function __construct(private readonly PDO $pdo) {}

        /**
         * @return AlbumModel[]
         * @throws JsonMapper_Exception
         */
        public function getAll(): array
        {
            $statement = $this->pdo->query(
                    "select 
                        albums.id,
                        albums.title,
                        albums.singer_id,
                        albums.year,
                        singers.name as singer_name 
                        from albums left join singers on singers.id = albums.singer_id;");

            $mapper = new JsonMapper();

            return array_map(
                    static function (object $row) use ($mapper): AlbumModel {
                        $row->singer = new \stdClass();
                        $row->singer->id = $row->singer_id;
                        $row->singer->name = $row->singer_name ?? 'No name';


                        return $mapper->map($row, new AlbumModel());
                    },
                    $statement->fetchAll()
            );
        }

        public function getById(int $id): AlbumModel
        {
            $statement = $this->pdo->prepare(
                    "select 
                        albums.id,
                        albums.title,
                        albums.singer_id,
                        albums.year,
                        singers.name as artists_name 
                        from albums left join singers on singers.id = albums.singer_id
                        where albums.id = :id");
            $statement->execute(['id' => $id]);

            $mapper = new JsonMapper();

            if ($statement->rowCount() > 0) {
                $row = $statement->fetch();
                $row->singer = new \stdClass();
                $row->singer->id = $row->singer_id;
                $row->singer->name = $row->singer_name ?? 'No name';
                return $mapper->map($row, new AlbumModel());
            }

            throw new Exception("Album not found");
        }

        public function removeById(int $id): void
        {
            $sql = "delete from albums where id=:id;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
        }

        public function update(AlbumCreateUpdateModel $model): void
        {
            $updates = ['id' => $model->id];
            $fields = [];
            foreach ($model as $param => $value) {
                if ($param!=='id' && $value!==null) {
                    if ($param==='singerId') {
                        $param = 'singer_id';
                    }
                    $updates[$param] = $value;
                    $fields[] = $param.'=:'.$param;
                }
            }

            $fields = join(',', $fields);
            $sql = "UPDATE albums SET ".$fields." WHERE id=:id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($updates);
        }

        public function create(AlbumCreateUpdateModel $model): void
        {
            $data = [
                    'title'     => $model->title,
                    'year'      => $model->year,
                    'singer_id' => $model->singerId,

            ];
            $sql = "insert into albums (`title`, `year`, `singer_id`) values (:title, :year, :singer_id);";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($data);
        }
    }
