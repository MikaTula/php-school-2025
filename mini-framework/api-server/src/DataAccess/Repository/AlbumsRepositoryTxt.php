<?php

declare(strict_types=1);

namespace App\DataAccess\Repository;

use App\Api\Models\AlbumCreateUpdateModel;
use App\Api\Models\AlbumModel;
use App\DataAccess\Interfaces\AlbumsRepositoryInterface;
use App\Utils\DataUtils;
use Exception;
use JsonMapper;

class AlbumsRepositoryTxt implements AlbumsRepositoryInterface
{
    private array $albums;

    public function __construct(private readonly string $filePath)
    {
        $this->albums = DataUtils::readDataSource($this->filePath);
    }

    /**
     * @return AlbumModel[]
     */
    public function getAll(): array
    {
        return $this->albums ?? [];
    }

    /**
     * @throws Exception
     */
    public function getById(int $id): AlbumModel
    {
        foreach ($this->albums as $album) {
            if ($album['id'] === $id) {
                $mapper = new JsonMapper();
                return $mapper->map((object)$album, new AlbumModel());
            }
        }
        throw new Exception('Album not found');
    }

    public function removeById(int $id): void
    {
        foreach ($this->albums as $key => $album) {
            if ($album['id'] === $id) {
                unset($this->albums[$key]);
            }
        }

        $this->updateData($this->albums);
    }

    private function updateData(mixed $data): void
    {
        DataUtils::writeDataSource($this->filePath, $data);
    }

    public function update(AlbumCreateUpdateModel $model): void
    {
        foreach ($this->albums as $key => $album) {
            if ($album['id'] === $model->id) {
                foreach ($model as $param => $value) {
                    if ($value !== null) {
                        $this->albums[$key][$param] = $value;
                    }
                }
                break;
            }
        }

        $this->updateData($this->albums);
    }

    public function create(AlbumCreateUpdateModel $model): void
    {
        if (count($this->albums) > 0) {
            $id = (int)end($this->albums)['id'] + 1;
        } else {
            $id = 0;
        }


        $this->albums[] = [
            'id' => $id,
            'name' => $model->name,
            'singerId' => $model->singerId,
            'year' => $model->year
        ];

        $this->updateData($this->albums);
    }
}
