<?php

declare(strict_types=1);

namespace App\DataAccess\Interfaces;

use App\Api\Models\FileModel;
use App\Api\Models\FileRenameModel;
use App\Domain\File;

interface FilesRepositoryInterface
{
    /**
     * @return null|FileModel[]
     */
    public function getAll(): ?array;

    public function getById(int $id): ?FileModel;

    public function removeById(int $id): void;

    public function rename(FileRenameModel $model): void; // put

    public function create(File $model): void; //post
}
