<?php

declare(strict_types=1);

namespace App\DataAccess\Interfaces;

use App\Api\Models\AlbumCreateUpdateModel;
use App\Api\Models\AlbumModel;

interface AlbumsRepositoryInterface
{
    /**
     * @return AlbumModel[]
     */
    public function getAll(): array;

    public function getById(int $id): AlbumModel;

    public function removeById(int $id): void;

    public function update(AlbumCreateUpdateModel $model): void; // put

    public function create(AlbumCreateUpdateModel $model): void; //post
}
