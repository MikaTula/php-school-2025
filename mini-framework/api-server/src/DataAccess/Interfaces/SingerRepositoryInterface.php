<?php

declare(strict_types=1);

namespace App\DataAccess\Interfaces;

use App\Api\Models\SingerCreateUpdateModel;
use App\Api\Models\SingerModel;

interface SingerRepositoryInterface
{
    /**
     * @return SingerModel[]
     */
    public function getAll(): array;

    public function getById(int $id): SingerModel;

    public function removeById(int $id): void;

    public function update(SingerCreateUpdateModel $model): void; // put

    public function create(SingerCreateUpdateModel $model): void; // post
}
