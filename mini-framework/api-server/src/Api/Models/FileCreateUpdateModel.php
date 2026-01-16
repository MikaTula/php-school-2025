<?php

declare(strict_types=1);

namespace App\Api\Models;

class FileCreateUpdateModel
{
    public string $name;

    /**
     * file array
     * @var array $file
     */
    public array $file;
}
