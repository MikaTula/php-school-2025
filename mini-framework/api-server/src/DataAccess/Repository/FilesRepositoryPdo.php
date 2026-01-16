<?php

declare(strict_types=1);

namespace App\DataAccess\Repository;

use App\Api\Models\FileModel;
use App\Api\Models\FileRenameModel;
use App\DataAccess\Interfaces\FilesRepositoryInterface;
use App\Domain\File;
use JsonMapper;
use JsonMapper_Exception;
use PDO;
use stdClass;

readonly class FilesRepositoryPdo implements FilesRepositoryInterface
{
    public function __construct(private PDO $pdo)
    {
    }

    public function create(File $model): void
    {
        $data = [
            'name' => $model->name,
            'path' => $model->path,
            'type' => $model->type,
            'size' => $model->size,
            'createdBy' => $model->createdBy,
        ];
        $sql = "insert into files (`name`, `path`, `type`,`size`,`created_by`)
                    values (:name, :path, :type, :size, :createdBy);";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($data);
    }

    /**
     * @return FileModel[]
     * @throws JsonMapper_Exception
     */
    public function getAll(): array
    {
        $statement = $this->pdo->query(
            "select
                        files.id,
                        files.name,
                        files.created_at,
                        files.path,
                        files.type,
                        files.created_by,
                        CONCAT(users.first_name, ' ' , users.last_name) as user_name
                        from files left join users on users.id = files.created_by"
        );

        return array_map(
            static function (object $row): FileModel {
                $mapper = new JsonMapper();
                $user = new stdClass();
                $user->name = $row->user_name;
                $user->id = $row->created_by;
                $row->createdBy = $user;

                return $mapper->map($row, new FileModel());
            },
            $statement->fetchAll()
        );
    }

    /**
     * @throws JsonMapper_Exception
     */
    public function getById(int $id): ?FileModel
    {
        $statement = $this->pdo->prepare(
            "select
                        files.id,
                        files.name,
                        files.created_at,
                        files.path,
                        files.type,
                        files.created_by,
                        CONCAT(users.first_name, ' ' , users.last_name) as user_name
                        from files left join users on users.id = files.created_by
                        where files.id = :id"
        );
        $statement->execute(['id' => $id]);

        $mapper = new JsonMapper();

        if ($statement->rowCount() > 0) {
            $row = $statement->fetch();
            $user = new stdClass();
            $user->name = $row->user_name;
            $user->id = $row->created_by;
            $row->createdBy = $user;
            return $mapper->map($row, new FileModel());
        }

        return null;
    }

    public function removeById(int $id): void
    {
        $sql = "delete from files where id=:id;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
    }

    public function rename(FileRenameModel $model): void
    {
        $sql = "UPDATE files SET files.name= :name WHERE id=:id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $model->id, 'name' => $model->name]);
    }
}
